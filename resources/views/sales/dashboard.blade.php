@extends('layouts.app')
@section('title', 'Sales Dashboard')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <!-- Header with Controls -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-3">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-slate-900 italic">Financial <span class="text-red-700">Command Desk</span></h1>
            <p class="text-slate-400 mt-1 text-xs font-bold tracking-[0.1em] uppercase opacity-70">Strategic Analytics & Reporting Cycle {{ $year }}</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-4">
            <form action="{{ route('sales.dashboard') }}" method="GET" class="flex items-center gap-2">
                <select name="year" onchange="this.form.submit()" class="bg-white/50 backdrop-blur-md border border-slate-200/60 rounded-lg px-4 py-2.5 text-sm font-bold focus:border-red-700 outline-none shadow-sm cursor-pointer transition-all hover:border-red-700/50">
                    @foreach(range(date('Y'), date('Y') - 5) as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Cycle {{ $y }}</option>
                    @endforeach
                </select>
            </form>
            <a href="#" class="bg-red-700 hover:bg-red-800 text-white px-6 py-2.5 rounded-lg text-base font-bold transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-900/40 flex items-center gap-2 backdrop-blur-md">
                <span>📥</span> Intelligence Export
            </a>
            <a href="{{ route('sales.payments.create') }}" class="bg-red-700 hover:bg-red-800 text-white px-6 py-2.5 rounded-lg text-base font-bold transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-900/40 backdrop-blur-md">
                + Client Payment
            </a>
        </div>
    </div>

    <!-- Section A: Revenue Overview -->
    <div class="bg-white/40 backdrop-blur-xl p-8 rounded-2xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-red-700/20 transition-all duration-500">
        <h2 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-3 italic">
            <span class="w-1.5 h-6 bg-red-700 rounded-full"></span>
            Revenue Overview
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="p-6 bg-white/60 border border-slate-200/50 rounded-xl hover:border-red-700/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-red-900/10 hover:bg-white">
                <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-2">Total Billed Amount</p>
                <div class="flex items-baseline">
                    <p class="text-3xl font-black text-slate-900 tabular-nums">{{ number_format($revenueOverview['total_billed']) }}</p>
                    <span class="text-sm font-black text-slate-400 ml-2 uppercase tracking-widest">RWF</span>
                </div>
            </div>
            <div class="p-6 bg-white/60 border border-slate-200/50 rounded-xl hover:border-red-700/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-red-900/10 hover:bg-white">
                <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-2">Total Payment Capital</p>
                <div class="flex items-baseline">
                    <p class="text-3xl font-black text-slate-900 tabular-nums">{{ number_format($revenueOverview['total_payment']) }}</p>
                    <span class="text-sm font-black text-slate-400 ml-2 uppercase tracking-widest">RWF</span>
                </div>
            </div>
            <div class="p-6 bg-white/60 border border-slate-200/50 rounded-xl hover:border-red-700/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-red-900/10 hover:bg-white">
                <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-2">Outstanding Receivables</p>
                <div class="flex items-baseline">
                    <p class="text-3xl font-black text-slate-900 tabular-nums">{{ number_format($revenueOverview['outstanding']) }}</p>
                    <span class="text-sm font-black text-slate-400 ml-2 uppercase tracking-widest">RWF</span>
                </div>
            </div>
            <div class="p-6 bg-red-50/50 border border-red-700/10 rounded-xl hover:bg-red-50/80 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-red-900/20">
                <p class="text-red-800 text-xs font-black uppercase tracking-widest mb-2">Net Revenue Position</p>
                <div class="flex items-baseline">
                    <p class="text-3xl font-black text-slate-900 tabular-nums">{{ number_format($revenueOverview['net_position']) }}</p>
                    <span class="text-sm font-black text-slate-400 ml-2 uppercase tracking-widest">RWF</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Section B: Regulatory Compliance -->
        <div class="bg-white/40 backdrop-blur-xl p-8 rounded-2xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-slate-300 transition-all duration-500">
            <h2 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-3 italic">
                <span class="w-1.5 h-6 bg-slate-800 rounded-full"></span>
                Regulatory Compliance
            </h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-6 bg-white/60 border border-slate-200/50 rounded-xl hover:border-slate-800/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-900/10 flex flex-col justify-between hover:bg-white">
                    <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-2">Total EBM Filings</p>
                    <p class="text-3xl font-black text-slate-900 tabular-nums">{{ number_format($regulatoryCompliance['ebm_filings']) }}</p>
                </div>
                <div class="p-6 bg-white/60 border border-slate-200/50 rounded-xl hover:border-slate-800/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-900/10 flex flex-col justify-between hover:bg-white">
                    <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-2">Total RRA Declarations</p>
                    <p class="text-3xl font-black text-slate-900 tabular-nums">{{ number_format($regulatoryCompliance['rra_declarations']) }}</p>
                </div>
                <div class="p-6 bg-white/60 border border-slate-200/50 rounded-xl hover:border-slate-800/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-900/10 flex flex-col justify-between hover:bg-white">
                    <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-2">Total RSSB Declarations</p>
                    <p class="text-3xl font-black text-slate-900 tabular-nums">{{ number_format($regulatoryCompliance['rssb_declarations']) }}</p>
                </div>
                <div class="p-6 bg-red-50/50 border border-red-200/50 rounded-xl hover:border-red-300 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-red-900/10 flex flex-col justify-between hover:bg-red-50/80">
                    <p class="text-red-800 text-xs font-black uppercase tracking-widest mb-2">Pending Compliance Items</p>
                    <p class="text-3xl font-black text-red-900 tabular-nums">{{ number_format($regulatoryCompliance['pending_items']) }}</p>
                </div>
            </div>
        </div>

        <!-- Section C: Client Financial Overview -->
        <div class="bg-white/40 backdrop-blur-xl p-8 rounded-2xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group hover:border-red-700/20 transition-all duration-500">
            <h2 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-3 italic">
                <span class="w-1.5 h-6 bg-red-900 rounded-full"></span>
                Client Financial Overview
            </h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-white/60 border border-slate-200/50 rounded-xl hover:bg-white transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-900/5 hover:border-red-700/30">
                    <span class="text-sm font-bold text-slate-600">Total Institutional Clients</span>
                    <span class="text-lg font-black text-slate-900">{{ number_format($clientFinancial['institutional_clients']) }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-white/60 border border-slate-200/50 rounded-xl hover:bg-white transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-900/5 hover:border-red-700/30">
                    <span class="text-sm font-bold text-slate-600">Active Billing Accounts</span>
                    <span class="text-lg font-black text-slate-900">{{ number_format($clientFinancial['active_billing']) }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-white/60 border border-red-100/50 rounded-xl hover:bg-white transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-900/10 hover:border-red-700/30">
                    <span class="text-sm font-bold text-red-800">Overdue Accounts</span>
                    <span class="text-lg font-black text-red-700">{{ number_format($clientFinancial['overdue_accounts']) }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-white/60 border border-slate-200/50 rounded-xl hover:bg-white transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-900/5 hover:border-red-700/30">
                    <span class="text-sm font-bold text-slate-600">Recently Paid Accounts</span>
                    <span class="text-lg font-black text-slate-900">{{ number_format($clientFinancial['recently_paid']) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
