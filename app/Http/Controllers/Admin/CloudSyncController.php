<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CloudSync;
use App\Models\BillingDocument;
use App\Models\Ticket;
use App\Models\TaxCompliance;
use App\Models\Ebm;
use App\Models\TravelDocument;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Barryvdh\DomPDF\Facade\Pdf;

class CloudSyncController extends Controller
{
    public function index()
    {
        $syncs = CloudSync::with('admin')->latest()->get();
        return view('admin.cloud_sync.index', compact('syncs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|digits:4',
            'month' => 'required|digits:2',
        ]);

        $year = $request->year;
        $month = $request->month;

        $dailySyncsCount = CloudSync::whereDate('created_at', now())
            ->where('status', 'completed')
            ->count();

        if ($dailySyncsCount >= 3) {
            return back()->with('error', "You have reached the limit of 3 successful synchronizations for today. Please wait until tomorrow for further backups.");
        }

        $sync = CloudSync::updateOrCreate(
            ['year' => $year, 'month' => $month],
            ['status' => 'processing', 'synced_by' => auth()->id(), 'error_log' => null]
        );

        try {
            $monthName = date("F", mktime(0, 0, 0, $month, 10));
            $timestamp = now()->format('Y-m-d_H-i-s');
            $basePath = "Stunning_Portal_Data/Sync_{$timestamp}_{$year}_{$monthName}";

            // 0. Trigger Database Backup
            Artisan::call('backup:run', ['--only-db' => true]);
            $backupConfigName = env('APP_NAME', 'Laravel');
            $localDisk = Storage::disk('local');
            if ($localDisk->exists($backupConfigName)) {
                $files = $localDisk->files($backupConfigName);
                $latestZip = collect($files)->filter(function ($file) {
                    return str_ends_with($file, '.zip');
                })->sortByDesc(function ($file) use ($localDisk) {
                    return $localDisk->lastModified($file);
                })->first();

                if ($latestZip) {
                    $zipContent = $localDisk->get($latestZip);
                    $zipName = basename($latestZip);
                    Storage::disk('google')->put("{$basePath}/Database_Backup/{$zipName}", $zipContent);
                }
            }

            // 1. Sync Invoices (Generated on the fly)
            $invoices = BillingDocument::with(['client', 'organization', 'items'])
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->get();
                
            foreach ($invoices as $invoice) {
                $clientName = $invoice->client ? $invoice->client->name : 'Unknown_Client';
                $clientName = preg_replace('/[^A-Za-z0-9\- ]/', '', $clientName);
                
                $pdf = Pdf::loadView('ops.invoice.pdf.template', compact('invoice'));
                $fileName = "Invoice_{$invoice->document_number}.pdf";
                
                Storage::disk('google')->put("{$basePath}/Invoices/{$clientName}/{$fileName}", $pdf->output());
            }

            // 2. Sync Tickets
            $tickets = Ticket::whereYear('created_at', $year)->whereMonth('created_at', $month)->get();
            foreach ($tickets as $ticket) {
                if ($ticket->file_path && Storage::disk('public')->exists($ticket->file_path)) {
                    $clientName = preg_replace('/[^A-Za-z0-9\- ]/', '', $ticket->client_names ?? 'Unknown_Client');
                    $fileContent = Storage::disk('public')->get($ticket->file_path);
                    $fileName = basename($ticket->file_path);
                    Storage::disk('google')->put("{$basePath}/Tickets/{$clientName}/{$fileName}", $fileContent);
                }
            }

            // 3. Sync Taxes
            $taxes = TaxCompliance::whereYear('created_at', $year)->whereMonth('created_at', $month)->get();
            foreach ($taxes as $tax) {
                if ($tax->receipt_path && Storage::disk('public')->exists($tax->receipt_path)) {
                    $taxType = preg_replace('/[^A-Za-z0-9\- ]/', '', $tax->type);
                    $fileContent = Storage::disk('public')->get($tax->receipt_path);
                    $fileName = basename($tax->receipt_path);
                    Storage::disk('google')->put("{$basePath}/Taxes/{$taxType}/{$fileName}", $fileContent);
                }
            }

            // 4. Sync EBMs
            $ebms = Ebm::whereYear('created_at', $year)->whereMonth('created_at', $month)->get();
            foreach ($ebms as $ebm) {
                if ($ebm->file_path && Storage::disk('public')->exists($ebm->file_path)) {
                    $clientName = preg_replace('/[^A-Za-z0-9\- ]/', '', $ebm->client_name ?? 'Unknown_Client');
                    $fileContent = Storage::disk('public')->get($ebm->file_path);
                    $fileName = basename($ebm->file_path);
                    Storage::disk('google')->put("{$basePath}/EBM_Receipts/{$clientName}/{$fileName}", $fileContent);
                }
            }

            // 5. Sync Passports
            $passports = TravelDocument::with('client')
                ->where('type', 'passport')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->get();
            foreach ($passports as $passport) {
                if ($passport->file_path && Storage::disk('local')->exists($passport->file_path)) {
                    $clientName = preg_replace('/[^A-Za-z0-9\- ]/', '', optional($passport->client)->name ?? 'Unknown_Client');
                    $fileContent = Storage::disk('local')->get($passport->file_path);
                    $fileName = basename($passport->file_path);
                    Storage::disk('google')->put("{$basePath}/Passports/{$clientName}/{$fileName}", $fileContent);
                }
            }

            $sync->update(['status' => 'completed']);
            \App\Services\ActivityLogger::log('Admin', 'Cloud Sync', 'Export', "Macro-Sync", "Successfully synced {$year}-{$month} to Google Drive");

            return back()->with('success', "Cloud synchronization for {$monthName} {$year} completed securely.");

        } catch (\Exception $e) {
            $sync->update(['status' => 'failed', 'error_log' => $e->getMessage()]);
            \Illuminate\Support\Facades\Log::error("CloudSync Error: " . $e->getMessage());
            return back()->with('error', 'Synchronization failed. Please verify your .env Google credentials or check the logs. Error: ' . $e->getMessage());
        }
    }
}
