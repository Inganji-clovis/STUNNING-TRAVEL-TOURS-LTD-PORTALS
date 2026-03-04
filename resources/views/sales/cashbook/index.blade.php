@extends('layouts.app')

@section('title', 'Sales & Cashbook Command Center')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-in float-up duration-700">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end bg-gradient-to-r from-orange-50 to-amber-50 p-8 rounded-[2rem] border border-orange-100 shadow-2xl shadow-orange-900/10">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-full text-orange-600 font-bold text-sm shadow-sm mb-4">
                <span>✨</span> Real-time Financial Engine
            </div>
            <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-amber-600 tracking-tight">Daily Cashbook</h1>
            <p class="text-slate-500 font-medium text-lg mt-2">Active Session: {{ \Carbon\Carbon::parse($cashDay->date)->format('l, F j, Y') }}</p>
        </div>
        <div class="flex gap-4 mt-6 md:mt-0">
            @if(Auth::user()->role === 'admin')
            <form action="{{ route('sales.cashbook.close') }}" method="POST" onsubmit="return confirm('Close the day? This will lock all transactions and carry balances forward.');">
                @csrf
                <input type="hidden" name="cash_day_id" value="{{ $cashDay->id }}">
                <button type="submit" class="bg-slate-900 hover:bg-black text-white px-8 py-4 rounded-2xl shadow-xl transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 font-bold uppercase tracking-[0.2em] text-sm flex items-center gap-2">
                    🔒 Close Day
                </button>
            </form>
            @endif
        </div>
    </div>

    @include('layouts.alerts')

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- 1. Opening Cash -->
        <div class="bg-white p-6 rounded-2xl border-l-[6px] border-blue-500 border-y border-r border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="h-12 w-12 bg-blue-50 rounded-xl flex items-center justify-center mb-6 border border-blue-100 group-hover:scale-105 transition-transform text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
            </div>
            <p class="text-slate-500 font-black text-xs uppercase tracking-[0.2em] mb-4">1. Opening Cash</p>
            <div class="space-y-3">
                <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                    <span class="text-sm font-bold text-slate-500">RWF</span>
                    <span class="text-[28px] font-extrabold tabular-nums text-slate-800">{{ number_format($cashDay->opening_balance) }}</span>
                </div>
                <div class="flex justify-between items-center pb-1">
                    <span class="text-sm font-bold text-slate-500">USD</span>
                    <span class="text-[22px] font-bold tabular-nums text-slate-800">${{ number_format($cashDay->opening_balance_usd, 2) }}</span>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-4 uppercase font-bold tracking-widest">Starting Balance</p>
        </div>

        <!-- 2. Total Income -->
        <div class="bg-white p-6 rounded-2xl border-l-[6px] border-emerald-500 border-y border-r border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="h-12 w-12 bg-emerald-50 rounded-xl flex items-center justify-center mb-6 border border-emerald-100 group-hover:scale-105 transition-transform text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <p class="text-slate-500 font-black text-xs uppercase tracking-[0.2em] mb-4">2. Total Income</p>
            <div class="space-y-3">
                <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                    <span class="text-sm font-bold text-slate-500">RWF</span>
                    <span class="text-[28px] font-extrabold tabular-nums text-emerald-600">+{{ number_format($mainIncomeRw) }}</span>
                </div>
                <div class="flex justify-between items-center pb-1">
                    <span class="text-sm font-bold text-slate-500">USD</span>
                    <span class="text-[22px] font-bold tabular-nums text-emerald-600">+${{ number_format($mainIncomeUsd, 2) }}</span>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-4 uppercase font-bold tracking-widest">Cash Revenue</p>
        </div>

        <!-- 3. Total Expenses -->
        <div class="bg-white p-6 rounded-2xl border-l-[6px] border-red-500 border-y border-r border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="h-12 w-12 bg-red-50 rounded-xl flex items-center justify-center mb-6 border border-red-100 group-hover:scale-105 transition-transform text-red-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
            </div>
            <p class="text-slate-500 font-black text-xs uppercase tracking-[0.2em] mb-4">3. Total Expenses</p>
            <div class="space-y-3">
                <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                    <span class="text-sm font-bold text-slate-500">RWF</span>
                    <span class="text-[28px] font-extrabold tabular-nums text-red-600">-{{ number_format($mainExpenseRw) }}</span>
                </div>
                <div class="flex justify-between items-center pb-1">
                    <span class="text-sm font-bold text-slate-500">USD</span>
                    <span class="text-[22px] font-bold tabular-nums text-red-600">-${{ number_format($mainExpenseUsd, 2) }}</span>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-4 uppercase font-bold tracking-widest">OPEX & CAPEX</p>
        </div>

        <!-- 4. Closing Cash -->
        <div class="bg-white p-6 rounded-2xl border-l-[6px] border-indigo-500 border-y border-r border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="h-12 w-12 bg-indigo-50 rounded-xl flex items-center justify-center mb-6 border border-indigo-100 group-hover:scale-105 transition-transform text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
            </div>
            <p class="text-slate-500 font-black text-xs uppercase tracking-[0.2em] mb-4">4. Closing Cash</p>
            <div class="space-y-3">
                <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                    <span class="text-sm font-bold text-slate-500">RWF</span>
                    <span class="text-[28px] font-extrabold tabular-nums text-slate-800">{{ number_format($currentMainBalanceRw) }}</span>
                </div>
                <div class="flex justify-between items-center pb-1">
                    <span class="text-sm font-bold text-slate-500">USD</span>
                    <span class="text-[22px] font-bold tabular-nums text-slate-800">${{ number_format($currentMainBalanceUsd, 2) }}</span>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-4 uppercase font-bold tracking-widest">Auto Calculated</p>
        </div>
    </div>

    <!-- Transaction Form & History -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Action Panel -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-2xl border-l-[6px] border-orange-500 border-y border-r border-slate-200 shadow-sm p-6">
                <h2 class="text-[20px] font-extrabold text-slate-800 mb-6 flex items-center gap-3">
                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Quick Actions
                </h2>
                <div class="grid grid-cols-1 gap-3 text-sm font-bold tracking-widest uppercase">
                    <button onclick="document.getElementById('modal-income').classList.remove('hidden')" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 py-3 rounded-lg transition-colors border border-emerald-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add Income
                    </button>
                    <button onclick="document.getElementById('modal-expense').classList.remove('hidden')" class="bg-red-50 hover:bg-red-100 text-red-700 py-3 rounded-lg transition-colors border border-red-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                        Add Expense
                    </button>
                    <button onclick="document.getElementById('modal-ticket').classList.remove('hidden')" class="bg-blue-50 hover:bg-blue-100 text-blue-700 py-3 rounded-lg transition-colors border border-blue-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        Add Ticket Sale
                    </button>
                    <button onclick="document.getElementById('modal-petty').classList.remove('hidden')" class="bg-amber-50 hover:bg-amber-100 text-amber-700 py-3 rounded-lg transition-colors border border-amber-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm-7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Petty Cash
                    </button>
                    <a href="{{ route('sales.cashbook.reports') ?? '#' }}" class="bg-slate-50 hover:bg-slate-100 text-slate-700 py-3 rounded-lg text-center transition-colors border border-slate-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        View Reports
                    </a>
                </div>
            </div>

            <!-- Petty Cash Summary Widget -->
            <div class="bg-slate-900 border-l-[6px] border-amber-500 p-6 rounded-2xl shadow-sm text-white">
                <h3 class="font-bold text-slate-400 text-xs uppercase tracking-widest mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Petty Cash Status
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center bg-white/10 p-4 rounded-lg border border-white/10">
                        <span class="text-sm font-medium text-slate-300">RWF</span>
                        <span class="text-[28px] font-extrabold tabular-nums">{{ number_format($currentPettyBalanceRw) }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-white/10 p-4 rounded-lg border border-white/10">
                        <span class="text-sm font-medium text-slate-300">USD</span>
                        <span class="text-[22px] font-bold tabular-nums">${{ number_format($currentPettyBalanceUsd, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ledger -->
        <div class="lg:col-span-2 bg-white rounded-2xl border-l-[6px] border-slate-600 border-y border-r border-slate-200 shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-[24px] font-extrabold text-slate-800 flex items-center gap-3">
                    <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Daily Ledger
                </h2>
                <div class="bg-slate-100 text-slate-500 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest">
                    {{ $transactions->count() }} Entries
                </div>
            </div>

            @if($transactions->isEmpty())
                <div class="text-center py-16 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200">
                    <svg class="w-12 h-12 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    <p class="text-slate-500 font-medium tracking-wide">The ledger is empty for today.</p>
                </div>
            @else
                <div class="overflow-hidden rounded-2xl border border-slate-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 text-xs uppercase tracking-widest font-bold border-b border-slate-100">
                                <th class="p-4">Time</th>
                                <th class="p-4">Source</th>
                                <th class="p-4">Category</th>
                                <th class="p-4">Description</th>
                                <th class="p-4 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($transactions as $tx)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="p-4 text-sm font-medium text-slate-500">{{ $tx->created_at->format('H:i') }}</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold {{ $tx->source === 'main' ? 'bg-indigo-50 text-indigo-600' : 'bg-amber-50 text-amber-600' }}">
                                        {!! $tx->source === 'main' ? '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg> Main' : '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm-7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> Petty' !!}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-slate-700 text-sm">{{ $tx->category->name }}</div>
                                    <div class="text-xs text-slate-400 font-medium">{{ strtoupper($tx->category->classification) }}</div>
                                </td>
                                <td class="p-4 text-sm text-slate-600 max-w-xs truncate" title="{{ $tx->description }}">
                                    {{ $tx->description ?? '—' }}
                                </td>
                                <td class="p-4 text-right text-base font-semibold tabular-nums {{ $tx->type === 'income' ? 'text-green-500' : 'text-slate-800' }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }}{{ number_format($tx->amount, $tx->currency === 'USD' ? 2 : 0) }} <span class="text-xs font-bold text-slate-400">{{ $tx->currency }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Modals -->
    <!-- Add Income Modal -->
    <div id="modal-income" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4">
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden animate-in zoom-in-95 duration-200">
            <div class="bg-emerald-600 p-6 flex justify-between items-center text-white">
                <h3 class="text-2xl font-[700] tracking-tight flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Income
                </h3>
                <button onclick="document.getElementById('modal-income').classList.add('hidden')" class="text-white/70 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('sales.cashbook.transaction.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <input type="hidden" name="cash_day_id" value="{{ $cashDay->id }}">
                <input type="hidden" name="source" value="main">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Currency</label>
                        <select name="currency" class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-green-500">
                            <option value="RWF">RWF</option>
                            <option value="USD">USD</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Amount</label>
                        <input type="number" step="0.01" name="amount" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-black text-slate-800 outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Category</label>
                        <select name="category_id" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-green-500">
                            @foreach($categories->where('type', 'income')->where('name', '!=', 'Airticket') as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Payment Method</label>
                        <select name="payment_method" class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-green-500">
                            <option value="Cash">Cash</option>
                            <option value="Bank">Bank</option>
                            <option value="Mobile Money">Mobile Money</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-medium text-slate-700 outline-none focus:ring-2 focus:ring-green-500 resize-none"></textarea>
                </div>
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-4 rounded-xl transition-colors">Save Income</button>
            </form>
        </div>
    </div>

    <!-- Add Expense Modal -->
    <div id="modal-expense" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4">
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden animate-in zoom-in-95 duration-200">
            <div class="bg-red-700 p-6 flex justify-between items-center text-white">
                <h3 class="text-2xl font-[700] tracking-tight flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                    Add Expense
                </h3>
                <button onclick="document.getElementById('modal-expense').classList.add('hidden')" class="text-white/70 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('sales.cashbook.transaction.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <input type="hidden" name="cash_day_id" value="{{ $cashDay->id }}">
                <input type="hidden" name="source" value="main">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Currency</label>
                        <select name="currency" class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-red-500">
                            <option value="RWF">RWF</option>
                            <option value="USD">USD</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Amount</label>
                        <input type="number" step="0.01" name="amount" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-black text-slate-800 outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Category (OPEX/CAPEX)</label>
                    <select name="category_id" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-red-500">
                        @foreach($categories->where('type', 'expense') as $category)
                            <option value="{{ $category->id }}">{{ $category->name }} ({{ strtoupper($category->classification) }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-medium text-slate-700 outline-none focus:ring-2 focus:ring-red-500 resize-none"></textarea>
                </div>
                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-4 rounded-xl transition-colors">Save Expense</button>
            </form>
        </div>
    </div>

    <!-- Add Ticket Sale Modal -->
    <div id="modal-ticket" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4">
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden animate-in zoom-in-95 duration-200">
            <div class="bg-blue-800 p-6 flex justify-between items-center text-white">
                <h3 class="text-2xl font-[700] tracking-tight flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    Add Ticket Sale
                </h3>
                <button onclick="document.getElementById('modal-ticket').classList.add('hidden')" class="text-white/70 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('sales.cashbook.transaction.store') }}" method="POST" class="p-6 space-y-5" onsubmit="return validateTicketForm(this)">
                @csrf
                <input type="hidden" name="cash_day_id" value="{{ $cashDay->id }}">
                <input type="hidden" name="source" value="main">
                <input type="hidden" name="amount" id="ticket-amount">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Currency</label>
                        <select name="currency" class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="USD">USD</option>
                            <option value="RWF">RWF</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Category</label>
                        <select name="category_id" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach($categories->filter(fn($c) => strtolower($c->name) === 'airticket' || strtolower($c->name) === 'airtickets') as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="bg-blue-50 p-4 rounded-xl space-y-4 border border-blue-100">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-black uppercase text-blue-600 tracking-widest">Financial Breakdown</span>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-1">TTC (Total Ticket Cost) <span class="text-blue-500 font-extrabold">*</span></label>
                        <input type="number" step="0.01" name="ttc" id="ticket-ttc" required class="w-full bg-white border-0 rounded-xl px-4 py-3 font-black text-slate-800 outline-none focus:ring-2 focus:ring-blue-500" oninput="calculateRC()">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-1">Service Fee (Profit) <span class="text-blue-500 font-extrabold">*</span></label>
                        <input type="number" step="0.01" name="service_fee" id="ticket-fee" required class="w-full bg-white border-0 rounded-xl px-4 py-3 font-black text-green-600 outline-none focus:ring-2 focus:ring-blue-500" oninput="calculateRC()">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-1">Rate Collected (RC) <span class="text-blue-500 font-extrabold">= TTC + Fee</span></label>
                        <input type="number" step="0.01" name="rc" id="ticket-rc" required readonly class="w-full bg-blue-100/50 border-0 rounded-xl px-4 py-3 font-black text-blue-900 outline-none cursor-not-allowed">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Payment Method</label>
                        <select name="payment_method" class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Cash">Cash</option>
                            <option value="Bank">Bank</option>
                            <option value="Mobile Money">Mobile Money</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Airline / PNR</label>
                        <input name="description" placeholder="e.g. RwandAir PNR: ABCDEF" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-medium text-slate-700 outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 rounded-xl transition-colors">Save Ticket Sale</button>
            </form>
        </div>
    </div>

    <!-- Petty Cash Entry Modal -->
    <div id="modal-petty" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4">
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden animate-in zoom-in-95 duration-200">
            <div class="bg-amber-500 p-6 flex justify-between items-center text-white">
                <h3 class="text-2xl font-[700] tracking-tight flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm-7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Petty Cash Entry
                </h3>
                <button onclick="document.getElementById('modal-petty').classList.add('hidden')" class="text-white/70 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('sales.cashbook.transaction.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <input type="hidden" name="cash_day_id" value="{{ $cashDay->id }}">
                <input type="hidden" name="source" value="petty">
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Entry Type</label>
                    <select id="petty-type-select" class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-emerald-500" onchange="togglePettyCategories()">
                        <option value="income">Added Funds (Replenish)</option>
                        <option value="expense">Spent Funds (Expense)</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Currency</label>
                        <select name="currency" class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="RWF">RWF</option>
                            <option value="USD">USD</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Amount</label>
                        <input type="number" step="0.01" name="amount" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-black text-slate-800 outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Category</label>
                    <!-- Income Categories -->
                    <select name="category_id" id="petty-income-cats" class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-emerald-500">
                        @foreach($categories->where('type', 'income') as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <!-- Expense Categories (Hidden initially) -->
                    <select name="category_id" id="petty-expense-cats" class="hidden w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-emerald-500" disabled>
                        @foreach($categories->where('type', 'expense') as $category)
                            <option value="{{ $category->id }}">{{ $category->name }} ({{ strtoupper($category->classification) }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-medium text-slate-700 outline-none focus:ring-2 focus:ring-emerald-500 resize-none"></textarea>
                </div>
                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-4 rounded-xl transition-colors">Save Petty Cash Entry</button>
            </form>
        </div>
    </div>
</div>

<script>
    function calculateRC() {
        const ttc = parseFloat(document.getElementById('ticket-ttc').value) || 0;
        const fee = parseFloat(document.getElementById('ticket-fee').value) || 0;
        const rc = ttc + fee;
        document.getElementById('ticket-rc').value = rc.toFixed(2);
        document.getElementById('ticket-amount').value = rc.toFixed(2);
    }
    function validateTicketForm(form) {
        if(parseFloat(form.rc.value) <= 0) {
            alert("RC must be greater than 0.");
            return false;
        }
        return true;
    }
    function togglePettyCategories() {
        const type = document.getElementById('petty-type-select').value;
        const incCats = document.getElementById('petty-income-cats');
        const expCats = document.getElementById('petty-expense-cats');

        if(type === 'income') {
            incCats.classList.remove('hidden');
            incCats.removeAttribute('disabled');
            expCats.classList.add('hidden');
            expCats.setAttribute('disabled', 'disabled');
        } else {
            expCats.classList.remove('hidden');
            expCats.removeAttribute('disabled');
            incCats.classList.add('hidden');
            incCats.setAttribute('disabled', 'disabled');
        }
    }
</script>
@endsection
