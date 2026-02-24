<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        
        // A) Revenue Overview
        $totalBilled = \App\Models\BillingDocument::whereYear('created_at', $year)->sum('amount');
        $totalPayment = \App\Models\ClientPayment::whereYear('payment_date', $year)->sum('paid_amount');
        $outstanding = $totalBilled - $totalPayment; // Simplified logic for dashboard
        
        $revenueOverview = [
            'total_billed' => $totalBilled,
            'total_payment' => $totalPayment,
            'outstanding' => $outstanding > 0 ? $outstanding : 0,
            'net_position' => $totalPayment,
        ];

        // B) Regulatory Compliance
        $regulatoryCompliance = [
            'ebm_filings' => \App\Models\Ebm::whereYear('date', $year)->count(),
            'rra_declarations' => \App\Models\RraFile::whereYear('created_at', $year)->count(),
            'rssb_declarations' => \App\Models\TaxCompliance::whereIn('type', ['pension', 'maternity', 'bhi'])->whereYear('created_at', $year)->count(),
            'pending_items' => \App\Models\TaxCompliance::whereNull('payment_date')->whereYear('created_at', $year)->count(),
        ];

        $clientFinancial = [
            'institutional_clients' => \App\Models\Client::whereNotNull('organization_id')->count(),
            'active_billing' => \App\Models\BillingDocument::whereIn('status', ['pending'])->distinct('client_id')->count('client_id'),
            'overdue_accounts' => \App\Models\BillingDocument::where('status', 'pending')->where('due_date', '<', now())->count(),
            'recently_paid' => \App\Models\ClientPayment::where('payment_date', '>=', now()->subDays(30))->count(),
        ];

        return view('sales.dashboard', compact('year', 'revenueOverview', 'regulatoryCompliance', 'clientFinancial'));
    }}
