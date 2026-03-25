@extends('layouts.app')
@section('title', 'Manage Users')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-[#334155]">User <span class="text-red-600 italic">Directory</span></h1>
            <p class="text-[12px] font-bold text-slate-400 mt-2 tracking-[0.15em] uppercase">Manage system personnel and institutional access protocols.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-8 py-3.5 rounded-2xl font-extrabold shadow-lg shadow-red-600/20 transition-all">+ Add New Personnel</a>
    </div>

    <!-- Personnel Filter -->
    <div class="premium-card p-6 border-l-[6px] border-l-red-400 mb-8">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1 relative group">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email designation or institutional role..." 
                       class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-[#334155] focus:outline-none focus:border-red-400 focus:ring-4 focus:ring-red-100 transition-all pl-12">
                <span class="absolute left-4 top-4 text-slate-400 group-focus-within:text-red-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-8 py-4 rounded-2xl text-[12px] font-extrabold transition-all uppercase tracking-[0.1em]">Execute Search</button>
                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="bg-white border border-slate-300 text-slate-500 hover:text-red-600 hover:bg-red-50 hover:border-red-200 px-6 py-4 rounded-2xl text-[12px] font-extrabold transition-all uppercase tracking-[0.1em] flex items-center justify-center">Clear</a>
                @endif
            </div>
        </form>
    </div>

    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left bg-white">
                <thead class="bg-slate-50/50 border-b border-slate-100/50">
                    <tr class="text-[10px] uppercase tracking-[0.15em] text-slate-400 font-bold">
                        <th class="px-8 py-5">Personnel Profile</th>
                        <th class="px-8 py-5">Institutional Role</th>
                        <th class="px-8 py-5">Protocol Status</th>
                        <th class="px-8 py-5 text-right">Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/50">
                    @foreach($users as $user)
                    <tr class="hover:bg-slate-50/80 transition-all duration-200 group">
                        <td class="px-8 py-6">
                            <div class="font-extrabold text-[15px] text-[#334155] group-hover:text-red-600 transition-colors">{{ $user->name }}</div>
                            <div class="flex items-center space-x-2 mt-1.5 uppercase font-black tracking-widest text-[9px]">
                                <span class="text-red-600">ID: {{ $user->username }}</span>
                                <span class="text-slate-300">|</span>
                                <span class="text-slate-400">{{ $user->email }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-[0.2em] bg-red-50 text-red-600 border border-red-100/50">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center space-x-3">
                                <span class="relative flex h-2 w-2">
                                  @if($user->is_active)
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                  @else
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-slate-300"></span>
                                  @endif
                                </span>
                                <span class="text-[11px] font-black uppercase tracking-[0.15em] {{ $user->is_active ? 'text-[#334155]' : 'text-slate-400' }}">{{ $user->is_active ? 'Online' : 'Revoked' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end items-center space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.users.show', $user) }}" class="p-3 bg-white border border-slate-200 hover:bg-red-600 hover:border-red-600 hover:text-white rounded-xl transition-all shadow-sm text-slate-500" title="Profile Analytics">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-3 bg-white border border-slate-200 hover:bg-red-600 hover:border-red-600 hover:text-white rounded-xl transition-all shadow-sm text-slate-500" title="Edit Profile">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.users.reset_password', $user) }}" method="POST" class="inline" onsubmit="return confirm('Generate a new random password for this user?');">
                                    @csrf
                                    <button class="p-3 bg-white border border-slate-200 hover:bg-amber-500 hover:border-amber-500 hover:text-white rounded-xl transition-all shadow-sm text-slate-500" title="Reset Password">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.force_logout', $user) }}" method="POST" class="inline" onsubmit="return confirm('Forcefully terminate all active sessions for this user?');">
                                    @csrf
                                    <button class="p-3 bg-white border border-slate-200 hover:bg-slate-800 hover:border-slate-800 hover:text-white rounded-xl transition-all shadow-sm text-slate-500" title="Force Logout">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="inline" onsubmit="return confirm('Change access rights for this user?');">
                                    @csrf
                                    <button class="p-3 bg-white border border-slate-200 hover:bg-red-700 hover:border-red-700 hover:text-white rounded-xl transition-all shadow-sm text-slate-500" title="{{ $user->is_active ? 'Suspend Access' : 'Activate Access' }}">
                                        @if($user->is_active)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
