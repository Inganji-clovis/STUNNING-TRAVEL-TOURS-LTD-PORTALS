@extends('layouts.app')
@section('title', 'Executive Operations')

@section('content')
<div x-data="{ 
    showIncome: false, 
    showExpense: false, 
    showTicket: false, 
    showPetty: false,
    showDispatchFile: false,
    showDispatchClient: false,
    ticketTTS: '',
    ticketSF: '',
    get expectedRC() { 
        let tts = parseFloat(this.ticketTTS) || 0;
        let sf = parseFloat(this.ticketSF) || 0;
        return (tts + sf).toFixed(2);
    }
}" class="space-y-8 animate-in slide-in-from-bottom duration-700">

    @if(session('success'))
        <div class="px-6 py-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl font-bold shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="px-6 py-4 bg-red-50 border border-red-200 text-red-800 rounded-xl font-bold shadow-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-3">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter text-slate-900 italic">MD <span class="text-red-700">Command Center</span></h1>
            <p class="text-slate-400 mt-1 text-xs font-bold tracking-[0.1em] uppercase opacity-70">Executive Financial & Operational Interventions</p>
        </div>
        
        <div class="flex gap-4 items-center">
            <a href="{{ route('md.audit.index') }}" class="bg-slate-900 hover:bg-black text-white px-6 py-2.5 rounded-lg text-sm font-bold tracking-widest uppercase transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-900/40 backdrop-blur-md">
                📊 Financial Audit
            </a>
        </div>
    </div>

    <!-- MAIN ACTION BUTTONS: FINANCIAL OPERATIONS -->
    <div>
        <h2 class="text-base font-semibold text-slate-400 uppercase tracking-widest mb-4 italic">Financial Overrides</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <button @click="showIncome = true" class="p-4 bg-white/60 border border-slate-200/50 rounded-xl hover:border-emerald-500/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:bg-emerald-50/30 flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">➕</div>
                <div class="text-left">
                    <p class="font-black text-slate-900">Add Income</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Global</p>
                </div>
            </button>
            <button @click="showExpense = true" class="p-4 bg-white/60 border border-slate-200/50 rounded-xl hover:border-red-500/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:bg-red-50/30 flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">➖</div>
                <div class="text-left">
                    <p class="font-black text-slate-900">Add Expense</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">OPEX/CAPEX</p>
                </div>
            </button>
            <button @click="showTicket = true" class="p-4 bg-white/60 border border-slate-200/50 rounded-xl hover:border-blue-500/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:bg-blue-50/30 flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">🎫</div>
                <div class="text-left">
                    <p class="font-black text-slate-900">Ticket Sale</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Aviation</p>
                </div>
            </button>
            <button @click="showPetty = true" class="p-4 bg-white/60 border border-slate-200/50 rounded-xl hover:border-amber-500/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:bg-amber-50/30 flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">💵</div>
                <div class="text-left">
                    <p class="font-black text-slate-900">Petty Cash</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Float</p>
                </div>
            </button>
        </div>
    </div>

    <!-- MAIN ACTION BUTTONS: OPERATIONS DISPATCH -->
    <div>
        <h2 class="text-base font-semibold text-slate-400 uppercase tracking-widest mb-4 italic mt-8">Operations Dispatch Routing</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <button @click="showDispatchFile = true" class="p-6 bg-indigo-50/50 border border-indigo-200/50 rounded-2xl hover:border-indigo-400/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-indigo-900/10 flex items-center gap-4 group text-left">
                <div class="w-16 h-16 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shadow-inner">📤</div>
                <div>
                    <h3 class="text-2xl font-bold text-indigo-900">Dispatch File to Operations</h3>
                    <p class="text-xs font-bold text-indigo-600/70 uppercase tracking-widest mt-1">Send Tickets / Invoices directly to Ops Portal</p>
                </div>
            </button>
            
            <button @click="showDispatchClient = true" class="p-6 bg-emerald-50/50 border border-emerald-200/50 rounded-2xl hover:border-emerald-400/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-900/10 flex items-center gap-4 group text-left">
                <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shadow-inner">👤</div>
                <div>
                    <h3 class="text-2xl font-bold text-emerald-900">Create Client for Operations</h3>
                    <p class="text-xs font-bold text-emerald-600/70 uppercase tracking-widest mt-1">Bypass reception directly to Ops Registry</p>
                </div>
            </button>
        </div>
    </div>

    
    <!-- Corporate Documents Gateway -->
    <div class="mt-8">
        <h2 class="text-base font-semibold text-slate-400 uppercase tracking-widest mb-4 italic">Executive Information Base</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                'client' => ['label' => 'Client Profiles', 'color' => 'bg-red-700', 'icon' => '👤', 'desc' => 'Individual & corporate traveler dossiers'],
                'contract' => ['label' => 'Contracts', 'color' => 'bg-slate-800', 'icon' => '📜', 'desc' => 'Operational & partnership agreements'],
                'letter' => ['label' => 'Letters', 'color' => 'bg-slate-900', 'icon' => '✉️', 'desc' => 'Official correspondence & resolutions']
            ] as $type => $data)
                <a href="{{ route('md.folders.index', ['tab' => $type]) }}" 
                   class="premium-card p-6 rounded-[2rem] group relative overflow-hidden flex flex-col items-center text-center hover:shadow-2xl transition-all hover:-translate-y-1 border border-slate-100">
                    <div class="absolute top-0 right-0 w-24 h-24 {{ $data['color'] }} opacity-[0.03] rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-700"></div>
                    
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-2xl mb-4 shadow-sm group-hover:rotate-6 transition-transform">
                        {{ $data['icon'] }}
                    </div>
                    
                    <h3 class="text-2xl font-bold text-slate-900 italic">{{ $data['label'] }}</h3>
                    <p class="text-xs text-slate-400 mt-2 font-bold px-4 leading-relaxed uppercase">{{ $data['desc'] }}</p>
                </a>
            @endforeach
        </div>
    </div>

    <!-- MODALS -->

    <!-- FINANCIAL MODALS (Mirrored from Sales) -->
    <div x-show="showIncome" style="display:none" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" @click.self="showIncome = false">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <div class="bg-emerald-600 p-6 text-white flex justify-between items-center">
                <h3 class="text-2xl font-bold italic">Add Income Override</h3>
                <button @click="showIncome = false" class="text-white/70 hover:text-white">&times;</button>
            </div>
            <form action="{{ route('md.financial.income') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <!-- Exact same form structure as Sales Portal -->
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Amount</label><input type="number" step="0.01" name="amount" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none focus:border-emerald-500"></div>
                    <div><label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Currency</label><select name="currency" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none focus:border-emerald-500"><option value="RWF">RWF</option><option value="USD">USD</option></select></div>
                </div>
                <div><label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Category</label><select name="category" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none focus:border-emerald-500"><option value="Visa">Visa Service</option><option value="Tour Package">Tour Package</option><option value="Hotel Booking">Hotel Booking</option><option value="Car Rental">Car Rental</option><option value="Commission">Commission</option><option value="Other">Other</option></select></div>
                <div><label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Payment Method</label><select name="payment_method" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none focus:border-emerald-500"><option value="Bank">Bank Transfer</option><option value="MoMo">Mobile Money</option><option value="Cash">Cash</option></select></div>
                <div><label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Description</label><input type="text" name="description" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none focus:border-emerald-500"></div>
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-lg transition-colors">Force Income Entry</button>
            </form>
        </div>
    </div>

    <!-- Expense Modal -->
    <div x-show="showExpense" style="display:none" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" @click.self="showExpense = false">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <div class="bg-red-700 p-6 text-white flex justify-between items-center"><h3 class="text-2xl font-bold italic">Add Expense Override</h3><button @click="showExpense = false" class="text-white/70 hover:text-white">&times;</button></div>
            <form action="{{ route('md.financial.expense') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Amount</label><input type="number" step="0.01" name="amount" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-red-500"></div>
                    <div><label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Currency</label><select name="currency" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-red-500"><option value="RWF">RWF</option><option value="USD">USD</option></select></div>
                </div>
                <div><label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Type</label><select name="category" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-red-500"><option value="OPEX - Salaries">OPEX - Salaries</option><option value="OPEX - Utilities">OPEX - Utilities</option><option value="OPEX - Marketing">OPEX - Marketing</option><option value="OPEX - Rent">OPEX - Rent</option><option value="CAPEX - Equipment">CAPEX - Equipment</option><option value="Other">Other</option></select></div>
                <div><label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Paid From</label><select name="source" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-red-500"><option value="main">Main Cash/Bank</option><option value="petty">Petty Cash</option></select></div>
                <div><label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Description</label><input type="text" name="description" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-red-500"></div>
                <button type="submit" class="w-full bg-red-700 hover:bg-red-800 text-white font-bold py-3 rounded-lg transition-colors">Force Expense Entry</button>
            </form>
        </div>
    </div>

    <!-- Ticket Modal -->
    <div x-show="showTicket" style="display:none" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" @click.self="showTicket = false">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <div class="bg-blue-800 p-6 text-white flex justify-between items-center"><h3 class="text-2xl font-bold italic">🎫 Airline Ticket Sale Override</h3><button @click="showTicket = false" class="text-white/70 hover:text-white">&times;</button></div>
            <form action="{{ route('md.financial.ticket') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Supplier</label><input type="text" name="supplier_name" required placeholder="e.g. RwandAir" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-blue-500"></div>
                    <div><label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Currency</label><select name="currency" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-blue-500"><option value="USD">USD</option><option value="RWF">RWF</option></select></div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div><label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">TTS</label><input type="number" step="0.01" name="tts" x-model="ticketTTS" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-3 font-black text-slate-900 focus:outline-none border-blue-500"></div>
                    <div><label class="block text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1">S.Fee (+)</label><input type="number" step="0.01" name="service_fee" x-model="ticketSF" required class="w-full bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-3 font-black text-emerald-900 focus:outline-none border-emerald-500"></div>
                    <div><label class="block text-[10px] font-black text-blue-600 uppercase tracking-widest mb-1">RC</label><input type="number" step="0.01" name="rc" :value="expectedRC" required class="w-full border-2 border-blue-500 rounded-lg px-3 py-3 font-black text-blue-900 focus:outline-none"></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Payment Method</label><select name="payment_method" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-blue-500"><option value="Bank">Bank Transfer</option><option value="MoMo">Mobile Money</option><option value="Cash">Cash</option></select></div>
                    <div><label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">PNR/Ref</label><input type="text" name="description" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-blue-500"></div>
                </div>
                <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 rounded-lg transition-colors">Force Ticket Entry</button>
            </form>
        </div>
    </div>

    <!-- Petty Cash Modal -->
    <div x-show="showPetty" style="display:none" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" @click.self="showPetty = false">
        <div class="bg-white w-full max-w-sm rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <div class="bg-amber-500 p-6 text-white flex justify-between items-center"><h3 class="text-2xl font-bold italic">Petty Cash Override</h3><button @click="showPetty = false" class="text-white/70 hover:text-white">&times;</button></div>
            <form action="{{ route('md.financial.petty-cash') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div><label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Action Type</label><select name="type" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-amber-500"><option value="spend">Record Expense (Spend)</option><option value="added">Top-Up (Add Funds)</option></select></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Amount</label><input type="number" step="0.01" name="amount" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-amber-500"></div>
                    <div><label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Currency</label><select name="currency" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-amber-500"><option value="RWF">RWF</option></select></div>
                </div>
                <div><label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Description</label><input type="text" name="description" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-amber-500"></div>
                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold py-3 rounded-lg transition-colors">Process Petty Cash</button>
            </form>
        </div>
    </div>


    <!-- OPS DISPATCH MODALS -->
    <!-- Dispatch File  -->
    <div x-show="showDispatchFile" style="display:none" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" @click.self="showDispatchFile = false">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <div class="bg-indigo-700 p-6 text-white flex justify-between items-center">
                <h3 class="text-2xl font-bold italic">Dispatch File to Ops</h3>
                <button @click="showDispatchFile = false" class="text-white/70 hover:text-white">&times;</button>
            </div>
            <form action="{{ route('md.dispatch.file') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div><label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Doc Type</label><select name="type" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-indigo-500"><option value="Ticket">Airline Ticket</option><option value="Invoice">Invoice</option><option value="Other">Other Document</option></select></div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">File Name/Title</label>
                    <input type="text" name="file_name" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-indigo-500">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Secure Upload</label>
                    <input type="file" name="file" required class="w-full p-2 border border-slate-200 rounded-lg bg-slate-50 text-sm">
                </div>
                <div><label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Instructions for Ops</label><input type="text" name="description" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-indigo-500"></div>
                <button type="submit" class="w-full bg-indigo-700 hover:bg-indigo-800 text-white font-bold py-3 rounded-lg transition-colors">Send to Operations</button>
            </form>
        </div>
    </div>

    <!-- Dispatch Client -->
    <div x-show="showDispatchClient" style="display:none" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" @click.self="showDispatchClient = false">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <div class="bg-emerald-700 p-6 text-white flex justify-between items-center">
                <h3 class="text-2xl font-bold italic">Create Ops Client</h3>
                <button @click="showDispatchClient = false" class="text-white/70 hover:text-white">&times;</button>
            </div>
            <form action="{{ route('md.dispatch.client') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div><label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Client Type</label><select name="type" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-emerald-500"><option value="individual">Individual Profiling</option><option value="organization">Corporate Organization</option></select></div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Full Names</label>
                    <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-emerald-500">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Contact Email</label>
                    <input type="email" name="email" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-emerald-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Phone</label>
                        <input type="text" name="phone" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Passport No.</label>
                        <input type="text" name="passport_number" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 font-bold text-slate-900 focus:outline-none border-emerald-500">
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-bold py-3 rounded-lg transition-colors">Save to Operations Base</button>
            </form>
        </div>
    </div>


</div>
@endsection
