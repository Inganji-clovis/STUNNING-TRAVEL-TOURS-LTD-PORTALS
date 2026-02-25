@extends('layouts.app')
@section('title', 'Security Settings')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-red-950">Security <span class="red-700 italic">Settings</span></h1>
            <p class="text-slate-400 mt-2 font-medium tracking-wide">Configure global authentication protocols and session policies.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-800 px-6 py-4 rounded-xl font-bold text-sm border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.security.store') }}" method="POST" class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Global Enforcement Toggle -->
            <div class="premium-card p-10 rounded-3xl relative overflow-hidden h-full">
                <div class="absolute top-0 right-0 p-8 opacity-[0.03] font-extrabold text-8xl pointer-events-none text-red-950 italic">POLICY</div>
                
                <h2 class="text-xl font-extrabold text-red-950 mb-1 relative z-10">Enforcement Protocols</h2>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-8 relative z-10">System-wide authorization constraints</p>
                
                <div class="space-y-4 relative z-10">
                    <div class="flex items-center justify-between bg-slate-50 border border-slate-100 p-5 rounded-2xl hover:border-red-200 transition-colors group">
                        <div class="pr-4">
                            <div class="font-bold text-sm text-red-950">Require Two-Factor Authentication (2FA)</div>
                            <div class="text-sm font-bold text-slate-400 mt-1">Force all personnel to setup 2FA upon next login.</div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0" x-data="{ checked: {{ $settings['require_2fa'] ? 'true' : 'false' }} }">
                            <input type="checkbox" name="settings[]" value="require_2fa" class="sr-only peer" x-model="checked">
                            <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-red-700 shadow-inner duration-300"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between bg-slate-50 border border-slate-100 p-5 rounded-2xl hover:border-red-200 transition-colors group">
                        <div class="pr-4">
                            <div class="font-bold text-sm text-red-950">Auto-Lock Inactive Accounts</div>
                            <div class="text-sm font-bold text-slate-400 mt-1">Automatically suspend accounts inactive for 30+ days.</div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0" x-data="{ checked: {{ $settings['auto_lock_inactive'] ? 'true' : 'false' }} }">
                            <input type="checkbox" name="settings[]" value="auto_lock_inactive" class="sr-only peer" x-model="checked">
                            <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-red-700 shadow-inner duration-300"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between bg-slate-50 border border-slate-100 p-5 rounded-2xl hover:border-red-200 transition-colors group">
                        <div class="pr-4">
                            <div class="font-bold text-sm text-red-950">Enforce Complex Passwords</div>
                            <div class="text-sm font-bold text-slate-400 mt-1">Require uppercase, numbers, and symbols for all credentials.</div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0" x-data="{ checked: {{ $settings['complex_passwords'] ? 'true' : 'false' }} }">
                            <input type="checkbox" name="settings[]" value="complex_passwords" class="sr-only peer" x-model="checked">
                            <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-red-700 shadow-inner duration-300"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Parameters Setup -->
            <div class="premium-card p-10 rounded-3xl relative overflow-hidden h-full flex flex-col">
                <div class="absolute top-0 right-0 p-8 opacity-[0.03] font-extrabold text-8xl pointer-events-none text-red-950 italic">LIMITS</div>
                
                <h2 class="text-xl font-extrabold text-red-950 mb-1 relative z-10">Session Parameters</h2>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-8 relative z-10">Configure operational thresholds</p>
                
                <div class="space-y-6 relative z-10">
                    <div class="space-y-2">
                        <label class="text-sm font-extrabold text-slate-400 uppercase tracking-[0.2em] ml-1">Session Timeout (Minutes)</label>
                        <div class="relative">
                            <input type="number" name="session_timeout" value="{{ old('session_timeout', $settings['session_timeout']) }}" min="5" max="1440" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-red-950 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-5 pointer-events-none text-slate-400 font-bold text-xs">MINS</div>
                        </div>
                        @error('session_timeout')
                            <p class="text-sunset-orange text-sm font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-extrabold text-slate-400 uppercase tracking-[0.2em] ml-1">Maximum Login Attempts</label>
                        <div class="relative">
                            <input type="number" name="max_login_attempts" value="{{ old('max_login_attempts', $settings['max_login_attempts']) }}" min="1" max="20" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-red-950 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-5 pointer-events-none text-slate-400 font-bold text-xs">TRIES</div>
                        </div>
                        <p class="text-sm font-bold text-slate-400 mt-1 ml-1">Lock account after this many failed attempts.</p>
                        @error('max_login_attempts')
                            <p class="text-sunset-orange text-sm font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-red-950 hover:bg-red-900 text-white px-10 py-4 rounded-xl text-xs font-extrabold shadow-lg shadow-red-900/20 transition-all uppercase tracking-[0.2em]">
                Enforce Security Policies
            </button>
        </div>
    </form>
</div>
@endsection
