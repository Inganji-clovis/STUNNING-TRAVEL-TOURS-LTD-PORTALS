@extends('layouts.app')

@section('title', 'Open Financial Day')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in float-up duration-700">
    <div class="bg-gradient-to-r from-orange-50 to-amber-50 p-8 rounded-[2rem] border border-orange-100 shadow-2xl shadow-orange-900/10 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-full text-orange-600 font-bold text-sm shadow-sm mb-4">
            <span>🌅</span> System Check
        </div>
        <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-amber-600 tracking-tight">Open Financial Day</h1>
        <p class="text-slate-500 font-medium text-lg mt-2">Before recording operations, confirm the opening balances for {{ \Carbon\Carbon::parse($today)->format('l, F j, Y') }}</p>
    </div>

    @include('layouts.alerts')

    <form action="{{ route('sales.cashbook.open') }}" method="POST" class="bg-white p-8 rounded-[2rem] shadow-xl border border-slate-100 relative overflow-hidden">
        @csrf
        <div class="absolute top-0 left-0 w-2 h-full bg-gradient-to-b from-orange-500 to-amber-500"></div>
        
        <input type="hidden" name="date" value="{{ $today }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Main Coffer -->
            <div class="space-y-6">
                <h3 class="font-extrabold text-xl text-slate-800 border-b border-slate-100 pb-2 flex items-center gap-2">
                    <span class="text-2xl">🏦</span> Main Coffer
                </h3>
                
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Opening RWF</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">RWF</span>
                        <input type="number" step="0.01" name="opening_balance" value="{{ $suggestedOpening }}" class="w-full pl-16 pr-4 py-4 bg-slate-50 border-0 rounded-2xl text-lg font-bold text-slate-800 focus:ring-2 focus:ring-orange-500 transition-all shadow-inner" required>
                    </div>
                    <p class="text-xs text-slate-400 font-medium mt-2">Suggested from yesterday's close</p>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Opening USD</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">$</span>
                        <input type="number" step="0.01" name="opening_balance_usd" value="{{ $suggestedOpeningUsd }}" class="w-full pl-12 pr-4 py-4 bg-slate-50 border-0 rounded-2xl text-lg font-bold text-slate-800 focus:ring-2 focus:ring-orange-500 transition-all shadow-inner" required>
                    </div>
                </div>
            </div>

            <!-- Petty Cash -->
            <div class="space-y-6">
                <h3 class="font-extrabold text-xl text-slate-800 border-b border-slate-100 pb-2 flex items-center gap-2">
                    <span class="text-2xl">💵</span> Petty Cash
                </h3>
                
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Petty Cash RWF</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">RWF</span>
                        <input type="number" step="0.01" name="opening_petty_balance" value="{{ $suggestedPettyOpening }}" class="w-full pl-16 pr-4 py-4 bg-slate-50 border-0 rounded-2xl text-lg font-bold text-slate-800 focus:ring-2 focus:ring-orange-500 transition-all shadow-inner" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Petty Cash USD</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">$</span>
                        <input type="number" step="0.01" name="opening_petty_balance_usd" value="{{ $suggestedPettyOpeningUsd }}" class="w-full pl-12 pr-4 py-4 bg-slate-50 border-0 rounded-2xl text-lg font-bold text-slate-800 focus:ring-2 focus:ring-orange-500 transition-all shadow-inner" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10 flex justify-end">
            <button type="submit" class="bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-700 hover:to-amber-700 text-white px-10 py-5 rounded-2xl shadow-xl transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 font-black uppercase tracking-[0.2em] text-sm flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                Lock & Open Day
            </button>
        </div>
    </form>
</div>
@endsection
