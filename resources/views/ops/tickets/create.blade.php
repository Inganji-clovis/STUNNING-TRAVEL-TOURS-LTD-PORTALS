@extends('layouts.app')
@section('title', 'Log New Flight Ticket')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in duration-700">
    <div class="mb-10">
        <a href="{{ route('ops.tickets.index') }}" class="text-slate-400 hover:text-red-700 transition-colors flex items-center space-x-2 mb-6 font-bold text-xs uppercase tracking-widest">
            <span>← Return to Ticket Ledger</span>
        </a>
        <div class="flex items-center space-x-4 mb-2">
            <div class="w-12 h-12 bg-red-700/10 rounded-2xl flex items-center justify-center">
                <span class="text-2xl">✈️</span>
            </div>
            <div>
                <h1 class="text-4xl font-extrabold tracking-tighter text-slate-900">Log <span class="text-red-700 italic">Ticket</span></h1>
                <p class="text-slate-400 font-medium tracking-wide">Secure capture of PNR records and flight documents.</p>
            </div>
        </div>
    </div>

    <div class="premium-card p-10 rounded-3xl relative overflow-hidden bg-slate-50/50">
        <div class="absolute top-0 right-0 p-8 opacity-[0.03] font-black text-8xl pointer-events-none text-slate-900 italic">TICKET</div>
        
        <form action="{{ route('ops.tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 relative">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- PNR -->
                <div class="space-y-2">
                    <label class="text-[0.65rem] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">PNR (Booking Ref)</label>
                    <input type="text" name="pnr" required class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-slate-900 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm uppercase placeholder:text-slate-300" placeholder="AB12CD">
                </div>

                <!-- Ticket Number -->
                <div class="space-y-2">
                    <label class="text-[0.65rem] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Ticket Number</label>
                    <input type="text" name="ticket_number" required class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-slate-900 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm placeholder:text-slate-300" placeholder="014-1234567890">
                </div>
            </div>

            <!-- Client Names -->
            <div class="space-y-2">
                <label class="text-[0.65rem] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Passenger Names (As on Ticket)</label>
                <input type="text" name="client_names" required class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-slate-900 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm placeholder:text-slate-300" placeholder="John Doe / Jane Doe">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Departure Date -->
                <div class="space-y-2">
                    <label class="text-[0.65rem] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Departure Date</label>
                    <input type="date" name="departure_date" required class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-slate-900 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm">
                </div>

                <!-- Return Date -->
                <div class="space-y-2">
                    <label class="text-[0.65rem] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Return Date (Optional)</label>
                    <input type="date" name="return_date" class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-slate-900 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Flight Number -->
                <div class="space-y-2">
                    <label class="text-[0.65rem] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Flight Number</label>
                    <input type="text" name="flight_number" required class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-slate-900 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm uppercase placeholder:text-slate-300" placeholder="WB 301">
                </div>

                <!-- Airline Name -->
                <div class="space-y-2">
                    <label class="text-[0.65rem] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Airline Name</label>
                    <input type="text" name="airline_name" required class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-slate-900 focus:outline-none focus:border-red-700 focus:ring-4 focus:ring-red-700/5 transition-all shadow-sm placeholder:text-slate-300" placeholder="RwandAir">
                </div>
            </div>

            <!-- Upload Field -->
            <div class="space-y-2">
                <label class="text-[0.65rem] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Ticket Document (PDF/Image)</label>
                <div class="relative group">
                    <input type="file" name="ticket_file" accept=".pdf,.jpeg,.jpg,.png" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="w-full bg-white border-2 border-dashed border-slate-200 group-hover:border-red-700/50 rounded-2xl p-10 flex flex-col items-center justify-center transition-all shadow-sm">
                        <span class="text-4xl mb-3 grayscale opacity-30 group-hover:grayscale-0 group-hover:opacity-100 transition-all">📄</span>
                        <p class="text-sm text-slate-400 font-bold tracking-tight">Click or drag document here</p>
                        <p class="text-[0.6rem] text-slate-400 mt-2 uppercase tracking-widest font-black">Max 5MB • PDF, JPG, PNG</p>
                    </div>
                </div>
                @error('ticket_file')
                    <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full bg-red-700 hover:bg-red-800 text-white py-5 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-red-900/20 transition-all flex items-center justify-center space-x-3 hover:-translate-y-1 transform active:scale-[0.98]">
                    <span>Secure & Archive Ticket</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
