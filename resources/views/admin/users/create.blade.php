@extends('layouts.app')
@section('title', 'Create User')

@section('content')
<div class="max-w-2xl animate-in slide-in-from-bottom duration-700">
    <div class="mb-10">
        <a href="{{ route('admin.users.index') }}" class="text-slate-400 hover:red-700 transition-colors flex items-center space-x-2 mb-6 font-bold text-xs uppercase tracking-widest">
            <span>← Return to Personnel Directory</span>
        </a>
        <div class="flex items-center space-x-4 mb-2">
            <div class="w-12 h-12 bg-red-700/10 rounded-2xl flex items-center justify-center">
                <span class="text-2xl">👤</span>
            </div>
            <div>
                <h1 class="text-4xl font-extrabold tracking-tighter text-red-950">Enroll <span class="red-700 italic">Personnel</span></h1>
                <p class="text-slate-400 font-medium tracking-wide">Initialize new system accounts and define institutional authority.</p>
            </div>
        </div>
    </div>

    <div class="premium-card p-10 rounded-3xl relative overflow-hidden">
        <div class="absolute top-0 right-0 p-8 opacity-[0.03] font-extrabold text-8xl pointer-events-none text-red-950 italic">IDENTITY</div>
        
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-8 relative">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2 text-red-950">
                    <label class="text-[0.65rem] font-extrabold text-slate-400 uppercase tracking-[0.2em] ml-1">Full Designation</label>
                    <input type="text" name="name" required placeholder="Full Name" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-red-950 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm">
                </div>
                <div class="space-y-2 text-red-950">
                    <label class="text-[0.65rem] font-extrabold text-slate-400 uppercase tracking-[0.2em] ml-1">Institutional Email</label>
                    <input type="email" name="email" required placeholder="email@stunningrwanda.com" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-red-950 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2 text-red-950">
                    <label class="text-[0.65rem] font-extrabold text-slate-400 uppercase tracking-[0.2em] ml-1">Institutional Authority (Role)</label>
                    <div class="relative">
                        <select name="role" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-red-950 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all appearance-none cursor-pointer shadow-sm">
                            <option value="operations">Operations Management</option>
                            <option value="sales">Financial Sales & EBM</option>
                            <option value="md">Executive Director</option>
                            <option value="admin">System Architect (Admin)</option>
                        </select>
                        <div class="absolute right-5 top-5 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
                <div class="space-y-2 text-red-950">
                    <label class="text-[0.65rem] font-extrabold text-slate-400 uppercase tracking-[0.2em] ml-1">Contact Protocol (Phone)</label>
                    <input type="text" name="phone" placeholder="+250..." 
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-red-950 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm">
                </div>
            </div>

            <div class="space-y-4" x-data="passwordLogic()">
                <div class="space-y-2 text-red-950">
                    <label class="text-[0.65rem] font-extrabold text-slate-400 uppercase tracking-[0.2em] ml-1">Primary Passcode</label>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1 relative">
                            <input type="text" name="password" x-model="password" placeholder="Leave blank for institutional auto-generation" 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-red-950 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm">
                        </div>
                        <button type="button" @click="generatePassword" class="bg-red-50 hover:bg-red-100 text-red-800 border border-red-100 px-6 py-4 rounded-2xl font-bold text-xs uppercase tracking-widest transition-all whitespace-nowrap shadow-sm">
                            Auto-Generate
                        </button>
                    </div>
                </div>

                <!-- Strength Indicator -->
                <div class="space-y-2" x-show="password.length > 0" x-transition>
                    <div class="flex justify-between items-center px-1">
                        <span class="text-[0.6rem] font-bold uppercase tracking-wider" :class="strengthColor(strength())" x-text="strengthLabel(strength())"></span>
                    </div>
                    <div class="flex space-x-2 h-1.5">
                        <div class="flex-1 rounded-full transition-all duration-300" :class="strength() >= 1 ? strengthBg(strength()) : 'bg-slate-200'"></div>
                        <div class="flex-1 rounded-full transition-all duration-300" :class="strength() >= 2 ? strengthBg(strength()) : 'bg-slate-200'"></div>
                        <div class="flex-1 rounded-full transition-all duration-300" :class="strength() >= 3 ? strengthBg(strength()) : 'bg-slate-200'"></div>
                        <div class="flex-1 rounded-full transition-all duration-300" :class="strength() >= 4 ? strengthBg(strength()) : 'bg-slate-200'"></div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('passwordLogic', () => ({
                        password: '',
                        generatePassword() {
                            const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
                            let pass = '';
                            for (let i = 0; i < 16; i++) {
                                pass += chars.charAt(Math.floor(Math.random() * chars.length));
                            }
                            this.password = pass;
                        },
                        strength() {
                            let score = 0;
                            if (this.password.length > 7) score += 1;
                            if (this.password.match(/[A-Z]/)) score += 1;
                            if (this.password.match(/[0-9]/)) score += 1;
                            if (this.password.match(/[^a-zA-Z0-9]/)) score += 1;
                            return score;
                        },
                        strengthLabel(score) {
                            if (score === 0) return 'Weak';
                            if (score === 1) return 'Fair';
                            if (score === 2) return 'Good';
                            if (score === 3) return 'Strong';
                            if (score === 4) return 'Exceptional';
                            return 'Weak';
                        },
                        strengthColor(score) {
                            if (score <= 1) return 'text-red-500';
                            if (score === 2) return 'text-orange-500';
                            if (score >= 3) return 'text-green-500';
                            return 'text-slate-400';
                        },
                        strengthBg(score) {
                            if (score <= 1) return 'bg-red-500';
                            if (score === 2) return 'bg-orange-500';
                            if (score >= 3) return 'bg-green-500';
                            return 'bg-slate-200';
                        }
                    }))
                })
            </script>

            <div class="pt-6">
                <button type="submit" class="w-full bg-red-950 hover:bg-red-900 text-white py-5 rounded-2xl font-extrabold text-xs uppercase tracking-[0.2em] shadow-xl shadow-slate-200 transition-all flex items-center justify-center space-x-3 group">
                    <span>Initialize Personnel Account</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
