@extends('layouts.app')
@section('title', 'RRA File Vault')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-slate-900 italic">RRA <span class="text-red-700">File Vault</span></h1>
            <p class="text-slate-400 mt-1 text-xs font-bold tracking-[0.1em] uppercase opacity-70">Secure centralized document storage</p>
        </div>
        
        <div class="flex gap-4 w-full lg:w-auto items-center">
            <a href="{{ route('sales.rra.create') }}" class="bg-red-700 hover:bg-red-800 text-white px-6 py-2.5 rounded-lg text-base font-bold transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-900/40 text-center flex-1 lg:flex-none flex items-center justify-center">
                + Archive Document
            </a>
        </div>
    </div>

    <!-- Advanced Search & Filters -->
    <div class="bg-white/40 backdrop-blur-xl p-6 rounded-2xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        <form action="{{ route('sales.rra.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            
            <div class="col-span-1 lg:col-span-2 relative group">
                <label class="block text-sm font-black uppercase tracking-widest text-slate-400 mb-1">Search Description</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Keyword..." class="w-full bg-white/60 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-red-700 hover:border-red-700/50 transition-all pl-10 shadow-sm">
                <span class="absolute left-3 top-8 text-slate-400 group-focus-within:text-red-700 transition-colors">🔍</span>
            </div>
            
            <div>
                <label class="block text-sm font-black uppercase tracking-widest text-slate-400 mb-1">File Type</label>
                <select name="type_filter" class="w-full bg-white/60 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-red-700 shadow-sm cursor-pointer hover:border-red-700/50 transition-all">
                    <option value="">All Types</option>
                    <option value="ebm" {{ request('type_filter') == 'ebm' ? 'selected' : '' }}>EBM Returns</option>
                    <option value="ibm" {{ request('type_filter') == 'ibm' ? 'selected' : '' }}>IBM Filings</option>
                    <option value="rra_monthly" {{ request('type_filter') == 'rra_monthly' ? 'selected' : '' }}>RRA Monthly</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-black uppercase tracking-widest text-slate-400 mb-1">Fiscal Year</label>
                <select name="year" class="w-full bg-white/60 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-red-700 shadow-sm cursor-pointer hover:border-red-700/50 transition-all">
                    <option value="">All Years</option>
                    @for($i = date('Y'); $i >= 2020; $i--)
                        <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-sm font-black uppercase tracking-widest text-slate-400 mb-1">Month</label>
                <select name="month" class="w-full bg-white/60 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-red-700 shadow-sm cursor-pointer hover:border-red-700/50 transition-all">
                    <option value="">All Months</option>
                    @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $m)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-span-1 lg:col-span-5 flex gap-4 mt-2 justify-end">
                <button type="submit" class="bg-red-700 hover:bg-red-800 text-white px-8 py-2.5 rounded-lg text-sm font-bold transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-900/40">Apply Filters</button>
                @if(request()->anyFilled(['search', 'type_filter', 'year', 'month']))
                    <a href="{{ route('sales.rra.index') }}" class="bg-white/60 hover:bg-white text-slate-600 px-6 py-2.5 rounded-lg text-sm font-bold transition-all border border-slate-200">Clear</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Grid -->
    <div class="bg-white/40 backdrop-blur-xl border border-white/40 rounded-2xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="border-b border-white/40 bg-white/60 text-sm uppercase tracking-widest text-slate-400 font-black">
                        <th class="px-6 py-4">File Type</th>
                        <th class="px-6 py-4">Period</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4">Upload Date</th>
                        <th class="px-6 py-4">Archived By</th>
                        <th class="px-6 py-4 font-black"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/40">
                    @forelse ($files as $file)
                    <tr class="hover:bg-white/60 transition-colors duration-300 group">
                        <td class="px-6 py-4">
                            @if($file->type === 'ebm')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-black bg-slate-100 text-slate-600 uppercase tracking-widest border border-slate-200">EBM Return</span>
                            @elseif($file->type === 'ibm')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-black bg-red-50/80 text-red-500 uppercase tracking-widest border border-red-200/50">IBM Filing</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-black bg-slate-800 text-slate-100 uppercase tracking-widest border border-slate-900">RRA Monthly</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-black text-slate-900">{{ $file->month }} {{ $file->year }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-slate-700 max-w-xs truncate" title="{{ $file->description }}">{{ $file->description ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-xs font-semibold text-slate-700">{{ $file->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-slate-500 uppercase tracking-widest">{{ $file->uploader->name ?? 'System' }}</td>
                        <td class="px-6 py-4 text-right relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="text-slate-400 hover:text-red-700 transition-colors p-2 focus:outline-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" x-transition.opacity.scale.origin.top.right class="absolute right-8 top-10 w-48 bg-white/90 backdrop-blur-md border border-white/40 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] z-10 py-2" style="display: none;">
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="block px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-red-700 transition-colors">Download File</a>
                                
                                @role('admin')
                                <div class="border-t border-slate-200/50 my-1"></div>
                                <form action="{{ route('sales.rra.destroy', $file->id) }}" method="POST" onsubmit="return confirm('Confirm permanent deletion of this archived document?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm font-black text-red-700 hover:bg-red-50 transition-colors">Delete Document</button>
                                </form>
                                @endrole
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-semibold tracking-wide">
                            No archival records discovered in the vault.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
