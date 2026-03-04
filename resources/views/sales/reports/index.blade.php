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
                â† Back to Dashboard
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
        <div class="premium-card p-8 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <h2 class="text-[16px] font-extrabold text-[#334155] tracking-widest mb-6 flex items-center gap-2">
                Monthly Summary
            </h2>
            <div class="space-y-4 relative z-10">
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em]">Gross Income RWF</span>
                    <span class="text-[14px] font-extrabold tabular-nums text-indigo-600">{{ number_format($monthlyIncomeRwf) }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em]">Gross Expense RWF</span>
                    <span class="text-[14px] font-extrabold tabular-nums text-red-600">-{{ number_format($monthlyExpenseRwf) }}</span>
                </div>
                <div class="flex justify-between items-center bg-indigo-50/50 p-4 rounded-xl border border-indigo-100/50 mt-4">
                    <span class="text-[10px] font-black text-indigo-600/70 uppercase tracking-[0.15em]">Net Position RWF</span>
                    <span class="text-[24px] font-extrabold tabular-nums text-indigo-700">{{ number_format($monthlyIncomeRwf - $monthlyExpenseRwf) }}</span>
                </div>
            </div>
        </div>

        <!-- Ticket Profit -->
        <div class="premium-card p-8 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/30 to-transparent pointer-events-none"></div>
            <h2 class="text-[16px] font-extrabold text-[#334155] tracking-widest mb-6 flex items-center gap-2 relative z-10">Ticket Profit Summary (Service Fee)</h2>
            <div class="space-y-4 relative z-10 text-center py-6 bg-emerald-50/50 rounded-2xl border border-emerald-100/50">
                <p class="text-[32px] font-extrabold text-emerald-700 tabular-nums">{{ number_format($totalProfitRwf) }} <span class="text-[10px] text-emerald-600/70 font-black tracking-widest uppercase ml-1">RWF</span></p>
                <p class="text-[20px] font-extrabold text-[#334155] tabular-nums">{{ number_format($totalProfitUsd, 2) }} <span class="text-[10px] text-emerald-600/70 font-bold tracking-widest uppercase ml-1">USD</span></p>
            </div>
            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-[0.15em] mt-5 text-center relative z-10">Pure Profit from tickets this month</p>
        </div>

        <!-- OPEX VS CAPEX -->
        <div class="premium-card p-8 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <h2 class="text-[16px] font-extrabold text-[#334155] tracking-widest mb-6 flex items-center gap-2">OPEX vs CAPEX</h2>
            <div class="space-y-6 relative z-10">
                <div class="p-5 bg-slate-50/50 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden">
                    <div class="absolute inset-y-0 left-0 w-1 bg-red-400"></div>
                    <p class="text-[10px] font-bold uppercase text-red-500/80 tracking-[0.15em] mb-1">OPEX Total</p>
                    <p class="text-[24px] font-extrabold tabular-nums text-[#334155]">{{ number_format($opexRwf) }} <span class="text-[10px] text-slate-400 font-bold tracking-widest uppercase ml-1">RWF</span></p>
                </div>
                <div class="p-5 bg-slate-50/50 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden">
                    <div class="absolute inset-y-0 left-0 w-1 bg-amber-400"></div>
                    <p class="text-[10px] font-bold uppercase text-amber-600/80 tracking-[0.15em] mb-1">CAPEX Total</p>
                    <p class="text-[24px] font-extrabold tabular-nums text-[#334155]">{{ number_format($capexRwf) }} <span class="text-[10px] text-slate-400 font-bold tracking-widest uppercase ml-1">RWF</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Grids -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Supplier Payables -->
        <div class="premium-card p-8 relative overflow-hidden group">
            <h2 class="text-[16px] font-extrabold text-[#334155] tracking-widest mb-6 border-b border-slate-100 pb-4">Supplier Liability (TTC)</h2>
            @if($supplierPayables->count() > 0)
                <div class="overflow-hidden rounded-2xl border border-slate-100/50 bg-slate-50/30">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 border-b border-slate-100/50">
                            <tr>
                                <th class="p-4 text-[10px] text-slate-400 font-bold uppercase tracking-[0.15em]">Supplier</th>
                                <th class="p-4 text-right text-[10px] text-slate-400 font-bold uppercase tracking-[0.15em]">Owed RWF</th>
                                <th class="p-4 text-right text-[10px] text-slate-400 font-bold uppercase tracking-[0.15em]">Owed USD</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/50">
                            @foreach($supplierPayables as $supplier => $amounts)
                                <tr class="hover:bg-white transition-colors">
                                    <td class="p-4 text-[#334155] font-bold">{{ $supplier }}</td>
                                    <td class="p-4 text-right text-[15px] font-extrabold tabular-nums text-red-600">{{ number_format($amounts['RWF']) }}</td>
                                    <td class="p-4 text-right text-[15px] font-extrabold tabular-nums text-red-600">{{ number_format($amounts['USD'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12 bg-slate-50/50 rounded-2xl border-2 border-dashed border-slate-200/50">
                    <p class="text-slate-400 font-bold tracking-widest uppercase text-[10px]">No supplier liabilities recorded.</p>
                </div>
            @endif
        </div>

        <!-- Income By Category -->
        <div class="premium-card p-8 relative overflow-hidden group">
            <h2 class="text-[16px] font-extrabold text-[#334155] tracking-widest mb-6 border-b border-slate-100 pb-4">Income Distribution</h2>
            @if($incomeByCategory->count() > 0)
                <div class="overflow-hidden rounded-2xl border border-slate-100/50 bg-slate-50/30">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 border-b border-slate-100/50">
                            <tr>
                                <th class="p-4 text-[10px] text-slate-400 font-bold uppercase tracking-[0.15em]">Category</th>
                                <th class="p-4 text-right text-[10px] text-slate-400 font-bold uppercase tracking-[0.15em]">Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/50">
                            @foreach($incomeByCategory as $categoryName => $records)
                                <tr class="hover:bg-white transition-colors">
                                    <td class="p-4 text-[#334155] font-bold">{{ $categoryName }}</td>
                                    <td class="p-4 text-right text-[15px] font-extrabold tabular-nums text-emerald-600">
                                        @foreach($records as $rec)
                                            <div class="flex items-center justify-end gap-1">
                                                {{ number_format($rec->total, $rec->currency=='USD'?2:0) }} 
                                                <span class="text-[9px] text-emerald-500/70 font-black tracking-widest">{{ $rec->currency }}</span>
                                            </div>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12 bg-slate-50/50 rounded-2xl border-2 border-dashed border-slate-200/50">
                    <p class="text-slate-400 font-bold tracking-widest uppercase text-[10px]">No income recorded for this period.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
