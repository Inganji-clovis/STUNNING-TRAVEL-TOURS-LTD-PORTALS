<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\CashCategory;
use App\Models\CashDay;
use App\Models\CashTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashbookController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d');
        $cashDay = CashDay::where('date', $today)->first();
        
        // If no day exists for today, we show the "Open Day" screen
        if (!$cashDay) {
            $lastDay = CashDay::orderBy('date', 'desc')->first();
            $suggestedOpening = $lastDay ? $lastDay->closing_balance : 0;
            $suggestedPettyOpening = $lastDay ? $lastDay->closing_petty_balance : 0;
            $suggestedOpeningUsd = $lastDay ? $lastDay->closing_balance_usd : 0;
            $suggestedPettyOpeningUsd = $lastDay ? $lastDay->closing_petty_balance_usd : 0;
            return view('sales.cashbook.open_day', compact('today', 'suggestedOpening', 'suggestedPettyOpening', 'suggestedOpeningUsd', 'suggestedPettyOpeningUsd'));
        }

        $transactions = $cashDay->transactions()->with(['category', 'creator'])->latest()->get();
        
        $mainIncomeRw = $transactions->where('source', 'main')->where('currency', 'RWF')->where('type', 'income')->sum('amount');
        $mainExpenseRw = $transactions->where('source', 'main')->where('currency', 'RWF')->where('type', 'expense')->sum('amount');
        $mainIncomeUsd = $transactions->where('source', 'main')->where('currency', 'USD')->where('type', 'income')->sum('amount');
        $mainExpenseUsd = $transactions->where('source', 'main')->where('currency', 'USD')->where('type', 'expense')->sum('amount');
        
        $pettyIncomeRw = $transactions->where('source', 'petty')->where('currency', 'RWF')->where('type', 'income')->sum('amount');
        $pettyExpenseRw = $transactions->where('source', 'petty')->where('currency', 'RWF')->where('type', 'expense')->sum('amount');
        $pettyIncomeUsd = $transactions->where('source', 'petty')->where('currency', 'USD')->where('type', 'income')->sum('amount');
        $pettyExpenseUsd = $transactions->where('source', 'petty')->where('currency', 'USD')->where('type', 'expense')->sum('amount');
        
        // Dynamic balances for open day
        $currentMainBalanceRw = $cashDay->opening_balance + $mainIncomeRw - $mainExpenseRw;
        $currentPettyBalanceRw = $cashDay->opening_petty_balance + $pettyIncomeRw - $pettyExpenseRw;
        
        $currentMainBalanceUsd = $cashDay->opening_balance_usd + $mainIncomeUsd - $mainExpenseUsd;
        $currentPettyBalanceUsd = $cashDay->opening_petty_balance_usd + $pettyIncomeUsd - $pettyExpenseUsd;
        
        $categories = CashCategory::where('is_active', true)->get();
        
        return view('sales.cashbook.index', compact(
            'cashDay', 'transactions', 'categories',
            'mainIncomeRw', 'mainExpenseRw', 'pettyIncomeRw', 'pettyExpenseRw',
            'mainIncomeUsd', 'mainExpenseUsd', 'pettyIncomeUsd', 'pettyExpenseUsd',
            'currentMainBalanceRw', 'currentPettyBalanceRw', 'currentMainBalanceUsd', 'currentPettyBalanceUsd'
        ));
    }

    public function openDay(Request $request)
    {
        $request->validate([
            'date' => 'required|date|unique:cash_days,date',
            'opening_balance' => 'required|numeric|min:0',
            'opening_petty_balance' => 'required|numeric|min:0',
            'opening_balance_usd' => 'required|numeric|min:0',
            'opening_petty_balance_usd' => 'required|numeric|min:0',
        ]);

        CashDay::create([
            'date' => $request->date,
            'opening_balance' => $request->opening_balance,
            'opening_petty_balance' => $request->opening_petty_balance,
            'opening_balance_usd' => $request->opening_balance_usd,
            'opening_petty_balance_usd' => $request->opening_petty_balance_usd,
            'status' => 'open',
        ]);

        return redirect()->route('sales.cashbook.index')->with('success', 'Financial day opened successfully. You can now view the ledger.');
    }

    public function closeDay(Request $request)
    {
        // Only Admin can close the day as per requirements
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'Only administrators can close the financial day.');
        }

        $cashDay = CashDay::findOrFail($request->cash_day_id);
        
        if ($cashDay->status === 'closed') {
            return back()->with('error', 'This day is already closed.');
        }

        $mainIncomeRw = $cashDay->transactions()->where('source', 'main')->where('currency', 'RWF')->where('type', 'income')->sum('amount');
        $mainExpenseRw = $cashDay->transactions()->where('source', 'main')->where('currency', 'RWF')->where('type', 'expense')->sum('amount');
        $closingBalanceRw = $cashDay->opening_balance + $mainIncomeRw - $mainExpenseRw;

        $pettyIncomeRw = $cashDay->transactions()->where('source', 'petty')->where('currency', 'RWF')->where('type', 'income')->sum('amount');
        $pettyExpenseRw = $cashDay->transactions()->where('source', 'petty')->where('currency', 'RWF')->where('type', 'expense')->sum('amount');
        $closingPettyBalanceRw = $cashDay->opening_petty_balance + $pettyIncomeRw - $pettyExpenseRw;

        $mainIncomeUsd = $cashDay->transactions()->where('source', 'main')->where('currency', 'USD')->where('type', 'income')->sum('amount');
        $mainExpenseUsd = $cashDay->transactions()->where('source', 'main')->where('currency', 'USD')->where('type', 'expense')->sum('amount');
        $closingBalanceUsd = $cashDay->opening_balance_usd + $mainIncomeUsd - $mainExpenseUsd;

        $pettyIncomeUsd = $cashDay->transactions()->where('source', 'petty')->where('currency', 'USD')->where('type', 'income')->sum('amount');
        $pettyExpenseUsd = $cashDay->transactions()->where('source', 'petty')->where('currency', 'USD')->where('type', 'expense')->sum('amount');
        $closingPettyBalanceUsd = $cashDay->opening_petty_balance_usd + $pettyIncomeUsd - $pettyExpenseUsd;

        $cashDay->update([
            'closing_balance' => $closingBalanceRw,
            'closing_petty_balance' => $closingPettyBalanceRw,
            'closing_balance_usd' => $closingBalanceUsd,
            'closing_petty_balance_usd' => $closingPettyBalanceUsd,
            'status' => 'closed',
            'closed_by' => Auth::id(),
            'closed_at' => now(),
        ]);

        return redirect()->route('sales.dashboard')->with('success', 'Financial day closed and locked.');
    }

    public function storeTransaction(Request $request)
    {
        $request->validate([
            'cash_day_id' => 'required|exists:cash_days,id',
            'category_id' => 'required|exists:cash_categories,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:RWF,USD',
            'source' => 'required|in:main,petty',
            'payment_method' => 'nullable|string',
            'description' => 'nullable|string',
            'rc' => 'nullable|numeric|min:0',
            'ttc' => 'nullable|numeric|min:0',
            'service_fee' => 'nullable|numeric|min:0',
        ]);

        $cashDay = CashDay::findOrFail($request->cash_day_id);
        
        if ($cashDay->status === 'closed') {
            return back()->with('error', 'Cannot add transactions to a closed day.');
        }

        $category = CashCategory::findOrFail($request->category_id);

        if ($category->name === 'Airticket' || $category->name === 'Airtickets' || strtolower($category->name) === 'airticket') {
            $rc = (float) $request->rc;
            $ttc = (float) $request->ttc;
            $service_fee = (float) $request->service_fee;

            // Validate logic
            if (abs($rc - ($ttc + $service_fee)) > 0.01) {
                return back()->with('error', 'Ticketing Error: Rate Collected (RC) must exactly equal Total Ticket Cost (TTC) plus Service Fee.')->withInput();
            }

            if (abs((float)$request->amount - $rc) > 0.01) {
                return back()->with('error', 'Ticketing Error: Total amount must match Rate Collected (RC).')->withInput();
            }
        }

        CashTransaction::create([
            'cash_day_id' => $request->cash_day_id,
            'category_id' => $request->category_id,
            'type' => $category->type,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'source' => $request->source,
            'payment_method' => $request->payment_method,
            'rc' => $request->rc,
            'ttc' => $request->ttc,
            'service_fee' => $request->service_fee,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'Transaction recorded.');
    }

    public function categories()
    {
        $categories = CashCategory::orderBy('type')->orderBy('name')->get();
        return view('sales.cashbook.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'classification' => 'required|in:opex,capex,none',
        ]);

        CashCategory::create($request->all());

        return back()->with('success', 'Category created successfully.');
    }

    public function reports(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $days = CashDay::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc')
            ->get();

        $transactions = CashTransaction::whereHas('cashDay', function($q) use ($year, $month) {
            $q->whereYear('date', $year)->whereMonth('date', $month);
        })->with('category')->get();

        $monthlyIncomeRw = $transactions->where('currency', 'RWF')->where('type', 'income')->sum('amount');
        $monthlyIncomeUsd = $transactions->where('currency', 'USD')->where('type', 'income')->sum('amount');
        
        $monthlyOpexRw = $transactions->where('currency', 'RWF')->filter(function($t) {
            return $t->category && $t->category->classification === 'opex';
        })->sum('amount');
        $monthlyOpexUsd = $transactions->where('currency', 'USD')->filter(function($t) {
            return $t->category && $t->category->classification === 'opex';
        })->sum('amount');
        
        $monthlyCapexRw = $transactions->where('currency', 'RWF')->filter(function($t) {
            return $t->category && $t->category->classification === 'capex';
        })->sum('amount');
        $monthlyCapexUsd = $transactions->where('currency', 'USD')->filter(function($t) {
            return $t->category && $t->category->classification === 'capex';
        })->sum('amount');

        // Ticketing Profit and Payables
        $ticketProfitRw = $transactions->where('currency', 'RWF')->sum('service_fee');
        $ticketProfitUsd = $transactions->where('currency', 'USD')->sum('service_fee');
        
        $supplierPayableRw = $transactions->where('currency', 'RWF')->sum('ttc');
        $supplierPayableUsd = $transactions->where('currency', 'USD')->sum('ttc');

        $netPositionRw = $monthlyIncomeRw - ($monthlyOpexRw + $monthlyCapexRw);
        $netPositionUsd = $monthlyIncomeUsd - ($monthlyOpexUsd + $monthlyCapexUsd);

        return view('sales.cashbook.reports', compact(
            'days', 'month', 'year',
            'monthlyIncomeRw', 'monthlyOpexRw', 'monthlyCapexRw', 'netPositionRw',
            'monthlyIncomeUsd', 'monthlyOpexUsd', 'monthlyCapexUsd', 'netPositionUsd',
            'ticketProfitRw', 'ticketProfitUsd', 'supplierPayableRw', 'supplierPayableUsd',
            'transactions'
        ));
    }
}
