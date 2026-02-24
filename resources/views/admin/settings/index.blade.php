@extends('layouts.app')
@section('title', 'Company Settings')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-red-950">Company <span class="red-700 italic">Settings</span></h1>
            <p class="text-slate-400 mt-2 font-medium tracking-wide">Manage primary organizational details and system localization.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-800 px-6 py-4 rounded-xl font-bold text-sm border border-green-200">
            {{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="bg-sunset-orange/10 border border-sunset-orange/20 text-sunset-orange px-6 py-4 rounded-xl font-bold text-sm">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Branding Setup -->
            <div class="lg:col-span-1 premium-card p-10 rounded-3xl relative overflow-hidden h-full flex flex-col items-center justify-center text-center">
                <div class="absolute top-0 right-0 p-8 opacity-[0.03] font-extrabold text-8xl pointer-events-none text-red-950 italic">BRAND</div>
                
                <div class="relative z-10 w-full">
                    <div class="w-32 h-32 mx-auto mb-6 rounded-2xl border-4 border-white shadow-xl overflow-hidden bg-slate-50 flex items-center justify-center relative group">
                        @if($settings['company_logo'])
                            <img src="{{ asset('storage/' . $settings['company_logo']) }}" alt="Company Logo" class="w-full h-full object-contain p-2">
                        @else
                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        @endif
                        <div class="absolute inset-0 bg-red-950/80 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-white text-xs font-bold uppercase tracking-widest">Update</span>
                        </div>
                        <input type="file" name="company_logo" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                    
                    <h2 class="text-xl font-extrabold text-red-950 mb-1">Corporate Emblem</h2>
                    <p class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-widest leading-relaxed">Accepted formats: JPG, PNG, SVG<br>Max file dimension: 2MB.</p>
                </div>
            </div>

            <!-- Profile Data -->
            <div class="lg:col-span-2 premium-card p-10 rounded-3xl relative overflow-hidden h-full">
                <div class="absolute top-0 right-0 p-8 opacity-[0.03] font-extrabold text-8xl pointer-events-none text-red-950 italic">PROFILE</div>
                
                <h2 class="text-xl font-extrabold text-red-950 mb-1 relative z-10">Organizational Profile</h2>
                <p class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-widest mb-8 relative z-10">Define primary system identifiers</p>
                
                <div class="space-y-6 relative z-10">
                    <div class="space-y-2">
                        <label class="text-[0.65rem] font-extrabold text-slate-400 uppercase tracking-[0.2em] ml-1">Registered Company Name</label>
                        <input type="text" name="company_name" value="{{ old('company_name', $settings['company_name']) }}" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-red-950 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[0.65rem] font-extrabold text-slate-400 uppercase tracking-[0.2em] ml-1">Support Email Address</label>
                            <input type="email" name="support_email" value="{{ old('support_email', $settings['support_email']) }}" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-red-950 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[0.65rem] font-extrabold text-slate-400 uppercase tracking-[0.2em] ml-1">Contact Hotline</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-red-950 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                        <div class="space-y-2">
                            <label class="text-[0.65rem] font-extrabold text-slate-400 uppercase tracking-[0.2em] ml-1">Default Base Currency</label>
                            <input type="text" name="default_currency" value="{{ old('default_currency', $settings['default_currency']) }}" required placeholder="e.g. RWF, USD"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-red-950 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="text-[0.65rem] font-extrabold text-slate-400 uppercase tracking-[0.2em] ml-1">System Timezone</label>
                            <select name="timezone" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-red-950 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm appearance-none">
                                @foreach(timezone_identifiers_list() as $tz)
                                    <option value="{{ $tz }}" {{ old('timezone', $settings['timezone']) === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-red-950 hover:bg-red-900 text-white px-10 py-4 rounded-xl text-xs font-extrabold shadow-lg shadow-red-900/20 transition-all uppercase tracking-[0.2em]">
                Save Operational Profile
            </button>
        </div>
    </form>
</div>
@endsection
