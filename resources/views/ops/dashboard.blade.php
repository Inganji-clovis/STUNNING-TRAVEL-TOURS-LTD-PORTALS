@extends('layouts.app')
@section('title', 'Operations Dashboard')

@section('content')
<div class="space-y-8 animate-in fade-in duration-1000">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-slate-900 italic">Operations <span class="red-700">Dashboard</span></h1>
            <p class="text-slate-400 mt-1 text-xs font-bold tracking-[0.1em] uppercase opacity-70">Client Relationship & Document Logistics</p>
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
            <div class="premium-card p-6 rounded-2xl relative overflow-hidden group">
                <div class="absolute -right-2 -top-2 text-slate-50 text-5xl font-black group-hover:text-red-700/5 transition-colors">{{ strtoupper(substr($label, 0, 3)) }}</div>
                <p class="text-sm font-black text-slate-400 opacity-60 uppercase tracking-[0.2em] mb-1">{{ str_replace('_', ' ', str_replace('total_', '', $label)) }}</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-2xl font-black text-slate-900 tabular-nums">{{ $value }}</h3>
                    <div class="h-8 w-8 bg-slate-50/50 rounded-lg flex items-center justify-center text-slate-300 group-hover:bg-red-700/10 group-hover:text-red-700 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Client Management -->
        <div class="premium-card p-8 rounded-[2rem] relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-red-700/5 rounded-full -mr-16 -mt-16"></div>
            <h2 class="text-xl font-black text-slate-900 mb-6 tracking-tighter border-b border-white/50 pb-3 italic">Client <span class="red-700">Management</span></h2>
            <div class="grid grid-cols-1 gap-3">
                <a href="{{ route('ops.clients.create') }}" class="flex items-center justify-between p-4 bg-slate-50/50 border border-slate-200/50 rounded-xl hover:bg-red-700 group transition-all">
                    <div>
                        <p class="text-sm font-bold text-slate-900 group-hover:text-white transition-colors">Add New Client</p>
                        <p class="text-sm text-slate-400 group-hover:text-white/70 transition-colors">Individual or corporate profile enrollment</p>
                    </div>
                    <span class="text-slate-300 group-hover:text-white transition-colors">Ã¢â€ â€™</span>
                </a>
                <a href="{{ route('ops.clients.index') }}" class="flex items-center justify-between p-4 bg-white/50 border border-slate-200/50 rounded-xl hover:border-red-700 transition-all group">
                    <div>
                        <p class="text-sm font-bold text-slate-900">View Clients</p>
                        <p class="text-sm text-slate-400">Database lookup and document archival</p>
                    </div>
                    <span class="text-slate-200 group-hover:text-red-700 transition-colors">Ã¢â€ â€™</span>
                </a>
            </div>
        </div>

        <!-- Organization Management -->
        <div class="premium-card p-8 rounded-[2rem] relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-slate-100/50 rounded-full -mr-16 -mt-16"></div>
            <h2 class="text-xl font-black text-slate-900 mb-6 tracking-tighter border-b border-white/50 pb-3 italic">Organization <span class="text-slate-400">Management</span></h2>
            <div class="grid grid-cols-1 gap-3">
                <a href="{{ route('ops.organizations.index') }}" class="flex items-center justify-between p-4 bg-slate-50/50 border border-slate-200/50 rounded-xl hover:bg-slate-900 group transition-all">
                    <div>
                        <p class="text-sm font-bold text-slate-900 group-hover:text-white transition-colors">View Organizations</p>
                        <p class="text-sm text-slate-400 group-hover:text-white/70 transition-colors">Manage corporate partners and institutional groups</p>
                    </div>
                    <span class="text-slate-300 group-hover:text-white transition-colors">Ã¢â€ â€™</span>
                </a>
            </div>
        </div>

        <!-- Passport Management -->
        <div class="premium-card p-8 rounded-[2rem] relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-red-700/10 rounded-full -mr-16 -mt-16"></div>
            <h2 class="text-xl font-black text-slate-900 mb-6 tracking-tighter border-b border-white/50 pb-3 italic">Passport <span class="red-700">Records</span></h2>
            <div class="grid grid-cols-1 gap-3">
                <a href="{{ route('ops.passport.index') }}" class="flex items-center justify-between p-4 bg-slate-50/50 border border-slate-200/50 rounded-xl hover:bg-red-700 group transition-all">
                    <div>
                        <p class="text-sm font-bold text-slate-900 group-hover:text-white transition-colors">View Passport Registry</p>
                        <p class="text-sm text-slate-400 group-hover:text-white/70 transition-colors">Secure archival of traveler primary identification</p>
                    </div>
                    <span class="text-slate-300 group-hover:text-white transition-colors">Ã¢â€ â€™</span>
                </a>
            </div>
        </div>

        <!-- Financial Logistics -->
        <div class="premium-card p-8 rounded-[2rem] relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-sunset-orange/5 rounded-full -mr-16 -mt-16"></div>
            <h2 class="text-xl font-black text-slate-900 mb-6 tracking-tighter border-b border-white/50 pb-3 italic">Invoices & <span class="sunset-orange">Tickets</span></h2>
            <div class="grid grid-cols-1 gap-3">
                <a href="{{ route('ops.tickets.index') }}" class="flex items-center justify-between p-4 bg-slate-50/50 border border-slate-200/50 rounded-xl hover:bg-sunset-orange group transition-all">
                    <div>
                        <p class="text-sm font-bold text-slate-900 group-hover:text-white transition-colors">Manage Flight Tickets</p>
                        <p class="text-sm text-slate-400 group-hover:text-white/70 transition-colors">Archive and review client PNRs and e-tickets</p>
                    </div>
                    <span class="text-slate-300 group-hover:text-white transition-colors">→</span>
                </a>
                <a href="{{ route('ops.invoice.index') }}" class="flex items-center justify-between p-4 bg-white/50 border border-slate-200/50 rounded-xl hover:border-sunset-orange transition-all group">
                    <div>
                        <p class="text-sm font-bold text-slate-900">View Invoices</p>
                        <p class="text-sm text-slate-400">Trace existing invoices and settlements</p>
                    </div>
                    <span class="text-slate-200 group-hover:sunset-orange transition-colors">→</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
