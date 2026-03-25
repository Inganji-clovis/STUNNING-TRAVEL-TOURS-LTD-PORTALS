<?php

namespace App\Http\Controllers\Md;

use App\Http\Controllers\Controller;
use App\Models\CashDay;
use App\Models\CashTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FinancialReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('m'));
        $year = $request->get('year', now()->format('Y'));
        
        $selectedDate = Carbon::createFromDate($year, $month, 1);
        $startDate = $selectedDate->copy()->startOfMonth();
        $endDate = $selectedDate->copy()->endOfMonth();

        $cashDays = CashDay::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'asc')
            ->get();

        // Monthly Totals
        $firstDay = $cashDays->first();
        $lastDay = $cashDays->last();

        $totalOpeningBalance = $firstDay ? ($firstDay->opening_balance + $firstDay->opening_petty_balance) : 0;
        
        $monthlyTransactions = CashTransaction::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->get();

        $monthlyIncome = $monthlyTransactions->where('type', 'income')->sum('amount');
        
        $opexTransactions = $monthlyTransactions->filter(function($t) {
            return $t->category && $t->category->classification === 'opex';
        });
        $monthlyOpex = $opexTransactions->sum('amount');

        $capexTransactions = $monthlyTransactions->filter(function($t) {
            return $t->category && $t->category->classification === 'capex';
        });
        $monthlyCapex = $capexTransactions->sum('amount');

        $totalExpenses = $monthlyOpex + $monthlyCapex;
        $netPosition = $monthlyIncome - $totalExpenses;
        
        $latestClosingBalance = $lastDay ? ($lastDay->closing_balance + $lastDay->closing_petty_balance) : ($firstDay ? ($firstDay->opening_balance + $firstDay->opening_petty_balance) : 0);

        // Prep daily logs for table including totals per day
        $dailyLogs = $cashDays->map(function($day) {
            $dayTransactions = $day->transactions;
            $income = $dayTransactions->where('type', 'income')->sum('amount');
            $opex = $dayTransactions->filter(function($t) {
                return $t->category && $t->category->classification === 'opex';
            })->sum('amount');
            $capex = $dayTransactions->filter(function($t) {
                return $t->category && $t->category->classification === 'capex';
            })->sum('amount');
            
            return (object) [
                'id' => $day->id,
                'date' => $day->date,
                'opening' => $day->opening_balance + $day->opening_petty_balance,
                'income' => $income,
                'opex' => $opex,
                'capex' => $capex,
                'expenses' => $opex + $capex,
                'closing' => $day->closing_balance + $day->closing_petty_balance, // if closed
                'status' => $day->status
            ];
        });

        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[$m] = date('F', mktime(0, 0, 0, $m, 1));
        }

        return view('md.financial-report.index', compact(
            'dailyLogs', 'totalOpeningBalance', 'monthlyIncome', 'monthlyOpex', 
            'monthlyCapex', 'totalExpenses', 'netPosition', 'latestClosingBalance',
            'month', 'year', 'months'
        ));
    }

    public function show(CashDay $cashDay)
    {
        $transactions = $cashDay->transactions()->with(['category', 'creator'])->get();
        return view('md.financial-report.show', compact('cashDay', 'transactions'));
    }
}
