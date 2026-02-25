<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        
        // A) Revenue Overview (Split by Currency)
        $totalBilledRwf = \App\Models\BillingDocument::whereYear('created_at', $year)->whereMonth('created_at', $month)->where('currency', 'RWF')->sum('amount');
        $totalBilledUsd = \App\Models\BillingDocument::whereYear('created_at', $year)->whereMonth('created_at', $month)->where('currency', 'USD')->sum('amount');
        
        $totalPaymentRwf = \App\Models\ClientPayment::whereYear('payment_date', $year)->whereMonth('payment_date', $month)->where('currency', 'RWF')->sum('paid_amount');
        $totalPaymentUsd = \App\Models\ClientPayment::whereYear('payment_date', $year)->whereMonth('payment_date', $month)->where('currency', 'USD')->sum('paid_amount');
        
        $outstandingRwf = $totalBilledRwf - $totalPaymentRwf;
        $outstandingUsd = $totalBilledUsd - $totalPaymentUsd;
        
        $revenueOverview = [
            'rwf' => [
                'total_billed' => $totalBilledRwf,
                'total_payment' => $totalPaymentRwf,
                'outstanding' => $outstandingRwf > 0 ? $outstandingRwf : 0,
                'net_position' => $totalPaymentRwf,
            ],
            'usd' => [
                'total_billed' => $totalBilledUsd,
                'total_payment' => $totalPaymentUsd,
                'outstanding' => $outstandingUsd > 0 ? $outstandingUsd : 0,
                'net_position' => $totalPaymentUsd,
            ]
        ];

        // B) Regulatory Compliance
        $regulatoryCompliance = [
            'ebm_filings' => \App\Models\Ebm::whereYear('date', $year)->whereMonth('date', $month)->count(),
            'rra_declarations' => \App\Models\RraFile::whereYear('created_at', $year)->whereMonth('created_at', $month)->count(),
            'rssb_declarations' => \App\Models\TaxCompliance::whereIn('type', ['pension', 'maternity', 'bhi'])->whereYear('created_at', $year)->whereMonth('created_at', $month)->count(),
            'pending_items' => \App\Models\TaxCompliance::whereNull('payment_date')->whereYear('created_at', $year)->whereMonth('created_at', $month)->count(),
        ];

        $clientFinancial = [
            'institutional_clients' => \App\Models\Client::whereNotNull('organization_id')->count(),
            'active_billing' => \App\Models\BillingDocument::whereIn('status', ['pending'])->distinct('client_id')->count('client_id'),
            'overdue_accounts' => \App\Models\BillingDocument::where('status', 'pending')->where('due_date', '<', now())->count(),
            'recently_paid' => \App\Models\ClientPayment::where('payment_date', '>=', now()->subDays(30))->count(),
        ];

        return view('sales.dashboard', compact('year', 'month', 'revenueOverview', 'regulatoryCompliance', 'clientFinancial'));
    }}
