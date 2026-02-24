@extends('layouts.app')
@section('title', 'Tickets Registry')

@section('content')
<div class="space-y-8 animate-in slide-in-from-bottom duration-700">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-slate-900">Tickets <span class="text-red-700 italic">Registry</span></h1>
            <p class="text-slate-400 mt-2 font-medium tracking-wide">Comprehensive archive of issued client flight tickets and PNR details.</p>
        </div>
        <a href="{{ route('ops.tickets.create') }}" class="bg-red-700 hover:bg-red-800 text-white px-8 py-3.5 rounded-xl font-bold shadow-lg shadow-red-900/20 transition-all">+ Upload Ticket</a>
    </div>

    <!-- Intelligence Search -->
    <div class="premium-card p-6 rounded-2xl bg-slate-50/50">
        <form action="{{ route('ops.tickets.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1 relative group">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by PNR, ticket number, client names, or airline..." 
                       class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3.5 text-sm font-medium focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all pl-12 shadow-sm">
                <span class="absolute left-4 top-4 text-slate-300 group-focus-within:text-red-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
            </div>
            <button type="submit" class="bg-red-700 hover:bg-red-800 text-white px-8 py-2 rounded-xl text-xs font-bold transition-all shadow-sm">Filter</button>
            @if(request('search'))
                <a href="{{ route('ops.tickets.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-500 px-6 py-2 rounded-xl text-xs font-bold transition-all border border-slate-200">Clear</a>
            @endif
        </form>
    </div>

    <div class="premium-card rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 text-[0.65rem] uppercase tracking-[0.15em] text-slate-400 font-black">
                        <th class="px-8 py-6 border-b border-slate-100">PNR / Ticket</th>
                        <th class="px-8 py-6 border-b border-slate-100">Client / Airline</th>
                        <th class="px-8 py-6 border-b border-slate-100">Flight</th>
                        <th class="px-8 py-6 border-b border-slate-100">Dates</th>
                        <th class="px-8 py-6 border-b border-slate-100 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @if($tickets->count() > 0)
                        @foreach($tickets as $ticket)
                        <tr class="hover:bg-slate-50/50 transition-all duration-200 group">
                            <td class="px-8 py-6">
                                <div class="font-bold text-slate-900 group-hover:text-red-700 transition-colors">{{ $ticket->pnr }}</div>
                                <div class="text-[0.65rem] font-black text-slate-400 tracking-[0.2em] mt-1">{{ $ticket->ticket_number }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="font-bold text-slate-900 tracking-tight">{{ $ticket->client_names }}</div>
                                <div class="text-[0.65rem] font-black text-slate-400 uppercase tracking-tighter mt-1">{{ $ticket->airline_name }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="font-black text-slate-900">{{ $ticket->flight_number }}</span>
                            </td>
                            <td class="px-8 py-6 text-[0.7rem] font-black text-slate-400 uppercase tracking-widest">
                                <div>DEP: {{ $ticket->departure_date->format('d M Y') }}</div>
                                @if($ticket->return_date)
                                <div class="mt-1">RET: {{ $ticket->return_date->format('d M Y') }}</div>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right space-x-2">
                                <a href="{{ Storage::url($ticket->file_path) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-red-700 hover:text-white text-slate-600 rounded-lg text-[0.6rem] font-black uppercase tracking-widest transition-all shadow-sm">
                                    View
                                </a>
                                <form action="{{ route('ops.tickets.destroy', $ticket) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this ticket record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-50 hover:bg-red-700 hover:text-white text-red-600 rounded-lg text-[0.6rem] font-black uppercase tracking-widest transition-all shadow-sm">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="px-8 py-32 text-center text-slate-400 italic">
                                <div class="flex flex-col items-center space-y-4">
                                    <div class="text-5xl grayscale opacity-30">✈️</div>
                                    <p class="text-lg font-medium tracking-tight">Ticket directory is currently empty.</p>
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
