@extends('layouts.app')
@section('title', 'Portal Access Settings')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-red-950">Portal <span class="red-700 italic">Access Settings</span></h1>
            <p class="text-slate-400 mt-2 font-medium tracking-wide">Manage global lockdowns and institutional IP restrictions.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-800 px-6 py-4 rounded-xl font-bold text-sm border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.portals.store') }}" method="POST" class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Global Portal Locks -->
            <div class="premium-card p-10 rounded-3xl relative overflow-hidden h-full">
                <div class="absolute top-0 right-0 p-8 opacity-[0.03] font-extrabold text-8xl pointer-events-none text-red-950 italic">LOCKS</div>
                
                <h2 class="text-xl font-extrabold text-red-950 mb-1 relative z-10">Global Portal Locks</h2>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-8 relative z-10">Toggle access to institutional sections</p>
                
                <div class="space-y-4 relative z-10">
                    <div class="flex items-center justify-between bg-slate-50 border border-slate-100 p-5 rounded-2xl hover:border-red-200 transition-colors group">
                        <div>
                            <div class="font-bold text-sm text-red-950">Operations Portal</div>
                            <div class="text-sm font-extrabold text-slate-400 tracking-widest uppercase mt-0.5">operations_portal_active</div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer" x-data="{ checked: {{ $settings['operations_portal_active'] ? 'true' : 'false' }} }">
                            <input type="checkbox" name="portals[]" value="operations_portal_active" class="sr-only peer" x-model="checked">
                            <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-red-700 shadow-inner duration-300"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between bg-slate-50 border border-slate-100 p-5 rounded-2xl hover:border-red-200 transition-colors group">
                        <div>
                            <div class="font-bold text-sm text-red-950">Financial Sales Portal</div>
                            <div class="text-sm font-extrabold text-slate-400 tracking-widest uppercase mt-0.5">sales_portal_active</div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer" x-data="{ checked: {{ $settings['sales_portal_active'] ? 'true' : 'false' }} }">
                            <input type="checkbox" name="portals[]" value="sales_portal_active" class="sr-only peer" x-model="checked">
                            <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-red-700 shadow-inner duration-300"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between bg-slate-50 border border-slate-100 p-5 rounded-2xl hover:border-red-200 transition-colors group">
                        <div>
                            <div class="font-bold text-sm text-red-950">Executive Suite</div>
                            <div class="text-sm font-extrabold text-slate-400 tracking-widest uppercase mt-0.5">md_portal_active</div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer" x-data="{ checked: {{ $settings['md_portal_active'] ? 'true' : 'false' }} }">
                            <input type="checkbox" name="portals[]" value="md_portal_active" class="sr-only peer" x-model="checked">
                            <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-red-700 shadow-inner duration-300"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Network Security -->
            <div class="premium-card p-10 rounded-3xl relative overflow-hidden h-full flex flex-col">
                <div class="absolute top-0 right-0 p-8 opacity-[0.03] font-extrabold text-8xl pointer-events-none text-red-950 italic">NETWORK</div>
                
                <h2 class="text-xl font-extrabold text-red-950 mb-1 relative z-10">Institutional IP Whitelist</h2>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-8 relative z-10">Restrict access to secure networks</p>
                
                <div class="flex-1 space-y-2 text-red-950 relative z-10 flex flex-col">
                    <label class="text-sm font-extrabold text-slate-400 uppercase tracking-[0.2em] ml-1">Allowed IP Addresses</label>
                    <textarea name="ip_whitelist" placeholder="Enter IP addresses separated by commas (e.g. 192.168.1.1, 10.0.0.5)&#10;Leave empty to allow all incoming connections." 
                              class="flex-1 w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-red-950 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm resize-none custom-scrollbar leading-relaxed">{{ old('ip_whitelist', $ipWhitelist) }}</textarea>
                </div>
            </div>

        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-red-950 hover:bg-red-900 text-white px-10 py-4 rounded-xl text-xs font-extrabold shadow-lg shadow-red-900/20 transition-all uppercase tracking-[0.2em]">
                Synchronize Security Protocols
            </button>
        </div>
    </form>
</div>
@endsection
