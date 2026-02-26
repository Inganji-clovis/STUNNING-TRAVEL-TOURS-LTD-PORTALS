@extends('layouts.app')
@section('title', 'Cloud Sync Command Center')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-3">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-slate-900 italic">Cloud <span class="text-red-700">Sync</span></h1>
            <p class="text-slate-400 mt-1 text-xs font-bold tracking-[0.1em] uppercase opacity-70">Secondary Google Drive File Recovery System</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-xl flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <span class="text-emerald-500 text-xl font-bold">✓</span>
                <span class="font-semibold text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <span class="text-red-500 text-xl font-bold">⚠</span>
                <span class="font-semibold text-sm">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Trigger Console -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="col-span-1 glass border border-slate-200 p-8 rounded-3xl shadow-sm">
            <h2 class="text-lg font-black tracking-tighter text-slate-800 mb-6">Trigger Macro-Sync</h2>
            
            <form action="{{ route('admin.cloud-sync.store') }}" method="POST" class="space-y-6" onsubmit="return confirm('This will bundle all generated files from the selected month and stream them to Google Drive. Proceed?');">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">Select Year</label>
                    <select name="year" required class="w-full bg-white/60 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold focus:border-red-700 outline-none transition-all shadow-sm">
                        @for($y = date('Y') + 1; $y >= 2024; $y--)
                            <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">Select Month</label>
                    <select name="month" required class="w-full bg-white/60 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold focus:border-red-700 outline-none transition-all shadow-sm">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ sprintf('%02d', $m) }}" {{ date('m') == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl text-xs text-slate-500 font-medium">
                    This engine will extract Invoices, Tickets, EBM Receipts, and Tax files and hierarchically organize them by Client Name.
                </div>

                <button type="submit" class="w-full bg-red-700 hover:bg-red-800 text-white font-black uppercase tracking-widest text-sm py-4 rounded-xl transition-all shadow-lg shadow-red-900/20 hover:-translate-y-0.5">
                    Start Secure Sync
                </button>
            </form>
        </div>

        <div class="col-span-1 lg:col-span-2 glass border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white/40">
                <h2 class="text-lg font-black tracking-tighter text-slate-800">Sync Ledger</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-200">Target Period</th>
                            <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-200">Triggered By</th>
                            <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-200 text-center">Status</th>
                            <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-200">Execution Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white/40">
                        @forelse($syncs as $sync)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-bold text-slate-800">{{ date("F", mktime(0, 0, 0, $sync->month, 10)) }} {{ $sync->year }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-600">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-red-100 text-red-700 flex items-center justify-center text-xs font-black uppercase">
                                            {{ substr($sync->admin->name ?? '?', 0, 1) }}
                                        </div>
                                        {{ $sync->admin->name ?? 'System' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($sync->status === 'completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-black bg-emerald-50 text-emerald-600 uppercase tracking-widest border border-emerald-200/50">Completed</span>
                                    @elseif($sync->status === 'failed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-black bg-red-50 text-red-600 uppercase tracking-widest border border-red-200/50" title="{{ $sync->error_log }}">Failed</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-black bg-amber-50 text-amber-600 uppercase tracking-widest border border-amber-200/50">Processing</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-slate-500">{{ $sync->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">☁️</div>
                                    <p class="text-slate-500 text-sm font-bold">No synchronization records found in the ledger.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
