@extends('layouts.app')

@section('title', 'Financial Intelligence & Reports')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-in float-up duration-700">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end bg-gradient-to-r from-blue-50 to-indigo-50 p-8 rounded-[2rem] border border-blue-100 shadow-2xl shadow-blue-900/10">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-full text-blue-600 font-bold text-sm shadow-sm mb-4">
                <span>📊</span> Financial Intelligence
            </div>
            <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-700 to-indigo-600 tracking-tight">Executive Summary</h1>
            <p class="text-slate-500 font-medium text-lg mt-2">Comprehensive performance oversight and ledger records.</p>
        </div>
        <div class="mt-6 md:mt-0">
            <form action="{{ route('sales.cashbook.reports') }}" method="GET" class="flex items-center gap-4 bg-white p-3 rounded-2xl shadow-sm border border-slate-100">
                <select name="month" class="bg-slate-50 border-0 rounded-xl px-4 py-2 font-bold text-slate-700 outline-none">
                    @for($m=1; $m<=12; $m++)
                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ $month == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>
                <select name="year" class="bg-slate-50 border-0 rounded-xl px-4 py-2 font-bold text-slate-700 outline-none">
                    @for($y=date('Y'); $y>=2024; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-xl shadow-lg transition-transform hover:scale-105">
                    🔍
                </button>
            </form>
        </div>
    </div>

    <!-- Monthly Summary Dual Currency -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- RWF Section -->
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50">
            <h3 class="text-lg font-black text-slate-800 uppercase tracking-widest flex items-center gap-2 mb-6 border-b border-slate-100 pb-4">
                <span>💰</span> RWF Performance
            </h3>
            <div class="grid grid-cols-2 gap-6">
                <div class="p-6 bg-slate-50 rounded-2xl relative overflow-hidden group hover:shadow-lg transition-all">
                    <span class="text-4xl absolute -right-4 -bottom-4 opacity-5 group-hover:scale-150 transition-transform">📈</span>
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-[0.2em] mb-2">Total Revenue</p>
                    <p class="text-2xl font-black text-green-600">Fr {{ number_format($monthlyIncomeRw) }}</p>
                </div>
                
                <div class="p-6 bg-slate-50 rounded-2xl relative overflow-hidden group hover:shadow-lg transition-all">
                    <span class="text-4xl absolute -right-4 -bottom-4 opacity-5 group-hover:scale-150 transition-transform">📉</span>
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-[0.2em] mb-2">OPEX (Operating)</p>
                    <p class="text-2xl font-black text-orange-600">Fr {{ number_format($monthlyOpexRw) }}</p>
                </div>

                <div class="p-6 bg-slate-50 rounded-2xl relative overflow-hidden group hover:shadow-lg transition-all">
                    <span class="text-4xl absolute -right-4 -bottom-4 opacity-5 group-hover:scale-150 transition-transform">🏗️</span>
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-[0.2em] mb-2">CAPEX (Capital)</p>
                    <p class="text-2xl font-black text-rose-600">Fr {{ number_format($monthlyCapexRw) }}</p>
                </div>

                <div class="p-6 {{ $netPositionRw >= 0 ? 'bg-green-50' : 'bg-red-50' }} rounded-2xl relative overflow-hidden group transform hover:scale-105 transition-transform shadow-md">
                    <span class="text-4xl absolute -right-4 -bottom-4 opacity-10 group-hover:scale-150 transition-transform">✨</span>
                    <p class="{{ $netPositionRw >= 0 ? 'text-green-600/60' : 'text-red-600/60' }} font-bold text-xs uppercase tracking-[0.2em] mb-2">Net Position</p>
                    <p class="text-2xl font-black {{ $netPositionRw >= 0 ? 'text-green-700' : 'text-red-700' }}">Fr {{ number_format($netPositionRw) }}</p>
                </div>
            </div>
        </div>

        <!-- USD Section -->
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50">
            <h3 class="text-lg font-black text-slate-800 uppercase tracking-widest flex items-center gap-2 mb-6 border-b border-slate-100 pb-4">
                <span>💵</span> USD Performance
            </h3>
            <div class="grid grid-cols-2 gap-6">
                <div class="p-6 bg-slate-50 rounded-2xl relative overflow-hidden group hover:shadow-lg transition-all">
                    <span class="text-4xl absolute -right-4 -bottom-4 opacity-5 group-hover:scale-150 transition-transform">📈</span>
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-[0.2em] mb-2">Total Revenue</p>
                    <p class="text-2xl font-black text-green-600">${{ number_format($monthlyIncomeUsd, 2) }}</p>
                </div>
                
                <div class="p-6 bg-slate-50 rounded-2xl relative overflow-hidden group hover:shadow-lg transition-all">
                    <span class="text-4xl absolute -right-4 -bottom-4 opacity-5 group-hover:scale-150 transition-transform">📉</span>
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-[0.2em] mb-2">OPEX (Operating)</p>
                    <p class="text-2xl font-black text-orange-600">${{ number_format($monthlyOpexUsd, 2) }}</p>
                </div>

                <div class="p-6 bg-slate-50 rounded-2xl relative overflow-hidden group hover:shadow-lg transition-all">
                    <span class="text-4xl absolute -right-4 -bottom-4 opacity-5 group-hover:scale-150 transition-transform">🏗️</span>
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-[0.2em] mb-2">CAPEX (Capital)</p>
                    <p class="text-2xl font-black text-rose-600">${{ number_format($monthlyCapexUsd, 2) }}</p>
                </div>

                <div class="p-6 {{ $netPositionUsd >= 0 ? 'bg-green-50' : 'bg-red-50' }} rounded-2xl relative overflow-hidden group transform hover:scale-105 transition-transform shadow-md">
                    <span class="text-4xl absolute -right-4 -bottom-4 opacity-10 group-hover:scale-150 transition-transform">✨</span>
                    <p class="{{ $netPositionUsd >= 0 ? 'text-green-600/60' : 'text-red-600/60' }} font-bold text-xs uppercase tracking-[0.2em] mb-2">Net Position</p>
                    <p class="text-2xl font-black {{ $netPositionUsd >= 0 ? 'text-green-700' : 'text-red-700' }}">${{ number_format($netPositionUsd, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticketing & Supplier Liability -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 my-8">
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50">
            <h3 class="text-lg font-black text-slate-800 uppercase tracking-widest flex items-center gap-2 mb-6 border-b border-slate-100 pb-4">
                <span>🎫</span> Ticketing Profit <span class="text-xs text-slate-400">(Service Fees)</span>
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center bg-green-50 p-6 rounded-2xl border border-green-100 hover:shadow-md transition-shadow">
                    <span class="text-sm font-black text-green-700 uppercase tracking-widest">RWF</span>
                    <span class="text-3xl font-black text-green-600">Fr {{ number_format($ticketProfitRw) }}</span>
                </div>
                <div class="flex justify-between items-center bg-green-50 p-6 rounded-2xl border border-green-100 hover:shadow-md transition-shadow">
                    <span class="text-sm font-black text-green-700 uppercase tracking-widest">USD</span>
                    <span class="text-3xl font-black text-green-600">${{ number_format($ticketProfitUsd, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50">
            <h3 class="text-lg font-black text-slate-800 uppercase tracking-widest flex items-center gap-2 mb-6 border-b border-slate-100 pb-4">
                <span>🏢</span> Supplier Liability <span class="text-xs text-slate-400">(TTC)</span>
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center bg-rose-50 p-6 rounded-2xl border border-rose-100 hover:shadow-md transition-shadow">
                    <span class="text-sm font-black text-rose-700 uppercase tracking-widest">RWF</span>
                    <span class="text-3xl font-black text-rose-600">Fr {{ number_format($supplierPayableRw) }}</span>
                </div>
                <div class="flex justify-between items-center bg-rose-50 p-6 rounded-2xl border border-rose-100 hover:shadow-md transition-shadow">
                    <span class="text-sm font-black text-rose-700 uppercase tracking-widest">USD</span>
                    <span class="text-3xl font-black text-rose-600">${{ number_format($supplierPayableUsd, 2) }}</span>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-6 text-center">Funds owed to suppliers / airlines pending settlement</p>
        </div>
    </div>

    <!-- Daily Log Master List -->
    <div class="bg-white rounded-[2rem] shadow-xl border border-slate-100 p-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 border-b border-slate-100 pb-6">
            <h2 class="text-2xl font-black text-slate-800 flex items-center gap-3">
                <span class="text-indigo-500">📅</span> Daily Cash Sessions
            </h2>
            <div class="bg-indigo-50 text-indigo-700 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-widest shadow-sm">
                {{ $days->count() }} Sessions this Month
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($days as $day)
                <div class="border border-slate-100 rounded-2xl p-6 bg-slate-50 hover:bg-white hover:shadow-xl transition-all group relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-2 h-full {{ $day->status === 'closed' ? 'bg-slate-300' : 'bg-green-500' }}"></div>
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-lg font-black text-slate-800">{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</p>
                            <p class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-widest">{{ $day->status === 'closed' ? '🔒 Closed' : '🟢 Open' }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm flex flex-col justify-between">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">RWF Main Ending</span>
                            <span class="font-black text-slate-800">Fr {{ number_format($day->closing_balance) }}</span>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1 mt-2">USD Main Ending</span>
                            <span class="font-black text-slate-800">${{ number_format($day->closing_balance_usd, 2) }}</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm flex flex-col justify-between">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">RWF Petty Cash Ending</span>
                            <span class="font-black text-slate-700">Fr {{ number_format($day->closing_petty_balance) }}</span>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1 mt-2">USD Petty Cash Ending</span>
                            <span class="font-black text-slate-700">${{ number_format($day->closing_petty_balance_usd, 2) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($days->isEmpty())
            <div class="text-center py-16">
                <div class="text-6xl mb-4 opacity-50">👻</div>
                <p class="text-slate-500 font-medium text-lg">No financial sessions found for {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}.</p>
            </div>
        @endif
    </div>
</div>
@endsection
