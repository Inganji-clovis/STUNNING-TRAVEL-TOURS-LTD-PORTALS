@extends('layouts.app')
@section('title', 'EBM Management')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-slate-900 italic">EBM <span class="text-red-700">Registry</span></h1>
            <p class="text-slate-400 mt-1 text-xs font-bold tracking-[0.1em] uppercase opacity-70">Electronic Billing Machine compliance & archival</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto items-center">
            <form action="{{ route('sales.ebm.index') }}" method="GET" class="relative group flex-1 sm:flex-none">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search TIN or Company..." class="w-full sm:w-80 bg-white/60 backdrop-blur-md border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-red-700 transition-all pl-10 shadow-sm placeholder:text-slate-400">
                <span class="absolute left-3 top-2.5 text-slate-400 group-focus-within:text-red-700 transition-colors">🔍</span>
            </form>
            <a href="{{ route('sales.ebm.create') }}" class="bg-red-700 hover:bg-red-800 text-white px-6 py-2.5 rounded-lg text-base font-bold transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-900/40 text-center flex items-center justify-center backdrop-blur-md w-full sm:w-auto">
                + New Record
            </a>
        </div>
    </div>

    <!-- Registry Table -->
    <div class="bg-white/40 backdrop-blur-xl border border-white/40 rounded-2xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="border-b border-white/40 bg-white/60 text-sm uppercase tracking-widest text-slate-400 font-black">
                        <th class="px-6 py-4">EBM Reference</th>
                        <th class="px-6 py-4">Managing Entity</th>
                        <th class="px-6 py-4">TIN</th>
                        <th class="px-6 py-4">Linked Invoice</th>
                        <th class="px-6 py-4 text-right">Transaction Value</th>
                        <th class="px-6 py-4 text-right">VAT Amount</th>
                        <th class="px-6 py-4">Submission Date</th>
                        <th class="px-6 py-4">Archived By</th>
                        <th class="px-6 py-4 text-right">Preview</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/40">
                    @forelse ($ebms as $ebm)
                    <tr class="hover:bg-white/60 transition-colors duration-300 group">
                        <td class="px-6 py-4 font-bold text-slate-900">EBM-{{ str_pad($ebm->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4 font-bold text-slate-900 text-sm uppercase tracking-widest">{{ $ebm->company_name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-white/60 border border-slate-200 text-sm font-black text-slate-600 rounded uppercase tracking-widest">{{ $ebm->tin_number }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($ebm->invoice)
                                <a href="#" class="text-sm font-bold text-red-700 hover:text-red-800 transition-colors underline decoration-red-700/30 underline-offset-4 hover:decoration-red-700">{{ $ebm->invoice->document_number }}</a>
                            @else
                                <span class="text-sm text-slate-400 font-black uppercase tracking-widest">Unlinked</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right font-black text-slate-900 tabular-nums">{{ number_format($ebm->amount_rwf) }} RWF</td>
                        <td class="px-6 py-4 text-right font-bold text-slate-500 tabular-nums">{{ number_format($ebm->vat_amount ?? 0) }} RWF</td>
                        <td class="px-6 py-4 text-xs font-semibold text-slate-700">{{ \Carbon\Carbon::parse($ebm->date)->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-slate-500 uppercase tracking-widest">{{ $ebm->uploader->name ?? 'System' }}</td>
                        <td class="px-6 py-4 text-right" x-data>
                            <button @click="$dispatch('open-preview', { url: '{{ asset('storage/' . $ebm->file_path) }}', type: '{{ strtolower(pathinfo($ebm->file_path, PATHINFO_EXTENSION)) }}' })" 
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 hover:bg-red-50 text-slate-400 hover:text-red-700 transition-all shadow-sm hover:shadow-md hover:shadow-red-900/10" 
                                title="Quick Preview">
                                👁️
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-slate-500 font-semibold tracking-wide">
                            No EBM records discovered in this cycle.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $ebms->links() }}
    </div>

    <!-- Quick Preview Modal -->
    <div x-data="{ open: false, url: '', ext: '' }" 
         x-show="open" 
         @open-preview.window="open = true; url = $event.detail.url; ext = $event.detail.type;"
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         style="display: none;">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" 
             x-show="open" 
             x-transition.opacity 
             @click="open = false"></div>

        <!-- Modal -->
        <div class="bg-white/90 backdrop-blur-xl border border-white/40 rounded-2xl shadow-2xl overflow-hidden w-full max-w-4xl relative z-10 transform transition-all flex flex-col h-[80vh]"
             x-show="open" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 scale-95 translate-y-4" 
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200/50 bg-white/50">
                <h3 class="text-lg font-black text-slate-900 italic">Document <span class="text-red-700">Preview</span></h3>
                <div class="flex items-center gap-4">
                    <a :href="url" target="_blank" class="text-sm font-black uppercase tracking-widest text-slate-400 hover:text-red-700 transition-colors">Open in New Tab</a>
                    <button @click="open = false" class="text-slate-400 hover:text-red-700 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-auto bg-slate-50/50 p-6 flex items-center justify-center">
                <template x-if="ext === 'pdf'">
                    <iframe :src="url" class="w-full h-full rounded shadow-sm border border-slate-200/50"></iframe>
                </template>
                <template x-if="ext === 'jpg' || ext === 'jpeg' || ext === 'png'">
                    <img :src="url" class="max-w-full max-h-full rounded shadow-sm border border-slate-200/50 object-contain" alt="EBM Document">
                </template>
                <template x-if="ext !== 'pdf' && ext !== 'jpg' && ext !== 'jpeg' && ext !== 'png'">
                    <div class="text-center">
                        <p class="text-sm font-bold text-slate-500 mb-2">Preview not available for this file type.</p>
                        <a :href="url" download class="inline-block bg-slate-900 text-white px-4 py-2 rounded text-sm font-bold shadow hover:bg-black transition-colors">Download File</a>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection