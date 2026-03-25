@extends('layouts.app')
@section('title', 'Edit Invoice')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in duration-700">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('ops.invoice.index') }}" class="text-slate-400 hover:text-white transition-colors flex items-center space-x-2 mb-4">
                <span>Ã¢â€ Â Back to Invoices</span>
            </a>
            <h1 class="text-3xl font-bold italic tracking-tighter">Edit Invoice <span class="text-red-600">#{{ $invoice->document_number }}</span></h1>
            <p class="text-slate-400">Updating billing record for {{ $invoice->client->name }}</p>
        </div>
    </div>

    <div class="glass border border-slate-200 p-8 rounded-3xl">
        <form action="{{ route('ops.invoice.update', $invoice) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Amount -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Amount</label>
                    <input type="number" name="amount" value="{{ $invoice->amount }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all">
                </div>

                <!-- Currency -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Currency</label>
                    <select name="currency" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all appearance-none">
                        <option value="RWF" {{ $invoice->currency === 'RWF' ? 'selected' : '' }}>RWF</option>
                        <option value="USD" {{ $invoice->currency === 'USD' ? 'selected' : '' }}>USD</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                 <!-- Status -->
                 <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Status</label>
                    <select name="status" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all appearance-none">
                        <option value="pending" {{ $invoice->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $invoice->status === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="cancelled" {{ $invoice->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Date (Read-only for now) -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Original Date</label>
                    <input type="text" value="{{ $invoice->created_at->format('Y-m-d') }}" disabled class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-slate-500 outline-none">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-red-600/20">
                    Update Invoice Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
