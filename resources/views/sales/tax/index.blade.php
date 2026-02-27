@extends('layouts.app')
@section('title', 'Tax & Compliance Hub')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-slate-900 italic">Tax & <span class="text-red-700">Compliance Hub</span></h1>
            <p class="text-slate-400 mt-1 text-xs font-bold tracking-[0.1em] uppercase opacity-70">Consolidated RSSB & RRA regulatory filings</p>
        </div>
        
        <div class="flex gap-4 w-full lg:w-auto items-center">
            <a href="{{ route('sales.tax.create', ['type' => $type]) }}" class="bg-red-700 hover:bg-red-800 text-white px-6 py-2.5 rounded-lg text-base font-bold transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-900/40 text-center flex-1 lg:flex-none">
                + New Filing
            </a>
        </div>
    </div>

    <!-- Compliance Summary -->
    <div class="bg-white/40 backdrop-blur-xl p-8 rounded-2xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-red-700/20 transition-all duration-500">
        <h2 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-3 italic">
            <span class="w-1.5 h-6 bg-red-700 rounded-full"></span>
            Compliance Summary: {{ strtoupper($type) }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="p-6 bg-white/60 border border-slate-200/50 rounded-xl hover:bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-red-900/10">
                <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-2">Total Declared</p>
                <div class="flex items-baseline">
                    <h3 class="text-3xl font-black text-slate-900 tabular-nums">{{ number_format($stats['total_declared']) }}</h3>
                    <span class="text-sm font-black text-slate-400 ml-2 uppercase tracking-widest">RWF</span>
                </div>
            </div>
            
            <div class="p-6 bg-white/60 border border-slate-200/50 rounded-xl hover:bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-red-900/10">
                <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-2">Total Payment</p>
                <div class="flex items-baseline">
                    <h3 class="text-3xl font-black text-slate-900 tabular-nums">{{ number_format($stats['total_payment']) }}</h3>
                    <span class="text-sm font-black text-slate-400 ml-2 uppercase tracking-widest">RWF</span>
                </div>
            </div>

            <div class="p-6 bg-red-50/50 border border-red-700/10 rounded-xl hover:bg-red-50/80 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-red-900/20">
                <p class="text-red-800 text-xs font-black uppercase tracking-widest mb-2">Pending Payments</p>
                <div class="flex items-baseline">
                    <h3 class="text-3xl font-black text-red-900 tabular-nums">{{ number_format($stats['pending_payments']) }}</h3>
                    <span class="text-sm font-black text-red-400 ml-2 uppercase tracking-widest">Files</span>
                </div>
            </div>

            <div class="p-6 bg-slate-50 border border-slate-200/50 rounded-xl hover:bg-white flex flex-col justify-center items-center transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-900/5">
                <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-1">Compliance Ratio</p>
                <h3 class="text-2xl font-black text-slate-900">{{ $stats['compliance_ratio'] }}</h3>
            </div>
        </div>
    </div>

    <!-- Sub-Navigation Tabs -->
    <div class="flex flex-wrap gap-3">
        @foreach(['pension' => 'Pension RSSB', 'maternity' => 'Maternity RSSB', 'bhi' => 'BHI RSSB', 'rra' => 'RRA TAX'] as $key => $label)
            <a href="{{ route('sales.tax.index', ['type' => $key]) }}" 
               class="px-6 py-3 rounded-lg font-bold text-sm transition-all duration-300 shadow-sm border {{ $type === $key ? 'bg-red-700 text-white border-red-700 shadow-red-900/20 scale-105' : 'bg-white/60 text-slate-500 border-slate-200 hover:border-red-700/50 hover:text-red-700 hover:bg-white backdrop-blur-md' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- Search -->
    <div class="bg-white/40 backdrop-blur-xl p-6 rounded-2xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        <form action="{{ route('sales.tax.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center">
            <input type="hidden" name="type" value="{{ $type }}">
            <div class="flex-1 w-full relative group">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Auth Code..." class="w-full bg-white/60 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-red-700 hover:border-red-700/50 transition-all pl-10 shadow-sm">
                <span class="absolute left-3 top-2.5 text-slate-400 group-focus-within:text-red-700 transition-colors">🔍</span>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" class="bg-red-700 hover:bg-red-800 text-white px-8 py-2.5 rounded-lg text-sm font-bold transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-900/40 w-full md:w-auto">Filter</button>
                @if(request('search'))
                    <a href="{{ route('sales.tax.index', ['type' => $type]) }}" class="bg-white/60 hover:bg-white text-slate-600 px-6 py-2.5 rounded-lg text-sm font-bold transition-all border border-slate-200 w-full md:w-auto text-center">Clear</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Registry Table -->
    <div class="bg-white/40 backdrop-blur-xl border border-white/40 rounded-2xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="border-b border-white/40 bg-white/60 text-sm uppercase tracking-widest text-slate-400 font-black">
                        <th class="px-3 py-4">Auth Code</th>
                        <th class="px-3 py-4 text-right whitespace-nowrap">Declared Value</th>
                        <th class="px-3 py-4 text-right whitespace-nowrap">Receipt Value</th>
                        <th class="px-3 py-4 whitespace-nowrap">Declaration Date</th>
                        <th class="px-3 py-4 whitespace-nowrap">Payment Date</th>
                        <th class="px-3 py-4 whitespace-nowrap">Status</th>
                        <th class="px-3 py-4">Filed By</th>
                        <th class="px-3 py-4 font-black"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/40">
                    @forelse ($records as $record)
                    <tr class="hover:bg-white/60 transition-colors duration-300 group">
                        <td class="px-3 py-4">
                            <span class="font-black text-slate-900 font-mono">{{ $record->declaration_number }}</span>
                        </td>
                        <td class="px-3 py-4 text-right whitespace-nowrap">
                            <div class="font-black text-slate-900 tabular-nums">{{ number_format($record->declared_amount) }}</div>
                            <div class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-0.5">RWF</div>
                        </td>
                        <td class="px-3 py-4 text-right whitespace-nowrap">
                            @if($record->receipt_amount)
                                <div class="font-black text-slate-900 tabular-nums">{{ number_format($record->receipt_amount) }}</div>
                                <div class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-0.5">RWF</div>
                            @else
                                <span class="text-slate-400 font-bold">---</span>
                            @endif
                        </td>
                        <td class="px-3 py-4 text-xs font-semibold text-slate-700 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($record->created_at)->format('d M Y') }}
                        </td>
                        <td class="px-3 py-4 text-xs font-semibold text-slate-700 whitespace-nowrap">
                            {{ $record->payment_date ? \Carbon\Carbon::parse($record->payment_date)->format('d M Y') : 'Pending' }}
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            @if($record->receipt_file)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-black bg-slate-100 text-slate-600 uppercase tracking-widest border border-slate-200">Paid</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-black bg-red-50/80 text-red-500 uppercase tracking-widest border border-red-200/50">Declared</span>
                            @endif
                        </td>
                        <td class="px-3 py-4 text-sm font-bold text-slate-500 uppercase tracking-widest">
                            {{ $record->uploader->name ?? 'System' }}
                        </td>
                        <td class="px-3 py-4 text-right relative whitespace-nowrap" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="text-slate-400 hover:text-red-700 transition-colors p-2 focus:outline-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                            </button>
                            
                            <div x-show="open" x-transition.opacity.scale.origin.top.right class="absolute right-8 top-10 w-48 bg-white/90 backdrop-blur-md border border-white/40 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] z-10 py-2" style="display: none;">
                                <a href="{{ route('vault.access', ['type' => 'tax-declaration', 'id' => $record->id]) }}" target="_blank" class="block px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-red-700 transition-colors">View Declaration (Phase 1)</a>
                                @if($record->receipt_file)
                                    <a href="{{ route('vault.access', ['type' => 'tax-receipt', 'id' => $record->id]) }}" target="_blank" class="block px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-red-700 transition-colors">View Receipt (Phase 2)</a>
                                @else
                                    <div class="border-t border-slate-200/50 my-1"></div>
                                    <button @click="$dispatch('open-payment-modal', { id: {{ $record->id }}, declared: {{ $record->declared_amount }}, ref: '{{ $record->declaration_number }}' }); open = false;" class="block w-full text-left px-4 py-2 text-sm font-black text-red-700 hover:bg-red-50 transition-colors">
                                        Pay (Phase 2)
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-400 font-semibold tracking-wide border-t border-white/40">
                            No compliance records discovered in this registry.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Phase 2 Payment Modal -->
<div x-data="{ open: false, recordId: null, declaredAmount: 0, refNum: '' }" 
     x-show="open" 
     @open-payment-modal.window="open = true; recordId = $event.detail.id; declaredAmount = $event.detail.declared; refNum = $event.detail.ref;"
     class="fixed inset-0 z-50 flex items-center justify-center px-4"
     style="display: none;">
    
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" 
         x-show="open" 
         x-transition.opacity 
         @click="open = false"></div>

    <div class="bg-white/90 backdrop-blur-xl border border-white/40 rounded-2xl shadow-2xl overflow-hidden w-full max-w-lg relative z-10 transform transition-all"
         x-show="open" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0 scale-95 translate-y-4" 
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
         x-transition:leave-end="opacity-0 scale-95 translate-y-4">
        
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200/50 bg-white/50">
            <h3 class="text-lg font-black text-slate-900 italic">Phase 2: <span class="text-red-700">Payment</span></h3>
            <button @click="open = false" class="text-slate-400 hover:text-red-700 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form x-bind:action="'/sales/tax/' + recordId" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <p class="text-sm font-semibold text-slate-500 mb-4">You are paying <span class="font-black text-slate-900" x-text="refNum"></span>. Strict protocol requires the receipt amount to perfectly match the declared amount of <span class="font-black text-slate-900" x-text="declaredAmount.toLocaleString()"></span> RWF.</p>
            
            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-1">Receipt Amount (RWF) <span class="text-red-500">*</span></label>
                <input type="number" name="receipt_amount" required x-bind:value="declaredAmount" readonly class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-semibold focus:outline-none shadow-sm cursor-not-allowed text-slate-500">
                <p class="text-sm text-slate-400 mt-1 font-bold">LOCKED for Data Integrity</p>
            </div>
            
            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-1">Payment Date <span class="text-red-500">*</span></label>
                <input type="date" name="payment_date" required class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-red-700 hover:border-red-700/50 transition-all shadow-sm">
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-1">Receipt Document (PDF) <span class="text-red-500">*</span></label>
                <input type="file" name="receipt_file" required class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-red-700 hover:border-red-700/50 transition-all shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
            </div>

            <div class="pt-4 flex justify-end gap-3">
                <button type="button" @click="open = false" class="bg-white hover:bg-slate-50 text-slate-600 px-6 py-2.5 rounded-lg text-sm font-bold transition-all border border-slate-200 shadow-sm">Cancel</button>
                <button type="submit" class="bg-red-700 hover:bg-red-800 text-white px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-900/40">Secure Payment</button>
            </div>
        </form>
    </div>
</div>
@endsection
