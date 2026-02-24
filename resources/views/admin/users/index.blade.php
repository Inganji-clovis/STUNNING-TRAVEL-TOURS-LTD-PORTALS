@extends('layouts.app')
@section('title', 'Manage Users')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-red-950">User <span class="red-700 ">Directory</span></h1>
            <p class="text-slate-400 mt-2 font-medium tracking-wide">Manage system personnel and institutional access protocols.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="bg-red-700 hover:bg-red-900 text-white px-8 py-3.5 rounded-xl font-bold shadow-lg shadow-red-900/20 transition-all">+ Add New Personnel</a>
    </div>

    <!-- Personnel Filter -->
    <div class="premium-card p-6 rounded-2xl bg-slate-50/50">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1 relative group">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email designation or institutional role..." 
                       class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3.5 text-sm font-medium focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all pl-12 shadow-sm">
                <span class="absolute left-4 top-4 text-slate-300 group-focus-within:text-red-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
            </div>
            <button type="submit" class="bg-red-950 hover:bg-red-900 text-white px-8 py-2 rounded-xl text-xs font-bold transition-all shadow-sm">Search</button>
            @if(request('search'))
                <a href="{{ route('admin.users.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-500 px-6 py-2 rounded-xl text-xs font-bold transition-all border border-slate-200">Clear</a>
            @endif
        </form>
    </div>

    <div class="premium-card rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 text-[0.65rem] uppercase tracking-[0.15em] text-slate-400 font-extrabold">
                        <th class="px-8 py-6 border-b border-slate-100">Personnel Profile</th>
                        <th class="px-8 py-6 border-b border-slate-100">Institutional Role</th>
                        <th class="px-8 py-6 border-b border-slate-100">Protocol Status</th>
                        <th class="px-8 py-6 border-b border-slate-100 text-right">Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($users as $user)
                    <tr class="hover:bg-slate-50/50 transition-all duration-200 group">
                        <td class="px-8 py-6">
                            <div class="font-bold text-red-950 group-hover:red-700 transition-colors">{{ $user->name }}</div>
                            <div class="text-[0.65rem] font-extrabold text-slate-400 tracking-widest mt-1">{{ $user->email }}</div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[0.55rem] font-extrabold uppercase tracking-widest bg-red-50 text-red-800 border border-red-200 shadow-sm">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center space-x-2">
                                <span class="relative flex h-2 w-2">
                                  @if($user->is_active)
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-600"></span>
                                  @else
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-slate-300"></span>
                                  @endif
                                </span>
                                <span class="text-[0.65rem] font-extrabold uppercase tracking-widest {{ $user->is_active ? 'text-red-950' : 'text-slate-500' }}">{{ $user->is_active ? 'Online' : 'Revoked' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end items-center space-x-2">
                                <a href="{{ route('admin.users.show', $user) }}" class="p-2.5 bg-slate-50 hover:bg-red-700 hover:text-white rounded-xl transition-all shadow-sm group" title="Profile Analytics">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2.5 bg-slate-50 hover:bg-red-900 hover:text-white rounded-xl transition-all shadow-sm" title="Edit Profile">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.users.reset_password', $user) }}" method="POST" class="inline" onsubmit="return confirm('Generate a new random password for this user?');">
                                    @csrf
                                    <button class="p-2.5 bg-slate-50 hover:bg-red-600 hover:text-white rounded-xl transition-all shadow-sm" title="Reset Password">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.force_logout', $user) }}" method="POST" class="inline" onsubmit="return confirm('Forcefully terminate all active sessions for this user?');">
                                    @csrf
                                    <button class="p-2.5 bg-slate-50 hover:bg-red-800 hover:text-white rounded-xl transition-all shadow-sm" title="Force Logout">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="inline" onsubmit="return confirm('Change access rights for this user?');">
                                    @csrf
                                    <button class="p-2.5 bg-slate-50 hover:bg-slate-800 hover:text-white rounded-xl transition-all shadow-sm" title="{{ $user->is_active ? 'Suspend Access' : 'Activate Access' }}">
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
