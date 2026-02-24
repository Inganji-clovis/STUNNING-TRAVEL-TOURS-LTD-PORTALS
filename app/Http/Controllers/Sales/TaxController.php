<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'pension');
        $query = \App\Models\TaxCompliance::where('type', $type)->with('uploader');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function(\Illuminate\Database\Eloquent\Builder $q) use ($search) {
                $q->where('declaration_number', 'like', "%{$search}%");
            });
        }

        $records = $query->latest()->get();
        
        $stats = [
            'total_declared' => \App\Models\TaxCompliance::where('type', $type)->sum('declared_amount'),
            'total_payment' => \App\Models\TaxCompliance::where('type', $type)->sum('receipt_amount'),
            'pending_payments' => \App\Models\TaxCompliance::where('type', $type)->whereNull('receipt_amount')->count(),
            'compliance_ratio' => '0%'
        ];
        
        if ($stats['total_declared'] > 0) {
            $stats['compliance_ratio'] = number_format(($stats['total_payment'] / $stats['total_declared']) * 100, 1) . '%';
        }

        return view('sales.tax.index', compact('records', 'type', 'stats'));
    }

    public function create(Request $request)
    {
        $type = $request->get('type', 'pension');
        return view('sales.tax.create', compact('type'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:pension,maternity,bhi,rra',
            'declaration_number' => 'required|string|max:255',
            'declared_amount' => 'required|numeric|min:0',
            'declaration_file' => 'required|file|mimes:pdf|max:10240',
        ]);

        $declPath = $request->file('declaration_file')->store('tax_vault/' . $data['type'] . '/declarations', 'public');

        \App\Models\TaxCompliance::create([
            'type' => $data['type'],
            'declaration_number' => $data['declaration_number'],
            'declared_amount' => $data['declared_amount'],
            'declaration_file' => $declPath,
            'uploaded_by' => auth()->id(),
        ]);

        \App\Services\ActivityLogger::log('Sales', 'Tax Hub', 'Create', $data['declaration_number'], "Filed {$data['type']} tax compliance (Phase 1)");

        return redirect()->route('sales.tax.index', ['type' => $data['type']])->with('success', 'Phase 1: Tax filing recorded successfully.');
    }

    public function update(Request $request, $id)
    {
        $tax = \App\Models\TaxCompliance::findOrFail($id);
        
        $data = $request->validate([
            'receipt_amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'receipt_file' => 'required|file|mimes:pdf|max:10240',
        ]);

        if ($data['receipt_amount'] != $tax->declared_amount) {
            return back()->withErrors(['receipt_amount' => 'Inconsistent amounts: Receipt amount must precisely match declared amount.']);
        }

        $receiptPath = $request->file('receipt_file')->store('tax_vault/' . $tax->type . '/receipts', 'public');

        $tax->update([
            'receipt_amount' => $data['receipt_amount'],
            'payment_date' => $data['payment_date'],
            'receipt_file' => $receiptPath,
        ]);

        \App\Services\ActivityLogger::log('Sales', 'Tax Hub', 'Update', $tax->declaration_number, "Paid {$tax->type} tax compliance (Phase 2)");

        return redirect()->route('sales.tax.index', ['type' => $tax->type])->with('success', 'Phase 2: Tax payment recorded successfully.');
    }
}
