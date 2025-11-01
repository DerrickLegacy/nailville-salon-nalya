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

        // Get transactions grouped by day and type
        $transactions = Transaction::select(
            DB::raw('DAY(created_at) as day'),
            DB::raw('SUM(CASE WHEN transaction_type = "Income" THEN amount ELSE 0 END) as income'),
            DB::raw('SUM(CASE WHEN transaction_type = "Expense" THEN amount ELSE 0 END) as expense'),
            DB::raw('SUM(CASE WHEN transaction_type = "Income" THEN 1 ELSE 0 END) as income_count'),
            DB::raw('SUM(CASE WHEN transaction_type = "Expense" THEN 1 ELSE 0 END) as expense_count')
        )
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // Prepare data for line chart
        $chartData = [];
        foreach ($transactions as $t) {
            $chartData[] = [
                'day' => $t->day, // X-axis: day of the month
                'Income' => (float) $t->income,
                'Expense' => (float) $t->expense,
                'IncomeCount' => (int) $t->income_count,
                'ExpenseCount' => (int) $t->expense_count,
            ];
        }

        return $chartData;
    }
    public function monthlyTransactionsChart()
    {
        $year = Carbon::now()->year;

        // Get transactions grouped by month and type
        $transactions = Transaction::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(CASE WHEN transaction_type = "Income" THEN amount ELSE 0 END) as income'),
            DB::raw('SUM(CASE WHEN transaction_type = "Expense" THEN amount ELSE 0 END) as expense')
        )
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Prepare data for Morris.js
        $chartData = [];
        foreach ($transactions as $t) {
            $chartData[] = [
                'month' => Carbon::create()->month($t->month)->format('M'), // e.g., 'Sep'
                'Income' => (float) $t->income,
                'Expense' => (float) $t->expense,
            ];
        }

        return $chartData;
    }
}
