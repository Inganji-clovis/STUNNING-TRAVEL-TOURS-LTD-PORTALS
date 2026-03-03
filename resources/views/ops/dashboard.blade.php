@extends('layouts.app')
@section('title', 'Operations Dashboard')

@section('content')
<div class="space-y-8 animate-in fade-in duration-1000">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-[#334155]">Operations <span class="italic text-red-600">Dashboard</span></h1>
            <p class="text-[12px] text-slate-400 mt-2 font-bold tracking-[0.15em] uppercase">Client Relationship & Document Logistics</p>
        </div>
        
        <div class="flex gap-4 w-full lg:w-auto">
            <a href="{{ route('ops.clients.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-8 py-3.5 rounded-2xl font-extrabold shadow-lg shadow-red-600/20 transition-all text-center flex-1 lg:flex-none">
                + New Client
            </a>
        </div>
    </div>

    <!-- Stats Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($stats as $label => $value)
            <div class="premium-card p-8 group relative overflow-hidden">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em] mb-4 relative z-10">{{ str_replace('_', ' ', str_replace('total_', '', $label)) }}</p>
                <div class="flex items-end justify-between relative z-10 border-t border-slate-100/50 pt-4 mt-2">
                    <h3 class="text-[32px] font-extrabold text-[#334155] tabular-nums group-hover:text-red-600 transition-colors">{{ $value }}</h3>
                    <div class="h-10 w-10 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-red-50 group-hover:text-red-600 transition-all border border-slate-100 shadow-sm group-hover:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if(isset($dispatches) && $dispatches->count() > 0)
    <!-- MD Dispatches Section -->
    <div class="premium-card overflow-hidden mb-12">
        <div class="p-8 border-b border-slate-100/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/30">
            <div>
                <h2 class="text-[18px] font-extrabold text-[#334155] flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    Executive Dispatches
                </h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.15em] mt-2">Incoming files directly from the Managing Director</p>
            </div>
            <span class="inline-flex items-center px-4 py-2 rounded-xl text-[10px] font-extrabold uppercase tracking-[0.15em] bg-indigo-50 border border-indigo-100 text-indigo-600 shadow-sm">{{ $dispatches->count() }} Pending</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left bg-white">
                <thead class="bg-slate-50/50 border-b border-slate-100/50">
                    <tr class="text-[10px] uppercase tracking-[0.15em] text-slate-400 font-bold">
                        <th class="px-8 py-5">Document</th>
                        <th class="px-8 py-5">Notes / Instructions</th>
                        <th class="px-8 py-5">Sent By</th>
                        <th class="px-8 py-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/50">
                    @foreach($dispatches as $dispatch)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <span class="text-[14px] font-extrabold text-[#334155] block group-hover:text-indigo-600 transition-colors">{{ $dispatch->file_name ?? $dispatch->type }}</span>
                            <span class="text-[9px] font-black tracking-[0.2em] uppercase text-indigo-500/70 mt-1 inline-block bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100/50">{{ $dispatch->type }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-[13px] font-medium text-slate-500 italic">"{{ $dispatch->description ?? 'No specific instructions.' }}"</span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-[11px] font-bold bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-lg shadow-sm text-slate-600 truncate">{{ $dispatch->sender->name ?? 'MD' }}</span>
                            <span class="block text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-[0.1em]">{{ $dispatch->created_at->diffForHumans() }}</span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <a href="{{ Storage::url($dispatch->file_path) }}" target="_blank" class="text-[10px] font-extrabold uppercase tracking-[0.1em] text-indigo-600 hover:text-white hover:bg-indigo-600 bg-indigo-50 border border-indigo-100 px-4 py-2.5 rounded-xl transition-all shadow-sm">Download / View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Client Management -->
        <div class="premium-card p-8 group relative overflow-hidden">
            <h2 class="text-[16px] font-extrabold text-[#334155] tracking-[0.15em] uppercase mb-8 pb-4 border-b border-slate-100/50 flex items-center gap-3">
                <span class="w-1.5 h-6 bg-blue-600 rounded-full"></span>
                Client Management
            </h2>
            <div class="grid grid-cols-1 gap-4">
                <a href="{{ route('ops.clients.create') }}" class="flex items-center justify-between p-5 bg-slate-50/50 border border-slate-100/50 rounded-2xl hover:border-blue-200 hover:bg-white hover:shadow-sm transition-all group/item">
                    <div>
                        <p class="text-[14px] font-extrabold text-[#334155] group-hover/item:text-blue-600 transition-colors">Add New Client</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.1em] mt-1">Individual or corporate enrollment</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-300 group-hover/item:text-blue-600 group-hover/item:border-blue-200 transition-all shadow-sm group-hover/item:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </a>
                <a href="{{ route('ops.clients.index') }}" class="flex items-center justify-between p-5 bg-slate-50/50 border border-slate-100/50 rounded-2xl hover:border-blue-200 hover:bg-white hover:shadow-sm transition-all group/item">
                    <div>
                        <p class="text-[14px] font-extrabold text-[#334155] group-hover/item:text-blue-600 transition-colors">View Clients</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.1em] mt-1">Database lookup and archival</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-300 group-hover/item:text-blue-600 group-hover/item:border-blue-200 transition-all shadow-sm group-hover/item:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </a>
            </div>
        </div>

        <!-- Organization Management -->
        <div class="premium-card p-8 group relative overflow-hidden">
            <h2 class="text-[16px] font-extrabold text-[#334155] tracking-[0.15em] uppercase mb-8 pb-4 border-b border-slate-100/50 flex items-center gap-3">
                <span class="w-1.5 h-6 bg-indigo-600 rounded-full"></span>
                Organization Management
            </h2>
            <div class="grid grid-cols-1 gap-4">
                <a href="{{ route('ops.organizations.index') }}" class="flex items-center justify-between p-5 bg-slate-50/50 border border-slate-100/50 rounded-2xl hover:border-indigo-200 hover:bg-white hover:shadow-sm transition-all group/item">
                    <div>
                        <p class="text-[14px] font-extrabold text-[#334155] group-hover/item:text-indigo-600 transition-colors">View Organizations</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.1em] mt-1">Manage corporate partners</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-300 group-hover/item:text-indigo-600 group-hover/item:border-indigo-200 transition-all shadow-sm group-hover/item:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </a>
            </div>
        </div>

        <!-- Passport Management -->
        <div class="premium-card p-8 group relative overflow-hidden">
            <h2 class="text-[16px] font-extrabold text-[#334155] tracking-[0.15em] uppercase mb-8 pb-4 border-b border-slate-100/50 flex items-center gap-3">
                <span class="w-1.5 h-6 bg-emerald-600 rounded-full"></span>
                Passport Records
            </h2>
            <div class="grid grid-cols-1 gap-4">
                <a href="{{ route('ops.passport.index') }}" class="flex items-center justify-between p-5 bg-slate-50/50 border border-slate-100/50 rounded-2xl hover:border-emerald-200 hover:bg-white hover:shadow-sm transition-all group/item">
                    <div>
                        <p class="text-[14px] font-extrabold text-[#334155] group-hover/item:text-emerald-600 transition-colors">View Passport Registry</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.1em] mt-1">Secure archival of traveler ID</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-300 group-hover/item:text-emerald-600 group-hover/item:border-emerald-200 transition-all shadow-sm group-hover/item:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </a>
            </div>
        </div>

        <!-- Financial Logistics -->
        <div class="premium-card p-8 group relative overflow-hidden">
            <h2 class="text-[16px] font-extrabold text-[#334155] tracking-[0.15em] uppercase mb-8 pb-4 border-b border-slate-100/50 flex items-center gap-3">
                <span class="w-1.5 h-6 bg-amber-500 rounded-full"></span>
                Invoices & Tickets
            </h2>
            <div class="grid grid-cols-1 gap-4">
                <a href="{{ route('ops.tickets.index') }}" class="flex items-center justify-between p-5 bg-slate-50/50 border border-slate-100/50 rounded-2xl hover:border-amber-200 hover:bg-white hover:shadow-sm transition-all group/item">
                    <div>
                        <p class="text-[14px] font-extrabold text-[#334155] group-hover/item:text-amber-600 transition-colors">Manage Flight Tickets</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.1em] mt-1">Archive and review PNRs/e-tickets</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-300 group-hover/item:text-amber-600 group-hover/item:border-amber-200 transition-all shadow-sm group-hover/item:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </a>
                <a href="{{ route('ops.invoice.index') }}" class="flex items-center justify-between p-5 bg-slate-50/50 border border-slate-100/50 rounded-2xl hover:border-amber-200 hover:bg-white hover:shadow-sm transition-all group/item">
                    <div>
                        <p class="text-[14px] font-extrabold text-[#334155] group-hover/item:text-amber-600 transition-colors">View Invoices</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.1em] mt-1">Trace existing invoices & settlements</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-300 group-hover/item:text-amber-600 group-hover/item:border-amber-200 transition-all shadow-sm group-hover/item:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
