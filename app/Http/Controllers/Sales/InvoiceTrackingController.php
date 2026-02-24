<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceTrackingController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\BillingDocument::with('client', 'organization')
            ->whereIn('type', ['invoice', 'proforma']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function(\Illuminate\Database\Eloquent\Builder $q) use ($search) {
                $q->where('document_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  })->orWhereHas('organization', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->filled('date_start')) {
            $query->whereDate('issue_date', '>=', $request->date_start);
        }
        if ($request->filled('date_end')) {
            $query->whereDate('issue_date', '<=', $request->date_end);
        }
        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }
        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }

        $clients = \App\Models\Client::orderBy('name')->get();
        $invoices = $query->latest()->get();
        return view('sales.invoices.index', compact('invoices', 'clients'));
    }

    public function update(Request $request, $id)
    {
        $invoice = \App\Models\BillingDocument::findOrFail($id);
        $request->validate(['status' => 'required|in:pending,paid,cancelled']);
        
        $invoice->update(['status' => $request->status]);

        \App\Services\ActivityLogger::log('Sales', 'Invoice Tracker', 'Update', $invoice->document_number, "Changed status to {$request->status}");

        return back()->with('success', 'Invoice status updated.');
    }}
