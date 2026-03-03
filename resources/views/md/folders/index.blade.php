@extends('layouts.app')
@section('title', 'Corporate Repositories')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-[#334155]">Corporate <span class="italic text-red-600">Vault</span></h1>
            <p class="text-[12px] font-bold text-slate-400 mt-2 tracking-[0.15em] uppercase">Centralized Document Archival System</p>
        </div>
        <button onclick="document.getElementById('createFolderModal').classList.remove('hidden')" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-2xl font-bold transition-all shadow-lg shadow-red-600/20 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            New {{ ucfirst($type) }} Folder
        </button>
    </div>

    <!-- Category Tabs -->
    <div class="flex flex-wrap gap-4">
        @foreach(['client' => 'Client Folders', 'contract' => 'Contracts', 'letter' => 'Letters'] as $tab => $label)
            <a href="{{ route('md.folders.index', ['type' => $tab]) }}" 
               class="px-8 py-4 rounded-2xl font-extrabold transition-all duration-300 {{ $type === $tab ? 'bg-red-600 text-white shadow-lg shadow-red-600/20' : 'bg-white text-slate-500 border border-slate-100 hover:text-red-600 hover:border-red-200 hover:bg-red-50/50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- Repository Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        @if(count($folders) > 0)
            @foreach($folders as $folder)
            <div class="premium-card p-8 rounded-3xl group hover:border-red-200 text-center flex flex-col items-center relative transition-all hover:-translate-y-1">
                <a href="{{ route('md.folders.show', $folder) }}" class="absolute inset-0 z-10 w-full h-full block"></a>
                <div class="w-20 h-20 rounded-3xl flex items-center justify-center mb-6 transition-all duration-500 relative z-0 {{ $type === 'client' ? 'bg-red-50 text-red-600 group-hover:bg-red-100' : ($type === 'contract' ? 'bg-slate-100 text-slate-700 group-hover:bg-slate-200' : 'bg-indigo-50 text-indigo-700 group-hover:bg-indigo-100') }}">
                    @if($type === 'client') 
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    @elseif($type === 'contract') 
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    @else 
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    @endif
                </div>
                <div class="w-full">
                    <h3 class="font-extrabold text-[#334155] group-hover:text-red-600 transition-colors text-[18px] mb-2 truncate px-2">{{ $folder->name }}</h3>
                    <div class="inline-block px-3 py-1 bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] rounded-lg border border-slate-100 mb-6">{{ $folder->year }} Archive</div>
                </div>
                <div class="pt-5 border-t border-slate-100/50 w-full flex justify-between items-center relative z-20">
                    <span class="text-[11px] font-bold text-slate-400">{{ $folder->files_count ?? $folder->files->count() }} Files</span>
                    
                    <div class="flex items-center space-x-4">
                        <form action="{{ route('md.folders.destroy', $folder) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this repository and all its managed files? This action cannot be undone.');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-slate-300 hover:text-red-600 transition-colors" title="Delete Repository">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                        <a href="{{ route('md.folders.show', $folder) }}" class="text-red-600 font-extrabold text-sm hover:underline flex items-center gap-1 group-hover:translate-x-1 transition-transform">Open <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></a>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="col-span-full py-32 text-center bg-slate-50/50 border-2 border-dashed border-slate-200/50 rounded-[32px]">
                <div class="w-24 h-24 bg-white rounded-[2rem] shadow-sm flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path></svg>
                </div>
                <p class="text-slate-400 font-bold uppercase tracking-[0.15em] text-[12px]">No repositories discovered</p>
                <div class="mt-8 relative z-20">
                    <button onclick="document.getElementById('createFolderModal').classList.remove('hidden')" class="bg-white border border-slate-200 text-[#334155] px-8 py-3.5 rounded-2xl font-extrabold shadow-sm hover:border-slate-300 hover:shadow-md transition-all">Initialize First Repository</button>
                </div>
            </div>
        @endif
    </div>
</div>
</div>

<!-- Create Modal -->
<div id="createFolderModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[60] hidden flex items-center justify-center p-4">
    <div class="bg-white border border-slate-100 w-full max-w-md rounded-[32px] overflow-hidden animate-in zoom-in duration-300 shadow-2xl p-2">
        <div class="bg-red-600 p-8 rounded-[24px] text-white">
            <h2 class="text-2xl font-extrabold tracking-tighter">Initialize Repository</h2>
            <p class="text-[11px] text-red-100 font-bold mt-2 uppercase tracking-[0.15em]">Setup a secure archival space for {{ $type }}.</p>
        </div>
        <form action="{{ route('md.folders.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
            
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] ml-1">Folder Designation</label>
                <input type="text" name="name" required placeholder="e.g. {{ $type === 'client' ? 'Derrick Rutaremara' : 'Staff Contracts 2026' }}" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-400 focus:ring-4 focus:ring-red-100 outline-none transition-all font-bold placeholder:text-slate-300 text-[#334155]">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] ml-1">Fiscal Year Cycle</label>
                <input type="text" name="year" value="{{ date('Y') }}" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-400 focus:ring-4 focus:ring-red-100 outline-none transition-all font-bold text-[#334155]">
            </div>

            <div class="flex gap-4 pt-4">
                <button type="button" onclick="document.getElementById('createFolderModal').classList.add('hidden')" class="flex-1 bg-white border border-slate-200 text-slate-500 font-extrabold py-4 rounded-2xl hover:bg-slate-50 hover:text-slate-700 transition-all">Abort</button>
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-extrabold py-4 rounded-2xl transition-all shadow-lg shadow-red-600/20">Initialize</button>
            </div>
        </form>
    </div>
</div>
@endsection
