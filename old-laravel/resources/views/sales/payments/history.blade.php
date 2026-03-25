@extends('layouts.app')
@section('title', 'Payment History')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end bg-white/40 backdrop-blur-xl p-8 rounded-2xl border border-white/40 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-full text-slate-600 font-bold text-sm shadow-sm mb-4 border border-slate-100">
                <span>⏱️</span> Audit Log
            </div>
            <h1 class="text-3xl font-black tracking-tighter text-slate-900 italic">Payment <span class="text-red-700">History</span></h1>
            <p class="text-slate-500 mt-2 text-sm font-bold tracking-widest uppercase opacity-80">Reference: {{ $payment->invoice->document_number ?? 'D-'.str_pad($payment->billing_document_id, 4, '0', STR_PAD_LEFT) }}</p>
        </div>
        
        <div class="flex gap-4 mt-6 lg:mt-0 items-center">
            <a href="{{ route('sales.payments.index') }}" class="bg-white hover:bg-slate-50 text-slate-700 px-6 py-3 rounded-xl border border-slate-200 text-sm font-bold transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-900/5 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Registry
            </a>
        </div>
    </div>

    <!-- Active Payment Context -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-6 bg-white/60 border border-slate-200/50 rounded-xl">
            <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-2">Client</p>
            <h3 class="text-lg font-black text-slate-900">{{ $payment->client->name }}</h3>
        </div>
        <div class="p-6 bg-white/60 border border-slate-200/50 rounded-xl">
            <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-2">Transaction Value</p>
            <h3 class="text-lg font-black text-slate-900 tabular-nums">{{ number_format($payment->paid_amount) }} <span class="text-xs text-slate-400 font-bold ml-1">{{ $payment->currency }}</span></h3>
        </div>
        <div class="p-6 bg-white/60 border border-slate-200/50 rounded-xl">
            <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-2">Recorded By</p>
            <h3 class="text-lg font-black text-slate-900">{{ $payment->recorder->name ?? 'System' }}</h3>
        </div>
    </div>

    <!-- Timeline Wrapper -->
    <div class="bg-white/40 backdrop-blur-xl border border-white/40 rounded-2xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8">
        <h2 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-8 border-b border-white/40 pb-4">Activity Timeline</h2>
        
        <div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
            @forelse($activities as $activity)
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                <!-- Icon marker -->
                <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-slate-100 text-slate-500 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                    @if($activity->action === 'Create')
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    @elseif($activity->action === 'Update')
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    @elseif($activity->action === 'Delete')
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    @else
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @endif
                </div>
                
                <!-- Event Content -->
                <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white/80 p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group-hover:-translate-y-1 duration-300">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[10px] font-black uppercase tracking-widest {{ $activity->action === 'Create' ? 'text-emerald-600' : ($activity->action === 'Delete' ? 'text-red-600' : 'text-blue-600') }}">
                            {{ $activity->action }}
                        </span>
                        <time class="text-xs font-bold text-slate-400">{{ $activity->created_at->diffForHumans() }}</time>
                    </div>
                    <p class="text-sm font-semibold text-slate-700 mt-2 leading-relaxed">
                        {{ $activity->description }}
                    </p>
                    <div class="mt-4 flex items-center gap-2 pt-4 border-t border-slate-100/60">
                        <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-600">
                            {{ substr($activity->user->name ?? '?', 0, 1) }}
                        </div>
                        <span class="text-xs font-bold text-slate-500">{{ $activity->user->name ?? 'System' }}</span>
                        <span class="text-[10px] text-slate-400 ml-auto">{{ \Carbon\Carbon::parse($activity->created_at)->format('M d, Y H:i') }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <p class="text-slate-400 font-bold uppercase tracking-widest text-sm">No activity records found for this payment reference.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
