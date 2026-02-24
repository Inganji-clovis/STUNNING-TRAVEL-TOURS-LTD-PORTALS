@extends('layouts.app')
@section('title', 'Archive EBM')

@section('content')
<div class="max-w-3xl animate-in slide-in-from-bottom duration-700">
    <div class="mb-8">
        <a href="{{ route('sales.ebm.index') }}" class="text-slate-400 hover:text-red-700 transition-colors flex items-center space-x-2 mb-4 font-bold text-xs uppercase tracking-widest">
            <span>← Back to EBM Registry</span>
        </a>
        <h1 class="text-4xl font-extrabold text-slate-900 tracking-tighter underline decoration-red-700 decoration-4 underline-offset-8">Archive EBM</h1>
        <p class="text-slate-400 mt-4 font-medium uppercase text-[0.65rem] tracking-[0.3em]">Electronic Billing Machine Protocol</p>
    </div>

    <div class="premium-card p-12 rounded-3xl relative overflow-hidden">
        <div class="absolute top-0 right-0 p-8 opacity-[0.03] pointer-events-none">
            <img src="{{ asset('assets/brand/logo.png') }}" alt="Logo" class="w-24 grayscale">
        </div>

        <form action="{{ route('sales.ebm.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            <div class="grid grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest ml-1">Entity Designation</label>
                    <input type="text" name="company_name" required placeholder="Enter company name" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all font-bold text-lg placeholder:text-slate-300">
                </div>
                <div class="space-y-2">
                    <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest ml-1">TIN Identifier</label>
                    <input type="text" name="tin_number" required placeholder="123 456 789" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all font-mono text-lg tracking-widest placeholder:text-slate-300">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest ml-1">Archive Date</label>
                    <div class="relative">
                        <input type="date" name="date" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-red-700 transition-all text-slate-900 font-bold appearance-none">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest ml-1">Linked Invoice (Optional)</label>
                    <select name="billing_document_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-red-700 transition-all font-bold text-slate-900 appearance-none cursor-pointer">
                        <option value="">-- No Direct Link --</option>
                        @foreach($invoices ?? [] as $invoice)
                            <option value="{{ $invoice->id }}">{{ $invoice->document_number }} - {{ $invoice->client->name ?? 'Unknown' }} ({{ number_format($invoice->amount) }} {{ $invoice->currency }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest ml-1">Transaction Value (RWF)</label>
                    <input type="number" name="amount_rwf" required placeholder="0.00" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all font-black text-xl text-slate-900 tabular-nums placeholder:text-slate-300">
                </div>
                <div class="space-y-2">
                    <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest ml-1">Reported VAT (RWF)</label>
                    <input type="number" name="vat_amount" placeholder="0.00" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all font-black text-xl text-slate-700 tabular-nums placeholder:text-slate-300">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest ml-1">Dossier Description</label>
                <textarea name="description" rows="3" placeholder="Briefly describe the EBM transaction purpose..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-red-700 transition-all italic font-medium text-slate-600 placeholder:text-slate-300"></textarea>
            </div>

            <div class="group space-y-2">
                <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest ml-1">Binary Evidence Archival</label>
                <div class="relative w-full h-44 border-2 border-dashed border-slate-200 rounded-2xl flex flex-col items-center justify-center group-hover:border-red-700/50 hover:bg-slate-50 transition-all bg-slate-50/50 overflow-hidden">
                    <input type="file" name="file" required class="absolute inset-0 opacity-0 cursor-pointer z-10">
                    <div class="text-4xl mb-3 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500">📄</div>
                    <p class="text-sm text-slate-500 font-bold">Deploy intelligence or <span class="text-red-700">browse secure files</span></p>
                    <p class="text-[0.6rem] text-slate-400 mt-2 font-black uppercase tracking-widest underline underline-offset-4 decoration-slate-200">PDF, JPG, PNG (MAX 10MB)</p>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-50">
                <button type="submit" class="w-full bg-red-700 hover:bg-red-900 py-5 rounded-lg font-black text-white uppercase tracking-widest text-base shadow-xl shadow-red-900/20 transition-all hover:-translate-y-1 transform active:scale-[0.98]">
                    Securely Archive Protocol Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
