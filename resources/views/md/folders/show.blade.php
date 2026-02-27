@extends('layouts.app')
@section('title', $folder->name . ' - Repository')

@section('content')
<div class="space-y-12 animate-in fade-in duration-1000">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6">
        <div>
            <a href="{{ route('md.folders.index', ['type' => $folder->type]) }}" class="text-slate-400 hover:red-700 transition-colors flex items-center space-x-2 mb-4 font-bold text-xs uppercase tracking-widest">
                <span>← Back to {{ ucfirst($folder->type) }}s</span>
            </a>
            <h1 class="text-4xl font-extrabold tracking-tighter text-slate-900">{{ $folder->name }}</h1>
            <p class="text-slate-400 mt-2 font-medium">Vault Repository | Category: <span class="red-700 capitalize">{{ $folder->type }}</span> | Year: {{ $folder->year }}</p>
        </div>
        
        <div class="flex gap-4">
            <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="bg-white border border-slate-200 px-8 py-3.5 rounded-xl hover:bg-slate-50 transition-all font-bold text-slate-600 shadow-sm">
                Import Files
            </button>
            <button onclick="document.getElementById('uploadModal').classList.remove('hidden')" class="bg-red-700 hover:bg-red-800 px-8 py-3.5 rounded-xl font-bold text-white transition-all shadow-lg shadow-red-900/20">
                + Archive File
            </button>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- File List -->
        <div class="lg:col-span-2 space-y-6">
            @if(count($folder->files) > 0)
                @foreach($folder->files as $file)
                <div class="premium-card p-8 rounded-2xl flex items-center justify-between group hover:border-red-700 transition-all">
                    <div class="flex items-center space-x-8">
                        <div class="h-16 w-16 bg-slate-50 rounded-2xl flex items-center justify-center text-3xl border border-slate-100 shadow-sm group-hover:rotate-3 transition-transform">
                            📄
                        </div>
                        <div>
                            <h4 class="font-bold text-xl text-slate-900 group-hover:text-red-700 transition-colors">{{ $file->name }}</h4>
                            <p class="text-sm text-slate-400 font-black uppercase tracking-[0.2em] mt-1">{{ $file->file_type }} • Uploaded by {{ $file->uploader->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('vault.access', ['type' => 'corporate-file', 'id' => $file->id]) }}" target="_blank" class="p-4 bg-slate-50 hover:bg-red-700 hover:text-white rounded-2xl transition-all shadow-sm group/btn" title="View">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </a>
                        <a href="{{ route('vault.access', ['type' => 'corporate-file', 'id' => $file->id]) }}" class="p-4 bg-slate-50 hover:bg-slate-800 hover:text-white rounded-2xl transition-all shadow-sm" title="Download">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </a>
                    </div>
                </div>
                @endforeach
            @else
            <div class="py-32 text-center bg-white border border-dashed border-slate-200 rounded-3xl shadow-sm">
                <div class="text-5xl mb-6 grayscale opacity-30">🔍</div>
                <p class="text-slate-400 font-medium italic text-lg">No intelligence discovered in this vault.</p>
            </div>
            @endif
        </div>

        <!-- Folder Insights -->
        <div class="space-y-8">
            <div class="premium-card p-10 rounded-3xl bg-gradient-to-br from-red-700/5 to-transparent relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-red-700/10 rounded-full blur-3xl"></div>
                <h3 class="font-extrabold text-2xl text-slate-900 mb-8 tracking-tighter">Vault <span class="red-700">Insights</span></h3>
                <div class="space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                        <span class="text-sm font-black text-slate-400 uppercase tracking-widest">Total Entries</span>
                        <span class="font-bold text-slate-900 tabular-nums">{{ $folder->files->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                        <span class="text-sm font-black text-slate-400 uppercase tracking-widest">Storage Status</span>
                        <span class="font-bold red-700 uppercase tracking-tighter italic">Optimized</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-black text-slate-400 uppercase tracking-widest">Archive Date</span>
                        <span class="font-bold text-slate-900">{{ $folder->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 w-full max-w-lg rounded-3xl overflow-hidden shadow-2xl animate-in zoom-in duration-300">
        <div class="bg-slate-50/50 p-12 border-b border-slate-100 text-center relative">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-12 w-24 h-24 bg-white rounded-full shadow-premium p-3 border border-slate-100">
                <img src="{{ asset('assets/brand/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-6 tracking-tighter underline decoration-red-700 decoration-4 underline-offset-8">Secure Archive</h2>
            <p class="text-slate-400 mt-4 font-medium uppercase text-sm tracking-[0.3em]">Document Ingestion System</p>
        </div>
        <form action="{{ route('md.folders.upload', $folder) }}" method="POST" enctype="multipart/form-data" class="p-12 space-y-8">
            @csrf
            
            <div class="space-y-3">
                <label class="text-sm font-black text-slate-400 uppercase tracking-widest ml-1">Archive Name</label>
                <input type="text" name="name" required placeholder="e.g. Passport - Clovis" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/10 outline-none transition-all font-bold placeholder:text-slate-300">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-3">
                    <label class="text-sm font-black text-slate-400 uppercase tracking-widest ml-1">Classification</label>
                    <select name="file_type" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 focus:border-red-700 outline-none transition-all font-bold appearance-none cursor-pointer">
                        @if($folder->type === 'client')
                            <option value="ticket">Ticket</option>
                            <option value="passport">Passport</option>
                            <option value="invoice">Invoice</option>
                            <option value="other">Other</option>
                        @else
                            <option value="{{ $folder->type }}">{{ ucfirst($folder->type) }}</option>
                            <option value="appendix">Appendix</option>
                        @endif
                    </select>
                </div>
                <div class="space-y-3">
                    <label class="text-sm font-black text-slate-400 uppercase tracking-widest ml-1">Binary Stream</label>
                    <input type="file" name="file" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 focus:border-red-700 outline-none transition-all text-sm font-black">
                </div>
            </div>

            <div class="flex gap-4 pt-4 border-t border-slate-50 mt-4">
                <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')" class="flex-1 bg-white border border-slate-100 hover:bg-slate-50 text-slate-500 font-black py-5 rounded-xl transition-all uppercase tracking-widest text-xs">Abort</button>
                <button type="submit" class="flex-1 bg-red-700 hover:bg-red-800 text-white font-black py-5 rounded-xl transition-all shadow-lg shadow-red-900/20 uppercase tracking-widest text-xs">Execute Upload</button>
            </div>
        </form>
    </div>
</div>

<!-- Import Files Modal -->
<div id="importModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 w-full max-w-4xl rounded-3xl overflow-hidden shadow-2xl animate-in zoom-in duration-300">
        <div class="bg-slate-50/50 p-10 border-b border-slate-100 text-center relative flex justify-between items-center">
            <div class="flex items-center space-x-4 text-left">
                <div class="w-16 h-16 bg-white rounded-2xl shadow-premium p-2 border border-slate-100">
                    <img src="{{ asset('assets/brand/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tighter">Import Intelligence</h2>
                    <p class="text-slate-400 font-medium uppercase text-sm tracking-[0.3em]">Cross-Portal Data Extraction</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="text-slate-400 hover:text-red-700 transition-colors bg-white p-3 rounded-xl border border-slate-100 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="{{ route('md.folders.import', $folder) }}" method="POST" enctype="multipart/form-data" class="flex flex-col h-[60vh] max-h-[600px]">
            @csrf
            <div class="flex-1 overflow-y-auto p-10 space-y-8 custom-scrollbar">
                
                <!-- Physical File Upload -->
                <div class="p-6 bg-slate-50/50 rounded-2xl border border-slate-100 space-y-3">
                    <label class="text-sm font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        Direct Local Upload (Optional)
                    </label>
                    <input type="file" name="files[]" multiple class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 focus:border-red-700 outline-none transition-all text-xs font-medium text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                </div>

                <!-- Cross Portal Checkboxes -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                        <label class="text-sm font-black text-slate-900 tracking-tight flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            Available Cross-Portal Files
                        </label>
                        <span class="text-sm font-black text-slate-400 uppercase tracking-widest bg-slate-100 px-3 py-1 rounded-full">{{ count($availableFiles) }} Records Found</span>
                    </div>

                    @if(count($availableFiles) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pb-8">
                            @foreach($availableFiles as $f)
                            <label class="flex items-start gap-4 p-4 rounded-xl border border-slate-200 bg-white hover:border-red-400 hover:shadow-md transition-all cursor-pointer group">
                                <div class="relative flex items-center justify-center mt-1">
                                    <input type="checkbox" name="cross_portal_files[]" value="{{ $f['source'] }}||{{ $f['name'] }}||{{ $f['path'] }}" class="peer appearance-none w-5 h-5 border-2 border-slate-300 rounded-md checked:bg-red-700 checked:border-red-700 transition-all cursor-pointer">
                                    <svg class="w-3.5 h-3.5 text-white absolute pointer-events-none opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-bold text-slate-900 group-hover:text-red-700 transition-colors truncate" title="{{ $f['name'] }}">{{ $f['name'] }}</h4>
                                    <p class="text-sm text-slate-400 font-bold uppercase tracking-wider mt-0.5 truncate">{{ $f['source'] }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 bg-slate-50 border border-dashed border-slate-200 rounded-2xl">
                            <p class="text-slate-500 font-medium italic text-sm">No cross-portal intelligence records detected.</p>
                        </div>
                    @endif
                </div>

            </div>

            <div class="p-6 bg-slate-50 border-t border-slate-100 flex gap-4">
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="flex-1 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 font-black py-4 rounded-xl transition-all uppercase tracking-widest text-xs">Cancel Request</button>
                <button type="submit" class="flex-1 bg-red-700 hover:bg-red-800 text-white font-black py-4 rounded-xl transition-all shadow-lg shadow-red-900/20 uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Execute Total Import
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
