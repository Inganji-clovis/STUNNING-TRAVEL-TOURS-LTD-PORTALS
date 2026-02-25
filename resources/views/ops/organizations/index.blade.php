@extends('layouts.app')
@section('title', 'Organization Registry')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-slate-900">Organizations <span class="red-700 italic">Registry</span></h1>
            <p class="text-slate-400 mt-2 font-medium tracking-wide">Corporate partnerships and institutional travel groups.</p>
        </div>
        <div class="flex flex-wrap gap-4 w-full lg:w-auto">
            <a href="{{ route('ops.clients.index') }}" class="flex-1 lg:flex-none border border-slate-200 bg-white px-8 py-3.5 rounded-xl font-bold text-slate-600 hover:bg-slate-50 transition-all text-center">
                Client Registry
            </a>
            <a href="{{ route('ops.organizations.create') }}" class="flex-1 lg:flex-none bg-red-700 hover:bg-red-800 text-white px-8 py-3.5 rounded-xl font-bold shadow-lg shadow-red-900/20 transition-all text-center">
                + New Organization
            </a>
        </div>
    </div>

    <!-- Dynamic Search -->
    <div class="premium-card p-6 rounded-2xl bg-slate-50/50">
        <form action="{{ route('ops.organizations.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative group">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by organization designation or email..." class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all pl-12 shadow-sm">
                <span class="absolute left-4 top-3.5 text-slate-300 group-focus-within:text-red-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-red-700 hover:bg-red-800 text-white px-8 py-3 rounded-xl font-bold transition-all">Search</button>
                @if(request('search'))
                    <a href="{{ route('ops.organizations.index') }}" class="bg-white border border-slate-200 text-slate-400 p-3 rounded-xl hover:text-sunset-orange hover:border-sunset-orange transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="premium-card rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 text-sm uppercase tracking-[0.15em] text-slate-400 font-black">
                        <th class="px-8 py-6 border-b border-slate-100">Organization Name</th>
                        <th class="px-8 py-6 border-b border-slate-100">Email Address</th>
                        <th class="px-8 py-6 border-b border-slate-100 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @if($organizations->count() > 0)
                        @foreach($organizations as $org)
                        <tr class="hover:bg-slate-50/50 transition-all duration-200 group">
                            <td class="px-8 py-6">
                                <div class="font-bold text-slate-900 group-hover:text-red-700 transition-colors text-base">{{ $org->name }}</div>
                                <div class="text-sm font-black text-slate-400 tracking-[0.2em] mt-1">{{ number_format($org->clients_count ?? 0) }} AFFILIATED CLIENTS</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ $org->email ?? 'NO VERIFIED ADDRESS' }}</div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end">
                                    <a href="#" class="p-2.5 bg-slate-50 hover:bg-red-700 hover:text-white rounded-xl transition-all shadow-sm" title="View Dossier">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="px-8 py-32 text-center text-slate-400 italic">
                                <div class="flex flex-col items-center space-y-4">
                                    <div class="text-5xl grayscale opacity-30">Ã°Å¸ÂÂ¢</div>
                                    <p class="text-lg font-medium">No institutional entities discovered in this directory.</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
