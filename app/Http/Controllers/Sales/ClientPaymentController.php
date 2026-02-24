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
        $totalOutstanding = 0;
        $totalOverdue = 0;
        $now = now();
        
        foreach ($pendingInvoices as $inv) {
            $paid = $inv->clientPayments->sum('paid_amount');
            $balance = max(0, $inv->amount - $paid);
            $totalOutstanding += $balance;
            
            if ($inv->due_date && $inv->due_date < $now) {
                $totalOverdue += $balance;
            }
        }

        $stats = [
            'total_payment' => \App\Models\ClientPayment::sum('paid_amount'),
            'total_outstanding' => $totalOutstanding,
            'total_overdue' => $totalOverdue,
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

                \App\Models\ClientPayment::create([
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

                \App\Services\ActivityLogger::log('Sales', 'Client Payments', 'Create', $invoice->document_number, "Recorded {$data['currency']} {$data['paid_amount']} payment");
            });

            return redirect()->route('sales.payments.index')->with('success', 'Payment record added securely.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
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
