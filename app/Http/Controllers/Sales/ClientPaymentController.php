<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\ClientPayment::with('client', 'recorder', 'invoice');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function(\Illuminate\Database\Eloquent\Builder $q) use ($search) {
                $q->whereHas('client', function($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%");
                })->orWhere('payment_method', 'like', "%{$search}%")
                  ->orWhere('currency', 'like', "%{$search}%");
            });
        }

        $payments = $query->latest()->get();
        
        $pendingInvoices = \App\Models\BillingDocument::where('status', 'pending')->with('clientPayments')->get();
        $totalPaymentRw = \App\Models\ClientPayment::where('currency', 'RWF')->sum('paid_amount');
        $totalPaymentUsd = \App\Models\ClientPayment::where('currency', 'USD')->sum('paid_amount');

        $outstandingRw = \App\Models\BillingDocument::where('status', 'pending')
            ->where('currency', 'RWF')
            ->select(
                \Illuminate\Support\Facades\DB::raw('SUM(amount - (SELECT COALESCE(SUM(paid_amount), 0) FROM client_payments WHERE client_payments.billing_document_id = billing_documents.id)) as total')
            )->value('total') ?? 0;
            
        $outstandingUsd = \App\Models\BillingDocument::where('status', 'pending')
            ->where('currency', 'USD')
            ->select(
                \Illuminate\Support\Facades\DB::raw('SUM(amount - (SELECT COALESCE(SUM(paid_amount), 0) FROM client_payments WHERE client_payments.billing_document_id = billing_documents.id)) as total')
            )->value('total') ?? 0;

        $overdueRw = \App\Models\BillingDocument::where('status', 'pending')
            ->where('currency', 'RWF')
            ->where('due_date', '<', now())
            ->select(
                \Illuminate\Support\Facades\DB::raw('SUM(amount - (SELECT COALESCE(SUM(paid_amount), 0) FROM client_payments WHERE client_payments.billing_document_id = billing_documents.id)) as total')
            )->value('total') ?? 0;

        $overdueUsd = \App\Models\BillingDocument::where('status', 'pending')
            ->where('currency', 'USD')
            ->where('due_date', '<', now())
            ->select(
                \Illuminate\Support\Facades\DB::raw('SUM(amount - (SELECT COALESCE(SUM(paid_amount), 0) FROM client_payments WHERE client_payments.billing_document_id = billing_documents.id)) as total')
            )->value('total') ?? 0;

        $stats = [
            'total_payment_rw' => $totalPaymentRw,
            'total_payment_usd' => $totalPaymentUsd,
            'total_outstanding_rw' => $outstandingRw,
            'total_outstanding_usd' => $outstandingUsd,
            'total_overdue_rw' => $overdueRw,
            'total_overdue_usd' => $overdueUsd,
            'average_payment_time' => 14,
        ];

        return view('sales.payments.index', compact('payments', 'stats'));
    }

    public function create()
    {
        $clients = \App\Models\Client::all();
        $pendingInvoices = \App\Models\BillingDocument::where('status', 'pending')->with('client')->get();
        return view('sales.payments.create', compact('clients', 'pendingInvoices'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'billing_document_id' => 'required|exists:billing_documents,id',
            'paid_amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:Bank,MoMo,Card,Cheque',
            'currency' => 'required|in:USD,RWF',
        ]);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
                $invoice = \App\Models\BillingDocument::findOrFail($data['billing_document_id']);
                
                // Calculate previous payments
                $previouslyPaid = \App\Models\ClientPayment::where('billing_document_id', $invoice->id)->sum('paid_amount');
                $totalDue = $invoice->amount;
                $remainingBeforeThis = $totalDue - $previouslyPaid;

                if ($data['paid_amount'] > $remainingBeforeThis) {
                    throw new \Exception('Payment amount exceeds remaining balance on invoice.');
                }

                $remainingAfterThis = $remainingBeforeThis - $data['paid_amount'];

                $payment = \App\Models\ClientPayment::create([
                    'client_id' => $data['client_id'],
                    'billing_document_id' => $invoice->id,
                    'total_due' => $totalDue,
                    'paid_amount' => $data['paid_amount'],
                    'remaining_amount' => $remainingAfterThis,
                    'payment_date' => $data['payment_date'],
                    'payment_method' => $data['payment_method'],
                    'currency' => $data['currency'],
                    'recorded_by' => auth()->id(),
                ]);

                // Update invoice status if fully paid
                if ($remainingAfterThis <= 0) {
                    $invoice->update(['status' => 'paid']);
                }

                // Send Email Automation
                $invoice->load('client');
                if ($invoice->client->email) {
                    try {
                        \Illuminate\Support\Facades\Mail::to($invoice->client->email)->send(new \App\Mail\PaymentReceived($payment));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error("Failed to send payment email: " . $e->getMessage());
                    }
                }

                \App\Services\ActivityLogger::log('Sales', 'Client Payments', 'Create', $invoice->document_number, "Recorded {$data['currency']} {$data['paid_amount']} payment");
            });

            return redirect()->route('sales.payments.index')->with('success', 'Payment record added securely.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource's edit history.
     */
    public function history(string $id)
    {
        $payment = \App\Models\ClientPayment::with(['client', 'invoice', 'recorder'])->findOrFail($id);
        $reference = $payment->invoice->document_number ?? 'D-'.str_pad($payment->billing_document_id, 4, '0', STR_PAD_LEFT);
        
        $activities = \App\Models\ActivityLog::where('properties->module', 'Client Payments')
            ->where('properties->reference', $reference)
            ->with('user')
            ->latest()
            ->get();

        return view('sales.payments.history', compact('payment', 'activities'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
