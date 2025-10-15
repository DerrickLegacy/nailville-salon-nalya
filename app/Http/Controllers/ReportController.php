<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Service;
use App\Models\ApplicationConfigurationSetting;
use App\Models\Employee;
use App\Models\AppSetting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class ReportController extends Controller
{
    public function income(Request $request)
    {
        $report_type = 'Income';
        $services = Service::where('status', 'Active')->orderBy('name')->get();
        $employees = Employee::all()->map(fn($e) => [
            'id' => $e->employee_id,
            'name' => $e->full_name,
        ]);

        return view('pages.reports.income_expense', compact('services', 'employees', 'report_type'));
    }

    public function ajax_data(Request $request)
    {
        $report_type = $request->get('report_type', 'Income');
        $range = $request->get('range', 'today');
        $totalNumberOfDays = 1;
        $employee_id = $request->get('employee_id');
        $monthlyIncomeTarget = (float) AppSetting::where(
            'key',
            $report_type === 'Income' ? 'monthly_income_target' : 'monthly_expenses_target'
        )->value('value');
        $expected_income_target = 0;

        $query = Transaction::where('transaction_type', $report_type);

        if ($employee_id) {
            $query->where('employee_id', $employee_id);
        }

        switch ($range) {
            case 'This Week':
                $totalNumberOfDays = 7;
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $rangeLabel = 'This Week';
                if ($employee_id) {
                    $query->where('employee_id', $employee_id);
                }
                // income for this week given the monthly target
                $expected_income_target = ($monthlyIncomeTarget / 4);
                break;

            case 'This Month':
                $totalNumberOfDays = Carbon::now()->daysInMonth;
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
                if ($employee_id) {
                    $query->where('employee_id', $employee_id);
                }
                $rangeLabel = 'This Month';
                $expected_income_target = $monthlyIncomeTarget;
                break;

            case 'This Year':
                $totalNumberOfDays = Carbon::now()->isLeapYear() ? 366 : 365;
                $query->whereYear('created_at', Carbon::now()->year);
                $rangeLabel = 'This Year';
                if ($employee_id) {
                    $query->where('employee_id', $employee_id);
                }
                $expected_income_target = $monthlyIncomeTarget * 12;
                break;

            case 'Filter':
                try {
                    if ($request->filled('start_date') && $request->filled('end_date')) {
                        $start_input = $request->start_date;
                        $end_input = $request->end_date;

                        // Parse dates
                        try {
                            $start = Carbon::createFromFormat('Y-m-d', $start_input);
                            $end = Carbon::createFromFormat('Y-m-d', $end_input);
                        } catch (\Exception $e) {
                            $start = Carbon::createFromFormat('M j, Y', $start_input);
                            $end = Carbon::createFromFormat('M j, Y', $end_input);
                        }

                        $rangeLabel = $start->format('M j, Y') . ' - ' . $end->format('M j, Y');
                        $totalNumberOfDays = $start->diffInDays($end) + 1;

                        // Base query for transactions in range
                        $transactionQuery = Transaction::whereBetween('created_at', [$start, $end])
                            ->where('transaction_type', $report_type);

                        if ($employee_id) {
                            $transactionQuery->where('employee_id', $employee_id);
                        }

                        $employeeTransactions = $transactionQuery->get();

                        // Employee info
                        $employee = Employee::find($employee_id);

                        // Total income collected by this employee
                        $totalIncome = $employeeTransactions->sum('amount');

                        // Performance positions = count of transactions
                        $performancePositions = $employeeTransactions->count();

                        // rank among all employees in that period
                        $rankQuery = Transaction::select('employee_id', DB::raw('SUM(amount) as total'))
                            ->whereBetween('created_at', [$start, $end])
                            ->where('transaction_type', $report_type)
                            ->groupBy('employee_id')
                            ->orderByDesc('total')
                            ->get();

                        $positions = $rankQuery->pluck('employee_id')->toArray();
                        $employeeRank = array_search($employee_id, $positions) + 1; // 1-based rank

                        // Adjust target based on range
                        $daysInRange = $start->diffInDays($end) + 1;
                        $expected_income_target = ($monthlyIncomeTarget / 30) * $daysInRange;
                        // Return as JSON
                        $selectedEmpData = [
                            'employee_id' => $employee_id,
                            'name' => $employee ? $employee->first_name . ' ' . $employee->last_name : 'Unknown',
                            'expertise' => $employee ? $employee->job_title ?? $employee->department : 'N/A',
                            'performance_positions' => $performancePositions,
                            'total_income' => $totalIncome,
                            'rank' => $employeeRank,
                            'range_label' => $rangeLabel,
                        ];
                    } else {
                        // fallback today
                        $selectedEmpData = [
                            'name' => 'N/A',
                            'expertise' => 'N/A',
                            'performance_positions' => 0,
                            'total_income' => 0,
                            'rank' => 0,
                            'range_label' => 'Today'
                        ];
                    }
                } catch (\Exception $e) {
                    // fallback parsing error
                    $selectedEmpData = [
                        'name' => 'N/A',
                        'expertise' => 'N/A',
                        'performance_positions' => 0,
                        'total_income' => 0,
                        'rank' => 0,
                        'range_label' => 'Today'
                    ];
                }

                break;

            default: // Today
                $query->whereDate('created_at', Carbon::today());
                $rangeLabel = 'Today';
                $expected_income_target = $monthlyIncomeTarget / 30;

                break;
        }

        // Execute query
        $incomes = $query->orderBy('created_at', 'desc')->get();

        // Goals and totals
        $daily_goal = (float) ApplicationConfigurationSetting::get('daily_expected_income', 800000);
        $daily_goal_total = $daily_goal * $totalNumberOfDays;
        $total_income = $incomes->sum('amount');

        // Progress percentage
        $daily_percentage = $daily_goal_total > 0
            ? min(($total_income / $daily_goal_total) * 100, 1000)
            : 0;
        // $dailyPe= monthlyIncomeTarget

        // Group data by time period
        if ($range === 'This Year') {
            $grouped_by_period = $incomes->groupBy(fn($t) => Carbon::parse($t->created_at)->format('M'))
                ->map(fn($g) => $g->sum('amount'));
        } else {
            $grouped_by_period = $incomes->groupBy(fn($t) => Carbon::parse($t->created_at)->format('Y-m-d'))
                ->map(fn($g) => $g->sum('amount'));
        }

        // Response payload
        $data = [
            'range_label' => $rangeLabel,
            'total_income' => $total_income,
            'grouped' => $incomes->groupBy('service_description')->map->sum('amount'),
            'grouped_by_period' => $grouped_by_period,
            'daily_goal' => $daily_goal_total,
            'daily_percentage' => round($daily_percentage, 0),
            'total_days' => $totalNumberOfDays,
            'request' => $request->all(),
            'selectedEmpData' => $selectedEmpData ?? null,
            'monthlyIncomeTarget' => $monthlyIncomeTarget,
            'expected_income_target' => $expected_income_target,
            'report_type' => $report_type,
        ];

        return response()->json($data);
    }

    public function EmployerContribution(Request $request)
    {
        $range = $request->input('range', 'Today');
        $employee_id = $request->input('employee_id', null);
        $report_type = $request->input('report_type', 'Income');

        $query = Transaction::query()
            ->where('transaction_type', $report_type);

        $rangeLabel = '';

        switch ($range) {
            case 'This Week':
                $startDate = Carbon::now()->startOfWeek()->startOfDay();
                $endDate   = Carbon::now()->endOfWeek()->endOfDay();
                $rangeLabel = 'This Week';
                break;

            case 'This Month':
                $startDate = Carbon::now()->startOfMonth()->startOfDay();
                $endDate   = Carbon::now()->endOfMonth()->endOfDay();
                $rangeLabel = 'This Month';
                break;

            case 'This Year':
                $startDate = Carbon::now()->startOfYear()->startOfDay();
                $endDate   = Carbon::now()->endOfYear()->endOfDay();
                $rangeLabel = 'This Year';
                break;

            case 'Filter':
                $start_input = $request->input('start_date');
                $end_input   = $request->input('end_date');

                if ($start_input && $end_input) {
                    try {
                        $startDate = Carbon::createFromFormat('Y-m-d', $start_input)->startOfDay();
                        $endDate   = Carbon::createFromFormat('Y-m-d', $end_input)->endOfDay();
                    } catch (\Exception $e) {
                        $startDate = Carbon::parse($start_input)->startOfDay();
                        $endDate   = Carbon::parse($end_input)->endOfDay();
                    }

                    $rangeLabel = $startDate->format('M j, Y') . ' - ' . $endDate->format('M j, Y');
                } else {
                    $startDate = $endDate = Carbon::today();
                    $rangeLabel = 'Today';
                }
                break;

            default: // Today
                $startDate = Carbon::today()->startOfDay();
                $endDate   = Carbon::today()->endOfDay();
                $rangeLabel = 'Today';
                break;
        }

        // Apply report_type filter

        // Apply date filter with table prefix to avoid ambiguity
        $query->whereBetween('transactions.created_at', [$startDate, $endDate]);

        // Optional filter by employee
        if ($employee_id) {
            $query->where('transactions.employee_id', $employee_id);
        }

        // Aggregate top employers
        $topEmployers = $query->join('employees', 'transactions.employee_id', '=', 'employees.employee_id')
            ->select(
                DB::raw("CONCAT(employees.first_name, ' ', employees.last_name) as label"),
                DB::raw('COUNT(transactions.id) as invoice_count'),
                DB::raw('SUM(transactions.amount) as total_amount')
            )
            ->groupBy('transactions.employee_id', 'employees.first_name', 'employees.last_name')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();

        // Format for table
        $formatted = $topEmployers->map(function ($row) {
            return [
                'Employee' => $row->label,
                'Invoices' => (int) $row->invoice_count,
                'totalIncome' => number_format((float) $row->total_amount, 2), // formatted with comma
            ];
        });

        // Return JSON response
        return response()->json([
            'range_label' => $rangeLabel,
            'data' => $formatted
        ]);
    }
    public function expense()
    {
        $services = Service::where('status', 'Active')->orderBy('name')->get();
        $employees = Employee::all()->map(fn($e) => [
            'id' => $e->employee_id,
            'name' => $e->full_name,
        ]);
        $report_type = 'Expense';

        return view('pages.reports.income_expense', compact('services', 'employees', 'report_type'));
    }
    public function profit()
    {
        return view('pages.reports.profit');
    }
    public function goals()
    {
        return view('pages.reports.income');
    }
}
