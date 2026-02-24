@extends('layouts.app')
@section('title', 'Register New Client')

@section('content')
<div class="max-w-3xl animate-in slide-in-from-bottom duration-700">
    <div class="mb-8">
        <a href="{{ route('ops.clients.index') }}" class="text-slate-400 hover:text-red-700 transition-colors flex items-center space-x-2 mb-4 font-bold text-xs uppercase tracking-widest">
            <span>Ã¢â€ Â Back to Registry</span>
        </a>
        <h1 class="text-4xl font-extrabold text-slate-900 tracking-tighter underline decoration-red-700 decoration-4 underline-offset-8">New Client</h1>
        <p class="text-slate-400 mt-4 font-medium uppercase text-[0.65rem] tracking-[0.3em]">Individual traveler profile creation</p>
    </div>

    <div class="premium-card p-12 rounded-3xl relative overflow-hidden bg-white">
        <div class="absolute top-0 right-0 p-8 opacity-[0.03] pointer-events-none">
            <img src="{{ asset('assets/brand/logo.png') }}" alt="Logo" class="w-24 grayscale">
        </div>

        <form action="{{ route('ops.clients.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-8">
                    <div class="col-span-2 space-y-2">
                            <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest ml-1">Full Legal Name (Passport)</label>
                            <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all font-bold text-slate-900 placeholder:text-slate-300">
                    </div>
                    <div class="col-span-2 md:col-span-1 space-y-2">
                        <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                        <input type="email" name="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-red-700 transition-all font-bold text-slate-900 placeholder:text-slate-300">
                    </div>
                    <div class="col-span-2 md:col-span-1 space-y-2">
                        <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest ml-1">Phone Number</label>
                        <input type="text" name="phone" placeholder="+250..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-red-700 transition-all font-bold text-slate-900 placeholder:text-slate-300">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-8">
                    <div class="col-span-2 md:col-span-1 space-y-2">
                        <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest ml-1">Membership</label>
                        <select name="membership_type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-red-700 transition-all appearance-none cursor-pointer font-bold text-slate-900">
                            <option value="standard">Standard Individual</option>
                            <option value="corporate">Corporate Member</option>
                            <option value="premium">Premium Traveler</option>
                        </select>
                    </div>
                    <div class="col-span-2 md:col-span-1 space-y-2">
                        <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest ml-1">Membership Identifier</label>
                        <input type="text" name="membership_number" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-red-700 transition-all font-mono font-bold text-slate-900 placeholder:text-slate-300" placeholder="ST-100... Optional">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-8">
                    <div class="col-span-2 md:col-span-1 space-y-2">
                        <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest ml-1">Preferred Carrier</label>
                        <input type="text" name="airline_name" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-red-700 transition-all font-bold text-slate-900 placeholder:text-slate-300" placeholder="RwandAir, Ethiopian, etc.">
                    </div>
                    <div class="col-span-2 md:col-span-1 space-y-2">
                        <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest ml-1">Organization</label>
                        <select name="organization_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-red-700 transition-all appearance-none cursor-pointer font-bold text-slate-900">
                            <option value="">Status: INDIVIDUAL</option>
                            @foreach($organizations as $org)
                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-50">
                <button type="submit" class="w-full bg-red-700 hover:bg-red-800 py-5 rounded-xl font-black text-white uppercase tracking-widest text-sm shadow-xl shadow-red-900/20 transition-all hover:-translate-y-1 transform active:scale-[0.98]">
                    Register Client
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
