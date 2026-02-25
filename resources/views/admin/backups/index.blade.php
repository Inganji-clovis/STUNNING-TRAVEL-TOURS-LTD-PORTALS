@extends('layouts.app')
@section('title', 'Backup Management')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-red-950">Backup <span class="red-700 italic">Management</span></h1>
            <p class="text-slate-400 mt-2 font-medium tracking-wide">Generate and manage system-wide data snapshots.</p>
        </div>
        <form action="{{ route('admin.backups.create') }}" method="POST" onsubmit="return confirm('Initiate a new database backup? This may take a few moments.');">
            @csrf
            <button type="submit" class="bg-red-700 hover:bg-red-900 text-white px-8 py-3.5 rounded-xl font-bold shadow-lg shadow-red-900/20 transition-all flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                <span>Generate Snapshot</span>
            </button>
        </form>
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

    <div class="premium-card rounded-3xl overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-100/50 bg-slate-50/50 flex justify-between items-center">
            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-[0.2em]">Archived Snapshots</h3>
            <span class="text-xs font-bold text-slate-400">{{ count($backups) }} Archives Available</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-sm uppercase tracking-[0.15em] text-slate-400 font-extrabold">
                        <th class="px-8 py-5">Archive Identifier</th>
                        <th class="px-8 py-5">Volume Size</th>
                        <th class="px-8 py-5">Generation Time</th>
                        <th class="px-8 py-5 text-right">Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($backups as $backup)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="font-bold text-red-950 flex items-center space-x-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                <span class="font-mono text-sm">{{ $backup['file_name'] }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-sm font-extrabold uppercase tracking-widest bg-slate-100 text-slate-600 border border-slate-200">
                                {{ number_format($backup['file_size'] / 1048576, 2) }} MB
                            </span>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap">
                            <span class="text-sm font-bold text-slate-600">{{ \Carbon\Carbon::createFromTimestamp($backup['last_modified'])->format('M d, Y') }}</span>
                            <span class="text-sm font-extrabold text-slate-400 ml-1">{{ \Carbon\Carbon::createFromTimestamp($backup['last_modified'])->format('H:i') }}</span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end items-center space-x-2">
                                <a href="{{ route('admin.backups.download', $backup['file_name']) }}" class="p-2.5 bg-slate-50 hover:bg-red-700 hover:text-white rounded-xl transition-all shadow-sm group-hover:bg-white" title="Download Archive">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </a>
                                <form action="{{ route('admin.backups.restore', $backup['file_name']) }}" method="POST" class="inline" onsubmit="return confirm('Eradicate this snapshot completely?');">
                                    @csrf
                                    <button class="p-2.5 bg-slate-50 hover:bg-sunset-orange hover:text-white rounded-xl transition-all shadow-sm group-hover:bg-white" title="Eradicate Archive">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4 border border-slate-100 shadow-sm">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-500">No archives configured</h3>
                            <p class="text-xs font-medium text-slate-400 mt-1">Initiate a snapshot to begin securing system data.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
