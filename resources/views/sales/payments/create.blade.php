@extends('layouts.app')
@section('title', 'Record Client Payment')

@section('content')
<div class="max-w-3xl animate-in slide-in-from-bottom duration-700">
    <div class="mb-8">
        <a href="{{ route('sales.payments.index') }}" class="text-slate-400 hover:safari-green transition-colors flex items-center space-x-2 mb-4 font-bold text-xs uppercase tracking-widest">
            <span>← Back to Payments Ledger</span>
        </a>
        <h1 class="text-4xl font-extrabold text-slate-900 tracking-tighter underline decoration-safari-green decoration-4 underline-offset-8">Record Payment</h1>
        <p class="text-slate-400 mt-4 font-medium uppercase text-[0.65rem] tracking-[0.3em]">Financial Payment Protocol</p>
    </div>

    <div class="premium-card p-12 rounded-3xl relative overflow-hidden" x-data="{ total: 0, paid: 0, get remaining() { return this.total - this.paid } }" @update-total.window="total = $event.detail.total">
        <div class="absolute top-0 right-0 p-8 opacity-[0.03] pointer-events-none">
            <img src="{{ asset('assets/brand/logo.png') }}" alt="Logo" class="w-24 grayscale">
        </div>

        <form action="{{ route('sales.payments.store') }}" method="POST" class="space-y-8">
            @csrf
            <div class="space-y-2">
                <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest ml-1">Pending Invoice to Pay</label>
                <select name="billing_document_id" id="invoice_select" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-safari-green focus:ring-4 focus:ring-safari-green/5 transition-all font-bold text-lg appearance-none cursor-pointer" onchange="updateInvoiceDetails(this)">
                    <option value="">Select an outstanding invoice...</option>
                    @foreach($pendingInvoices as $invoice)
                        @php $balance = max(0, $invoice->amount - $invoice->clientPayments->sum('paid_amount')); @endphp
                        <option value="{{ $invoice->id }}" data-client="{{ $invoice->client_id }}" data-balance="{{ $balance }}" data-currency="{{ $invoice->currency }}">
                            {{ $invoice->document_number }} - {{ $invoice->client->name }} ({{ number_format($balance) }} {{ $invoice->currency }} Due)
                        </option>
                    @endforeach
                </select>
                <!-- Hidden inputs to satisfy controller validation -->
                <input type="hidden" name="client_id" id="client_id_hidden" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-2">
                    <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest ml-1">Total Contract Value</label>
                    <input type="number" name="total_due" x-model.number="total" required placeholder="0.00" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-safari-green transition-all font-black text-xl text-slate-900 tabular-nums">
                </div>
                <div class="space-y-2">
                    <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest ml-1">Current Payment</label>
                    <input type="number" name="paid_amount" x-model.number="paid" required placeholder="0.00" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-safari-green transition-all font-black text-xl text-safari-green tabular-nums">
                </div>
                <div class="space-y-2">
                    <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest ml-1">Outstanding Balance</label>
                    <div class="w-full bg-slate-100 border border-slate-200 rounded-xl px-6 py-4 font-black text-xl text-sunset-orange tabular-nums" x-text="new Intl.NumberFormat().format(remaining)">
                        0
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-2">
                    <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest ml-1">Transaction Date</label>
                    <input type="date" name="payment_date" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-safari-green transition-all font-bold">
                </div>
                <div class="space-y-2">
                    <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest ml-1">Payment Method</label>
                    <select name="payment_method" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-safari-green transition-all font-bold appearance-none cursor-pointer">
                        <option>Bank</option>
                        <option>MoMo</option>
                        <option>Card</option>
                        <option>Cheque</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-[0.65rem] font-black text-slate-400 uppercase tracking-widest ml-1">Currency Unit</label>
                    <select name="currency" id="currency_select" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-6 py-4 focus:outline-none focus:border-safari-green transition-all font-bold appearance-none cursor-pointer">
                        <option>RWF</option>
                        <option>USD</option>
                    </select>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-50">
                <button type="submit" class="w-full bg-red-700 hover:bg-red-900 py-5 rounded-lg font-black text-white uppercase tracking-widest text-base shadow-xl shadow-red-900/20 transition-all hover:-translate-y-1 transform active:scale-[0.98]">
                    Finalize & Record Payment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateInvoiceDetails(select) {
        if (!select.value) return;
        
        const option = select.options[select.selectedIndex];
        const clientId = option.getAttribute('data-client');
        const balance = option.getAttribute('data-balance');
        const currency = option.getAttribute('data-currency');
        
        document.getElementById('client_id_hidden').value = clientId;
        document.getElementById('currency_select').value = currency;
        
        // Update Alpine state from outside
        select.dispatchEvent(new CustomEvent('update-total', { 
            detail: { total: parseFloat(balance) }, 
            bubbles: true 
        }));
    }
</script>
@endsection
