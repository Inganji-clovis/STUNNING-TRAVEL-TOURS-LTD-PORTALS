<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CashTransaction;
use App\Models\CashDay;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        // Base query for the month
        $baseQuery = CashTransaction::with('category', 'cashDay')
            ->whereHas('cashDay', function($q) use ($month, $year) {
                $q->whereMonth('date', $month)->whereYear('date', $year);
            });

        // 1. Income by Category
        $incomeByCategory = (clone $baseQuery)
            ->where('type', 'income')
            ->selectRaw('category_id, currency, SUM(amount) as total')
            ->groupBy('category_id', 'currency')
            ->get()
            ->groupBy('category.name');

        // 2. OPEX vs CAPEX
        $expenses = (clone $baseQuery)
            ->where('type', 'expense')
            ->get();
        
        $opexRwf = $expenses->where('currency', 'RWF')->filter(fn($e) => str_contains($e->category->name, 'OPEX'))->sum('amount');
        $opexUsd = $expenses->where('currency', 'USD')->filter(fn($e) => str_contains($e->category->name, 'OPEX'))->sum('amount');
        
        $capexRwf = $expenses->where('currency', 'RWF')->filter(fn($e) => str_contains($e->category->name, 'CAPEX'))->sum('amount');
        $capexUsd = $expenses->where('currency', 'USD')->filter(fn($e) => str_contains($e->category->name, 'CAPEX'))->sum('amount');

        // 3. Ticket Profit & 4. Supplier Payable
        $tickets = (clone $baseQuery)
            ->whereNotNull('tts')
            ->get();
            
        $totalProfitRwf = $tickets->where('currency', 'RWF')->sum('service_fee');
        $totalProfitUsd = $tickets->where('currency', 'USD')->sum('service_fee');
        
        $supplierPayables = $tickets->groupBy('supplier_name')->map(function($ticketsData) {
            return [
                'RWF' => $ticketsData->where('currency', 'RWF')->sum('tts'),
                'USD' => $ticketsData->where('currency', 'USD')->sum('tts'),
            ];
        });

        // 5. Monthly Summary
        $monthlyIncomeRwf = (clone $baseQuery)->where('type', 'income')->where('currency', 'RWF')->sum('amount');
        $monthlyIncomeUsd = (clone $baseQuery)->where('type', 'income')->where('currency', 'USD')->sum('amount');
        
        $monthlyExpenseRwf = (clone $baseQuery)->where('type', 'expense')->where('currency', 'RWF')->sum('amount');
        $monthlyExpenseUsd = (clone $baseQuery)->where('type', 'expense')->where('currency', 'USD')->sum('amount');

        return view('sales.reports.index', compact(
            'month', 'year',
            'incomeByCategory',
            'opexRwf', 'opexUsd', 'capexRwf', 'capexUsd',
            'totalProfitRwf', 'totalProfitUsd',
            'supplierPayables',
            'monthlyIncomeRwf', 'monthlyIncomeUsd',
            'monthlyExpenseRwf', 'monthlyExpenseUsd'
        ));
    }
}
