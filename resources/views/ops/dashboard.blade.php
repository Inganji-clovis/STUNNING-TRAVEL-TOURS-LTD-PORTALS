@extends('layouts.app')
@section('title', 'Operations Dashboard')

@section('content')
<div class="space-y-8 animate-in fade-in duration-1000">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-slate-900">Operations <span class="text-red-700">Dashboard</span></h1>
            <p class="text-slate-400 mt-1 text-xs font-bold tracking-widest uppercase opacity-70">Client Relationship & Document Logistics</p>
        </div>
        
        <div class="flex gap-4 w-full lg:w-auto">
            <a href="{{ route('ops.clients.create') }}" class="bg-red-700 hover:bg-red-800 text-white px-8 py-3.5 rounded-2xl font-bold shadow-lg shadow-red-900/20 transition-all text-center flex-1 lg:flex-none">
                + New Client
            </a>
        </div>
    </div>

    <!-- Stats Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($stats as $label => $value)
            <div class="bg-white p-6 rounded-2xl border-l-[6px] border-red-500 border-y border-r border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 group">
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">{{ str_replace('_', ' ', str_replace('total_', '', $label)) }}</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-[28px] font-extrabold text-slate-900 tabular-nums">{{ $value }}</h3>
                    <div class="h-8 w-8 bg-slate-50/50 rounded-lg flex items-center justify-center text-slate-300 group-hover:bg-red-700/10 group-hover:text-red-700 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if(isset($dispatches) && $dispatches->count() > 0)
    <!-- MD Dispatches Section -->
    <div class="bg-white rounded-2xl border-l-[6px] border-indigo-500 border-y border-r border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <div>
                <h2 class="text-[20px] font-extrabold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    Executive Dispatches
                </h2>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">Incoming files directly from the Managing Director</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-indigo-50 border border-indigo-100 text-indigo-700">{{ $dispatches->count() }} Pending</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr class="text-[10px] uppercase tracking-widest text-slate-500 font-black">
                        <th class="px-6 py-4">Document</th>
                        <th class="px-6 py-4">Notes / Instructions</th>
                        <th class="px-6 py-4">Sent By</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($dispatches as $dispatch)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-slate-800 block">{{ $dispatch->file_name ?? $dispatch->type }}</span>
                            <span class="text-[10px] font-black tracking-widest uppercase text-slate-400 mt-1 inline-block">{{ $dispatch->type }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-slate-500 italic">"{{ $dispatch->description ?? 'No specific instructions.' }}"</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold bg-white border border-slate-200 px-2 py-1 rounded shadow-sm text-slate-500">{{ $dispatch->sender->name ?? 'MD' }}</span>
                            <span class="block text-[10px] text-slate-400 mt-1">{{ $dispatch->created_at->diffForHumans() }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ Storage::url($dispatch->file_path) }}" target="_blank" class="text-xs font-black uppercase text-indigo-600 hover:text-indigo-800 bg-indigo-50 border border-indigo-100 px-3 py-1.5 rounded transition-all">Download / View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Client Management -->
        <div class="bg-white p-8 rounded-2xl border-l-[6px] border-blue-500 border-y border-r border-slate-200 shadow-sm relative overflow-hidden">
            <h2 class="text-[20px] font-bold text-slate-800 mb-6 flex items-center gap-2">Client <span class="text-blue-600">Management</span></h2>
            <div class="grid grid-cols-1 gap-3">
                <a href="{{ route('ops.clients.create') }}" class="flex items-center justify-between p-4 bg-slate-50 border border-slate-100 rounded-xl hover:border-blue-500 group transition-all">
                    <div>
                        <p class="text-sm font-bold text-slate-900 group-hover:text-blue-700 transition-colors">Add New Client</p>
                        <p class="text-xs text-slate-500 mt-0.5">Individual or corporate profile enrollment</p>
                    </div>
                    <svg class="w-5 h-5 text-slate-300 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
                <a href="{{ route('ops.clients.index') }}" class="flex items-center justify-between p-4 bg-slate-50 border border-slate-100 rounded-xl hover:border-blue-500 transition-all group">
                    <div>
                        <p class="text-sm font-bold text-slate-900 group-hover:text-blue-700 transition-colors">View Clients</p>
                        <p class="text-xs text-slate-500 mt-0.5">Database lookup and document archival</p>
                    </div>
                    <svg class="w-5 h-5 text-slate-300 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>

        <!-- Organization Management -->
        <div class="bg-white p-8 rounded-2xl border-l-[6px] border-indigo-500 border-y border-r border-slate-200 shadow-sm relative overflow-hidden">
            <h2 class="text-[20px] font-bold text-slate-800 mb-6 flex items-center gap-2">Organization <span class="text-indigo-600">Management</span></h2>
            <div class="grid grid-cols-1 gap-3">
                <a href="{{ route('ops.organizations.index') }}" class="flex items-center justify-between p-4 bg-slate-50 border border-slate-100 rounded-xl hover:border-indigo-500 group transition-all">
                    <div>
                        <p class="text-sm font-bold text-slate-900 group-hover:text-indigo-700 transition-colors">View Organizations</p>
                        <p class="text-xs text-slate-500 mt-0.5">Manage corporate partners & groups</p>
                    </div>
                    <svg class="w-5 h-5 text-slate-300 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>

        <!-- Passport Management -->
        <div class="bg-white p-8 rounded-2xl border-l-[6px] border-emerald-500 border-y border-r border-slate-200 shadow-sm relative overflow-hidden">
            <h2 class="text-[20px] font-bold text-slate-800 mb-6 flex items-center gap-2">Passport <span class="text-emerald-600">Records</span></h2>
            <div class="grid grid-cols-1 gap-3">
                <a href="{{ route('ops.passport.index') }}" class="flex items-center justify-between p-4 bg-slate-50 border border-slate-100 rounded-xl hover:border-emerald-500 group transition-all">
                    <div>
                        <p class="text-sm font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">View Passport Registry</p>
                        <p class="text-xs text-slate-500 mt-0.5">Secure archival of traveler ID</p>
                    </div>
                    <svg class="w-5 h-5 text-slate-300 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>

        <!-- Financial Logistics -->
        <div class="bg-white p-8 rounded-2xl border-l-[6px] border-amber-500 border-y border-r border-slate-200 shadow-sm relative overflow-hidden">
            <h2 class="text-[20px] font-bold text-slate-800 mb-6 flex items-center gap-2">Invoices & <span class="text-amber-500">Tickets</span></h2>
            <div class="grid grid-cols-1 gap-3">
                <a href="{{ route('ops.tickets.index') }}" class="flex items-center justify-between p-4 bg-slate-50 border border-slate-100 rounded-xl hover:border-amber-500 group transition-all">
                    <div>
                        <p class="text-sm font-bold text-slate-900 group-hover:text-amber-600 transition-colors">Manage Flight Tickets</p>
                        <p class="text-xs text-slate-500 mt-0.5">Archive and review client PNRs and e-tickets</p>
                    </div>
                    <svg class="w-5 h-5 text-slate-300 group-hover:text-amber-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
                <a href="{{ route('ops.invoice.index') }}" class="flex items-center justify-between p-4 bg-slate-50 border border-slate-100 rounded-xl hover:border-amber-500 transition-all group">
                    <div>
                        <p class="text-sm font-bold text-slate-900 group-hover:text-amber-600">View Invoices</p>
                        <p class="text-xs text-slate-500 mt-0.5">Trace existing invoices and settlements</p>
                    </div>
                    <svg class="w-5 h-5 text-slate-300 group-hover:text-amber-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
