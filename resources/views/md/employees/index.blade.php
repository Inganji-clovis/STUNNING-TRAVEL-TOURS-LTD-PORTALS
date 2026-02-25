@extends('layouts.app')
@section('title', 'Employee Base')

@section('content')
<div class="space-y-8 animate-in fade-in duration-1000">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-slate-900 italic">Employee <span class="red-700">Base</span></h1>
            <p class="text-slate-400 mt-1 text-xs font-bold tracking-[0.1em] uppercase opacity-70">Staff Composition & Sentinel Overview</p>
        </div>
        
        <div class="flex items-center space-x-2 bg-slate-50 border border-slate-200 px-4 py-2 rounded-xl shadow-sm">
            <span class="h-2 w-2 rounded-full bg-slate-300"></span>
            <span class="text-sm font-black text-slate-500 uppercase tracking-widest">Read-Only Mode</span>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white p-6 rounded-[2rem] shadow-premium border border-slate-100">
        <form action="{{ route('md.employees.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email or phone..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 font-medium focus:outline-none focus:border-red-700 transition-all">
            </div>
            <div class="w-full md:w-64">
                <select name="portal" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:border-red-700 transition-all cursor-pointer">
                    <option value="">All Portals</option>
                    <option value="Sales" {{ request('portal') == 'Sales' ? 'selected' : '' }}>Financial Unit (Sales)</option>
                    <option value="Operations" {{ request('portal') == 'Operations' ? 'selected' : '' }}>Logistics Unit (Ops)</option>
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-slate-900 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl text-xs tracking-wider uppercase transition-colors shadow-md">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Employee Roster Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($employees as $employee)
            <div class="premium-card p-6 rounded-[2rem] border border-slate-200 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-slate-50 rounded-bl-[4rem] -z-10 group-hover:bg-red-50 transition-colors duration-500"></div>
                
                <div class="flex justify-between items-start mb-6">
                    <div class="w-14 h-14 bg-slate-900 text-white rounded-2xl flex items-center justify-center font-black text-xl shadow-lg">
                        {{ substr($employee->name, 0, 1) }}
                    </div>
                    <div class="flex flex-col items-end">
                        @if($employee->is_active ?? true)
                            <span class="text-sm font-black text-green-600 bg-green-50 border border-green-200 px-2.5 py-1 rounded-md uppercase tracking-wider">Active</span>
                        @else
                            <span class="text-sm font-black text-red-600 bg-red-50 border border-red-200 px-2.5 py-1 rounded-md uppercase tracking-wider">Suspended</span>
                        @endif
                        <span class="text-sm text-slate-400 font-bold mt-2 uppercase tracking-widest">ID: EMP-{{ str_pad($employee->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>

                <div class="space-y-1 mb-6">
                    <h3 class="text-lg font-black text-slate-900">{{ $employee->name }}</h3>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest flex items-center gap-2">
                        {{ ucfirst($employee->role) }} Specialist
                    </p>
                </div>

                <div class="space-y-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="flex items-center text-xs text-slate-600">
                        <svg class="w-4 h-4 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span class="font-medium truncate">{{ $employee->email }}</span>
                    </div>
                    @if($employee->phone)
                    <div class="flex items-center text-xs text-slate-600">
                        <svg class="w-4 h-4 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span class="font-medium font-mono">{{ $employee->phone }}</span>
                    </div>
                    @endif
                    <div class="flex items-center text-xs text-slate-600">
                        <svg class="w-4 h-4 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="font-medium">Joined {{ $employee->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 text-center">
                <div class="flex flex-col items-center space-y-4">
                    <div class="text-4xl text-slate-200">👥</div>
                    <p class="text-slate-400 font-medium italic">No personnel found matching criteria.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
