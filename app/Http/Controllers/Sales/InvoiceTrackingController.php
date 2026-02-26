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

    public function createIndividual(Request $request)
    {
        $client_name = $request->get('client_name');
        return view('sales.invoices.create_individual', compact('client_name'));
    }

    public function createOrganization()
    {
        $organizations = \App\Models\Organization::all();
        return view('sales.invoices.create_organization', compact('organizations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_names' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:RWF,USD',
            'date' => 'required|date',
            'organization_id' => 'nullable|exists:organizations,id',
            'tin' => 'nullable|string|max:50',
            'rate' => 'nullable|numeric|min:0',
            'amount_in_words' => 'nullable|string|max:500',
            'items' => 'nullable|array',
            'items.*.passenger_name' => 'nullable|string|max:255',
            'items.*.airline' => 'nullable|string|max:255',
            'items.*.destination' => 'required|string|max:255',
            'items.*.price_usd' => 'nullable|numeric|min:0',
            'items.*.price_rwf' => 'nullable|numeric|min:0',
        ]);

        // Find or Create Client
        $client = \App\Models\Client::firstOrCreate(
            ['name' => $request->full_names],
            [
                'organization_id' => $request->organization_id,
                'registered_by' => auth()->id(),
            ]
        );

        if ($request->filled('tin')) {
            $client->update(['tin' => $request->tin]);
        }

        $invoice = \App\Models\BillingDocument::create([
            'client_id' => $client->id,
            'organization_id' => $request->organization_id,
            'type' => 'invoice',
            'amount' => $request->amount,
            'currency' => $request->currency,
            'rate' => $request->rate,
            'amount_in_words' => $request->amount_in_words,
            'status' => 'pending',
            'file_path' => 'generated_on_request', // Placeholder
            'generated_by' => auth()->id(),
            'created_at' => $request->date,
        ]);

        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $item) {
                $invoice->items()->create([
                    'passenger_name' => $item['passenger_name'] ?? null,
                    'airline' => $item['airline'] ?? null,
                    'destination' => $item['destination'],
                    'price_usd' => $item['price_usd'] ?? null,
                    'price_rwf' => $item['price_rwf'] ?? null,
                ]);
            }
        }

        \App\Services\ActivityLogger::log('Sales', 'Invoices', 'Create', $invoice->document_number ?? 'INV-NEW', "Generated invoice for {$client->name}");

        return redirect()->route('sales.invoices.index')
            ->with('success', 'Invoice document recorded successfully.');
    }

    public function edit(\App\Models\BillingDocument $invoice)
    {
        $organizations = \App\Models\Organization::all();
        return view('sales.invoices.edit', compact('invoice', 'organizations'));
    }

    public function update(Request $request, \App\Models\BillingDocument $invoice)
    {
        $data = $request->validate([
            'amount' => 'sometimes|required|numeric|min:0',
            'currency' => 'sometimes|required|in:RWF,USD',
            'status' => 'required|in:pending,paid,cancelled',
        ]);

        $invoice->update($data);

        \App\Services\ActivityLogger::log('Sales', 'Invoices', 'Update', $invoice->document_number ?? 'INV-'.$invoice->id, "Updated invoice details/status to {$data['status']}");

        return redirect()->route('sales.invoices.index')
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(\App\Models\BillingDocument $invoice)
    {
        $documentNumber = $invoice->document_number ?? 'INV-'.$invoice->id;
        $invoice->delete();

        \App\Services\ActivityLogger::log('Sales', 'Invoices', 'Delete', $documentNumber, "Deleted invoice record");
        return back()->with('success', 'Invoice deleted successfully.');
    }

    public function generatePdf(\App\Models\BillingDocument $invoice)
    {
        $invoice->load(['client', 'organization', 'items']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ops.invoice.pdf.template', compact('invoice'));
        
        \App\Services\ActivityLogger::log('Sales', 'Invoices', 'Download', $invoice->document_number, "Generated PDF Invoice");

        return $pdf->stream('Invoice_' . $invoice->document_number . '.pdf');
    }}
