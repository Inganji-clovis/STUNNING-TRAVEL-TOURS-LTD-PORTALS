@extends('layouts.app')
@section('title', 'Initialize Role')

@section('content')
<div class="max-w-2xl animate-in slide-in-from-bottom duration-700">
    <div class="mb-10">
        <a href="{{ route('admin.roles.index') }}" class="text-slate-400 hover:text-red-700 transition-colors flex items-center space-x-2 mb-6 font-bold text-xs uppercase tracking-widest">
            <span>← Return to Role Management</span>
        </a>
        <div class="flex items-center space-x-4 mb-2">
            <div class="w-12 h-12 bg-red-700/10 rounded-2xl flex items-center justify-center">
                <span class="text-2xl">🛡️</span>
            </div>
            <div>
                <h1 class="text-4xl font-extrabold tracking-tighter text-red-950">Initialize <span class="red-700 italic">Role</span></h1>
                <p class="text-slate-400 font-medium tracking-wide">Define a new boundary of institutional authority.</p>
            </div>
        </div>
    </div>

    <div class="premium-card p-10 rounded-3xl relative overflow-hidden">
        <div class="absolute top-0 right-0 p-8 opacity-[0.03] font-extrabold text-8xl pointer-events-none text-red-950 italic">POLICY</div>
        
        <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-8 relative">
            @csrf
            
            <div class="space-y-2 text-red-950">
                <label class="text-[0.65rem] font-extrabold text-slate-400 uppercase tracking-[0.2em] ml-1">Role Designation Name</label>
                <input type="text" name="name" required placeholder="e.g. Senior Auditor" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-red-950 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm">
                @error('name')
                    <p class="text-sunset-orange text-xs font-bold mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full bg-red-950 hover:bg-red-900 text-white py-5 rounded-2xl font-extrabold text-xs uppercase tracking-[0.2em] shadow-xl shadow-slate-200 transition-all flex items-center justify-center space-x-3 group">
                    <span>Instantiate Role</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
