@extends('layouts.app')
@section('title', 'Client Registry')

@section('content')
<div class="space-y-12 animate-in fade-in duration-1000">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-slate-900">Clients <span class="red-700 italic">Registry</span></h1>
            <p class="text-slate-400 mt-2 font-medium tracking-wide">Management of individual and corporate traveler profiles.</p>
        </div>
        
        <div class="flex flex-wrap gap-4 w-full lg:w-auto">
            <a href="{{ route('ops.organizations.index') }}" class="flex-1 lg:flex-none border border-slate-200 bg-white px-8 py-3.5 rounded-xl font-bold text-slate-600 hover:bg-slate-50 transition-all text-center">
                Organizations
            </a>
            <a href="{{ route('ops.clients.create') }}" class="flex-1 lg:flex-none bg-red-700 hover:bg-red-800 text-white px-8 py-3.5 rounded-xl font-bold shadow-lg shadow-red-900/20 transition-all text-center">
                + New Client
            </a>
        </div>
    </div>

    <!-- Registry Search -->
    <div class="premium-card p-6 rounded-2xl bg-slate-50/50">
        <form action="{{ route('ops.clients.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative group">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by identity, contact or organization..." class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all pl-12 shadow-sm">
                <span class="absolute left-4 top-3.5 text-slate-300 group-focus-within:text-red-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-red-700 hover:bg-red-800 text-white px-8 py-3 rounded-xl font-bold transition-all">Search</button>
                @if(request('search'))
                    <a href="{{ route('ops.clients.index') }}" class="bg-white border border-slate-200 text-slate-400 p-3 rounded-xl hover:text-sunset-orange hover:border-sunset-orange transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Registry Table -->
    <div class="premium-card rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 text-sm uppercase tracking-[0.15em] text-slate-400 font-black">
                        <th class="px-8 py-6 border-b border-slate-100">Client Name</th>
                        <th class="px-8 py-6 border-b border-slate-100">Membership</th>
                        <th class="px-8 py-6 border-b border-slate-100">Airline</th>
                        <th class="px-8 py-6 border-b border-slate-100 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($clients as $client)
                    <tr class="hover:bg-slate-50/50 transition-all duration-200 group">
                        <td class="px-8 py-6">
                            <div class="font-bold text-slate-900 group-hover:text-red-700 transition-colors text-base">{{ $client->name }}</div>
                            <div class="text-sm font-black text-slate-400 tracking-[0.2em] mt-1">{{ $client->organization->name ?? 'PRIVATE AGENT' }} | {{ $client->email ?? 'IDENTITY MASKED' }}</div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-700 font-mono">{{ $client->membership_number ?? 'NON-MEMBER' }}</span>
                                <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded text-sm font-black uppercase tracking-widest w-fit {{ $client->membership_type === 'premium' ? 'bg-red-700/10 text-red-700 border border-red-700/20' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                                    {{ $client->membership_type }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ $client->airline_name ?? 'NOT REGISTERED' }}</span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('ops.clients.show', $client) }}" class="p-2.5 bg-slate-50 hover:bg-red-700 hover:text-white rounded-xl transition-all shadow-sm" title="View Dossier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="#" class="p-2.5 bg-slate-50 hover:bg-sunset-orange hover:text-white rounded-xl transition-all shadow-sm" title="Modify Record">
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
