<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EbmController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Ebm::query();

        if ($request->filled('search')) {
            $query->where('company_name', 'like', '%' . $request->search . '%')
                  ->orWhere('tin_number', 'like', '%' . $request->search . '%');
        }

        $ebms = $query->with('uploader', 'invoice')->latest()->paginate(10);
        return view('sales.ebm.index', compact('ebms'));
    }

    public function create()
    {
        $invoices = \App\Models\BillingDocument::all();
        return view('sales.ebm.create', compact('invoices'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'tin_number' => 'required|string|max:20',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'amount_rwf' => 'required|numeric|min:0',
            'vat_amount' => 'nullable|numeric|min:0',
            'billing_document_id' => 'nullable|exists:billing_documents,id',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,jpg,jpeg,png|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->store('ebm_vault/' . date('Y'), 'public');

        \App\Models\Ebm::create([
            'company_name' => $data['company_name'],
            'tin_number' => $data['tin_number'],
            'description' => $data['description'],
            'date' => $data['date'],
            'amount_rwf' => $data['amount_rwf'],
            'vat_amount' => $data['vat_amount'],
            'billing_document_id' => $data['billing_document_id'],
            'file_path' => $path,
            'uploaded_by' => auth()->id(),
        ]);

        \App\Services\ActivityLogger::log('Sales', 'EBM Registry', 'Create', $data['tin_number'], "Archived EBM for {$data['company_name']}");

        return redirect()->route('sales.ebm.index')->with('success', 'EBM record stored securely.');
    }
}
