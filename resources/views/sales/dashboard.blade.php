@extends('layouts.app')
@section('title', 'Financial Control Portal')

@section('content')
<div x-data="{ 
    showIncome: false, 
    showExpense: false, 
    showTicket: false, 
    showPetty: false, 
    showClose: false,
    ticketTTS: '',
    ticketSF: '',
    get expectedRC() { 
        let tts = parseFloat(this.ticketTTS) || 0;
        let sf = parseFloat(this.ticketSF) || 0;
        return (tts + sf).toFixed(2);
    }
}" class="space-y-8 animate-in slide-in-from-bottom duration-700">

    @if(session('success'))
        <div class="px-6 py-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl font-bold shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="px-6 py-4 bg-red-50 border border-red-200 text-red-800 rounded-xl font-bold shadow-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Header with Controls -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-3">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-slate-900 italic">Financial <span class="text-red-700">Control Portal</span></h1>
            <p class="text-slate-400 mt-1 text-xs font-bold tracking-[0.1em] uppercase opacity-70">Daily Cash Flow Operations - {{ \Carbon\Carbon::parse($day->date)->format('F d, Y') }}</p>
            <span class="inline-block mt-2 px-3 py-1 bg-{{ $day->status == 'open' ? 'emerald' : 'slate' }}-100 text-{{ $day->status == 'open' ? 'emerald' : 'slate' }}-800 text-[10px] font-black tracking-widest uppercase rounded-full border border-{{ $day->status == 'open' ? 'emerald' : 'slate' }}-200">
                Status: {{ $day->status }}
            </span>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <div class="bg-white/80 backdrop-blur-md text-slate-800 px-5 py-2.5 rounded-lg text-sm font-black tracking-widest uppercase flex items-center gap-3 shadow-sm border border-slate-200/60 transition-all hover:border-red-700/30" 
                 x-data="{ time: new Date() }" 
                 x-init="setInterval(() => time = new Date(), 1000)">
                <span x-text="time.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' })" class="text-red-700"></span>
            </div>
            <a href="{{ route('sales.reports.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-bold tracking-widest uppercase transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-indigo-900/40 backdrop-blur-md">
                📊 View Reports
            </a>
            @if($day->status === 'open')
            <button @click="showClose = true" class="bg-slate-900 hover:bg-black text-white px-6 py-2.5 rounded-lg text-sm font-bold tracking-widest uppercase transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-900/40 backdrop-blur-md">
                🔒 Close Day
            </button>
            @endif
        </div>
    </div>

    <!-- MAIN ACTION BUTTONS -->
    @if($day->status === 'open')
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <button @click="showIncome = true" class="p-6 bg-white border border-slate-200 rounded-xl hover:border-emerald-500 transition-all duration-300 hover:-translate-y-1 hover:shadow-sm flex items-center gap-4 group">
            <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
            <div class="text-left">
                <p class="font-black text-slate-900">Add Income</p>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">General Revenue</p>
            </div>
        </button>
        <button @click="showExpense = true" class="p-6 bg-white border border-slate-200 rounded-xl hover:border-red-500 transition-all duration-300 hover:-translate-y-1 hover:shadow-sm flex items-center gap-4 group">
            <div class="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
            </div>
            <div class="text-left">
                <p class="font-black text-slate-900">Add Expense</p>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">OPEX / CAPEX</p>
            </div>
        </button>
        <button @click="showTicket = true" class="p-6 bg-white border border-slate-200 rounded-xl hover:border-blue-500 transition-all duration-300 hover:-translate-y-1 hover:shadow-sm flex items-center gap-4 group">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
            </div>
            <div class="text-left">
                <p class="font-black text-slate-900">Ticket Sale</p>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Airlines</p>
            </div>
        </button>
        <button @click="showPetty = true" class="p-6 bg-white border border-slate-200 rounded-xl hover:border-amber-500 transition-all duration-300 hover:-translate-y-1 hover:shadow-sm flex items-center gap-4 group">
            <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div class="text-left">
                <p class="font-black text-slate-900">Petty Cash</p>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Sub-System</p>
            </div>
        </button>
    </div>
    @endif

    <!-- Section A: 4 Main Financial Summary Cards -->
    <div class="mt-8">
        <h2 class="text-[24px] font-[700] text-slate-900 mb-6 flex items-center gap-3">
            Daily Main Cash Flow
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Opening -->
            <div class="p-6 bg-white border-l-[6px] border-red-600 border-y border-r border-slate-200 rounded-xl shadow-sm flex flex-col justify-between">
                <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-4">1. Opening Cash</p>
                <div class="space-y-3">
                    <div class="flex items-baseline justify-between border-b border-slate-100 pb-2">
                        <div class="flex items-baseline"><p class="text-[28px] font-extrabold text-slate-900 tabular-nums">{{ number_format($day->opening_balance) }}</p><span class="text-xs font-black text-slate-400 ml-1 uppercase">RWF</span></div>
                    </div>
                    <div class="flex items-baseline justify-between pt-1">
                        <div class="flex items-baseline"><p class="text-[22px] font-bold text-slate-700 tabular-nums">{{ number_format($day->opening_balance_usd, 2) }}</p><span class="text-xs font-black text-slate-400 ml-1 uppercase">USD</span></div>
                    </div>
                </div>
            </div>
            
            <!-- Income -->
            <div class="p-6 bg-white border-l-[6px] border-blue-600 border-y border-r border-slate-200 rounded-xl shadow-sm flex flex-col justify-between">
                <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-4">2. Total Income</p>
                <div class="space-y-3">
                    <div class="flex items-baseline justify-between border-b border-slate-100 pb-2">
                        <div class="flex items-baseline"><p class="text-[28px] font-extrabold text-slate-900 tabular-nums">+{{ number_format($rwfIncome) }}</p><span class="text-xs font-black text-slate-400 ml-1 uppercase">RWF</span></div>
                    </div>
                    <div class="flex items-baseline justify-between pt-1">
                        <div class="flex items-baseline"><p class="text-[22px] font-bold text-slate-700 tabular-nums">+{{ number_format($usdIncome, 2) }}</p><span class="text-xs font-black text-slate-400 ml-1 uppercase">USD</span></div>
                    </div>
                </div>
            </div>

            <!-- Expenses -->
            <div class="p-6 bg-white border-l-[6px] border-orange-500 border-y border-r border-slate-200 rounded-xl shadow-sm flex flex-col justify-between">
                <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-4">3. Total Expenses</p>
                <div class="space-y-3">
                    <div class="flex items-baseline justify-between border-b border-slate-100 pb-2">
                        <div class="flex items-baseline"><p class="text-[28px] font-extrabold text-slate-900 tabular-nums">-{{ number_format($rwfExpense) }}</p><span class="text-xs font-black text-slate-400 ml-1 uppercase">RWF</span></div>
                    </div>
                    <div class="flex items-baseline justify-between pt-1">
                        <div class="flex items-baseline"><p class="text-[22px] font-bold text-slate-700 tabular-nums">-{{ number_format($usdExpense, 2) }}</p><span class="text-xs font-black text-slate-400 ml-1 uppercase">USD</span></div>
                    </div>
                </div>
            </div>

            <!-- Closing -->
            <div class="p-6 bg-white border-l-[6px] border-emerald-600 border-y border-r border-slate-200 rounded-xl shadow-sm flex flex-col justify-between">
                <p class="text-slate-500 text-xs font-black uppercase tracking-widest mb-4">4. Auto Closing Cash</p>
                <div class="space-y-3">
                    <div class="flex items-baseline justify-between border-b border-slate-100 pb-2">
                        <div class="flex items-baseline"><p class="text-[28px] font-extrabold text-slate-900 tabular-nums">{{ number_format($currentRwf) }}</p><span class="text-xs font-black text-slate-400 ml-1 uppercase">RWF</span></div>
                    </div>
                    <div class="flex items-baseline justify-between pt-1">
                        <div class="flex items-baseline"><p class="text-[22px] font-bold text-slate-700 tabular-nums">{{ number_format($currentUsd, 2) }}</p><span class="text-xs font-black text-slate-400 ml-1 uppercase">USD</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section B: Petty Cash Module & Recent Trans -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Petty Cash Card -->
        <div class="lg:col-span-1 bg-white p-8 rounded-2xl border-l-[6px] border-amber-500 border-y border-r border-slate-200 shadow-sm relative overflow-hidden group hover:border-amber-600 transition-all duration-500">
            <h2 class="text-[24px] font-[700] text-slate-900 mb-6 flex items-center gap-3">
                Petty Cash System
            </h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-slate-50 border border-slate-100 rounded-lg">
                    <span class="text-xs font-black text-slate-500 uppercase">Opening RWF</span>
                    <span class="text-base font-semibold tabular-nums text-slate-800">{{ number_format($day->opening_petty_balance) }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-white border border-emerald-100 rounded-lg text-emerald-700">
                    <span class="text-xs font-black uppercase">+ Added RWF</span>
                    <span class="text-base font-semibold tabular-nums">+{{ number_format($pettyRwfIncome) }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-white border border-red-100 rounded-lg text-red-700">
                    <span class="text-xs font-black uppercase">- Spent RWF</span>
                    <span class="text-base font-semibold tabular-nums">-{{ number_format($pettyRwfExpense) }}</span>
                </div>
                <div class="flex justify-between items-center p-4 bg-amber-50 rounded-lg text-amber-900 border border-amber-200 shadow-sm">
                    <span class="text-sm font-black uppercase tracking-widest">Closing Petty</span>
                    <span class="text-[22px] font-bold tabular-nums">{{ number_format($currentPettyRwf) }} <span class="text-xs">RWF</span></span>
                </div>
            </div>
        </div>

        <!-- Recent Transactions Log -->
        <div class="lg:col-span-2 bg-white p-8 rounded-2xl border-l-[6px] border-slate-500 border-y border-r border-slate-200 shadow-sm relative overflow-hidden group transition-all duration-500">
            <h2 class="text-[24px] font-[700] text-slate-900 mb-6 flex items-center gap-3">
                Today's Ledger
            </h2>
            <div class="max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                @if($transactions->count() > 0)
                    <table class="w-full text-left text-sm">
                        <thead class="text-xs text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="pb-3">Time</th>
                                <th class="pb-3">Category</th>
                                <th class="pb-3">Detail</th>
                                <th class="pb-3 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($transactions as $tx)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-3 text-slate-500 font-medium">{{ $tx->created_at->format('H:i') }}</td>
                                    <td class="py-3">
                                        <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded text-[10px] font-bold uppercase tracking-wider">
                                            {{ $tx->category->name }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-slate-700 font-medium truncate max-w-[150px]">
                                        {{ $tx->description ?? 'N/A' }} 
                                        @if($tx->source == 'petty') <span class="text-[10px] text-amber-600 font-bold ml-1">(PETTY)</span> @endif
                                    </td>
                                    <td class="py-3 text-right text-base font-semibold tabular-nums {{ $tx->type == 'income' ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ $tx->type == 'income' ? '+' : '-' }}{{ number_format($tx->amount, $tx->currency == 'USD' ? 2 : 0) }} <span class="text-[10px] text-slate-400">{{ $tx->currency }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-10 text-slate-400 font-bold text-sm">No transactions recorded today.</div>
                @endif
            </div>
        </div>
    </div>

    <!-- MODALS -->

    <!-- 1. Income Modal -->
    <div x-show="showIncome" style="display:none" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60" @click.self="showIncome = false">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <div class="bg-emerald-600 p-6 text-white flex justify-between items-center">
                <h3 class="text-2xl font-[700] tracking-tight">Add Income</h3>
                <button @click="showIncome = false" class="text-white/70 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('sales.financial.income') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Amount</label>
                        <input type="number" step="0.01" name="amount" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Currency</label>
                        <select name="currency" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none focus:border-emerald-500">
                            <option value="RWF">RWF</option><option value="USD">USD</option>
                        </select>
                    </div>
                </div>
                <!-- ... Categories -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Category</label>
                    <select name="category" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none focus:border-emerald-500">
                        <option value="Visa">Visa Service</option>
                        <option value="Tour Package">Tour Package</option>
                        <option value="Hotel Booking">Hotel Booking</option>
                        <option value="Car Rental">Car Rental</option>
                        <option value="Commission">Commission</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Payment Method</label>
                        <select name="payment_method" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none focus:border-emerald-500">
                            <option value="Bank">Bank Transfer</option>
                            <option value="MoMo">Mobile Money</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Description</label>
                    <input type="text" name="description" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none focus:border-emerald-500">
                </div>
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-lg transition-colors">Save Income Record</button>
            </form>
        </div>
    </div>

    <!-- 2. Expense Modal -->
    <div x-show="showExpense" style="display:none" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60" @click.self="showExpense = false">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <div class="bg-red-700 p-6 text-white flex justify-between items-center">
                <h3 class="text-2xl font-[700] tracking-tight">Add Expense</h3>
                <button @click="showExpense = false" class="text-white/70 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('sales.financial.expense') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Amount</label>
                        <input type="number" step="0.01" name="amount" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Currency</label>
                        <select name="currency" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none focus:border-red-500">
                            <option value="RWF">RWF</option><option value="USD">USD</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Type (OPEX/CAPEX)</label>
                    <select name="category" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none focus:border-red-500">
                        <option value="OPEX - Salaries">OPEX - Salaries</option>
                        <option value="OPEX - Utilities">OPEX - Utilities</option>
                        <option value="OPEX - Marketing">OPEX - Marketing</option>
                        <option value="OPEX - Rent">OPEX - Rent</option>
                        <option value="CAPEX - Equipment">CAPEX - Equipment</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Paid From</label>
                    <select name="source" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none focus:border-red-500">
                        <option value="main">Main Cash/Bank</option>
                        <option value="petty">Petty Cash</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Description</label>
                    <input type="text" name="description" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none focus:border-red-500">
                </div>
                <button type="submit" class="w-full bg-red-700 hover:bg-red-800 text-white font-bold py-3 rounded-lg transition-colors">Save Expense Record</button>
            </form>
        </div>
    </div>

    <!-- 3. Ticket Sale Modal -->
    <div x-show="showTicket" style="display:none" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60" @click.self="showTicket = false">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <div class="bg-blue-800 p-6 text-white flex justify-between items-center">
                <h3 class="text-2xl font-[700] tracking-tight flex items-center gap-2">Airline Ticket Sale</h3>
                <button @click="showTicket = false" class="text-white/70 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="bg-blue-50 px-6 py-3 border-b border-blue-100 flex gap-4 text-xs font-bold text-blue-900 uppercase tracking-widest">
                <span>Rule: <span class="bg-blue-200 px-1.5 py-0.5 rounded ml-1">RC = TTS + Service Fee</span></span>
                <span>Profit = Service Fee</span>
            </div>
            <form action="{{ route('sales.financial.ticket') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Supplier / Airline</label>
                        <input type="text" name="supplier_name" required placeholder="e.g. RwandAir" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Currency</label>
                        <select name="currency" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none focus:border-blue-500">
                            <option value="USD">USD</option><option value="RWF">RWF</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">TTS (Supplier Cost)</label>
                        <input type="number" step="0.01" name="tts" x-model="ticketTTS" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-3 font-black text-slate-900 focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1">Service Fee (+)</label>
                        <input type="number" step="0.01" name="service_fee" x-model="ticketSF" required class="w-full bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-3 font-black text-emerald-900 focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-blue-600 uppercase tracking-widest mb-1">RC (Total Collected)</label>
                        <input type="number" step="0.01" name="rc" :value="expectedRC" required class="w-full border-2 border-blue-500 rounded-lg px-3 py-3 font-black text-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <!-- Payment method and desc -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Payment Method</label>
                        <select name="payment_method" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-blue-500">
                            <option value="Bank">Bank Transfer</option>
                            <option value="MoMo">Mobile Money</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Description/PNR</label>
                        <input type="text" name="description" placeholder="PNR / Ticket No" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-blue-500">
                    </div>
                </div>
                <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 rounded-lg transition-colors shadow-lg shadow-blue-900/40">Record Ticket Validation</button>
            </form>
        </div>
    </div>

    <!-- 4. Petty Cash Modal -->
    <div x-show="showPetty" style="display:none" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60" @click.self="showPetty = false">
        <div class="bg-white w-full max-w-sm rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <div class="bg-amber-500 p-6 text-white flex justify-between items-center">
                <h3 class="text-2xl font-[700] tracking-tight">Petty Cash</h3>
                <button @click="showPetty = false" class="text-white/70 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('sales.financial.petty-cash') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Action Type</label>
                    <select name="type" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none focus:border-amber-500">
                        <option value="spend">Record Expense (Spend)</option>
                        <option value="added">Top-Up (Add Funds)</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Amount</label>
                        <input type="number" step="0.01" name="amount" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none focus:border-amber-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Currency</label>
                        <select name="currency" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none focus:border-amber-500">
                            <option value="RWF">RWF</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Description</label>
                    <input type="text" name="description" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none focus:border-amber-500">
                </div>
                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold py-3 rounded-lg transition-colors">Process Petty Cash</button>
            </form>
        </div>
    </div>

    <!-- 5. Close Day Modal -->
    <div x-show="showClose" style="display:none" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/80" @click.self="showClose = false">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <div class="bg-slate-900 p-6 text-white text-center">
                <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h3 class="text-2xl font-[700] tracking-tight">Confirm Daily Close</h3>
                <p class="text-slate-400 text-sm mt-2">This action will lock all transactions for today and roll the closing balances to tomorrow's opening balance. This cannot be undone.</p>
            </div>
            <div class="p-6 bg-slate-50 space-y-4 text-center">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white p-3 rounded-lg border border-slate-200 shadow-sm">
                        <p class="text-[10px] font-black text-slate-500 uppercase">Closing RWF</p>
                        <p class="text-xl font-black text-slate-900">{{ number_format($currentRwf) }}</p>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-slate-200 shadow-sm">
                        <p class="text-[10px] font-black text-slate-500 uppercase">Closing USD</p>
                        <p class="text-xl font-black text-slate-900">{{ number_format($currentUsd, 2) }}</p>
                    </div>
                </div>
                <form action="{{ route('sales.financial.close-day') }}" method="POST">
                    @csrf
                    <div class="flex gap-4">
                        <button type="button" @click="showClose = false" class="w-full bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold py-3 rounded-lg transition-colors">Cancel</button>
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg transition-colors shadow-lg shadow-red-900/40">Lock & Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
