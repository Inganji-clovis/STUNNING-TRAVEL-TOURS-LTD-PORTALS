@extends('layouts.app')
@section('title', 'System File Oversight')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-red-950">File <span class="red-700 italic">Oversight</span></h1>
            <p class="text-slate-400 mt-2 font-medium tracking-wide">Monitor and manage uploaded digital assets across all portals.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-800 px-6 py-4 rounded-xl font-bold text-sm border border-green-200">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-sunset-orange/10 border border-sunset-orange/20 text-sunset-orange px-6 py-4 rounded-xl font-bold text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- System File Search -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
        <form action="{{ route('admin.files.index') }}" method="GET" class="w-full flex space-x-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search files by name or directory path..." 
                       class="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-xl text-sm font-bold text-red-950 focus:ring-2 focus:ring-red-100 transition-all placeholder-slate-400" />
            </div>
            <button type="submit" class="bg-red-950 hover:bg-red-900 text-white px-8 py-3 rounded-xl font-bold uppercase tracking-widest text-xs transition-colors shadow-sm">
                Scan Storage
            </button>
            @if(request('search'))
                <a href="{{ route('admin.files.index') }}" class="px-6 py-3 rounded-xl font-bold uppercase tracking-widest text-xs transition-colors border border-slate-200 text-slate-500 hover:bg-slate-50 flex items-center justify-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <div class="premium-card rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-sm uppercase tracking-[0.15em] text-slate-400 font-extrabold">
                        <th class="px-8 py-5">File Identifier</th>
                        <th class="px-8 py-5">Storage Path</th>
                        <th class="px-8 py-5">Volume Size</th>
                        <th class="px-8 py-5">Last Modified</th>
                        <th class="px-8 py-5 text-right">Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($files as $file)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="font-bold text-red-950 flex items-center space-x-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                <span>{{ $file['name'] }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-sm font-extrabold uppercase tracking-widest bg-slate-100 text-slate-600 border border-slate-200">
                                {{ $file['directory'] === '.' ? 'Root' : $file['directory'] }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-xs font-bold text-slate-500">{{ number_format($file['size'] / 1024, 2) }} KB</span>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap">
                            <span class="text-xs font-bold text-slate-500">{{ \Carbon\Carbon::createFromTimestamp($file['last_modified'])->format('M d, Y H:i') }}</span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end items-center space-x-2">
                                <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" class="p-2.5 bg-slate-50 hover:bg-red-700 hover:text-white rounded-xl transition-all shadow-sm" title="View/Download File">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <form action="{{ route('admin.files.destroy', 'item') }}" method="POST" class="inline" onsubmit="return confirm('Permanently delete this storage asset? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="path" value="{{ $file['path'] }}">
                                    <button class="p-2.5 bg-slate-50 hover:bg-sunset-orange hover:text-white rounded-xl transition-all shadow-sm" title="Eradicate File">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4 border border-slate-100 shadow-sm">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-500">No storage assets found</h3>
                            <p class="text-xs font-medium text-slate-400 mt-1">The public storage directory is currently empty.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($files->hasPages())
        <div class="px-8 py-5 border-t border-slate-100 bg-white">
            {{ $files->appends(request()->query())->links('pagination::tailwind') }}
        </div>
        @endif
    </div>
</div>
@endsection
