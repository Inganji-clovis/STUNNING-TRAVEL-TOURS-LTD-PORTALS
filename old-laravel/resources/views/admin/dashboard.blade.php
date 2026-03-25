@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8 animate-in fade-in duration-1000">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-[#334155]">Admin <span class="italic text-red-600">Dashboard</span></h1>
            <p class="text-[12px] font-bold text-slate-400 mt-2 tracking-[0.15em] uppercase">System Control & Institutional Metrics.</p>
        </div>
        
        <div class="flex gap-4 w-full lg:w-auto">
            <a href="{{ route('admin.users.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-8 py-3.5 rounded-2xl font-extrabold shadow-lg shadow-red-600/20 transition-all text-center flex-1 lg:flex-none">
                + Add Personnel
            </a>
        </div>
    </div>

    <!-- Stats Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($stats as $label => $value)
            <div class="premium-card p-8 group relative overflow-hidden">
                <div class="absolute -right-2 -top-2 text-slate-50/50 text-[64px] font-extrabold group-hover:text-red-50 transition-colors pointer-events-none">{{ strtoupper(substr($label, 0, 3)) }}</div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em] mb-4 relative z-10">{{ str_replace('_', ' ', $label) }}</p>
                <div class="flex items-end justify-between relative z-10 border-t border-slate-100/50 pt-4 mt-2">
                    <h3 class="text-[32px] font-extrabold text-[#334155] tabular-nums group-hover:text-red-600 transition-colors">{{ $value }}</h3>
                    <div class="h-10 w-10 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-red-50 group-hover:text-red-600 transition-all border border-slate-100 shadow-sm group-hover:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Operations & Personnel -->
        <div class="lg:col-span-1 space-y-8">
            <div class="premium-card p-10 relative overflow-hidden h-full">
                <div class="absolute right-0 top-0 w-48 h-48 bg-red-50 rounded-full -mr-24 -mt-24 pointer-events-none transition-transform group-hover:scale-110"></div>
                <h2 class="text-[18px] font-extrabold text-[#334155] mb-8 border-b border-slate-100/50 pb-4 tracking-[0.15em] uppercase flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-red-600 rounded-full"></span>
                    Personnel Control
                </h2>
                <div class="grid grid-cols-1 gap-4">
                    <a href="{{ route('admin.users.create') }}" class="flex items-center justify-between p-5 bg-slate-50/50 border border-slate-100/50 rounded-2xl hover:bg-white hover:border-red-200 hover:shadow-sm group transition-all">
                        <div>
                            <p class="text-[14px] font-extrabold text-[#334155] group-hover:text-red-600 transition-colors">Add New User</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.1em] mt-1">Register new system admin</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-300 group-hover:text-red-600 group-hover:border-red-200 transition-all shadow-sm group-hover:scale-110">
                            <span class="font-black text-lg">â†’</span>
                        </div>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center justify-between p-5 bg-slate-50/50 border border-slate-100/50 rounded-2xl hover:bg-white hover:border-red-200 hover:shadow-sm group transition-all">
                        <div>
                            <p class="text-[14px] font-extrabold text-[#334155] group-hover:text-red-600 transition-colors">Manage Personnel</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.1em] mt-1">View registry and access protocols</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-300 group-hover:text-red-600 group-hover:border-red-200 transition-all shadow-sm group-hover:scale-110">
                            <span class="font-black text-lg">â†’</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Column: System Logs -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Security Notices -->
            <div class="premium-card p-10 relative overflow-hidden">
                <div class="flex items-center justify-between border-b border-slate-100/50 pb-4 mb-8">
                    <h2 class="text-[18px] font-extrabold text-[#334155] tracking-[0.15em] uppercase flex items-center gap-3">
                        <span class="w-1.5 h-6 bg-red-600 rounded-full animate-pulse"></span>
                        Security Notices
                    </h2>
                    <span class="flex h-3 w-3 relative">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                    </span>
                </div>
                
                @if($securityNotices->isEmpty())
                <div class="text-center py-8 text-slate-400 font-bold text-[10px] uppercase tracking-[0.2em]">
                    No active security alerts
                </div>
                @else
                <div class="space-y-4">
                    @foreach($securityNotices as $notice)
                    <div class="flex items-start justify-between p-5 bg-red-50/50 rounded-2xl border border-red-100/50 group hover:bg-white hover:border-red-200 hover:shadow-sm transition-all">
                        <div>
                            <p class="text-[13px] font-extrabold text-red-900 group-hover:text-red-600">{{ $notice->description }}</p>
                            <p class="text-[10px] text-red-500/80 font-bold mt-1.5 uppercase tracking-[0.1em]">Affected User: <span class="bg-red-100/50 px-2 py-0.5 rounded text-red-700">{{ $notice->user->name ?? 'System' }}</span> | IP: {{ $notice->ip_address }}</p>
                        </div>
                        <span class="text-[10px] font-black tracking-[0.1em] uppercase text-slate-400 bg-white border border-slate-100 px-3 py-1.5 rounded-lg shadow-sm whitespace-nowrap ml-4">{{ $notice->created_at->diffForHumans() }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Recent System Activities -->
            <div class="premium-card p-10 relative overflow-hidden">
                <h2 class="text-[18px] font-extrabold text-[#334155] tracking-[0.15em] uppercase mb-8 border-b border-slate-100/50 pb-4 flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-slate-300 rounded-full"></span>
                    System Activities
                </h2>
                
                @if($recentActivities->isEmpty())
                <div class="text-center py-8 text-slate-400 font-bold text-[10px] uppercase tracking-[0.2em]">
                    No recent activity logs found
                </div>
                @else
                <div class="space-y-4 cursor-default">
                    @foreach($recentActivities as $activity)
                    <div class="flex items-center justify-between p-5 bg-slate-50/50 rounded-2xl border border-slate-100/50 hover:bg-white hover:border-slate-200 hover:shadow-sm transition-all group">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 text-slate-400 flex items-center justify-center font-black text-sm shadow-sm group-hover:text-red-600 group-hover:border-red-100 transition-all">
                                {{ substr($activity->user->name ?? 'S', 0, 1) }}
                            </div>
                            <div>
                                <p class="text-[13px] font-extrabold text-[#334155]">{{ $activity->description }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.1em] mt-1.5">Action by: <span class="bg-white border border-slate-100 px-2 py-0.5 rounded text-slate-500 shadow-sm">{{ $activity->user->name ?? 'System' }}</span></p>
                            </div>
                        </div>
                        <span class="text-[10px] font-black tracking-[0.2em] uppercase text-slate-400 bg-white border border-slate-100 px-3 py-1.5 rounded-lg shadow-sm ml-4">{{ $activity->created_at->format('H:i') }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
