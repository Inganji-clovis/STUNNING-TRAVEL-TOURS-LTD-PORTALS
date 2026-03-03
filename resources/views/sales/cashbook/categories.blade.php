@extends('layouts.app')

@section('title', 'Expenditure & Revenue Classifications')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 animate-in float-up duration-700">
    <!-- Header -->
    <div class="flex justify-between items-center bg-gradient-to-r from-emerald-50 to-teal-50 p-8 rounded-[2rem] border border-emerald-100 shadow-2xl shadow-emerald-900/10">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-full text-emerald-600 font-bold text-sm shadow-sm mb-4">
                <span>📂</span> Ledger Architecture
            </div>
            <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-emerald-700 to-teal-600 tracking-tight">Classification Hierarchy</h1>
            <p class="text-slate-500 font-medium text-lg mt-2">Configure standard entries for OPEX, CAPEX, and Revenue mapping.</p>
        </div>
    </div>

    @include('layouts.alerts')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- New Category Form -->
        <div class="lg:col-span-1 border border-slate-100 bg-white rounded-[2rem] shadow-xl p-8 relative overflow-hidden">
            <h2 class="text-2xl font-black text-slate-800 mb-8 relative z-10 flex items-center gap-3">
                <span class="text-emerald-500">➕</span> Add Node
            </h2>
            
            <form action="{{ route('sales.cashbook.categories.store') }}" method="POST" class="space-y-6 relative z-10">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Category Name</label>
                    <input type="text" name="name" required placeholder="e.g. Flight Tickets, Office Rent..." class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-semibold text-slate-800 outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Entry Type</label>
                    <select name="type" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-semibold text-slate-800 outline-none focus:ring-2 focus:ring-emerald-500 transition-all cursor-pointer">
                        <option value="income">Income (Revenue)</option>
                        <option value="expense">Expense (Outflow)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Classification Code</label>
                    <select name="classification" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-semibold text-slate-800 outline-none focus:ring-2 focus:ring-emerald-500 transition-all cursor-pointer">
                        <option value="none">N/A (Generally for Income)</option>
                        <option value="opex">OPEX (Operational Expenditure)</option>
                        <option value="capex">CAPEX (Capital Expenditure)</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-emerald-500/30 transition-all hover:shadow-xl hover:-translate-y-0.5 uppercase tracking-widest text-sm">
                    💾 Register Component
                </button>
            </form>
        </div>

        <!-- Accounts Tree -->
        <div class="lg:col-span-2 bg-white rounded-[2rem] shadow-xl border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h2 class="text-2xl font-black text-slate-800">Chart of Dependencies</h2>
                <div class="bg-white text-slate-500 border border-slate-200 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-widest shadow-sm">
                    {{ $categories->count() }} Registered Nodes
                </div>
            </div>

            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-xs uppercase tracking-widest font-bold">
                        <th class="p-4 pl-8">Name</th>
                        <th class="p-4">Type</th>
                        <th class="p-4 text-right pr-8">Class</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($categories as $category)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="p-4 pl-8 font-extrabold text-slate-700 flex items-center gap-3">
                            <span class="text-xl">
                                {{ $category->type === 'income' ? '🟢' : '🔴' }}
                            </span>
                            {{ $category->name }}
                        </td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $category->type === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} uppercase tracking-widest">
                                {{ $category->type }}
                            </span>
                        </td>
                        <td class="p-4 text-right pr-8">
                            <span class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-100 px-3 py-1 rounded-lg">
                                {{ $category->classification }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
