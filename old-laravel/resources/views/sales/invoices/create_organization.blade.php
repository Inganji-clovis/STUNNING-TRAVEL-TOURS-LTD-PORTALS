@extends('layouts.app')
@section('title', "Record Organization's Invoice")

@section('content')
<div class="max-w-2xl mx-auto space-y-8 animate-in fade-in duration-700">
    <div>
        <h1 class="text-3xl font-bold">Record Organization Invoice</h1>
        <p class="text-slate-400 font-medium">Capture billing for a worker under a corporate partner</p>
    </div>

    <div class="glass border border-slate-200 p-8 rounded-[40px] shadow-2xl">
        <form action="{{ route('sales.invoices.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Organization Name -->
            <div class="space-y-2">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Select Organization</label>
                <select name="organization_id" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all">
                    <option value="">Choose Organization...</option>
                    @foreach($organizations as $org)
                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Full Names of Worker -->
            <div class="space-y-2">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Full Names of Worker</label>
                <input type="text" name="full_names" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all" placeholder="John Doe">
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Worker Email (For Automations)</label>
                <input type="email" name="email" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all" placeholder="john@example.com">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <!-- Amount -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Total Amount</label>
                    <input type="number" name="amount" required step="0.01" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all" placeholder="0.00">
                </div>

                <!-- Currency -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Currency</label>
                    <select name="currency" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all">
                        <option value="USD">USD</option>
                        <option value="RWF">RWF</option>
                    </select>
                </div>
            </div>
            
            <!-- Rate & Amount in words -->
            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Exchange Rate (Optional)</label>
                    <input type="number" name="rate" step="0.01" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all" placeholder="1467">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Amount in Words (Optional)</label>
                    <input type="text" name="amount_in_words" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all" placeholder="Five Thousand Dollars">
                </div>
            </div>

            <!-- Date -->
            <div class="space-y-2">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Invoice Date</label>
                <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 focus:border-red-700 focus:ring-4 focus:ring-red-700/5 outline-none transition-all">
            </div>

            <!-- Line Items -->
            <div class="pt-6 border-t border-slate-200">
                <div class="flex justify-between items-center mb-4">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Invoice Line Items</label>
                    <button type="button" onclick="addLineItem()" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">+ Add Item</button>
                </div>
                
                <div id="line-items-container" class="space-y-4">
                    <!-- Default first item -->
                    <div class="line-item flex gap-4 items-start bg-slate-50 p-4 border border-slate-200 rounded-2xl relative">
                        <div class="flex-1 grid grid-cols-4 gap-4">
                            <div>
                                <input type="text" name="items[0][passenger_name]" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-red-700 outline-none transition-all" placeholder="Passenger Name (Optional)">
                            </div>
                            <div>
                                <input type="text" name="items[0][airline]" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-red-700 outline-none transition-all" placeholder="Airline (Optional)">
                            </div>
                            <div class="col-span-2">
                                <input type="text" name="items[0][destination]" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-red-700 outline-none transition-all" placeholder="Destination / Route">
                            </div>
                            <div class="col-span-2">
                                <input type="number" step="0.01" name="items[0][price_usd]" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-red-700 outline-none transition-all" placeholder="Price USD">
                            </div>
                            <div class="col-span-2">
                                <input type="number" step="0.01" name="items[0][price_rwf]" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-red-700 outline-none transition-all" placeholder="Price RWF">
                            </div>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:bg-red-50 p-3 rounded-xl transition-all h-fit mt-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="pt-8 flex items-center justify-end space-x-4">
                <a href="{{ route('sales.invoices.index') }}" class="text-sm font-bold text-slate-400 hover:text-white transition-colors">Cancel</a>
                <button type="submit" class="bg-red-700 hover:bg-red-800 px-10 py-4 rounded-2xl font-black uppercase tracking-tighter transition-all text-white">Record Invoice</button>
            </div>
        </form>
    </div>
</div>

<script>
    let itemIndex = 1;
    function addLineItem() {
        const container = document.getElementById('line-items-container');
        const html = `
            <div class="line-item flex gap-4 items-start bg-slate-50 p-4 border border-slate-200 rounded-2xl relative animate-in zoom-in duration-300">
                <div class="flex-1 grid grid-cols-4 gap-4">
                    <div>
                        <input type="text" name="items[${itemIndex}][passenger_name]" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-red-700 outline-none transition-all" placeholder="Passenger Name (Optional)">
                    </div>
                    <div>
                        <input type="text" name="items[${itemIndex}][airline]" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-red-700 outline-none transition-all" placeholder="Airline (Optional)">
                    </div>
                    <div class="col-span-2">
                        <input type="text" name="items[${itemIndex}][destination]" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-red-700 outline-none transition-all" placeholder="Destination / Route">
                    </div>
                    <div class="col-span-2">
                        <input type="number" step="0.01" name="items[${itemIndex}][price_usd]" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-red-700 outline-none transition-all" placeholder="Price USD">
                    </div>
                    <div class="col-span-2">
                        <input type="number" step="0.01" name="items[${itemIndex}][price_rwf]" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-red-700 outline-none transition-all" placeholder="Price RWF">
                    </div>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:bg-red-50 p-3 rounded-xl transition-all h-fit mt-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        itemIndex++;
    }
</script>
@endsection
