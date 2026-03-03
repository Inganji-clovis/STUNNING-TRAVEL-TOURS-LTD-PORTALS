<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $day = \App\Models\CashDay::where('status', 'open')->first();
        if (!$day) {
            $lastDay = \App\Models\CashDay::where('status', 'closed')->orderBy('date', 'desc')->first();
            $day = \App\Models\CashDay::create([
                'date' => now()->toDateString(),
                'opening_balance' => $lastDay ? $lastDay->closing_balance : 0,
                'closing_balance' => $lastDay ? $lastDay->closing_balance : 0,
                'opening_petty_balance' => $lastDay ? $lastDay->closing_petty_balance : 0,
                'closing_petty_balance' => $lastDay ? $lastDay->closing_petty_balance : 0,
                'opening_balance_usd' => $lastDay ? $lastDay->closing_balance_usd : 0,
                'closing_balance_usd' => $lastDay ? $lastDay->closing_balance_usd : 0,
                'opening_petty_balance_usd' => $lastDay ? $lastDay->closing_petty_balance_usd : 0,
                'closing_petty_balance_usd' => $lastDay ? $lastDay->closing_petty_balance_usd : 0,
                'status' => 'open'
            ]);
        }

        $transactions = $day->transactions()->with('category')->latest()->get();

        // Main Cash Calcs
        $rwfIncome = $transactions->where('currency', 'RWF')->where('type', 'income')->where('source', 'main')->sum('amount');
        $rwfExpense = $transactions->where('currency', 'RWF')->where('type', 'expense')->where('source', 'main')->sum('amount');
        $currentRwf = $day->opening_balance + $rwfIncome - $rwfExpense;

        $usdIncome = $transactions->where('currency', 'USD')->where('type', 'income')->where('source', 'main')->sum('amount');
        $usdExpense = $transactions->where('currency', 'USD')->where('type', 'expense')->where('source', 'main')->sum('amount');
        $currentUsd = $day->opening_balance_usd + $usdIncome - $usdExpense;

        // Petty Cash Calcs
        $pettyRwfIncome = $transactions->where('currency', 'RWF')->where('type', 'income')->where('source', 'petty')->sum('amount');
        $pettyRwfExpense = $transactions->where('currency', 'RWF')->where('type', 'expense')->where('source', 'petty')->sum('amount');
        $currentPettyRwf = $day->opening_petty_balance + $pettyRwfIncome - $pettyRwfExpense;

        $pettyUsdIncome = $transactions->where('currency', 'USD')->where('type', 'income')->where('source', 'petty')->sum('amount');
        $pettyUsdExpense = $transactions->where('currency', 'USD')->where('type', 'expense')->where('source', 'petty')->sum('amount');
        $currentPettyUsd = $day->opening_petty_balance_usd + $pettyUsdIncome - $pettyUsdExpense;

        return view('sales.dashboard', compact(
            'day', 'transactions',
            'rwfIncome', 'rwfExpense', 'currentRwf',
            'usdIncome', 'usdExpense', 'currentUsd',
            'pettyRwfIncome', 'pettyRwfExpense', 'currentPettyRwf',
            'pettyUsdIncome', 'pettyUsdExpense', 'currentPettyUsd'
        ));
    }
}
