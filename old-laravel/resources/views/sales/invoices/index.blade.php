@extends('layouts.app')
@section('title', 'Invoice Tracker')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-3">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-slate-900 italic">Invoice <span class="text-red-700">Tracker</span></h1>
            <p class="text-slate-400 mt-1 text-xs font-bold tracking-[0.1em] uppercase opacity-70">Monitor receivables, taxation, and terms</p>
        </div>
        <div class="flex items-center gap-4" x-data="{ open: false }">
            <div class="relative">
                <button @click="open = !open" @click.away="open = false" class="bg-red-700 hover:bg-red-800 text-white px-6 py-2.5 rounded-lg text-base font-bold transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-900/40 flex items-center gap-2">
                    + Generate Invoice <span class="text-xs">▼</span>
                </button>
                <div x-show="open" x-transition.opacity class="absolute right-0 mt-2 w-56 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden z-50 py-2">
                    <a href="{{ route('sales.invoices.create.individual') }}" class="block px-4 py-3 text-sm font-bold text-slate-700 hover:bg-red-50 hover:text-red-700 transition-colors items-center gap-2 border-b border-slate-100">
                        <span class="text-lg">👤</span> Individual Client
                    </a>
                    <a href="{{ route('sales.invoices.create.organization') }}" class="block px-4 py-3 text-sm font-bold text-slate-700 hover:bg-red-50 hover:text-red-700 transition-colors items-center gap-2">
                        <span class="text-lg">🏢</span> Organizational Client
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Search & Filters -->
    <div class="bg-white/40 backdrop-blur-xl p-6 rounded-2xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        <form action="{{ route('sales.invoices.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 items-end">
            <div class="col-span-1 lg:col-span-2 relative group">
                <label class="block text-sm font-black uppercase tracking-widest text-slate-400 mb-1">Search Identifier</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Invoice # or Client..." class="w-full bg-white/60 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-red-700 hover:border-red-700/50 transition-all pl-10 shadow-sm">
                <span class="absolute left-3 top-8 text-slate-400 group-focus-within:text-red-700 transition-colors">🔍</span>
            </div>
            
            <div>
                <label class="block text-sm font-black uppercase tracking-widest text-slate-400 mb-1">Status</label>
                <select name="status_filter" class="w-full bg-white/60 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-red-700 shadow-sm cursor-pointer hover:border-red-700/50 transition-all">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status_filter') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="cancelled" {{ request('status_filter') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-black uppercase tracking-widest text-slate-400 mb-1">Client</label>
                <select name="client_id" class="w-full bg-white/60 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-red-700 shadow-sm cursor-pointer hover:border-red-700/50 transition-all">
                    <option value="">All Clients</option>
                    @foreach($clients ?? [] as $client)
                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2 col-span-1 lg:col-span-2">
                <div class="flex-1">
                    <label class="block text-sm font-black uppercase tracking-widest text-slate-400 mb-1">Date Range</label>
                    <div class="flex gap-2">
                        <input type="date" name="date_start" value="{{ request('date_start') }}" class="w-full bg-white/60 border border-slate-200 rounded-lg px-2 py-2.5 text-sm font-semibold focus:outline-none focus:border-red-700 shadow-sm">
                        <input type="date" name="date_end" value="{{ request('date_end') }}" class="w-full bg-white/60 border border-slate-200 rounded-lg px-2 py-2.5 text-sm font-semibold focus:outline-none focus:border-red-700 shadow-sm">
                    </div>
                </div>
            </div>

            <div class="col-span-1 lg:col-span-6 flex gap-4 mt-2 justify-end">
                <div class="flex gap-4 flex-1">
                    <input type="number" name="min_amount" value="{{ request('min_amount') }}" placeholder="Min Amount" class="w-1/4 bg-white/60 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-red-700 shadow-sm">
                    <input type="number" name="max_amount" value="{{ request('max_amount') }}" placeholder="Max Amount" class="w-1/4 bg-white/60 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-red-700 shadow-sm">
                </div>
                <button type="submit" class="bg-red-700 hover:bg-red-800 text-white px-8 py-2.5 rounded-lg text-sm font-bold transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-900/40">Apply Filters</button>
                @if(request()->anyFilled(['search', 'status_filter', 'client_id', 'date_start', 'date_end', 'min_amount', 'max_amount']))
                    <a href="{{ route('sales.invoices.index') }}" class="bg-white/60 hover:bg-white text-slate-600 px-6 py-2.5 rounded-lg text-sm font-bold transition-all border border-slate-200">Clear</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Grid -->
    <div class="bg-white/40 backdrop-blur-xl border border-white/40 rounded-2xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="border-b border-white/40 bg-white/60 text-sm uppercase tracking-widest text-slate-400 font-black">
                        <th class="px-6 py-4">Invoice #</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Client Name</th>
                        <th class="px-6 py-4">Organization</th>
                        <th class="px-6 py-4">Currency</th>
                        <th class="px-6 py-4 text-right">Billed Amount</th>
                        <th class="px-6 py-4 text-right">Tax Amount</th>
                        <th class="px-6 py-4">Issue Date</th>
                        <th class="px-6 py-4">Due Date</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4">Created By</th>
                        <th class="px-6 py-4 font-black"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/40">
                    @forelse ($invoices as $invoice)
                    <tr class="hover:bg-white/60 transition-colors duration-300 group">
                        <td class="px-6 py-4 font-bold text-slate-900">{{ $invoice->document_number ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500 font-semibold tracking-wider uppercase">{{ $invoice->type }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $invoice->client->name ?? 'Unknown' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500 uppercase tracking-widest">{{ $invoice->organization->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-slate-400 uppercase tracking-widest">{{ $invoice->currency }}</td>
                        <td class="px-6 py-4 text-right font-black text-slate-900">{{ number_format($invoice->amount) }}</td>
                        <td class="px-6 py-4 text-right font-bold text-slate-500">{{ number_format($invoice->tax_amount ?? 0) }}</td>
                        <td class="px-6 py-4 text-xs font-semibold text-slate-700">{{ $invoice->issue_date ? \Carbon\Carbon::parse($invoice->issue_date)->format('d M Y') : 'N/A' }}</td>
                        <td class="px-6 py-4 text-xs font-semibold text-red-800">{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') : 'N/A' }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($invoice->status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-black bg-red-50/80 text-red-500 uppercase tracking-widest border border-red-200/50">Pending</span>
                            @elseif($invoice->status === 'paid')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-black bg-slate-100 text-slate-600 uppercase tracking-widest border border-slate-200">Paid</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-black bg-red-900/10 text-red-900 uppercase tracking-widest border border-red-900/20">Cancelled</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs font-semibold text-slate-700">{{ $invoice->generator->name ?? 'System' }}</td>
                        <td class="px-6 py-4 text-right relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="text-slate-400 hover:text-red-700 transition-colors p-2 focus:outline-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" x-transition.opacity.scale.origin.top.right class="absolute right-8 top-10 w-48 bg-white/90 backdrop-blur-md border border-white/40 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] z-10 py-2" style="display: none;">
                                <a href="{{ route('sales.invoices.pdf', $invoice->id) }}" target="_blank" class="block px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-red-700 transition-colors">View Details</a>
                                <a href="{{ route('sales.invoices.edit', $invoice->id) }}" class="block px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-red-700 transition-colors">Edit Invoice</a>
                                <a href="{{ route('sales.invoices.pdf', $invoice->id) }}" class="block px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-red-700 transition-colors">Download PDF</a>
                                
                                <div class="border-t border-slate-200/50 my-1"></div>
                                
                                @if($invoice->status !== 'paid')
                                <form action="{{ route('sales.invoices.update', $invoice->id) }}" method="POST" onsubmit="return confirm('Confirm marking this invoice as Paid?');">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="paid">
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm font-black text-emerald-700 hover:bg-emerald-50 transition-colors">Mark as Paid</button>
                                </form>
                                @endif

                                @if($invoice->status !== 'pending')
                                <form action="{{ route('sales.invoices.update', $invoice->id) }}" method="POST" onsubmit="return confirm('Confirm marking this invoice as Pending?');">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="pending">
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm font-black text-amber-700 hover:bg-amber-50 transition-colors">Mark as Pending</button>
                                </form>
                                @endif

                                @if($invoice->status !== 'cancelled')
                                <form action="{{ route('sales.invoices.update', $invoice->id) }}" method="POST" onsubmit="return confirm('Confirm cancelling this invoice? This action cannot be fully undone.');">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm font-black text-red-700 hover:bg-red-50 transition-colors">Cancel Invoice</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="px-6 py-12 text-center text-slate-500 font-semibold">
                            No invoices trace reality.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
