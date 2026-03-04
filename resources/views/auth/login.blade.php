@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-slate-900">
    <!-- Visual Background Layer -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-cover bg-center" 
             style="background-image: url('{{ asset('assets/brand/clear_plane.png') }}');">
        </div>
        <!-- Light dark overlay to keep the white login form readable without altering the image -->
        <div class="absolute inset-0 bg-black/40"></div>
    </div>

    <div class="w-full max-w-5xl rounded-[1.5rem] overflow-hidden flex flex-col lg:flex-row shadow-[0_30px_60px_-15px_rgba(0,0,0,0.5)] border border-white/10 relative z-10 mx-4">
        <!-- Visual Side with Quote -->
        <div class="lg:w-1/2 relative min-h-[300px] lg:min-h-[500px] overflow-hidden flex items-end">
            <!-- Semi-transparent overlay to ensure text is readable -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/95 via-slate-900/40 to-black/20 backdrop-blur-[2px]"></div>
            <div class="p-10 lg:p-14 text-white relative z-10 flex flex-col justify-end h-full">
                <!-- Quote Icon (Optional touch) -->
                <svg class="w-10 h-10 text-red-500 mb-6 opacity-80" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 18L14.017 10.609C14.017 4.905 17.748 1.039 23 0L23.995 2.151C21.563 3.053 20 5.762 20 9.549L20 10.609L24 10.609L24 18L14.017 18ZM0 18L0 10.609C0 4.905 3.748 1.038 9 0L9.996 2.151C7.563 3.053 6 5.762 6 9.549L6 10.609L10 10.609L10 18L0 18Z"/></svg>
                
                <h2 class="text-3xl lg:text-4xl font-black tracking-tight mb-4 leading-[1.2] text-white">
                    "Success is no accident. It is <span class="text-red-500 italic">hard work</span>, perseverance, learning, studying, sacrifice and most of all, love of what you are doing."
                </h2>
                <div class="flex items-center space-x-4 mt-4">
                    <div class="h-1 w-12 bg-red-500 rounded-full"></div>
                    <p class="text-white/70 font-bold text-sm tracking-widest uppercase">Pelé</p>
                </div>
            </div>
        </div>

        <!-- Form Side -->
        <div class="lg:w-1/2 bg-white/95 backdrop-blur-3xl p-10 lg:p-14 flex flex-col justify-center relative">
            <div class="mb-10 w-full text-center">
                <div class="w-full h-40 mb-2 flex items-center justify-center">
                    <img src="{{ asset('assets/brand/logo-removebg-preview.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Welcome</h1>
                <p class="text-slate-500 font-bold text-sm uppercase tracking-widest opacity-60">staff portal</p>
            </div>

            <form action="{{ url('/login') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-sm font-black text-slate-900 uppercase tracking-widest ml-1"> Username</label>
                    <div class="relative group">
                        <input type="text" name="username" required placeholder="Personnel Username" class="w-full bg-slate-50/50 border border-slate-700/30 rounded-xl px-5 py-3.5 focus:bg-white focus:border-safari-green focus:ring-4 focus:ring-safari-green/10 outline-none transition-all font-bold text-slate-900 placeholder:text-slate-400 text-sm">
                        <span class="absolute right-5 top-3.5 text-slate-400 group-focus-within:text-safari-green transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </span>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center px-1">
                        <label class="text-sm font-black text-slate-900 uppercase tracking-widest">Access Key</label>
                        <a href="#" class="text-sm font-black text-red-600 hover:text-red-900 uppercase tracking-widest transition-colors">Restore Access?</a>
                    </div>
                    <div class="relative group">
                        <input type="password" name="password" required placeholder="••••••••" class="w-full bg-slate-50/50 border border-slate-700/30 rounded-xl px-5 py-3.5 focus:bg-white focus:border-safari-green focus:ring-4 focus:ring-safari-green/10 outline-none transition-all font-bold text-slate-900 placeholder:text-slate-400 text-sm">
                        <span class="absolute right-5 top-3.5 text-slate-400 group-focus-within:text-safari-green transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </span>
                    </div>
                </div>

                @if($errors->any())
                    <div class="p-4 bg-red-50/50 backdrop-blur-md border border-red-100 rounded-xl flex items-center space-x-3 text-red-600 animate-bounce">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <p class="text-sm font-black uppercase tracking-tight leading-normal">{{ $errors->first() }}</p>
                    </div>
                @endif

                <button type="submit" class="w-full bg-red-600 hover:bg-red-800 text-white py-4 rounded-xl font-black uppercase tracking-[0.2em] text-sm shadow-xl shadow-red-900/20 transition-all hover:-translate-y-0.5 active:scale-[0.98] duration-300">
                    Login
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm font-black text-slate-400 uppercase tracking-widest">
                    STUNNING TRAVEL & TOURS <span class="mx-2 opacity-30">|</span> RWANDA HQ
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
