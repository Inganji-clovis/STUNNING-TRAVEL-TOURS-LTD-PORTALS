@extends('layouts.app')
@section('title', 'Edit User: ' . $user->name)

@section('content')
<div class="max-w-2xl animate-in slide-in-from-bottom duration-700">
    <div class="mb-10">
        <a href="{{ route('admin.users.index') }}" class="text-slate-400 hover:text-red-700 transition-colors flex items-center space-x-2 mb-6 font-bold text-xs uppercase tracking-widest">
            <span>← Return to Personnel Directory</span>
        </a>
        <div class="flex items-center space-x-4 mb-2">
            <div class="w-12 h-12 bg-red-700/10 rounded-2xl flex items-center justify-center">
                <span class="text-2xl">✍️</span>
            </div>
            <div>
                <h1 class="text-4xl font-extrabold tracking-tighter text-red-950">Modify <span class="text-red-700 italic">Credentials</span></h1>
                <p class="text-slate-400 font-medium tracking-wide">Update institutional identity and authority protocols for <b>{{ $user->name }}</b></p>
            </div>
        </div>
    </div>

    <div class="premium-card p-10 rounded-3xl relative overflow-hidden">
        <div class="absolute top-0 right-0 p-8 opacity-[0.03] font-extrabold text-8xl pointer-events-none text-red-950 italic">IDENTITY</div>
        
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-8 relative">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2 text-red-950">
                    <label class="text-sm font-extrabold text-slate-400 uppercase tracking-[0.2em] ml-1">Full Designation</label>
                    <input type="text" name="name" value="{{ $user->name }}" required placeholder="Full Name" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-red-950 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm">
                </div>
                <div class="space-y-2 text-red-950">
                    <label class="text-sm font-extrabold text-slate-400 uppercase tracking-[0.2em] ml-1">System Username</label>
                    <input type="text" name="username" value="{{ $user->username }}" required placeholder="Username" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-red-950 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2 text-red-950">
                    <label class="text-sm font-extrabold text-slate-400 uppercase tracking-[0.2em] ml-1">Institutional Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" required placeholder="email@stunningrwanda.com" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-red-950 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm">
                </div>
                <div class="space-y-2 text-red-950">
                    <label class="text-sm font-extrabold text-slate-400 uppercase tracking-[0.2em] ml-1">Institutional Authority (Role)</label>
                    <div class="relative">
                        <select name="role" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-red-950 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all appearance-none cursor-pointer shadow-sm">
                            <option value="operations" {{ $user->role === 'operations' ? 'selected' : '' }}>Operations Management</option>
                            <option value="sales" {{ $user->role === 'sales' ? 'selected' : '' }}>Financial Sales & EBM</option>
                            <option value="md" {{ $user->role === 'md' ? 'selected' : '' }}>Executive Director</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>System Architect (Admin)</option>
                        </select>
                        <div class="absolute right-5 top-5 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2 text-red-950">
                    <label class="text-sm font-extrabold text-slate-400 uppercase tracking-[0.2em] ml-1">Contact Protocol (Phone)</label>
                    <input type="text" name="phone" value="{{ $user->phone }}" placeholder="+250..." 
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-red-950 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm">
                </div>
                <div class="space-y-2 text-red-950">
                    <label class="text-sm font-extrabold text-slate-400 uppercase tracking-[0.2em] ml-1">Update Access Key (Optional)</label>
                    <input type="password" name="password" placeholder="Leave blank to retain current" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-red-950 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm">
                </div>
            </div>

            <div class="pt-6 flex flex-col sm:flex-row gap-4">
                <button type="submit" class="flex-1 bg-red-950 hover:bg-red-900 text-white py-5 rounded-2xl font-extrabold text-xs uppercase tracking-[0.2em] shadow-xl shadow-slate-200 transition-all flex items-center justify-center space-x-3 group">
                    <span>Synchronize Profile Protocols</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-10 py-5 bg-white border border-slate-200 text-slate-500 rounded-2xl font-extrabold text-xs uppercase tracking-[0.2em] hover:bg-slate-50 transition-all flex items-center justify-center">
                    Abort
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
