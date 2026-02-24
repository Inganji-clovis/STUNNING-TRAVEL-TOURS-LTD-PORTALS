@extends('layouts.app')
@section('title', 'Invoice Management')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-slate-900">Invoice <span class="red-700 italic">Registry</span></h1>
            <p class="text-slate-400 mt-2 font-medium tracking-wide">Institutional billing and traveler financial records.</p>
        </div>
        
        <div class="flex items-center gap-4">
            @if($type === 'individual')
                <a href="{{ route('ops.invoice.create.individual') }}" class="bg-red-700 hover:bg-red-800 text-white px-8 py-3.5 rounded-xl font-bold shadow-lg shadow-red-900/20 transition-all text-center">
                    + Individual Invoice
                </a>
            @else
                <a href="{{ route('ops.invoice.create.organization') }}" class="bg-red-700 hover:bg-red-800 text-white px-8 py-3.5 rounded-xl font-bold shadow-lg shadow-red-900/20 transition-all text-center">
                    + Org Invoice
                </a>
            @endif
        </div>
    </div>

    <!-- Type Selection Sub-Nav -->
    <div class="flex items-center space-x-1 p-1.5 bg-slate-100 rounded-xl w-fit border border-slate-200 shadow-sm">
        <a href="{{ route('ops.invoice.index', ['type' => 'individual']) }}" 
           class="px-8 py-2.5 rounded-lg text-xs font-black uppercase tracking-widest transition-all {{ $type === 'individual' ? 'bg-white text-red-700 shadow-sm border border-slate-200' : 'text-slate-400 hover:text-slate-600' }}">
            Individual
        </a>
        <a href="{{ route('ops.invoice.index', ['type' => 'organization']) }}" 
           class="px-8 py-2.5 rounded-lg text-xs font-black uppercase tracking-widest transition-all {{ $type === 'organization' ? 'bg-white text-red-700 shadow-sm border border-slate-200' : 'text-slate-400 hover:text-slate-600' }}">
            Organization
        </a>
    </div>

    <!-- Dynamic Filtering -->
    <div class="premium-card p-6 rounded-2xl bg-slate-50/50">
        <form action="{{ route('ops.invoice.index') }}" method="GET" class="relative group">
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by client name or invoice number..." 
                   class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3.5 text-sm font-medium focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all pl-12 shadow-sm">
            <span class="absolute left-4 top-4 text-slate-300 group-focus-within:text-red-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <button type="submit" class="absolute right-3 top-2.5 bg-red-700 hover:bg-red-800 text-white px-6 py-2 rounded-lg text-xs font-bold transition-all shadow-sm">Search</button>
        </form>
    </div>

    <div class="premium-card rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 text-[0.65rem] uppercase tracking-[0.15em] text-slate-400 font-black">
                        <th class="px-8 py-6 border-b border-slate-100">Client Name</th>
                        @if($type === 'organization')
                        <th class="px-8 py-6 border-b border-slate-100">Organization</th>
                        @endif
                        <th class="px-8 py-6 border-b border-slate-100">Invoice #</th>
                        <th class="px-8 py-6 border-b border-slate-100">Amount</th>
                        <th class="px-8 py-6 border-b border-slate-100">Date</th>
                        <th class="px-8 py-6 border-b border-slate-100 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-slate-900">
                    @if($documents->count() > 0)
                        @foreach($documents as $doc)
                        <tr class="hover:bg-slate-50/50 transition-all duration-200 group">
                            <td class="px-8 py-6">
                                <div class="font-bold text-slate-900 group-hover:text-red-700 transition-colors">{{ $doc->client->name }}</div>
                            </td>
                            @if($type === 'organization')
                            <td class="px-8 py-6">
                                <span class="text-[0.65rem] font-bold px-2.5 py-1 bg-slate-100 text-slate-500 border border-slate-200 rounded uppercase tracking-tighter">{{ $doc->organization->name ?? 'PRIVATE AGENT' }}</span>
                            </td>
                            @endif
                            <td class="px-8 py-6 text-sm font-mono font-bold tracking-tighter text-slate-400">{{ $doc->document_number }}</td>
                            <td class="px-8 py-6">
                                <div class="flex items-baseline gap-1">
                                    <span class="font-black text-slate-900">{{ number_format($doc->amount) }}</span>
                                    <span class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest">{{ $doc->currency }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-[0.7rem] font-black text-slate-400 uppercase tracking-widest">{{ $doc->created_at->format('d M Y') }}</td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('ops.invoice.pdf', $doc) }}" target="_blank" class="p-2.5 bg-slate-50 hover:bg-red-700 hover:text-white rounded-xl transition-all shadow-sm" title="Generate PDF">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </a>
                                    <a href="{{ route('ops.invoice.edit', $doc) }}" class="p-2.5 bg-slate-50 hover:bg-sunset-orange hover:text-white rounded-xl transition-all shadow-sm" title="Modify">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="{{ $type === 'organization' ? '6' : '5' }}" class="px-8 py-32 text-center text-slate-400 italic">
                                <div class="flex flex-col items-center space-y-4">
                                    <div class="text-5xl grayscale opacity-30">📄</div>
                                    <p class="text-lg font-medium tracking-tight">Financial dossier directory is currently empty.</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
