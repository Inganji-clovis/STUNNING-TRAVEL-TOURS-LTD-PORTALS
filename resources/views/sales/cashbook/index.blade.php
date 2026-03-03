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
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 hover:shadow-2xl transition-all duration-300 group">
            <div class="h-14 w-14 bg-blue-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                <span class="text-2xl">🌅</span>
            </div>
            <p class="text-slate-400 font-bold text-xs uppercase tracking-[0.2em] mb-4">1. Opening Cash</p>
            <div class="space-y-3">
                <div class="flex justify-between items-center border-b border-slate-50 pb-2">
                    <span class="text-sm font-bold text-slate-500">RWF</span>
                    <span class="text-[28px] font-extrabold tabular-nums text-slate-800">{{ number_format($cashDay->opening_balance) }}</span>
                </div>
                <div class="flex justify-between items-center pb-1">
                    <span class="text-sm font-bold text-slate-500">USD</span>
                    <span class="text-[22px] font-bold tabular-nums text-slate-800">${{ number_format($cashDay->opening_balance_usd, 2) }}</span>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-4 uppercase font-bold tracking-widest text-center">Starting Balance</p>
        </div>

        <!-- 2. Total Income -->
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 hover:shadow-2xl transition-all duration-300 group">
            <div class="h-14 w-14 bg-green-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                <span class="text-2xl">📈</span>
            </div>
            <p class="text-slate-400 font-bold text-xs uppercase tracking-[0.2em] mb-4">2. Total Income</p>
            <div class="space-y-3">
                <div class="flex justify-between items-center border-b border-slate-50 pb-2">
                    <span class="text-sm font-bold text-slate-500">RWF</span>
                    <span class="text-[28px] font-extrabold tabular-nums text-green-500">+{{ number_format($mainIncomeRw) }}</span>
                </div>
                <div class="flex justify-between items-center pb-1">
                    <span class="text-sm font-bold text-slate-500">USD</span>
                    <span class="text-[22px] font-bold tabular-nums text-green-500">+${{ number_format($mainIncomeUsd, 2) }}</span>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-4 uppercase font-bold tracking-widest text-center">Cash Revenue</p>
        </div>

        <!-- 3. Total Expenses -->
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 hover:shadow-2xl transition-all duration-300 group">
            <div class="h-14 w-14 bg-red-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                <span class="text-2xl">📉</span>
            </div>
            <p class="text-slate-400 font-bold text-xs uppercase tracking-[0.2em] mb-4">3. Total Expenses</p>
            <div class="space-y-3">
                <div class="flex justify-between items-center border-b border-slate-50 pb-2">
                    <span class="text-sm font-bold text-slate-500">RWF</span>
                    <span class="text-[28px] font-extrabold tabular-nums text-red-500">-{{ number_format($mainExpenseRw) }}</span>
                </div>
                <div class="flex justify-between items-center pb-1">
                    <span class="text-sm font-bold text-slate-500">USD</span>
                    <span class="text-[22px] font-bold tabular-nums text-red-500">-${{ number_format($mainExpenseUsd, 2) }}</span>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-4 uppercase font-bold tracking-widest text-center">OPEX & CAPEX</p>
        </div>

        <!-- 4. Closing Cash -->
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 hover:shadow-2xl transition-all duration-300 group">
            <div class="h-14 w-14 bg-indigo-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                <span class="text-2xl">🏦</span>
            </div>
            <p class="text-slate-400 font-bold text-xs uppercase tracking-[0.2em] mb-4">4. Closing Cash</p>
            <div class="space-y-3">
                <div class="flex justify-between items-center border-b border-slate-50 pb-2">
                    <span class="text-sm font-bold text-slate-500">RWF</span>
                    <span class="text-[28px] font-extrabold tabular-nums text-slate-800">{{ number_format($currentMainBalanceRw) }}</span>
                </div>
                <div class="flex justify-between items-center pb-1">
                    <span class="text-sm font-bold text-slate-500">USD</span>
                    <span class="text-[22px] font-bold tabular-nums text-slate-800">${{ number_format($currentMainBalanceUsd, 2) }}</span>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-4 uppercase font-bold tracking-widest text-center">Auto Calculated</p>
        </div>
    </div>

    <!-- Transaction Form & History -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Action Panel -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-[2rem] shadow-xl border border-slate-100 p-8">
                <h2 class="text-[30px] font-extrabold text-slate-800 mb-6 flex items-center gap-3">
                    <span class="text-orange-500">⚡</span> Quick Actions
                </h2>
                <div class="grid grid-cols-1 gap-4 text-sm font-bold tracking-widest uppercase">
                    <button onclick="document.getElementById('modal-income').classList.remove('hidden')" class="bg-green-50 hover:bg-green-100 text-green-700 py-4 rounded-xl transition-colors border border-green-200">
                        ➕ Add Income
                    </button>
                    <button onclick="document.getElementById('modal-expense').classList.remove('hidden')" class="bg-red-50 hover:bg-red-100 text-red-700 py-4 rounded-xl transition-colors border border-red-200">
                        ➖ Add Expense
                    </button>
                    <button onclick="document.getElementById('modal-ticket').classList.remove('hidden')" class="bg-blue-50 hover:bg-blue-100 text-blue-700 py-4 rounded-xl transition-colors border border-blue-200">
                        🎫 Add Ticket Sale
                    </button>
                    <button onclick="document.getElementById('modal-petty').classList.remove('hidden')" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 py-4 rounded-xl transition-colors border border-emerald-200">
                        💵 Petty Cash Entry
                    </button>
                    <a href="{{ route('sales.cashbook.reports') ?? '#' }}" class="bg-slate-50 hover:bg-slate-100 text-slate-700 py-4 rounded-xl text-center block transition-colors border border-slate-200">
                        📊 View Reports
                    </a>
                </div>
            </div>

            <!-- Petty Cash Summary Widget -->
            <div class="bg-gradient-to-br from-slate-900 to-black p-6 rounded-[2rem] shadow-xl text-white">
                <h3 class="font-bold text-slate-400 text-xs uppercase tracking-widest mb-4">👛 Petty Cash Status</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center bg-white/10 p-4 rounded-xl border border-white/10">
                        <span class="text-sm font-medium text-slate-300">RWF</span>
                        <span class="text-[28px] font-extrabold tabular-nums">{{ number_format($currentPettyBalanceRw) }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-white/10 p-4 rounded-xl border border-white/10">
                        <span class="text-sm font-medium text-slate-300">USD</span>
                        <span class="text-[22px] font-bold tabular-nums">${{ number_format($currentPettyBalanceUsd, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ledger -->
        <div class="lg:col-span-2 bg-white rounded-[2rem] shadow-xl border border-slate-100 p-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-[30px] font-extrabold text-slate-800 flex items-center gap-3">
                    <span class="text-blue-500">📋</span> Daily Ledger
                </h2>
                <div class="bg-slate-100 text-slate-500 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest">
                    {{ $transactions->count() }} Entries
                </div>
            </div>

            @if($transactions->isEmpty())
                <div class="text-center py-16 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                    <div class="text-5xl mb-4 opacity-50">📭</div>
                    <p class="text-slate-500 font-medium">The ledger is empty for today.</p>
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
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="p-4 text-sm font-medium text-slate-500">{{ $tx->created_at->format('H:i') }}</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold {{ $tx->source === 'main' ? 'bg-indigo-50 text-indigo-600' : 'bg-emerald-50 text-emerald-600' }}">
                                        {{ $tx->source === 'main' ? '🏦 Main' : '👛 Petty' }}
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
    <div id="modal-income" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden animate-in zoom-in-95 duration-200">
            <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 flex justify-between items-center text-white">
                <h3 class="text-2xl font-bold tracking-wide">➕ Add Income</h3>
                <button onclick="document.getElementById('modal-income').classList.add('hidden')" class="text-white/70 hover:text-white transition-colors">✕</button>
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
    <div id="modal-expense" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden animate-in zoom-in-95 duration-200">
            <div class="bg-gradient-to-r from-red-500 to-red-600 p-6 flex justify-between items-center text-white">
                <h3 class="text-2xl font-bold tracking-wide">➖ Add Expense</h3>
                <button onclick="document.getElementById('modal-expense').classList.add('hidden')" class="text-white/70 hover:text-white transition-colors">✕</button>
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
    <div id="modal-ticket" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden animate-in zoom-in-95 duration-200">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 flex justify-between items-center text-white">
                <h3 class="text-2xl font-bold tracking-wide">🎫 Add Ticket Sale</h3>
                <button onclick="document.getElementById('modal-ticket').classList.add('hidden')" class="text-white/70 hover:text-white transition-colors">✕</button>
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
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-1">TTS (Total To Supplier) <span class="text-blue-500 font-extrabold">*</span></label>
                        <input type="number" step="0.01" name="tts" id="ticket-tts" required class="w-full bg-white border-0 rounded-xl px-4 py-3 font-black text-slate-800 outline-none focus:ring-2 focus:ring-blue-500" oninput="calculateRC()">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-1">Service Fee (Profit) <span class="text-blue-500 font-extrabold">*</span></label>
                        <input type="number" step="0.01" name="service_fee" id="ticket-fee" required class="w-full bg-white border-0 rounded-xl px-4 py-3 font-black text-green-600 outline-none focus:ring-2 focus:ring-blue-500" oninput="calculateRC()">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-1">Rate Collected (RC) <span class="text-blue-500 font-extrabold">= TTS + Fee</span></label>
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
    <div id="modal-petty" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden animate-in zoom-in-95 duration-200">
            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 p-6 flex justify-between items-center text-white">
                <h3 class="text-2xl font-bold tracking-wide">💵 Petty Cash Entry</h3>
                <button onclick="document.getElementById('modal-petty').classList.add('hidden')" class="text-white/70 hover:text-white transition-colors">✕</button>
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
        const tts = parseFloat(document.getElementById('ticket-tts').value) || 0;
        const fee = parseFloat(document.getElementById('ticket-fee').value) || 0;
        const rc = tts + fee;
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
