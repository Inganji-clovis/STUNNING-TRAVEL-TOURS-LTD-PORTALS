<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CashDay;
use App\Models\CashTransaction;
use App\Models\CashCategory;
use Illuminate\Support\Facades\DB;

class FinancialController extends Controller
{
    private function getOpenCashDay()
    {
        $day = CashDay::where('status', 'open')->first();
        if (!$day) {
            // Find last closed day to carry over balances
            $lastDay = CashDay::where('status', 'closed')->orderBy('date', 'desc')->first();
            
            $day = CashDay::create([
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
        return $day;
    }

    private function getCategory($name, $type)
    {
        return CashCategory::firstOrCreate(
            ['name' => $name, 'type' => $type],
            ['classification' => $type, 'is_active' => true]
        );
    }

    public function storeIncome(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:RWF,USD',
            'category' => 'required|string',
            'payment_method' => 'required|string',
            'description' => 'nullable|string'
        ]);

        $day = $this->getOpenCashDay();
        $category = $this->getCategory($request->category, 'income');

        CashTransaction::create([
            'cash_day_id' => $day->id,
            'category_id' => $category->id,
            'type' => 'income',
            'amount' => $request->amount,
            'currency' => $request->currency,
            'source' => 'main',
            'payment_method' => $request->payment_method,
            'description' => $request->description,
            'created_by' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Income recorded successfully.');
    }

    public function storeExpense(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:RWF,USD',
            'category' => 'required|string', // OPEX / CAPEX
            'source' => 'required|in:main,petty',
            'description' => 'required|string'
        ]);

        $day = $this->getOpenCashDay();
        $category = $this->getCategory($request->category, 'expense');

        CashTransaction::create([
            'cash_day_id' => $day->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => $request->amount,
            'currency' => $request->currency,
            'source' => $request->source,
            'description' => $request->description,
            'created_by' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Expense recorded successfully.');
    }

    public function storeTicketSale(Request $request)
    {
        $request->validate([
            'ttc' => 'required|numeric|min:0',
            'service_fee' => 'required|numeric|min:0',
            'rc' => 'required|numeric|min:0',
            'currency' => 'required|in:RWF,USD',
            'supplier_name' => 'required|string',
            'payment_method' => 'required|string',
            'description' => 'nullable|string'
        ]);

        // RC = TTC + Service Fee validation
        $expectedRc = $request->ttc + $request->service_fee;
        if ((float)$request->rc !== (float)$expectedRc) {
            return redirect()->back()->withErrors(['rc' => 'Rate Collected (RC) must exactly equal TTC + Service Fee. Expected: ' . $expectedRc])->withInput();
        }

        $day = $this->getOpenCashDay();
        $category = $this->getCategory('Airticket', 'income');

        CashTransaction::create([
            'cash_day_id' => $day->id,
            'category_id' => $category->id,
            'type' => 'income',
            // amount here is RC. Or maybe Service fee?
            // The prompt says: "Only Service Fee counts as profit. TTC is liability... RC is Cash Inflow."
            // So the cash box gets +RC.
            'amount' => $request->rc,
            'currency' => $request->currency,
            'source' => 'main',
            'payment_method' => $request->payment_method,
            'description' => $request->description ?? 'Ticket Sale',
            'ttc' => $request->ttc,
            'service_fee' => $request->service_fee,
            'supplier_name' => $request->supplier_name,
            'created_by' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Ticket sale recorded successfully.');
    }

    public function storePettyCash(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:RWF,USD',
            'type' => 'required|in:added,spend',
            'description' => 'required|string'
        ]);

        $day = $this->getOpenCashDay();
        // Petty cash added = moving money from main to petty? Or from external?
        // Let's treat "added" as an income type 'petty_cash_addition' which increases petty balance but also we might need to deduct from main if it comes from main. The prompt says: "Petty cash expenses must not directly reduce main cash unless replenished."
        // We will just record it as a transaction with source 'petty'.
        
        $category = $this->getCategory($request->type == 'added' ? 'Petty Cash Funding' : 'Petty Cash Expense', $request->type == 'added' ? 'income' : 'expense');

        CashTransaction::create([
            'cash_day_id' => $day->id,
            'category_id' => $category->id,
            'type' => $request->type == 'added' ? 'income' : 'expense',
            'amount' => $request->amount,
            'currency' => $request->currency,
            'source' => 'petty',
            'description' => $request->description,
            'created_by' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Petty cash updated successfully.');
    }

    public function closeDay(Request $request)
    {
        $day = CashDay::where('status', 'open')->first();
        if (!$day) {
            return redirect()->back()->withErrors(['closing' => 'No open day found.']);
        }

        // Calculate totals dynamically from transactions
        $transactions = $day->transactions;
        
        // RWF Calculations
        $rwfIncome = $transactions->where('currency', 'RWF')->where('type', 'income')->where('source', 'main')->sum('amount');
        $rwfExpense = $transactions->where('currency', 'RWF')->where('type', 'expense')->where('source', 'main')->sum('amount');
        $newClosingRwf = $day->opening_balance + $rwfIncome - $rwfExpense;
        
        // USD Calculations
        $usdIncome = $transactions->where('currency', 'USD')->where('type', 'income')->where('source', 'main')->sum('amount');
        $usdExpense = $transactions->where('currency', 'USD')->where('type', 'expense')->where('source', 'main')->sum('amount');
        $newClosingUsd = $day->opening_balance_usd + $usdIncome - $usdExpense;

        // Petty RWF
        $pettyRwfIncome = $transactions->where('currency', 'RWF')->where('type', 'income')->where('source', 'petty')->sum('amount');
        $pettyRwfExpense = $transactions->where('currency', 'RWF')->where('type', 'expense')->where('source', 'petty')->sum('amount');
        $newClosingPettyRwf = $day->opening_petty_balance + $pettyRwfIncome - $pettyRwfExpense;

        // Petty USD
        $pettyUsdIncome = $transactions->where('currency', 'USD')->where('type', 'income')->where('source', 'petty')->sum('amount');
        $pettyUsdExpense = $transactions->where('currency', 'USD')->where('type', 'expense')->where('source', 'petty')->sum('amount');
        $newClosingPettyUsd = $day->opening_petty_balance_usd + $pettyUsdIncome - $pettyUsdExpense;

        if ($newClosingRwf < 0 || $newClosingUsd < 0) {
            return redirect()->back()->withErrors(['closing' => 'Closing balance cannot be negative. Please check transactions.']);
        }

        $day->update([
            'closing_balance' => $newClosingRwf,
            'closing_balance_usd' => $newClosingUsd,
            'closing_petty_balance' => $newClosingPettyRwf,
            'closing_petty_balance_usd' => $newClosingPettyUsd,
            'status' => 'closed',
            'closed_by' => auth()->id(),
            'closed_at' => now(),
        ]);

        // Auto-create next day
        CashDay::create([
            'date' => now()->toDateString(),
            'opening_balance' => $newClosingRwf,
            'closing_balance' => $newClosingRwf,
            'opening_petty_balance' => $newClosingPettyRwf,
            'closing_petty_balance' => $newClosingPettyRwf,
            'opening_balance_usd' => $newClosingUsd,
            'closing_balance_usd' => $newClosingUsd,
            'opening_petty_balance_usd' => $newClosingPettyUsd,
            'closing_petty_balance_usd' => $newClosingPettyUsd,
            'status' => 'open'
        ]);

        return redirect()->back()->with('success', 'Day closed successfully. New day started.');
    }
}
