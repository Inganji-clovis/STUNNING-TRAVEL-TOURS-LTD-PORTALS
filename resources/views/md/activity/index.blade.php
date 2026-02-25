@extends('layouts.app')
@section('title', 'Global Activity Ledger')

@section('content')
<div class="space-y-8 animate-in fade-in duration-1000">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-slate-900 italic">Global <span class="red-700">Ledger</span></h1>
            <p class="text-slate-400 mt-1 text-xs font-bold tracking-[0.1em] uppercase opacity-70">Immutable Cross-Departmental Audit Trail</p>
        </div>
        
        <div class="flex items-center space-x-2 bg-slate-50 border border-slate-200 px-4 py-2 rounded-xl shadow-sm">
            <span class="h-2 w-2 rounded-full bg-red-700 animate-pulse"></span>
            <span class="text-sm font-black text-slate-500 uppercase tracking-widest">Syslog Active</span>
        </div>
    </div>

    <!-- Multi-faceted Filter -->
    <div class="bg-white p-6 rounded-[2rem] shadow-premium border border-slate-100">
        <form action="{{ route('md.activity.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-sm font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Keyword</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Action or Description" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm text-slate-700 focus:outline-none focus:border-red-700 focus:ring-1 focus:ring-red-700 transition-all">
            </div>
            <div>
                <label class="block text-sm font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Division</label>
                <select name="portal" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm font-bold text-slate-700 focus:outline-none focus:border-red-700 transition-all cursor-pointer">
                    <option value="">All Divisions</option>
                    <option value="Sales" {{ request('portal') == 'Sales' ? 'selected' : '' }}>Sales</option>
                    <option value="Operations" {{ request('portal') == 'Operations' ? 'selected' : '' }}>Operations</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Start Date</label>
                <input type="date" name="date_start" value="{{ request('date_start', today()->format('Y-m-d')) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm text-slate-700 font-medium focus:outline-none focus:border-red-700 transition-all cursor-pointer">
            </div>
            <div>
                <label class="block text-sm font-black text-slate-400 uppercase tracking-[0.2em] mb-2">End Date</label>
                <input type="date" name="date_end" value="{{ request('date_end', today()->format('Y-m-d')) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm text-slate-700 font-medium focus:outline-none focus:border-red-700 transition-all cursor-pointer">
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="w-full bg-slate-900 hover:bg-red-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs tracking-wider uppercase transition-colors shadow-md">
                    Filter
                </button>
                <a href="{{ route('md.activity.index') }}" class="w-full text-center bg-slate-100 hover:bg-slate-200 text-slate-500 font-bold py-2.5 px-4 rounded-xl text-xs tracking-wider uppercase transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Ledger Table -->
    <div class="premium-card rounded-[2rem] overflow-hidden border border-slate-200 shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 text-sm uppercase tracking-[0.15em] text-slate-500 font-black border-b border-slate-200">
                        <th class="px-8 py-5">Event ID & Module</th>
                        <th class="px-8 py-5">Initiator</th>
                        <th class="px-8 py-5">Activity Executed</th>
                        <th class="px-8 py-5">Context Definition</th>
                        <th class="px-8 py-5 text-right">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php
                        $groupedLogs = $logs->groupBy(function($log) {
                            return \Carbon\Carbon::parse($log->created_at)->format('l, jS \o\f F Y');
                        });
                    @endphp

                    @forelse ($groupedLogs as $dateGroup => $records)
                        <tr class="bg-slate-50/50">
                            <td colspan="5" class="px-8 py-4 border-b border-t border-slate-100">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-full bg-white border border-slate-200 shadow-sm flex items-center justify-center text-red-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700 uppercase tracking-widest">{{ $dateGroup }}</span>
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-sm font-medium bg-red-50 text-red-700 border border-red-100">{{ count($records) }} Events</span>
                                </div>
                            </td>
                        </tr>
                        @foreach($records as $log)
                        <tr class="hover:bg-slate-50/50 transition-colors group cursor-default">
                            <td class="px-8 py-4">
                                <div class="text-sm font-bold text-slate-400 mb-1 rounded bg-slate-100 inline-block px-1.5 py-0.5">EVT-{{ str_pad($log->id, 5, '0', STR_PAD_LEFT) }}</div>
                                <div class="text-xs font-black text-slate-800 uppercase tracking-tighter">{{ $log->properties['module'] ?? 'Unknown Module' }}</div>
                                <div class="text-sm text-slate-400 font-bold mt-1 uppercase">{{ $log->properties['portal'] ?? 'System' }} Division</div>
                            </td>
                            <td class="px-8 py-4">
                                <span class="text-xs font-bold text-slate-600 bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-sm">{{ $log->user->name ?? 'System Process' }}</span>
                            </td>
                            <td class="px-8 py-4">
                                <span class="text-xs font-black {{ str_contains(strtolower($log->action), 'delete') ? 'text-red-600' : 'text-slate-700' }} uppercase tracking-widest">{{ $log->action }}</span>
                            </td>
                            <td class="px-8 py-4">
                                <div class="font-bold text-slate-800 group-hover:text-red-700 transition-colors">{{ $log->properties['reference'] ?? 'General Entity' }}</div>
                                <div class="text-sm text-slate-500 leading-relaxed mt-1">"{{ $log->description }}"</div>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <div class="text-xs font-bold text-slate-600">{{ $log->created_at->format('M d, Y - H:i:s') }}</div>
                                <div class="text-sm text-slate-400 tracking-wider uppercase mt-1">{{ $log->ip_address }}</div>
                            </td>
                        </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center">
                                <div class="flex flex-col items-center space-y-4">
                                    <div class="text-4xl text-slate-200">🔍</div>
                                    <p class="text-slate-400 font-medium italic">No events found matching your audit criteria.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($logs->hasPages())
            <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
