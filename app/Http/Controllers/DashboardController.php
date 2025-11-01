<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataFeed;
use App\Models\Transaction;
use App\Models\ApplicationConfigurationSetting;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $cardData = $this->getDashboardCardData();
        $getTodaysIncomeSales = $this->calculateTodaysIncomeSales();
        $getTodaysExpense = $this->calculateTodaysExpense();
        $getTodaysNetIncome = $this->calculateTodaysNetIncome();
        $monthlyBusinessGoals = $this->monthlyBusinessGoals();
        $topIncomeTransactions = $this->topTransactions();
        $topExpenseTransactions = $this->topExpenseTransactions();


        return view('pages/dashboard/dashboard', compact(
            'cardData',
            'getTodaysIncomeSales',
            'getTodaysExpense',
            'getTodaysNetIncome',
            'monthlyBusinessGoals',
            'topIncomeTransactions',
            'topExpenseTransactions'
        ));
    }

    public function topTransactions()
    {
        // Fetch top 10 highest income transactions, most recent first
        $transactions = Transaction::with('employee')
            ->where('transaction_type', 'Income') // ensure correct column name
            ->orderBy('date', 'desc')      // most recent first
            ->orderBy('amount', 'desc')          // highest amounts first
            ->take(10)
            ->get();

        return $transactions;
    }

    public function topExpenseTransactions()
    {
        // Fetch top 10 highest income transactions, most recent first
        $transactions = Transaction::with('employee')
            ->where('transaction_type', 'Expense') // ensure correct column name
            ->orderBy('date', 'desc')      // most recent first
            ->orderBy('amount', 'desc')          // highest amounts first
            ->take(10)
            ->get();

        return $transactions;
    }



    /**
     * Summary of today's income sales compared to yesterday
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function getTodaysIncomeSales()
    {
        return response()->json($this->calculateTodaysIncomeSales());
    }

    /**
     * Displays the analytics screen
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function analytics()
    {
        return view('pages/dashboard/analytics');
    }

    /**
     * Displays the fintech screen
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function fintech()
    {
        return view('pages/dashboard/fintech');
    }


    private function calculateTodaysIncomeSales()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $todayIncome = Transaction::where('transaction_type', 'Income')
            ->whereDate('date', $today)
            ->sum('amount');

        $yesterdayIncome = Transaction::where('transaction_type', 'Income')
            ->whereDate('date', $yesterday)
            ->sum('amount');

        $percentageChange = 0;
        $trend = 'no_change';

        if ($yesterdayIncome > 0) {
            $percentageChange = (($todayIncome - $yesterdayIncome) / $yesterdayIncome) * 100;
            if ($todayIncome > $yesterdayIncome) {
                $trend = 'increase';
            } elseif ($todayIncome < $yesterdayIncome) {
                $trend = 'decrease';
            }
        } elseif ($yesterdayIncome == 0 && $todayIncome > 0) {
            // Today positive, yesterday zero → show as 100% or 'N/A'
            $percentageChange = 100; // or null if you prefer 'N/A'
            $trend = 'increase';
        } elseif ($todayIncome == 0 && $yesterdayIncome == 0) {
            $percentageChange = 0;
            $trend = 'no_change';
        }

        return [
            'today_income' => $todayIncome,
            'yesterday_income' => $yesterdayIncome,
            'percentage_change' => round($percentageChange, 2),
            'trend' => $trend,
        ];
    }

    private function calculateTodaysExpense()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $todayExpense = Transaction::where('transaction_type', 'Expense')
            ->whereDate('date', $today)
            ->sum('amount');

        $yesterdayExpense = Transaction::where('transaction_type', 'Expense')
            ->whereDate('date', $yesterday)
            ->sum('amount');

        $percentageChange = null;
        $trend = 'no_change';

        if ($yesterdayExpense > 0) {
            $percentageChange = (($todayExpense - $yesterdayExpense) / $yesterdayExpense) * 100;
            $trend = $todayExpense > $yesterdayExpense ? 'increase' : ($todayExpense < $yesterdayExpense ? 'decrease' : 'no_change');
        } elseif ($todayExpense > 0 && $yesterdayExpense == 0) {
            $percentageChange = null; // undefined over 0
            $trend = 'increase';
        } elseif ($todayExpense == 0 && $yesterdayExpense == 0) {
            $percentageChange = 0;
            $trend = 'no_change';
        }

        return [
            'today_expense' => $todayExpense,
            'yesterday_expense' => $yesterdayExpense,
            'percentage_change' => $percentageChange !== null ? round($percentageChange, 2) : null,
            'trend' => $trend,
        ];
    }


    private function calculateTodaysNetIncome()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Get today's totals
        $todayIncome = Transaction::where('transaction_type', 'Income')
            ->whereDate('date', $today)
            ->sum('amount');

        $todayExpense = Transaction::where('transaction_type', 'Expense')
            ->whereDate('date', $today)
            ->sum('amount');

        // Get yesterday's totals
        $yesterdayIncome = Transaction::where('transaction_type', 'Income')
            ->whereDate('date', $yesterday)
            ->sum('amount');

        $yesterdayExpense = Transaction::where('transaction_type', 'Expense')
            ->whereDate('date', $yesterday)
            ->sum('amount');

        // Calculate net incomes
        $todayNet = $todayIncome - $todayExpense;
        $yesterdayNet = $yesterdayIncome - $yesterdayExpense;

        $percentageChange = null;
        $trend = 'no_change';

        // Determine trend and percentage
        if ($yesterdayNet === 0) {
            if ($todayNet === 0) {
                $percentageChange = 0;
                $trend = 'no_change';
            } else {
                $percentageChange = null; // Undefined percentage when baseline is 0
                $trend = $todayNet > 0 ? 'increase' : 'decrease';
            }
        } else {
            // Use relative change based on sign of yesterday's net
            $percentageChange = (($todayNet - $yesterdayNet) / abs($yesterdayNet)) * 100;

            if ($todayNet > $yesterdayNet) {
                $trend = 'increase';
            } elseif ($todayNet < $yesterdayNet) {
                $trend = 'decrease';
            } else {
                $trend = 'no_change';
            }
        }

        return [
            'today_net' => $todayNet,
            'yesterday_net' => $yesterdayNet,
            'percentage_change' => $percentageChange !== null ? round($percentageChange, 2) : null,
            'trend' => $trend,
        ];
    }

    private function getDashboardCardData()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Today Invoices
        $todayInvoicesCount = Transaction::where('transaction_type', 'Income')
            ->whereDate('date', $today)
            ->count();

        // This Month Invoices
        $monthInvoicesCount = Transaction::where('transaction_type', 'Income')
            ->whereBetween('date', [$startOfMonth, Carbon::now()])
            ->count();

        // Today Sales
        $todaySales = Transaction::where('transaction_type', 'Income')
            ->whereDate('date', $today)
            ->sum('amount');

        // This Month Sales
        $monthSales = Transaction::where('transaction_type', 'Income')
            ->whereBetween('date', [$startOfMonth, Carbon::now()])
            ->sum('amount');

        return [
            'today_invoices' => $todayInvoicesCount,
            'month_invoices' => $monthInvoicesCount,
            'today_sales' => $todaySales,
            'month_sales' => $monthSales,
        ];
    }

    /**
     * Returns the application busines goal settings overview
     */
    public function monthlyBusinessGoals()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Income this month
        $incomeThisMonth = Transaction::where('transaction_type', 'Income')
            ->whereBetween('date', [$startOfMonth, Carbon::now()])
            ->sum('amount');

        $incomeTarget = (float) ApplicationConfigurationSetting::get('monthly_income_target', 0);

        // Expenses this month
        $expensesThisMonth = Transaction::where('transaction_type', 'Expense')
            ->whereBetween('date', [$startOfMonth, Carbon::now()])
            ->sum('amount');

        $expensesTarget = (float) ApplicationConfigurationSetting::get('monthly_expenses_target', 0);

        // Sales this month
        $salesThisMonth = Transaction::where('transaction_type', 'Income')
            ->whereBetween('date', [$startOfMonth, Carbon::now()])
            ->sum('amount'); // Assuming sales = income

        $salesTarget = (float) ApplicationConfigurationSetting::get('monthly_sales_target', 0);

        // Net Income this month
        $netIncomeThisMonth = $incomeThisMonth - $expensesThisMonth;
        $netIncomeTarget = $incomeTarget - $expensesTarget;
        // (float) ApplicationConfigurationSetting::get('monthly_net_income_target', 0);

        // Build card data array
        $cards = [
            [
                'title' =>  date('F') . ' Income',
                'value' => $incomeThisMonth,
                'target' => $incomeTarget,
                'percentage' => $incomeTarget > 0 ? round(($incomeThisMonth / $incomeTarget) * 100, 0) : 0,
                'color' => 'teal',
                'icon'  => 'money',
            ],
            [
                'title' => date('F') . ' Expenses',
                'value' => $expensesThisMonth,
                'target' => $expensesTarget,
                'percentage' => $expensesTarget > 0 ? round(($expensesThisMonth / $expensesTarget) * 100, 0) : 0,
                'color' => 'red',
                'icon'  => 'arrow-up',
            ],
            [
                'title' =>  date('F') . 'Net Income',
                'value' => $netIncomeThisMonth,
                'target' => $netIncomeTarget,
                'percentage' => $netIncomeTarget > 0 ? round(($netIncomeThisMonth / $netIncomeTarget) * 100, 0) : 0,
                'color' => 'orange',
                'icon'  => 'box',
            ],
        ];
        return $cards;
    }
}
