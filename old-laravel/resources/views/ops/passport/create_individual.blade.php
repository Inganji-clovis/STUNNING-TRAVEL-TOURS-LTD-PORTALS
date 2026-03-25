@extends('layouts.app')
@section('title', 'Record Individual Passport')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in duration-700">
    <div>
        <h1 class="text-3xl font-bold">Record Individual Passport</h1>
        <p class="text-slate-400 font-medium">Capture detailed travel credentials for an individual client</p>
    </div>

    <div class="glass border border-slate-200 p-8 rounded-[40px] shadow-2xl">
        <form action="{{ route('ops.passport.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Full Names -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Full Names</label>
                    <input type="text" name="full_names" value="{{ $client_name ?? '' }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all" placeholder="John Doe">
                </div>

                <!-- Passport Number -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Passport Number</label>
                    <input type="text" name="passport_number" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all font-mono" placeholder="PC1234567">
                </div>

                <!-- Country Code -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Country Code</label>
                    <input type="text" name="country_code" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all uppercase" placeholder="RWA">
                </div>

                <!-- Nationality -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Nationality</label>
                    <input type="text" name="nationality" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all" placeholder="Rwandan">
                </div>

                <!-- Date of Birth -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Date of Birth</label>
                    <input type="date" name="dob" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all">
                </div>

                <!-- Place of Birth -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Place of Birth</label>
                    <input type="text" name="place_of_birth" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all" placeholder="Kigali, Rwanda">
                </div>

                <!-- Date of Expiry -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Date of Expiry</label>
                    <input type="date" name="date_of_expiry" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all">
                </div>

                <!-- Gender -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Gender</label>
                    <select name="gender" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>

            <!-- Upload Field -->
            <div class="space-y-2">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Passport File (Digital Copy)</label>
                <div class="relative group">
                    <input type="file" name="passport_file" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="w-full bg-slate-50 border-2 border-dashed border-slate-200 group-hover:border-red-500/50 rounded-2xl p-8 flex flex-col items-center justify-center transition-all">
                        <span class="text-3xl mb-2">Ã°Å¸â€œâ€ž</span>
                        <p class="text-sm text-slate-400 font-medium">Click to upload or drag & drop PDF/Image</p>
                    </div>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-4">
                <a href="{{ route('ops.passport.index') }}" class="text-sm font-bold text-slate-400 hover:text-white transition-colors">Cancel</a>
                <button type="submit" class="bg-red-700 hover:bg-red-800 px-10 py-4 rounded-2xl font-black uppercase tracking-tighter transition-all text-white">Save Passport</button>
            </div>
        </form>
    </div>
</div>
@endsection
