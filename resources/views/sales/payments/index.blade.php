@extends('layouts.app')
@section('title', 'Client Payment Tracking')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-slate-900 italic">Client <span class="text-red-700">Payments</span></h1>
            <p class="text-slate-400 mt-1 text-xs font-bold tracking-[0.1em] uppercase opacity-70">Financial tracking & payment auditing</p>
        </div>
        
        <div class="flex gap-4 w-full lg:w-auto items-center">
            <a href="{{ route('sales.payments.create') }}" class="bg-red-700 hover:bg-red-800 text-white px-6 py-2.5 rounded-lg text-base font-bold transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-900/40 text-center flex-1 lg:flex-none">
                + Record Payment
            </a>
        </div>
    </div>

    <!-- Summary Strip -->
    <div class="bg-white/40 backdrop-blur-xl p-8 rounded-2xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-red-700/20 transition-all duration-500">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="p-6 bg-white/60 border border-slate-200/50 rounded-xl hover:border-red-700/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-red-900/10 hover:bg-white">
                <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-2">Total payment</p>
                <div class="flex items-baseline">
                    <h3 class="text-3xl font-black text-slate-900 tabular-nums">{{ number_format($stats['total_payment']) }}</h3>
                    <span class="text-[0.6rem] font-black text-slate-400 ml-2 uppercase tracking-widest">RWF</span>
                </div>
            </div>
            
            <div class="p-6 bg-white/60 border border-slate-200/50 rounded-xl hover:border-red-700/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-red-900/10 hover:bg-white">
                <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-2">Total Outstanding</p>
                <div class="flex items-baseline">
                    <h3 class="text-3xl font-black text-slate-900 tabular-nums">{{ number_format($stats['total_outstanding']) }}</h3>
                    <span class="text-[0.6rem] font-black text-slate-400 ml-2 uppercase tracking-widest">RWF</span>
                </div>
            </div>

            <div class="p-6 bg-red-50/50 border border-red-700/10 rounded-xl hover:bg-red-50/80 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-red-900/20">
                <p class="text-red-800 text-xs font-black uppercase tracking-widest mb-2">Total Overdue</p>
                <div class="flex items-baseline">
                    <h3 class="text-3xl font-black text-red-900 tabular-nums">{{ number_format($stats['total_overdue']) }}</h3>
                    <span class="text-[0.6rem] font-black text-slate-400 ml-2 uppercase tracking-widest">RWF</span>
                </div>
            </div>

            <div class="p-6 bg-slate-50 border border-slate-200/50 rounded-xl flex flex-col justify-center items-center hover:bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-900/5">
                <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-1">Avg Payment Time</p>
                <h3 class="text-2xl font-black text-slate-900 tabular-nums">{{ number_format($stats['average_payment_time']) }} <span class="text-xs font-bold text-slate-400">Days</span></h3>
            </div>
        </div>
    </div>

    <!-- Advanced Search -->
    <div class="bg-white/40 backdrop-blur-xl p-6 rounded-2xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        <form action="{{ route('sales.payments.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between group">
            <div class="w-full md:w-1/2 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Client, Method, Ref..." class="w-full bg-white/60 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-red-700 hover:border-red-700/50 transition-all pl-10 shadow-sm">
                <span class="absolute left-3 top-2.5 text-slate-400 group-focus-within:text-red-700 transition-colors">🔍</span>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" class="bg-red-700 hover:bg-red-800 text-white px-8 py-2.5 rounded-lg text-sm font-bold transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-900/40 w-full md:w-auto">Apply Filter</button>
                @if(request('search'))
                    <a href="{{ route('sales.payments.index') }}" class="bg-white/60 hover:bg-white text-slate-600 px-6 py-2.5 rounded-lg text-sm font-bold transition-all border border-slate-200 text-center w-full md:w-auto">Clear</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Registry Table -->
    <div class="bg-white/40 backdrop-blur-xl border border-white/40 rounded-2xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="border-b border-white/40 bg-white/60 text-[0.6rem] uppercase tracking-widest text-slate-400 font-black">
                        <th class="px-6 py-4">Invoice Reference</th>
                        <th class="px-6 py-4">Client</th>
                        <th class="px-6 py-4">Payment Method</th>
                        <th class="px-6 py-4 text-right">Paid Amount</th>
                        <th class="px-6 py-4 text-right">Remaining Balance</th>
                        <th class="px-6 py-4">Payment Date</th>
                        <th class="px-6 py-4">Recorded By</th>
                        <th class="px-6 py-4 font-black"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/40">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-white/60 transition-colors duration-300 group">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $payment->invoice->document_number ?? 'D-'.str_pad($payment->billing_document_id, 4, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900 text-sm">{{ $payment->client->name }}</div>
                            <div class="text-[0.65rem] font-semibold text-slate-400 uppercase tracking-widest mt-0.5">Client File</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-white/60 border border-slate-200 text-[0.65rem] font-black text-slate-600 rounded uppercase tracking-widest">{{ $payment->payment_method }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="font-black text-slate-900">{{ number_format($payment->paid_amount) }}</div>
                            <div class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $payment->currency }}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($payment->remaining_amount <= 0)
                                <span class="text-slate-400 font-black text-sm tabular-nums">0</span>
                                <div class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest mt-0.5">Cleared</div>
                            @else
                                <span class="text-red-700 font-black text-sm tabular-nums">{{ number_format($payment->remaining_amount) }}</span>
                                <div class="text-[0.65rem] font-black text-red-500 uppercase tracking-widest mt-0.5">Pending</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-700 text-sm">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-semibold text-slate-700">{{ $payment->recorder->name ?? 'System' }}</div>
                        </td>
                        <td class="px-6 py-4 text-right relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="text-slate-400 hover:text-red-700 transition-colors p-2 focus:outline-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" x-transition.opacity.scale.origin.top.right class="absolute right-8 top-10 w-48 bg-white/90 backdrop-blur-md border border-white/40 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] z-10 py-2" style="display: none;">
                                @if($payment->receipt_file)
                                    <a href="{{ asset('storage/' . $payment->receipt_file) }}" target="_blank" class="block px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-red-700 transition-colors">Download Receipt</a>
                                @endif
                                <a href="#" class="block px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-red-700 transition-colors">View Edit History</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-400 font-semibold tracking-wide">
                            No payment records found. Wait for collection.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
