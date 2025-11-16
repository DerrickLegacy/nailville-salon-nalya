<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Transaction;


class ChartController extends Controller
{
    public function chartData()
    {
        $startDate = Carbon::now()->subMonth()->startOfDay();

        $data = DB::table('transactions')
            ->select('date', DB::raw('SUM(amount) as total'))
            ->where('transaction_type', 'Income')
            ->where('date', '>=', $startDate->toDateString()) // use date column
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Format for chart libraries like Morris.js
        $formatted = $data->map(function ($row) {
            return [
                'y' => $row->date,
                'value' => (float) $row->total
            ];
        });

        return response()->json($formatted);
    }



    public function topEmployers()
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $topEmployers = DB::table('transactions')
            ->join('employees', 'transactions.employee_id', '=', 'employees.employee_id')
            ->select(
                DB::raw("CONCAT(employees.first_name, ' ', employees.last_name) as label"),
                DB::raw('COUNT(transactions.id) as invoice_count'),
                DB::raw('SUM(transactions.amount) as total_amount')
            )
            ->where('transactions.transaction_type', 'Income')
            ->whereBetween('transactions.created_at', [$startDate, $endDate]) // ✅ restrict to current month
            ->groupBy('transactions.employee_id', 'employees.first_name', 'employees.last_name')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();

        $formatted = $topEmployers->map(function ($row) {
            return [
                'label' => $row->label,
                'value' => (float) $row->total_amount,
                'invoice_count' => (int) $row->invoice_count
            ];
        });

        return response()->json([
            'month' => Carbon::now()->format('F Y'), // e.g. "September 2025"
            'data' => $formatted
        ]);
    }
    public function dailyTransactionsChart()
    {
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;

        // Number of days in the current month
        $daysInMonth = Carbon::now()->daysInMonth;

        // Step 1: Fetch transactions grouped by day
        $transactions = Transaction::select(
            DB::raw('DAY(date) as day'),
            DB::raw('SUM(CASE WHEN transaction_type = "Income" THEN amount ELSE 0 END) as income'),
            DB::raw('SUM(CASE WHEN transaction_type = "Expense" THEN amount ELSE 0 END) as expense'),
            DB::raw('SUM(CASE WHEN transaction_type = "Income" THEN 1 ELSE 0 END) as income_count'),
            DB::raw('SUM(CASE WHEN transaction_type = "Expense" THEN 1 ELSE 0 END) as expense_count')
        )
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day'); // key by day for easy lookup

        // Step 2: Build result with all days of month
        $chartData = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {

            $t = $transactions->get($day); // returns null if no record on that day

            $chartData[] = [
                'day' => $day,
                'Income' => $t ? (float)$t->income : 0,
                'Expense' => $t ? (float)$t->expense : 0,
                'IncomeCount' => $t ? (int)$t->income_count : 0,
                'ExpenseCount' => $t ? (int)$t->expense_count : 0,
            ];
        }

        return $chartData;
    }

    public function monthlyTransactionsChart()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $data = DB::table('transactions')
            ->select(
                DB::raw("DATE_FORMAT(MIN(date), '%b') as month"),
                DB::raw("SUM(CASE WHEN transaction_type = 'Income' THEN amount ELSE 0 END) as Income"),
                DB::raw("SUM(CASE WHEN transaction_type = 'Expense' THEN amount ELSE 0 END) as Expense")
            )
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->groupBy(DB::raw("YEAR(date), MONTH(date)"))
            ->limit(1)
            ->get();

        if ($data->isEmpty()) {
            $data = collect([[
                'month' => Carbon::now()->format('M'),
                'Income' => 0,
                'Expense' => 0,
            ]]);
        }

        return response()->json($data);
    }

    public function yearlyTransactionsChart()
    {
        $year = Carbon::now()->year;

        // Group transactions by month
        $transactions = DB::table('transactions')
            ->select(
                DB::raw('MONTH(date) as month_number'),
                DB::raw("SUM(CASE WHEN transaction_type = 'Income' THEN amount ELSE 0 END) as Income"),
                DB::raw("SUM(CASE WHEN transaction_type = 'Expense' THEN amount ELSE 0 END) as Expense")
            )
            ->whereYear('date', $year)
            ->groupBy(DB::raw('MONTH(date)'))
            ->orderBy(DB::raw('MONTH(date)'))
            ->get()
            ->keyBy('month_number');

        // Build full 12 months, fill missing months with 0
        $chartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartData[] = [
                'month' => Carbon::create()->month($m)->format('M'),
                'Income' => isset($transactions[$m]) ? (float)$transactions[$m]->Income : 0,
                'Expense' => isset($transactions[$m]) ? (float)$transactions[$m]->Expense : 0,
            ];
        }

        return response()->json($chartData);
    }
}
