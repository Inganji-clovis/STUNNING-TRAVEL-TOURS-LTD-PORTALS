@extends('layouts.app')
@section('title', 'Modify Passport Record')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in duration-700">
    <div>
        <h1 class="text-3xl font-bold">Modify Passport Record</h1>
        <p class="text-slate-400 font-medium">Update traveler credentials and identity files for {{ $document->client->name }}</p>
    </div>

    <div class="glass border border-slate-200 p-8 rounded-[40px] shadow-2xl">
        <form action="{{ route('ops.passport.update', $document) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Full Names -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Full Names</label>
                    <input type="text" name="full_names" value="{{ old('full_names', $document->client->name) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all" placeholder="John Doe">
                </div>

                <!-- Passport Number -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Passport Number</label>
                    <input type="text" name="passport_number" value="{{ old('passport_number', $document->passport_number) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all font-mono" placeholder="PC1234567">
                </div>

                <!-- Country Code -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Country Code</label>
                    <input type="text" name="country_code" value="{{ old('country_code', $document->country_code) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all uppercase" placeholder="RWA">
                </div>

                <!-- Nationality -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Nationality</label>
                    <input type="text" name="nationality" value="{{ old('nationality', $document->nationality) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all" placeholder="Rwandan">
                </div>

                <!-- Date of Birth -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Date of Birth</label>
                    <input type="date" name="dob" value="{{ old('dob', $document->dob ? $document->dob->format('Y-m-d') : '') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all">
                </div>

                <!-- Place of Birth -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Place of Birth</label>
                    <input type="text" name="place_of_birth" value="{{ old('place_of_birth', $document->place_of_birth) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all" placeholder="Kigali, Rwanda">
                </div>

                <!-- Date of Expiry -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Date of Expiry</label>
                    <input type="date" name="date_of_expiry" value="{{ old('date_of_expiry', $document->expiry_date ? $document->expiry_date->format('Y-m-d') : '') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all">
                </div>

                <!-- Gender -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Gender</label>
                    <select name="gender" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all">
                        <option value="male" {{ $document->gender == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ $document->gender == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ $document->gender == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>

            <!-- Upload Field -->
            <div class="space-y-2">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Passport File (Optional Replace)</label>
                <div class="relative group">
                    <input type="file" name="passport_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="w-full bg-slate-50 border-2 border-dashed border-slate-200 group-hover:border-red-500/50 rounded-2xl p-8 flex flex-col items-center justify-center transition-all">
                        <span class="text-3xl mb-2">📄</span>
                        <p class="text-sm text-slate-400 font-medium">Click to replace or drag & drop PDF/Image</p>
                    </div>
                </div>
                @if($document->file_path)
                <p class="text-xs text-slate-400 ml-1">Current file: <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-red-700 font-bold hover:underline">View Asset</a></p>
                @endif
            </div>

            <div class="pt-4 flex items-center justify-end space-x-4">
                <a href="{{ route('ops.passport.index') }}" class="text-sm font-bold text-slate-400 hover:text-white transition-colors">Cancel</a>
                <button type="submit" class="bg-red-700 hover:bg-red-800 px-10 py-4 rounded-2xl font-black uppercase tracking-tighter transition-all text-white shadow-lg shadow-red-900/20">Update Credentials</button>
            </div>
        </form>
    </div>
</div>
@endsection
