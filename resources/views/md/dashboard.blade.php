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
                <span class="text-[0.6rem] font-black text-slate-400 uppercase tracking-[0.2em] ml-3 mr-2">Dept</span>
                <select name="portal" onchange="this.form.submit()" class="bg-slate-50 border-none rounded-xl px-4 py-2 text-xs font-bold text-slate-700 outline-none cursor-pointer">
                    <option value="">All Divisions</option>
                    <option value="Sales" {{ request('portal') == 'Sales' ? 'selected' : '' }}>Sales</option>
                    <option value="Operations" {{ request('portal') == 'Operations' ? 'selected' : '' }}>Operations</option>
                </select>
            </div>
            
            <div class="flex items-center">
                <span class="text-[0.6rem] font-black text-slate-400 uppercase tracking-[0.2em] ml-2 mr-2">Staff</span>
                <select name="employee_id" onchange="this.form.submit()" class="bg-slate-50 border-none rounded-xl px-4 py-2 text-xs font-bold text-slate-700 outline-none cursor-pointer">
                    <option value="">All Staff</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center border-l border-slate-100 pl-4">
                <span class="text-[0.6rem] font-black text-slate-400 uppercase tracking-[0.2em] mr-2">Year</span>
                <select name="year" onchange="this.form.submit()" class="bg-slate-50 border-none rounded-xl px-6 py-2.5 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-red-700/20 outline-none transition-all cursor-pointer">
                    @foreach(range(date('Y'), date('Y') - 5) as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- Portal Activity Registry -->
    <div class="premium-card rounded-[2rem] overflow-hidden">
        <div class="p-6 border-b border-white/30 flex justify-between items-center bg-slate-50/50">
            <div>
                <h2 class="text-lg font-black text-slate-900 italic">Activity Log</h2>
                <p class="text-[0.65rem] text-slate-400 font-bold uppercase tracking-widest">Consolidated Transactions ({{ $year }})</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="h-2 w-2 rounded-full bg-red-700 animate-pulse"></span>
                <span class="text-[0.6rem] font-black text-red-700 uppercase tracking-widest">Live Engine</span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white text-[0.65rem] uppercase tracking-[0.15em] text-slate-400 font-black">
                        <th class="px-8 py-6 border-b border-slate-100">Portal Unit</th>
                        <th class="px-8 py-6 border-b border-slate-100">Category</th>
                        <th class="px-8 py-6 border-b border-slate-100">Reference Entity</th>
                        <th class="px-8 py-6 border-b border-slate-100 text-right">Transaction Value</th>
                        <th class="px-8 py-6 border-b border-slate-100 text-right">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @php
                        $groupedActivity = $activity_log->groupBy(function($log) {
                            return $log->properties['portal'] ?? 'System';
                        });
                    @endphp

                    @forelse($groupedActivity as $portal => $records)
                        <tr class="bg-slate-50/30">
                            <td colspan="5" class="px-8 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="h-1.5 w-8 rounded-full {{ $portal === 'Sales' ? 'bg-slate-800' : 'bg-red-700' }}"></div>
                                    <span class="text-[0.7rem] font-black text-slate-700 uppercase tracking-widest">{{ $portal }} Division</span>
                                </div>
                            </td>
                        </tr>
                        @foreach($records as $record)
                            <tr class="hover:bg-slate-50/80 transition-all duration-200 group cursor-default">
                                <td class="px-8 py-5">
                                    <span class="text-[0.7rem] font-bold text-slate-500 bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-sm">{{ $record->properties['module'] ?? 'Unknown' }}</span>
                                    <div class="text-[0.6rem] text-slate-400 mt-3 font-semibold uppercase tracking-wider">User: {{ $record->user->name ?? 'System' }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-xs font-black text-slate-600 uppercase tracking-tighter">{{ $record->action }}</span>
                                </td>
                                <td class="px-8 py-5" colspan="2">
                                    <div class="font-bold text-slate-800 group-hover:text-red-700 transition-colors">{{ $record->properties['reference'] ?? 'System Event' }}</div>
                                    <div class="text-[0.65rem] text-slate-400 italic mt-1 bg-white p-2 border border-slate-100 rounded inline-block shadow-sm">"{{ $record->description }}"</div>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="text-xs font-bold text-slate-500">{{ $record->created_at->format('d M, Y H:i') }}</div>
                                    <div class="text-[0.55rem] text-slate-400 tracking-wider uppercase mt-1">{{ $record->created_at->diffForHumans() }}</div>
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
                <p class="text-[0.65rem] text-slate-400 mt-2 font-medium px-4 leading-relaxed opacity-70">{{ $data['desc'] }}</p>
                
                <div class="mt-6 flex items-center text-[0.6rem] font-black text-red-700 uppercase tracking-[0.2em] opacity-0 group-hover:opacity-100 transition-all transform translate-y-2 group-hover:translate-y-0">
                    Open Repository →
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
