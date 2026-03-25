@extends('layouts.app')
@section('title', 'Register New Organization')

@section('content')
<div class="max-w-3xl animate-in slide-in-from-bottom duration-700">
    <div class="mb-8">
        <a href="{{ route('ops.organizations.index') }}" class="text-slate-400 hover:text-red-700 transition-colors flex items-center space-x-2 mb-4 font-bold text-xs uppercase tracking-widest">
            <span>Ã¢â€ Â Back to Registry</span>
        </a>
        <h1 class="text-4xl font-extrabold text-slate-900 tracking-tighter underline decoration-red-700 decoration-4 underline-offset-8">New Organization</h1>
        <p class="text-slate-400 mt-4 font-medium uppercase text-sm tracking-[0.3em]">Institutional partner profile creation</p>
    </div>

    <div class="premium-card p-12 rounded-3xl relative overflow-hidden bg-white">
        <form action="{{ route('ops.organizations.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-sm font-black text-slate-400 uppercase tracking-widest ml-1">Organization Name</label>
                    <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all font-bold text-slate-900 placeholder:text-slate-300">
                </div>

                <div class="grid grid-cols-2 gap-8">
                    <div class="col-span-2 md:col-span-1 space-y-2">
                        <label class="block text-sm font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                        <input type="email" name="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-red-700 transition-all font-bold text-slate-900 placeholder:text-slate-300">
                    </div>
                    <div class="col-span-2 md:col-span-1 space-y-2">
                        <label class="block text-sm font-black text-slate-400 uppercase tracking-widest ml-1">Phone Number</label>
                        <input type="text" name="phone" placeholder="+250..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-red-700 transition-all font-bold text-slate-900 placeholder:text-slate-300">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-black text-slate-400 uppercase tracking-widest ml-1">Physical Address</label>
                    <textarea name="address" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-red-700 transition-all font-bold text-slate-900 placeholder:text-slate-300"></textarea>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-50">
                <button type="submit" class="w-full bg-red-700 hover:bg-red-800 py-5 rounded-xl font-black text-white uppercase tracking-widest text-sm shadow-xl shadow-red-900/20 transition-all hover:-translate-y-1 transform active:scale-[0.98]">
                    Register Organization
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
