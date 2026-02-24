<?php

namespace App\Http\Controllers\Ops;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\BillingDocument;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'individual');
        $search = $request->get('search');

        $query = BillingDocument::where('type', 'invoice')->with(['client', 'organization']);

        if ($type === 'individual') {
            $query->whereNull('organization_id');
        } else {
            $query->whereNotNull('organization_id');
        }

        if ($search) {
            $query->where(function(\Illuminate\Database\Eloquent\Builder $q) use ($search) {
                $q->where('document_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $documents = $query->latest()->get();

        return view('ops.invoice.index', compact('documents', 'type', 'search'));
    }

    public function createIndividual(Request $request)
    {
        $client_name = $request->get('client_name');
        return view('ops.invoice.create_individual', compact('client_name'));
    }

    public function createOrganization()
    {
        $organizations = \App\Models\Organization::all();
        return view('ops.invoice.create_organization', compact('organizations'));
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
        $client = Client::firstOrCreate(
            ['name' => $request->full_names],
            [
                'organization_id' => $request->organization_id,
                'registered_by' => auth()->id(),
            ]
        );

        if ($request->filled('tin')) {
            $client->update(['tin' => $request->tin]);
        }

        $invoice = BillingDocument::create([
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

        \App\Services\ActivityLogger::log('Operations', 'Invoices', 'Create', $invoice->document_number ?? 'INV-NEW', "Generated invoice for {$client->name}");

        return redirect()->route('ops.invoice.index', ['type' => $request->organization_id ? 'organization' : 'individual'])
            ->with('success', 'Invoice document recorded successfully.');
    }

    public function edit(BillingDocument $invoice)
    {
        $organizations = \App\Models\Organization::all();
        return view('ops.invoice.edit', compact('invoice', 'organizations'));
    }

    public function update(Request $request, BillingDocument $invoice)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:RWF,USD',
            'status' => 'required|in:pending,paid,cancelled',
        ]);

        $invoice->update($data);

        \App\Services\ActivityLogger::log('Operations', 'Invoices', 'Update', $invoice->document_number ?? 'INV-'.$invoice->id, "Updated invoice status to {$data['status']}");

        return redirect()->route('ops.invoice.index', ['type' => $invoice->organization_id ? 'organization' : 'individual'])
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(BillingDocument $invoice)
    {
        $documentNumber = $invoice->document_number ?? 'INV-'.$invoice->id;
        $invoice->delete();

        \App\Services\ActivityLogger::log('Operations', 'Invoices', 'Delete', $documentNumber, "Deleted invoice record");
        return back()->with('success', 'Invoice deleted successfully.');
    }

    public function generatePdf(BillingDocument $invoice)
    {
        $invoice->load(['client', 'organization', 'items']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ops.invoice.pdf.template', compact('invoice'));
        
        \App\Services\ActivityLogger::log('Operations', 'Invoices', 'Download', $invoice->document_number, "Generated PDF Invoice");

        return $pdf->stream('Invoice_' . $invoice->document_number . '.pdf');
    }
}
