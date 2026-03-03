@extends('layouts.app')
@section('title', 'Financial Reports')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-3">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-slate-900">Financial Reports</h1>
            <p class="text-slate-400 mt-1 text-xs font-bold tracking-widest uppercase opacity-70">Comprehensive Monthly Aggregations</p>
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
        <div class="bg-white p-6 rounded-2xl border-l-[6px] border-indigo-500 border-y border-r border-slate-200 shadow-sm relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <h2 class="text-[16px] font-[700] text-slate-800 uppercase tracking-widest mb-4 flex items-center gap-2">
                Monthly Summary
            </h2>
            <div class="space-y-4 relative z-10">
                <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase">Gross Income RWF</span>
                    <span class="text-base font-semibold tabular-nums text-indigo-600">{{ number_format($monthlyIncomeRwf) }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase">Gross Expense RWF</span>
                    <span class="text-base font-semibold tabular-nums text-red-600">-{{ number_format($monthlyExpenseRwf) }}</span>
                </div>
                <div class="flex justify-between items-center bg-indigo-50 p-3 rounded-lg border border-indigo-100 mt-2">
                    <span class="text-xs font-black text-indigo-800 uppercase">Net Position RWF</span>
                    <span class="text-[22px] font-bold tabular-nums text-indigo-700">{{ number_format($monthlyIncomeRwf - $monthlyExpenseRwf) }}</span>
                </div>
            </div>
        </div>

        <!-- Ticket Profit -->
        <div class="bg-white p-6 rounded-2xl border-l-[6px] border-emerald-500 border-y border-r border-slate-200 shadow-sm relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <h2 class="text-[16px] font-[700] text-slate-800 uppercase tracking-widest mb-4 flex items-center gap-2">Ticket Profit Summary (Service Fee)</h2>
            <div class="space-y-4 relative z-10 text-center py-4 bg-emerald-50 rounded-xl border border-emerald-100">
                <p class="text-[28px] font-extrabold text-emerald-700 tabular-nums">{{ number_format($totalProfitRwf) }} <span class="text-sm text-emerald-500 font-bold">RWF</span></p>
                <p class="text-[22px] font-bold text-emerald-600 tabular-nums">{{ number_format($totalProfitUsd, 2) }} <span class="text-xs text-emerald-500 font-bold">USD</span></p>
            </div>
            <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mt-4 text-center">Pure Profit from tickets this month</p>
        </div>

        <!-- OPEX VS CAPEX -->
        <div class="bg-white p-6 rounded-2xl border-l-[6px] border-red-500 border-y border-r border-slate-200 shadow-sm relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <h2 class="text-[16px] font-[700] text-slate-800 uppercase tracking-widest mb-4 flex items-center gap-2">OPEX vs CAPEX</h2>
            <div class="space-y-4 relative z-10">
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 shadow-sm">
                    <p class="text-[11px] font-black uppercase text-red-600 tracking-widest mb-1">OPEX Total</p>
                    <p class="text-[22px] font-bold tabular-nums text-slate-900">{{ number_format($opexRwf) }} <span class="text-xs text-slate-500 font-bold">RWF</span></p>
                </div>
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 shadow-sm">
                    <p class="text-[11px] font-black uppercase text-red-600 tracking-widest mb-1">CAPEX Total</p>
                    <p class="text-[22px] font-bold tabular-nums text-slate-900">{{ number_format($capexRwf) }} <span class="text-xs text-slate-500 font-bold">RWF</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Grids -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Supplier Payables -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden group">
            <h2 class="text-[16px] font-[700] text-slate-800 uppercase tracking-widest mb-6">Supplier Liability (TTS)</h2>
            @if($supplierPayables->count() > 0)
                <div class="overflow-hidden rounded-xl border border-slate-100">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-[10px] text-slate-500 font-bold uppercase tracking-widest border-b border-slate-100">
                            <tr><th class="p-4">Supplier</th><th class="p-4 text-right">Owed RWF</th><th class="p-4 text-right">Owed USD</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($supplierPayables as $supplier => $amounts)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4 text-slate-800 font-[700]">{{ $supplier }}</td>
                                    <td class="p-4 text-right text-base font-bold tabular-nums text-red-600">{{ number_format($amounts['RWF']) }}</td>
                                    <td class="p-4 text-right text-base font-bold tabular-nums text-red-600">{{ number_format($amounts['USD'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200">
                    <p class="text-slate-500 font-medium">No supplier liabilities recorded.</p>
                </div>
            @endif
        </div>

        <!-- Income By Category -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden group">
            <h2 class="text-[16px] font-[700] text-slate-800 uppercase tracking-widest mb-6">Income Distribution</h2>
            @if($incomeByCategory->count() > 0)
                <div class="overflow-hidden rounded-xl border border-slate-100">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-[10px] text-slate-500 font-bold uppercase tracking-widest border-b border-slate-100">
                            <tr><th class="p-4">Category</th><th class="p-4 text-right">Revenue</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($incomeByCategory as $categoryName => $records)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4 text-slate-800 font-[700]">{{ $categoryName }}</td>
                                    <td class="p-4 text-right text-base font-bold tabular-nums text-emerald-600">
                                        @foreach($records as $rec)
                                            <div class="flex items-center justify-end gap-1">
                                                {{ number_format($rec->total, $rec->currency=='USD'?2:0) }} 
                                                <span class="text-[10px] text-slate-400 font-bold">{{ $rec->currency }}</span>
                                            </div>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200">
                    <p class="text-slate-500 font-medium">No income recorded for this period.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
