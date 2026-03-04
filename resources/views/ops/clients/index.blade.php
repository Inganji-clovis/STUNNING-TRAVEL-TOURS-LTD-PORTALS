@extends('layouts.app')
@section('title', 'Client Registry')

@section('content')
<div class="space-y-12 animate-in fade-in duration-1000">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-[#334155]">Clients <span class="italic text-red-600">Registry</span></h1>
            <p class="text-[12px] font-bold text-slate-400 mt-2 tracking-[0.15em] uppercase">Management of Individual and Corporate Traveler Profiles</p>
        </div>
        
        <div class="flex flex-wrap gap-4 w-full lg:w-auto">
            <a href="{{ route('ops.organizations.index') }}" class="flex-1 lg:flex-none border border-slate-200 bg-white px-8 py-3.5 rounded-2xl font-extrabold text-[#334155] hover:border-slate-300 hover:shadow-sm transition-all text-center">
                Organizations
            </a>
            <a href="{{ route('ops.clients.create') }}" class="flex-1 lg:flex-none bg-red-600 hover:bg-red-700 text-white px-8 py-3.5 rounded-2xl font-extrabold shadow-lg shadow-red-600/20 transition-all text-center">
                + New Client
            </a>
        </div>
    </div>

    <!-- Registry Search -->
    <div class="premium-card p-6 border-l-[6px] border-l-slate-400 mb-8">
        <form action="{{ route('ops.clients.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative group">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by identity, contact or organization..." class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none focus:border-red-400 focus:ring-4 focus:ring-red-100 transition-all pl-12 text-[#334155]">
                <span class="absolute left-4 top-4 text-slate-400 group-focus-within:text-red-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-8 py-4 rounded-2xl font-extrabold transition-all text-[12px] uppercase tracking-[0.1em]">Execute Search</button>
                @if(request('search'))
                    <a href="{{ route('ops.clients.index') }}" class="bg-white border border-slate-300 text-slate-600 p-4 rounded-2xl hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Registry Table -->
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left bg-white">
                <thead class="bg-slate-50/50 border-b border-slate-100/50">
                    <tr class="text-[10px] uppercase tracking-[0.15em] text-slate-400 font-bold">
                        <th class="px-8 py-5">Client Name</th>
                        <th class="px-8 py-5">Membership</th>
                        <th class="px-8 py-5">Airline</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/50">
                    @foreach($clients as $client)
                    <tr class="hover:bg-slate-50/80 transition-all duration-200 group">
                        <td class="px-8 py-6">
                            <div class="font-extrabold text-[15px] text-[#334155] group-hover:text-red-600 transition-colors">{{ $client->name }}</div>
                            <div class="text-[10px] font-black text-slate-400 tracking-[0.1em] mt-1.5 uppercase">{{ $client->organization->name ?? 'PRIVATE AGENT' }} <span class="text-slate-300 px-1">|</span> {{ $client->email ?? 'IDENTITY MASKED' }}</div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col gap-1 items-start">
                                <span class="text-[14px] font-extrabold text-[#334155] tracking-wider">{{ $client->membership_number ?? 'NON-MEMBER' }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-[0.2em] {{ $client->membership_type === 'premium' ? 'bg-amber-50 text-amber-600 border border-amber-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                    {{ $client->membership_type }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-lg">{{ $client->airline_name ?? 'NOT REGISTERED' }}</span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('ops.clients.show', $client) }}" class="p-3 bg-white border border-slate-200 hover:bg-red-600 hover:border-red-600 hover:text-white rounded-xl transition-all shadow-sm text-slate-500" title="View Dossier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('ops.clients.edit', $client) }}" class="p-3 bg-white border border-slate-200 hover:bg-red-600 hover:border-red-600 hover:text-white rounded-xl transition-all shadow-sm text-slate-500" title="Modify Record">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection
