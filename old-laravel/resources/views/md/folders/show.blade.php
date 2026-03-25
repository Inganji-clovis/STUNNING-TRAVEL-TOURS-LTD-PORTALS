@extends('layouts.app')
@section('title', $folder->name . ' - Repository')

@section('content')
<div class="space-y-12 animate-in fade-in duration-1000">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div>
            <a href="{{ route('md.folders.index', ['type' => $folder->type]) }}" class="text-slate-400 hover:text-red-600 transition-colors flex items-center gap-2 mb-4 font-bold text-[10px] uppercase tracking-[0.15em]">
                Ã¢â€ Â Back to {{ ucfirst($folder->type) }}s
            </a>
            <h1 class="text-4xl font-extrabold tracking-tighter text-[#334155]">{{ $folder->name }}</h1>
            <p class="text-[12px] font-bold text-slate-400 mt-2 tracking-[0.15em] uppercase">Vault Repository | Category: <span class="text-red-600">{{ $folder->type }}</span> | Year: {{ $folder->year }}</p>
        </div>
        
        <div class="flex flex-wrap gap-4">
            <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="bg-white border border-slate-200 px-8 py-3.5 rounded-2xl hover:bg-slate-50 transition-all font-extrabold text-slate-600 shadow-sm">
                Import Files
            </button>
            <button onclick="document.getElementById('uploadModal').classList.remove('hidden')" class="bg-red-600 hover:bg-red-700 px-8 py-3.5 rounded-2xl font-extrabold text-white transition-all shadow-lg shadow-red-600/20">
                + Archive File
            </button>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- File List -->
        <div class="lg:col-span-2 space-y-4">
            @if(count($folder->files) > 0)
                @foreach($folder->files as $file)
                <div class="premium-card p-6 flex flex-col md:flex-row items-start md:items-center justify-between group hover:border-red-200 transition-all gap-4">
                    <div class="flex items-center gap-6">
                        <div class="h-14 w-14 bg-slate-50 rounded-2xl flex items-center justify-center text-2xl border border-slate-100 shadow-sm group-hover:rotate-3 transition-transform shrink-0">
                            Ã°Å¸â€œâ€ž
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-extrabold text-[18px] text-[#334155] group-hover:text-red-600 transition-colors truncate">{{ $file->name }}</h4>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.15em] mt-1 truncate">{{ $file->file_type }} Ã¢â‚¬Â¢ Uploaded by {{ $file->uploader->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0 w-full md:w-auto mt-4 md:mt-0 pt-4 md:pt-0 border-t border-slate-100/50 md:border-0 justify-end">
                        <a href="{{ route('vault.access', ['type' => 'corporate-file', 'id' => $file->id]) }}" target="_blank" class="p-4 bg-slate-50 hover:bg-red-600 hover:text-white rounded-2xl transition-all shadow-sm text-slate-500" title="View">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </a>
                        <a href="{{ route('vault.access', ['type' => 'corporate-file', 'id' => $file->id]) }}" class="p-4 bg-slate-50 hover:bg-red-600 hover:text-white rounded-2xl transition-all shadow-sm text-slate-500" title="Download">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </a>
                    </div>
                </div>
                @endforeach
            @else
            <div class="py-24 text-center bg-slate-50/50 border-2 border-dashed border-slate-200/50 rounded-[32px]">
                <div class="w-20 h-20 bg-white rounded-3xl shadow-sm flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <p class="text-[12px] text-slate-400 font-bold uppercase tracking-[0.15em]">No intelligence discovered in this vault.</p>
            </div>
            @endif
        </div>

        <!-- Folder Insights -->
        <div class="space-y-8">
            <div class="premium-card p-10 bg-gradient-to-br from-red-50/50 to-transparent relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-red-600/5 rounded-full blur-3xl"></div>
                <h3 class="font-extrabold text-[22px] text-[#334155] mb-8 tracking-tighter relative z-10">Vault Insights</h3>
                <div class="space-y-6 relative z-10">
                    <div class="flex justify-between items-center border-b border-slate-100/50 pb-4">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em]">Total Entries</span>
                        <span class="font-extrabold text-[18px] text-[#334155] tabular-nums">{{ $folder->files->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-100/50 pb-4">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em]">Storage Status</span>
                        <span class="font-black text-[10px] text-emerald-600 uppercase tracking-[0.15em]">Optimized</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em]">Archive Date</span>
                        <span class="font-extrabold text-[#334155]">{{ $folder->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[60] hidden flex items-center justify-center p-4">
    <div class="bg-white border border-slate-100 w-full max-w-lg rounded-[32px] overflow-hidden shadow-2xl animate-in zoom-in duration-300 p-2">
        <div class="bg-slate-50 border border-slate-100/50 p-12 rounded-[24px] text-center relative mb-2">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-12 w-24 h-24 bg-white rounded-full shadow-lg shadow-slate-200/50 p-3 border border-slate-100">
                <img src="{{ asset('assets/brand/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <h2 class="text-[28px] font-extrabold text-[#334155] mt-6 tracking-tighter decoration-red-600 decoration-4 underline-offset-8 underline">Secure Archive</h2>
            <p class="text-[10px] text-slate-400 font-black mt-3 uppercase tracking-[0.25em]">Document Ingestion System</p>
        </div>
        <form action="{{ route('md.folders.upload', $folder) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
            @csrf
            
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em] ml-1">Archive Name</label>
                <input type="text" name="name" required placeholder="e.g. Passport - Clovis" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 focus:border-red-400 focus:ring-4 focus:ring-red-100 outline-none transition-all font-bold placeholder:text-slate-300 text-[#334155]">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em] ml-1">Classification</label>
                    <select name="file_type" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 focus:border-red-400 outline-none transition-all font-bold appearance-none cursor-pointer text-[#334155]">
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
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em] ml-1">Binary Stream</label>
                    <input type="file" name="file" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3.5 focus:border-red-400 outline-none transition-all text-sm font-bold text-[#334155]">
                </div>
            </div>

            <div class="flex gap-4 pt-4 border-t border-slate-100/50">
                <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')" class="flex-1 bg-white border border-slate-200 hover:bg-slate-50 text-slate-500 font-extrabold py-4 rounded-2xl transition-all uppercase tracking-[0.15em] text-[10px]">Abort</button>
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-extrabold py-4 rounded-2xl transition-all shadow-lg shadow-red-600/20 uppercase tracking-[0.15em] text-[10px]">Execute Upload</button>
            </div>
        </form>
    </div>
</div>

<!-- Import Files Modal -->
<div id="importModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[60] hidden flex items-center justify-center p-4">
    <div class="bg-white border border-slate-100 w-full max-w-4xl rounded-[32px] overflow-hidden shadow-2xl animate-in zoom-in duration-300 p-2">
        <div class="bg-slate-50/50 p-8 rounded-[24px] border border-slate-100/50 relative flex justify-between items-center mb-2">
            <div class="flex items-center space-x-6 text-left">
                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm p-2 border border-slate-100">
                    <img src="{{ asset('assets/brand/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-[#334155] tracking-tighter">Import Intelligence</h2>
                    <p class="text-slate-400 font-black uppercase text-[10px] tracking-[0.2em] mt-1">Cross-Portal Data Extraction</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="text-slate-400 hover:text-red-600 transition-colors bg-white p-3 rounded-2xl border border-slate-100 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="{{ route('md.folders.import', $folder) }}" method="POST" enctype="multipart/form-data" class="flex flex-col h-[60vh] max-h-[600px] bg-white rounded-b-[24px]">
            @csrf
            <div class="flex-1 overflow-y-auto p-10 space-y-8 custom-scrollbar">
                
                <!-- Physical File Upload -->
                <div class="p-8 bg-slate-50/50 rounded-2xl border border-slate-100 space-y-4">
                    <label class="text-[10px] font-bold text-[#334155] uppercase tracking-[0.15em] flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        Direct Local Upload (Optional)
                    </label>
                    <input type="file" name="files[]" multiple class="w-full bg-white border border-slate-200 rounded-2xl px-4 py-3 focus:border-red-400 outline-none transition-all text-sm font-bold text-slate-600 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-[10px] file:font-extrabold file:tracking-widest file:uppercase file:bg-red-50 file:text-red-600 hover:file:bg-red-100">
                </div>

                <!-- Cross Portal Checkboxes -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <label class="text-[12px] font-extrabold text-[#334155] tracking-[0.1em] uppercase flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            Available Cross-Portal Files
                        </label>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-xl">{{ count($availableFiles) }} Records Found</span>
                    </div>

                    @if(count($availableFiles) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pb-8">
                            @foreach($availableFiles as $f)
                            <label class="flex items-start gap-4 p-5 rounded-2xl border border-slate-200 bg-white hover:border-red-300 hover:shadow-md transition-all cursor-pointer group">
                                <div class="relative flex items-center justify-center mt-1">
                                    <input type="checkbox" name="cross_portal_files[]" value="{{ $f['source'] }}||{{ $f['name'] }}||{{ $f['path'] }}" class="peer appearance-none w-5 h-5 border-2 border-slate-300 rounded-lg checked:bg-red-600 checked:border-red-600 transition-all cursor-pointer">
                                    <svg class="w-3.5 h-3.5 text-white absolute pointer-events-none opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-[14px] font-extrabold text-[#334155] group-hover:text-red-600 transition-colors truncate" title="{{ $f['name'] }}">{{ $f['name'] }}</h4>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.15em] mt-1 truncate">{{ $f['source'] }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-slate-50/50 border border-dashed border-slate-200/50 rounded-3xl">
                            <p class="text-slate-400 font-bold tracking-[0.15em] uppercase text-[10px]">No cross-portal intelligence records detected.</p>
                        </div>
                    @endif
                </div>

            </div>

            <div class="p-8 border-t border-slate-100/50 flex gap-4 bg-white rounded-b-[24px]">
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="flex-1 bg-white border border-slate-200 hover:bg-slate-50 text-slate-500 font-extrabold py-4 rounded-2xl transition-all uppercase tracking-[0.15em] text-[10px]">Cancel Request</button>
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-extrabold py-4 rounded-2xl transition-all shadow-lg shadow-red-600/20 uppercase tracking-[0.15em] text-[10px] flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Execute Total Import
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
