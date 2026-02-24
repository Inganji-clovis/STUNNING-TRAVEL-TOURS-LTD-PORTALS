@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8 animate-in fade-in duration-1000">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tighter text-red-950 ">Admin <span class="text-red-700">Dashboard</span></h1>
            <p class="text-slate-400 mt-1 text-xs font-bold tracking-[0.1em] uppercase opacity-70">System control and institutional metrics.</p>
        </div>
        
        <div class="flex gap-4 w-full lg:w-auto">
            <a href="{{ route('admin.users.create') }}" class="bg-red-700 hover:bg-red-900 text-white px-8 py-3.5 rounded-2xl font-bold shadow-lg shadow-red-900/20 transition-all text-center flex-1 lg:flex-none">
                + Add Personnel
            </a>
        </div>
    </div>

    <!-- Stats Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($stats as $label => $value)
            <div class="premium-card p-6 rounded-2xl relative overflow-hidden group border border-white/50 hover:border-red-500/50 hover:shadow-[0_0_20px_rgba(185,28,28,0.15)] bg-white/90 backdrop-blur-md transition-all duration-300 transform hover:-translate-y-1">
                <div class="absolute -right-2 -top-2 text-slate-50 text-5xl font-extrabold group-hover:text-red-50 transition-colors">{{ strtoupper(substr($label, 0, 3)) }}</div>
                <p class="text-[0.6rem] font-extrabold text-slate-400 opacity-60 uppercase tracking-[0.2em] mb-1">{{ str_replace('_', ' ', $label) }}</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-2xl font-extrabold text-red-950 tabular-nums">{{ $value }}</h3>
                    <div class="h-8 w-8 bg-slate-50 rounded-xl flex items-center justify-center text-slate-300 group-hover:bg-red-100 group-hover:text-red-600 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Operations & Personnel -->
        <div class="lg:col-span-1 space-y-8">
            <div class="premium-card p-8 rounded-[2rem] relative overflow-hidden border border-slate-200 bg-white shadow-sm">
                <div class="absolute right-0 top-0 w-32 h-32 bg-red-100 rounded-full -mr-16 -mt-16 opacity-50"></div>
                <h2 class="text-xl font-extrabold text-red-950 mb-6 tracking-tighter border-b border-slate-100 pb-3">Personnel <span class="text-red-700">Control</span></h2>
                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('admin.users.create') }}" class="flex items-center justify-between p-4 bg-slate-50/50 border border-slate-200/50 rounded-xl hover:bg-red-700 hover:border-red-700 group transition-all">
                        <div>
                            <p class="text-sm font-bold text-red-950 group-hover:text-white transition-colors">Add New User</p>
                            <p class="text-[0.65rem] text-slate-400 group-hover:text-white/70 transition-colors">Register new system administrator or staff</p>
                        </div>
                        <span class="text-slate-300 group-hover:text-white transition-colors">→</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center justify-between p-4 bg-white border border-slate-200/50 rounded-xl hover:border-red-400 transition-all group shadow-sm hover:shadow-md">
                        <div>
                            <p class="text-sm font-bold text-red-700 group-hover:text-red-900 transition-colors">Manage Personnel</p>
                            <p class="text-[0.65rem] text-slate-400">View registry and institutional access protocols</p>
                        </div>
                        <span class="text-slate-200 group-hover:text-red-600 transition-colors">→</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Column: System Logs -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Security Notices -->
            <div class="premium-card p-8 rounded-[2rem] border border-red-100 bg-white/95 shadow-sm">
                <div class="flex items-center justify-between border-b border-red-50 pb-3 mb-6">
                    <h2 class="text-xl font-extrabold text-red-950 tracking-tighter">Security <span class="text-red-700">Notices</span></h2>
                    <span class="flex h-3 w-3 relative">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                    </span>
                </div>
                
                @if($securityNotices->isEmpty())
                <div class="text-center py-6 text-slate-400 font-bold text-xs uppercase tracking-widest">
                    No active security alerts
                </div>
                @else
                <div class="space-y-3">
                    @foreach($securityNotices as $notice)
                    <div class="flex items-start justify-between p-3 bg-red-50/50 rounded-xl border border-red-100/50">
                        <div>
                            <p class="text-xs font-bold text-red-900">{{ $notice->description }}</p>
                            <p class="text-[0.6rem] text-red-500 font-medium mt-0.5">Affected User: {{ $notice->user->name ?? 'System' }} | IP: {{ $notice->ip_address }}</p>
                        </div>
                        <span class="text-[0.6rem] font-bold text-slate-400">{{ $notice->created_at->diffForHumans() }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Recent System Activities -->
            <div class="premium-card p-8 rounded-[2rem] border border-slate-200 bg-white/95 shadow-sm">
                <h2 class="text-xl font-extrabold text-red-950 mb-6 tracking-tighter border-b border-slate-100 pb-3">System <span class="text-red-700">Activities</span></h2>
                
                @if($recentActivities->isEmpty())
                <div class="text-center py-6 text-slate-400 font-bold text-xs uppercase tracking-widest">
                    No recent activity logs found
                </div>
                @else
                <div class="space-y-3">
                    @foreach($recentActivities as $activity)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100 hover:border-red-200 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-red-50 text-red-700 flex items-center justify-center font-bold text-xs">
                                {{ substr($activity->user->name ?? 'S', 0, 1) }}
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-700">{{ $activity->description }}</p>
                                <p class="text-[0.6rem] text-slate-400 font-medium">Action by: <span class="text-slate-600">{{ $activity->user->name ?? 'System' }}</span></p>
                            </div>
                        </div>
                        <span class="text-[0.6rem] font-bold text-slate-400">{{ $activity->created_at->format('H:i') }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
