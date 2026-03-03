@extends('layouts.app')
@section('title', 'Financial Reports')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-3">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-slate-900 italic">Financial <span class="text-slate-500">Reports</span></h1>
            <p class="text-slate-400 mt-1 text-xs font-bold tracking-[0.1em] uppercase opacity-70">Comprehensive Monthly Aggregations</p>
        </div>
        
        <div class="flex gap-4 items-center">
            <a href="{{ route('sales.dashboard') }}" class="text-slate-500 hover:text-slate-800 font-bold text-sm tracking-widest uppercase transition-colors">
                ← Back to Dashboard
            </a>
            
            <form action="{{ route('sales.reports.index') }}" method="GET" class="flex items-center gap-2">
                <select name="month" onchange="this.form.submit()" class="bg-white/50 border border-slate-200 rounded-lg px-4 py-2 text-sm font-bold">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ sprintf('%02d', $m) }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Monthly Summary -->
        <div class="bg-white/60 backdrop-blur-xl p-6 rounded-2xl border border-slate-200/50 shadow-sm relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 to-transparent z-0"></div>
            <h2 class="text-base font-semibold text-indigo-900 uppercase tracking-widest mb-4 relative z-10">Monthly Summary</h2>
            <div class="space-y-4 relative z-10">
                <div class="flex justify-between items-center border-b border-indigo-100 pb-2">
                    <span class="text-xs font-bold text-indigo-600/70 uppercase">Gross Income RWF</span>
                    <span class="text-base font-semibold tabular-nums text-indigo-900">{{ number_format($monthlyIncomeRwf) }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-indigo-100 pb-2">
                    <span class="text-xs font-bold text-indigo-600/70 uppercase">Gross Expense RWF</span>
                    <span class="text-base font-semibold tabular-nums text-red-600">-{{ number_format($monthlyExpenseRwf) }}</span>
                </div>
                <div class="flex justify-between items-center bg-indigo-50 p-2 rounded">
                    <span class="text-xs font-black text-indigo-800 uppercase">Net Position RWF</span>
                    <span class="text-[22px] font-bold tabular-nums text-indigo-900">{{ number_format($monthlyIncomeRwf - $monthlyExpenseRwf) }}</span>
                </div>
            </div>
        </div>

        <!-- Ticket Profit -->
        <div class="bg-white/60 backdrop-blur-xl p-6 rounded-2xl border border-slate-200/50 shadow-sm relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/50 to-transparent z-0"></div>
            <h2 class="text-base font-semibold text-emerald-900 uppercase tracking-widest mb-4 relative z-10">Ticket Profit Summary (Service Fee)</h2>
            <div class="space-y-4 relative z-10 text-center py-4">
                <p class="text-[28px] font-extrabold text-emerald-700 tabular-nums">{{ number_format($totalProfitRwf) }} <span class="text-sm text-emerald-500">RWF</span></p>
                <p class="text-[22px] font-bold text-emerald-600 tabular-nums">{{ number_format($totalProfitUsd, 2) }} <span class="text-xs text-emerald-400">USD</span></p>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mt-4">Pure Profit from tickets this month</p>
            </div>
        </div>

        <!-- OPEX VS CAPEX -->
        <div class="bg-white/60 backdrop-blur-xl p-6 rounded-2xl border border-slate-200/50 shadow-sm relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-red-50/50 to-transparent z-0"></div>
            <h2 class="text-base font-semibold text-red-900 uppercase tracking-widest mb-4 relative z-10">OPEX vs CAPEX</h2>
            <div class="space-y-4 relative z-10">
                <div class="p-3 bg-white/80 rounded-lg border border-red-100 shadow-sm">
                    <p class="text-[10px] font-black uppercase text-red-500 tracking-widest">OPEX Total</p>
                    <p class="text-[22px] font-bold tabular-nums text-slate-900">{{ number_format($opexRwf) }} <span class="text-xs text-slate-400">RWF</span></p>
                </div>
                <div class="p-3 bg-white/80 rounded-lg border border-red-100 shadow-sm">
                    <p class="text-[10px] font-black uppercase text-red-500 tracking-widest">CAPEX Total</p>
                    <p class="text-[22px] font-bold tabular-nums text-slate-900">{{ number_format($capexRwf) }} <span class="text-xs text-slate-400">RWF</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Supplier Payables -->
        <div class="bg-white/60 backdrop-blur-xl p-6 rounded-2xl border border-slate-200/50 shadow-sm relative overflow-hidden group">
            <h2 class="text-base font-semibold text-slate-900 uppercase tracking-widest mb-4 italic">Supplier Liability (TTS)</h2>
            @if($supplierPayables->count() > 0)
                <table class="w-full text-left text-sm">
                    <thead class="text-[10px] text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                        <tr><th class="pb-2">Supplier</th><th class="pb-2 text-right">Owed RWF</th><th class="pb-2 text-right">Owed USD</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($supplierPayables as $supplier => $amounts)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 text-slate-700 font-bold">{{ $supplier }}</td>
                                <td class="py-3 text-right text-base font-semibold tabular-nums text-red-600">{{ number_format($amounts['RWF']) }}</td>
                                <td class="py-3 text-right text-base font-semibold tabular-nums text-red-600">{{ number_format($amounts['USD'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-center text-slate-400 text-sm font-bold py-6">No supplier liabilities recorded.</p>
            @endif
        </div>

        <!-- Income By Category -->
        <div class="bg-white/60 backdrop-blur-xl p-6 rounded-2xl border border-slate-200/50 shadow-sm relative overflow-hidden group">
            <h2 class="text-base font-semibold text-slate-900 uppercase tracking-widest mb-4 italic">Income Distribution</h2>
            @if($incomeByCategory->count() > 0)
                <table class="w-full text-left text-sm">
                    <thead class="text-[10px] text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                        <tr><th class="pb-2">Category</th><th class="pb-2 text-right">Revenue</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($incomeByCategory as $categoryName => $records)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 text-slate-700 font-bold">{{ $categoryName }}</td>
                                <td class="py-3 text-right text-base font-semibold tabular-nums text-emerald-600">
                                    @foreach($records as $rec)
                                        <div>{{ number_format($rec->total, $rec->currency=='USD'?2:0) }} <span class="text-[10px] text-slate-400">{{ $rec->currency }}</span></div>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-center text-slate-400 text-sm font-bold py-6">No income recorded for this period.</p>
            @endif
        </div>
    </div>
</div>
@endsection
