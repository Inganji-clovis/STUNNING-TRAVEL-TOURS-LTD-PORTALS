@extends('layouts.app')
@section('title', 'New Tax Filing: ' . strtoupper($type))

@section('content')
<div class="max-w-4xl animate-in slide-in-from-bottom duration-700">
    <div class="mb-8">
        <a href="{{ route('sales.tax.index', ['type' => $type]) }}" class="text-slate-400 hover:safari-green transition-colors flex items-center space-x-2 mb-4 font-bold text-xs uppercase tracking-widest">
            <span>← Back to {{ strtoupper($type) }} Registry</span>
        </a>
        <h1 class="text-4xl font-extrabold text-slate-900 tracking-tighter underline decoration-safari-green decoration-4 underline-offset-8">Regulatory Filing</h1>
        <p class="text-slate-400 mt-4 font-medium uppercase text-sm tracking-[0.3em]">Institutional Compliance Record: {{ strtoupper($type) }}</p>
    </div>

    <form action="{{ route('sales.tax.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Part 1: Declaration -->
            <div class="premium-card p-10 rounded-3xl space-y-6 relative overflow-hidden bg-white">
                <div class="absolute top-0 right-0 p-6 opacity-[0.03] pointer-events-none">
                    <span class="text-8xl font-black text-slate-900">01</span>
                </div>
                <h2 class="text-sm font-black text-safari-green uppercase tracking-[0.2em] border-b border-slate-50 pb-4">Audit Phase: Declaration</h2>
                
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-black text-slate-400 uppercase tracking-widest ml-1">Auth Code / Declaration #</label>
                        <input type="text" name="declaration_number" required placeholder="DEC-XXXXX" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-4 focus:outline-none focus:border-safari-green focus:ring-4 focus:ring-safari-green/5 transition-all font-mono font-bold text-slate-900 placeholder:text-slate-300">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-black text-slate-400 uppercase tracking-widest ml-1">Assessed Value (RWF)</label>
                        <input type="number" name="declared_amount" required placeholder="0.00" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-4 focus:outline-none focus:border-safari-green transition-all font-black text-xl text-slate-900 tabular-nums placeholder:text-slate-300">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-black text-slate-400 uppercase tracking-widest ml-1">Documentation (PDF)</label>
                        <div class="relative group">
                            <input type="file" name="declaration_file" required class="w-full text-sm text-slate-400 font-bold file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-black file:bg-slate-100 file:text-slate-500 hover:file:bg-safari-green hover:file:text-white transition-all cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Part 2: Payment Receipt -->
            <div class="premium-card p-10 rounded-3xl space-y-6 relative overflow-hidden bg-white shadow-xl shadow-slate-200/40">
                <div class="absolute top-0 right-0 p-6 opacity-[0.03] pointer-events-none">
                    <span class="text-8xl font-black text-slate-900">02</span>
                </div>
                <h2 class="text-sm font-black text-sunset-orange uppercase tracking-[0.2em] border-b border-slate-50 pb-4">Verification Phase: Payment</h2>
                
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-black text-slate-400 uppercase tracking-widest ml-1">Receipted Value (RWF)</label>
                        <input type="number" name="receipt_amount" placeholder="Matching Amount" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-4 focus:outline-none focus:border-sunset-orange focus:ring-4 focus:ring-sunset-orange/5 transition-all font-black text-xl text-sunset-orange tabular-nums placeholder:text-slate-300">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-black text-slate-400 uppercase tracking-widest ml-1">Execution Date</label>
                        <input type="date" name="payment_date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-4 focus:outline-none focus:border-sunset-orange transition-all font-bold text-slate-900">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-black text-slate-400 uppercase tracking-widest ml-1">Evidence (PDF)</label>
                        <div class="relative group">
                            <input type="file" name="receipt_file" class="w-full text-sm text-slate-400 font-bold file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-black file:bg-slate-100 file:text-slate-500 hover:file:bg-sunset-orange hover:file:text-white transition-all cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-50 mt-12">
            <button type="submit" class="w-full bg-red-700 hover:bg-red-900 py-5 rounded-lg font-black text-white uppercase tracking-widest text-base shadow-xl shadow-red-900/20 transition-all hover:-translate-y-1 transform active:scale-[0.98]">
                Execute Compliance Record Ingestion
            </button>>
            <p class="text-center text-sm text-slate-400 mt-6 font-black uppercase tracking-[0.3em]">Institutional Verification Phase is optional if payment is pending archive</p>
        </div>
    </form>
</div>
@endsection
