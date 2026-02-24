@extends('layouts.app')
@section('title', 'Activity Logs')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-red-950">System <span class="red-700 italic">Logs</span></h1>
            <p class="text-slate-400 mt-2 font-medium tracking-wide">Monitor personnel activities and system operations.</p>
        </div>
    </div>

    <!-- Analytics Dashboard Search -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
        <form action="{{ route('admin.logs.index') }}" method="GET" class="w-full flex space-x-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by personnel, action type, or event description..." 
                       class="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-xl text-sm font-bold text-red-950 focus:ring-2 focus:ring-red-100 transition-all placeholder-slate-400" />
            </div>
            <button type="submit" class="bg-red-950 hover:bg-red-900 text-white px-8 py-3 rounded-xl font-bold uppercase tracking-widest text-xs transition-colors shadow-sm">
                Analyze
            </button>
            @if(request('search'))
                <a href="{{ route('admin.logs.index') }}" class="px-6 py-3 rounded-xl font-bold uppercase tracking-widest text-xs transition-colors border border-slate-200 text-slate-500 hover:bg-slate-50 flex items-center justify-center">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <div class="premium-card rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[0.65rem] uppercase tracking-[0.15em] text-slate-400 font-extrabold">
                        <th class="px-8 py-5">Timestamp</th>
                        <th class="px-8 py-5">Personnel</th>
                        <th class="px-8 py-5">Action Identifier</th>
                        <th class="px-8 py-5">Event Description</th>
                        <th class="px-8 py-5 text-right">Network Origin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-5 whitespace-nowrap">
                            <span class="text-xs font-bold text-slate-500">{{ $log->created_at->format('M d, Y') }}</span>
                            <span class="text-[0.65rem] font-extrabold text-slate-400 ml-1">{{ $log->created_at->format('H:i:s') }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="font-bold text-red-950">{{ $log->user ? $log->user->name : 'System Generated' }}</div>
                            @if($log->user)
                                <div class="text-[0.65rem] font-extrabold text-slate-400 uppercase tracking-widest mt-0.5">{{ $log->user->role }}</div>
                            @endif
                        </td>
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[0.65rem] font-extrabold uppercase tracking-widest bg-slate-100 text-slate-600 border border-slate-200">
                                {{ str_replace('_', ' ', $log->action) }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-sm font-bold text-slate-600">{{ $log->description }}</span>
                        </td>
                        <td class="px-8 py-5 text-right whitespace-nowrap">
                            <span class="font-mono text-xs font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded-md">{{ $log->ip_address ?? 'N/A' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-500">No activity records found</h3>
                            <p class="text-xs font-medium text-slate-400 mt-1">There are no operational events matching the current parameters.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($logs->hasPages())
        <div class="px-8 py-5 border-t border-slate-100 bg-white">
            {{ $logs->appends(request()->query())->links('pagination::tailwind') }}
        </div>
        @endif
    </div>
</div>
@endsection
