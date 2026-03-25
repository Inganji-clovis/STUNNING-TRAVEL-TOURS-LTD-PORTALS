<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\TravelDocument;
use App\Models\Ticket;
use App\Models\TaxCompliance;
use App\Models\Ebm;
use App\Models\CorporateDocument;
use App\Services\ActivityLogger;

class DocumentVaultController extends Controller
{
    public function access($type, $id)
    {
        $document = null;
        $path = '';
        $reference = '';
        $module = '';

        switch ($type) {
            case 'passport':
                $document = TravelDocument::findOrFail($id);
                $path = $document->file_path;
                $reference = $document->passport_number;
                $module = 'Passport Registry';
                break;
            case 'ticket':
                $document = Ticket::findOrFail($id);
                $path = $document->file_path;
                $reference = $document->pnr ?? $document->ticket_number;
                $module = 'Ticket Module';
                break;
            case 'tax-declaration':
                $document = TaxCompliance::findOrFail($id);
                $path = $document->declaration_file;
                $reference = $document->declaration_number ?? $document->type;
                $module = 'Tax Compliance (Declaration)';
                break;
            case 'tax-receipt':
                $document = TaxCompliance::findOrFail($id);
                $path = $document->receipt_file;
                $reference = $document->declaration_number ?? $document->type;
                $module = 'Tax Compliance (Receipt)';
                break;
            case 'ebm':
                $document = Ebm::findOrFail($id);
                $path = $document->file_path;
                $reference = $document->invoice_number;
                $module = 'EBM Registry';
                break;
            case 'corporate':
                $document = CorporateDocument::findOrFail($id);
                $path = $document->file_path;
                $reference = $document->title;
                $module = 'Corporate Vault';
                break;
            default:
                abort(404);
        }

        // Search logic: prioritize local (private), fallback to public (migration phase)
        $disk = 'local';
        if (!Storage::disk($disk)->exists($path)) {
            $disk = 'public';
        }

        if (!$path || !Storage::disk($disk)->exists($path)) {
            abort(404);
        }

        $file = Storage::disk($disk)->get($path);
        $mimeType = Storage::disk($disk)->mimeType($path);

        $this->logView($module, $type, $reference);

        return response($file, 200)->header('Content-Type', $mimeType);
    }

    private function logView($module, $type, $reference)
    {
        ActivityLogger::log(
            'Secure Vault',
            $module,
            'View',
            $reference,
            "Accessed sensitive " . strtoupper($type) . " document."
        );
    }
}
