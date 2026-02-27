@extends('layouts.app')
@section('title', 'Corporate Vault')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-red-500 tracking-tight">Corporate Document Vault</h1>
            <p class="text-slate-400">Secure access to company critical documents</p>
        </div>
        <a href="{{ route('md.vault.create') }}" class="bg-red-600 hover:bg-red-700 px-6 py-3 rounded-xl font-semibold transition-all">Upload New Document</a>
    </div>

    <!-- Search Bar -->
    <div class="glass border border-slate-200 p-4 rounded-2xl">
        <form action="{{ route('md.vault.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1 relative group">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title, category or description..." class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-red-500 transition-all pl-10">
                <span class="absolute left-3 top-2.5 text-slate-500 group-focus-within:text-red-500 transition-colors">🔍</span>
            </div>
            <button type="submit" class="bg-slate-100 hover:bg-slate-200 px-6 py-2 rounded-xl text-sm font-semibold transition-all border border-slate-300">Filter</button>
            @if(request('search'))
                <a href="{{ route('md.vault.index') }}" class="bg-red-500/10 text-red-500 px-4 py-2 rounded-xl text-sm font-semibold transition-all border border-red-500/20">Clear</a>
            @endif
        </form>
    </div>

    <div class="glass border border-slate-200 rounded-3xl overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-slate-200 bg-white/50">
                    <th class="px-6 py-4 text-xs uppercase tracking-wider text-slate-500 font-semibold">Document Title</th>
                    <th class="px-6 py-4 text-xs uppercase tracking-wider text-slate-500 font-semibold">Category</th>
                    <th class="px-6 py-4 text-xs uppercase tracking-wider text-slate-500 font-semibold">Uploaded By</th>
                    <th class="px-6 py-4 text-xs uppercase tracking-wider text-slate-500 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @if($documents->count() > 0)
                    @foreach($documents as $doc)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-medium flex items-center space-x-3">
                                <span class="p-2 bg-slate-50 border border-slate-200 rounded-lg">📄</span>
                                <span>{{ $doc->title }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-white border border-slate-200">
                                {{ strtoupper($doc->category) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            {{ $doc->uploader->name }}
                            <div class="text-sm text-slate-500 uppercase">{{ $doc->created_at->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('vault.access', ['type' => 'corporate', 'id' => $doc->id]) }}" target="_blank" class="text-xs text-red-500 hover:underline">Secure Download</a>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center text-slate-400 italic">Vault is empty. No corporate documents archived yet.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
