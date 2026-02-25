@extends('layouts.app')
@section('title', 'Executive Overview')

@section('content')
<div class="space-y-8 animate-in fade-in duration-1000">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-slate-900 italic">MD <span class="red-700">Dashboard</span></h1>
            <p class="text-slate-400 mt-1 text-xs font-bold tracking-[0.1em] uppercase opacity-70">Executive Monitoring & Group Strategy</p>
        </div>
        
        <form action="{{ route('md.dashboard') }}" method="GET" class="flex flex-wrap items-center gap-4 bg-white p-2 rounded-2xl shadow-premium border border-slate-100 mt-4 lg:mt-0">
            <div class="flex items-center">
                <span class="text-sm font-black text-slate-400 uppercase tracking-[0.2em] ml-3 mr-2">Dept</span>
                <select name="portal" onchange="this.form.submit()" class="bg-slate-50 border-none rounded-xl px-4 py-2 text-xs font-bold text-slate-700 outline-none cursor-pointer">
                    <option value="">All Divisions</option>
                    <option value="Sales" {{ request('portal') == 'Sales' ? 'selected' : '' }}>Sales</option>
                    <option value="Operations" {{ request('portal') == 'Operations' ? 'selected' : '' }}>Operations</option>
                </select>
            </div>
            
            <div class="flex items-center">
                <span class="text-sm font-black text-slate-400 uppercase tracking-[0.2em] ml-2 mr-2">Staff</span>
                <select name="employee_id" onchange="this.form.submit()" class="bg-slate-50 border-none rounded-xl px-4 py-2 text-xs font-bold text-slate-700 outline-none cursor-pointer">
                    <option value="">All Staff</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center border-l border-slate-100 pl-4">
                <span class="text-sm font-black text-slate-400 uppercase tracking-[0.2em] mr-2">Date</span>
                <input type="date" name="date" value="{{ request('date', $date) }}" onchange="this.form.submit()" class="bg-slate-50 border-none rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-red-700/20 outline-none transition-all cursor-pointer">
            </div>
        </form>
    </div>

    <!-- Portal Activity Registry -->
    <div class="premium-card rounded-[2rem] overflow-hidden">
        <div class="p-6 border-b border-white/30 flex justify-between items-center bg-slate-50/50">
            <div>
                <h2 class="text-lg font-black text-slate-900 italic">Activity Log</h2>
                <p class="text-sm text-slate-400 font-bold uppercase tracking-widest">Daily Transactions ({{ \Carbon\Carbon::parse($date)->format('M d, Y') }})</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="h-2 w-2 rounded-full bg-red-700 animate-pulse"></span>
                <span class="text-sm font-black text-red-700 uppercase tracking-widest">Live Engine</span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white text-sm uppercase tracking-[0.15em] text-slate-400 font-black">
                        <th class="px-3 py-6 border-b border-slate-100 whitespace-nowrap">Portal Unit</th>
                        <th class="px-3 py-6 border-b border-slate-100 whitespace-nowrap">Category</th>
                        <th class="px-3 py-6 border-b border-slate-100">Reference Entity</th>
                        <th class="px-3 py-6 border-b border-slate-100 text-right whitespace-nowrap">Transaction Value</th>
                        <th class="px-3 py-6 border-b border-slate-100 text-right whitespace-nowrap">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @php
                        $groupedActivity = $activity_log->groupBy(function($log) {
                            return $log->created_at->format('l, jS \o\f F Y');
                        });
                    @endphp

                    @forelse ($groupedActivity as $dateGroup => $records)
                        <tr class="bg-slate-50/50">
                            <td colspan="5" class="px-3 py-4 border-b border-t border-slate-100">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-full bg-white border border-slate-200 shadow-sm flex items-center justify-center text-red-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700 uppercase tracking-widest">{{ $dateGroup }}</span>
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-sm font-medium bg-red-50 text-red-700 border border-red-100">{{ count($records) }} Events</span>
                                </div>
                            </td>
                        </tr>
                        @foreach($records as $record)
                            <tr class="hover:bg-slate-50/80 transition-all duration-200 group cursor-default">
                                <td class="px-3 py-5 whitespace-nowrap">
                                    <span class="text-sm font-bold text-slate-500 bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-sm">{{ $record->properties['module'] ?? 'Unknown' }}</span>
                                    <div class="text-sm text-slate-400 mt-3 font-semibold uppercase tracking-wider">User: {{ $record->user->name ?? 'System' }}</div>
                                </td>
                                <td class="px-3 py-5 whitespace-nowrap">
                                    <span class="text-xs font-black text-slate-600 uppercase tracking-tighter">{{ $record->action }}</span>
                                </td>
                                <td class="px-3 py-5" colspan="2">
                                    <div class="font-bold text-slate-800 group-hover:text-red-700 transition-colors">{{ $record->properties['reference'] ?? 'System Event' }}</div>
                                    <div class="text-sm text-slate-400 italic mt-1 bg-white p-2 border border-slate-100 rounded inline-block shadow-sm">"{{ $record->description }}"</div>
                                </td>
                                <td class="px-3 py-5 text-right whitespace-nowrap">
                                    <div class="text-xs font-bold text-slate-500">{{ $record->created_at->format('d M, Y H:i') }}</div>
                                    <div class="text-sm text-slate-400 tracking-wider uppercase mt-1">{{ $record->created_at->diffForHumans() }}</div>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center">
                                <div class="flex flex-col items-center space-y-4">
                                    <div class="text-4xl text-slate-200">📭</div>
                                    <p class="text-slate-400 font-medium italic">No transactions archived matching criteria.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Corporate Documents -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach([
            'client' => ['label' => 'Client Profiles', 'color' => 'bg-red-700', 'icon' => '👤', 'desc' => 'Individual & corporate traveler dossiers'],
            'contract' => ['label' => 'Contracts', 'color' => 'bg-slate-800', 'icon' => '📜', 'desc' => 'Operational & partnership agreements'],
            'letter' => ['label' => 'Letters', 'color' => 'bg-slate-900', 'icon' => '✉️', 'desc' => 'Official correspondence & resolutions']
        ] as $type => $data)
            <a href="{{ route('md.folders.index', ['tab' => $type]) }}" 
               class="premium-card p-6 rounded-[2rem] group relative overflow-hidden flex flex-col items-center text-center">
                <div class="absolute top-0 right-0 w-24 h-24 {{ $data['color'] }} opacity-[0.03] rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-700"></div>
                
                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-2xl mb-4 shadow-sm group-hover:rotate-6 transition-transform">
                    {{ $data['icon'] }}
                </div>
                
                <h3 class="text-lg font-black text-slate-900 italic">{{ $data['label'] }}</h3>
                <p class="text-sm text-slate-400 mt-2 font-medium px-4 leading-relaxed opacity-70">{{ $data['desc'] }}</p>
                
                <div class="mt-6 flex items-center text-sm font-black text-red-700 uppercase tracking-[0.2em] opacity-0 group-hover:opacity-100 transition-all transform translate-y-2 group-hover:translate-y-0">
                    Open Repository →
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
