@extends('layouts.app')
@section('title', 'Corporate Repositories')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h1 class="text-3xl font-bold italic tracking-tighter">Corporate <span class="text-red-600">Repositories</span></h1>
            <p class="text-slate-400">Secure folder-based document archival system</p>
        </div>
        
        <button onclick="document.getElementById('createFolderModal').classList.remove('hidden')" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-semibold transition-all shadow-lg shadow-red-600/20">
            + New {{ ucfirst($type) }} Folder
        </button>
    </div>

    <!-- Tabbed Navigation -->
    <div class="flex items-center space-x-1 p-1 bg-white rounded-xl w-fit">
        <a href="{{ route('md.folders.index', ['type' => 'client']) }}" 
           class="px-6 py-2 rounded-lg text-sm font-medium transition-all {{ $type === 'client' ? 'bg-slate-100 text-red-700 shadow-sm border border-slate-200' : 'text-slate-400 hover:text-red-700' }}">
            Client Folders
        </a>
    <div class="space-y-12 animate-in fade-in duration-1000">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-slate-900">Corporate <span class="red-700 italic">Vault</span></h1>
            <p class="text-slate-400 mt-2 font-medium tracking-wide">Centralized document intelligence and archival system.</p>
        </div>
    </div>

    <!-- Category Tabs -->
    <div class="flex flex-wrap gap-3">
        @foreach(['client' => '👤 Client Folders', 'contract' => '📜 Contracts', 'letter' => '✉️ Letters'] as $tab => $label)
            <a href="{{ route('md.folders.index', ['type' => $tab]) }}" 
               class="px-8 py-4 rounded-2xl font-bold transition-all duration-300 shadow-sm border {{ $type === $tab ? 'bg-red-700 text-white border-red-700 shadow-red-900/20' : 'bg-white text-slate-500 border-slate-100 hover:border-red-700 hover:text-red-700 hover:bg-slate-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- Repository Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        @if(count($folders) > 0)
            @foreach($folders as $folder)
            <div class="premium-card p-8 rounded-3xl group hover:border-red-700 text-center space-y-6 flex flex-col items-center relative">
                <a href="{{ route('md.folders.show', $folder) }}" class="absolute inset-0 z-10 w-full h-full block"></a>
                <div class="w-20 h-20 bg-slate-50 rounded-2xl flex items-center justify-center text-4xl group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500 shadow-sm border border-slate-100 relative z-0">
                    @if($type === 'client') 👤 @elseif($type === 'contract') 📜 @else ✉️ @endif
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 group-hover:text-red-700 transition-colors text-lg">{{ $folder->name }}</h3>
                    <div class="mt-2 inline-block px-3 py-1 bg-slate-50 text-sm font-black text-slate-400 uppercase tracking-widest rounded-lg border border-slate-100">{{ $folder->year }} Archive</div>
                </div>
                <div class="pt-6 border-t border-slate-50 w-full flex justify-between items-center text-sm font-bold text-slate-400 px-2 italic relative z-20">
                    <span>{{ $folder->files_count ?? $folder->files->count() }} Managed Files</span>
                    
                    <div class="flex items-center space-x-3">
                        <form action="{{ route('md.folders.destroy', $folder) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this repository and all its managed files? This action cannot be undone.');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-slate-300 hover:text-red-700 transition-colors p-1" title="Delete Repository">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                        <a href="{{ route('md.folders.show', $folder) }}" class="red-700 not-italic font-black hover:underline">Open →</a>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="col-span-full py-32 text-center bg-white border border-dashed border-slate-200 rounded-3xl shadow-sm">
                <div class="text-5xl mb-6 grayscale opacity-30">📁</div>
                <p class="text-slate-400 font-medium italic text-lg">No repositories discovered in this category.</p>
                <div class="mt-8">
                    <button class="bg-red-700 text-white px-8 py-3.5 rounded-2xl font-bold shadow-lg shadow-red-900/20 hover:scale-105 transition-all">+ Initialize Repository</button>
                </div>
            </div>
        @endif
    </div>
</div>
</div>

<!-- Create Modal -->
<div id="createFolderModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white border border-slate-100 w-full max-w-md rounded-3xl overflow-hidden animate-in zoom-in duration-300 shadow-2xl">
        <div class="bg-slate-50 p-8 border-b border-slate-100">
            <h2 class="text-2xl font-bold tracking-tighter text-slate-900">Initialize <span class="red-700">Repository</span></h2>
            <p class="text-xs text-slate-400 font-medium mt-1">Setup a secure archival space for {{ $type }} intelligence.</p>
        </div>
        <form action="{{ route('md.folders.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
            
            <div class="space-y-2">
                <label class="text-sm font-black text-slate-400 uppercase tracking-widest ml-1">Folder Designation</label>
                <input type="text" name="name" required placeholder="e.g. {{ $type === 'client' ? 'Derrick Rutaremara' : 'Staff Contracts 2026' }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all font-bold placeholder:text-slate-300">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-black text-slate-400 uppercase tracking-widest ml-1">Fiscal Year Cycle</label>
                <input type="text" name="year" value="{{ date('Y') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all font-bold">
            </div>

            <div class="flex gap-4 pt-4">
                <button type="button" onclick="document.getElementById('createFolderModal').classList.add('hidden')" class="flex-1 bg-white border border-slate-200 text-slate-500 font-bold py-4 rounded-xl hover:bg-slate-50 transition-all">Abort</button>
                <button type="submit" class="flex-1 bg-red-700 hover:bg-red-800 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-red-900/20">Initialize</button>
            </div>
        </form>
    </div>
</div>
@endsection
