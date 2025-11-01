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
                $query->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $rangeLabel = 'This Week';
                if ($employee_id) {
                    $query->where('employee_id', $employee_id);
                }
                // income for this week given the monthly target
                $expected_income_target = ($monthlyIncomeTarget / 4);
                break;

            case 'This Month':
                $totalNumberOfDays = Carbon::now()->daysInMonth;
                $query->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year);
                if ($employee_id) {
                    $query->where('employee_id', $employee_id);
                }
                $rangeLabel = 'This Month';
                $expected_income_target = $monthlyIncomeTarget;
                break;

            case 'This Year':
                $totalNumberOfDays = Carbon::now()->isLeapYear() ? 366 : 365;
                $query->whereYear('date', Carbon::now()->year);
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
                        $transactionQuery = Transaction::whereBetween('date', [$start, $end])
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
                            ->whereBetween('date', [$start, $end])
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
                $query->whereDate('date', Carbon::today());
                $rangeLabel = 'Today';
                $expected_income_target = $monthlyIncomeTarget / 30;

                break;
        }

        // Execute query
        $incomes = $query->orderBy('date', 'desc')->get();

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
            $grouped_by_period = $incomes->groupBy(fn($t) => Carbon::parse($t->date)->format('M'))
                ->map(fn($g) => $g->sum('amount'));
        } else {
            $grouped_by_period = $incomes->groupBy(fn($t) => Carbon::parse($t->date)->format('Y-m-d'))
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
        $query->whereBetween('transactions.date', [$startDate, $endDate]);

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

    public function netIncome()
    {
        $report_type =  "Net Income";
        return view('pages.reports.netincome', compact('report_type'));
    }
    public function getNetIncomeData(Request $request)
    {
        $selectedPeriod = $request->input('selectedPeriod', 'Today');
        $startDate  = trim($request->input('startDate', ''));
        $endDate  = trim($request->input('endDate', ''));
        $searchTerm = trim($request->input('searchTerm', ''));
        $monthlyIncomeTarget = (float) AppSetting::where('key', 'monthly_income_target')->value('value');
        $monthlyExpensesTarget = (float) AppSetting::where('key', 'monthly_expenses_target')->value('value');
        $monthlyNetIncomeTarget = $monthlyIncomeTarget - $monthlyExpensesTarget;
        $expected_income_target = 0;

        // Determine date range
        switch ($selectedPeriod) {
            case 'This Week':
                $startDate = Carbon::now()->startOfWeek()->startOfDay();
                $endDate = Carbon::now()->endOfWeek()->endOfDay();
                $expected_income_target = ($monthlyNetIncomeTarget / 4);

                break;
            case 'This Month':
                $startDate = Carbon::now()->startOfMonth()->startOfDay();
                $endDate = Carbon::now()->endOfMonth()->endOfDay();
                $expected_income_target = $monthlyNetIncomeTarget;

                break;
            case 'This Year':
                $startDate = Carbon::now()->startOfYear()->startOfDay();
                $endDate = Carbon::now()->endOfYear()->endOfDay();
                $expected_income_target = $monthlyNetIncomeTarget * 12;

                break;
            case 'Month Filter':

                $month = $request->input('month', null);
                $year = $request->input('year', Date('Y'));

                if ($month) {
                    $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
                    $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();
                    $expected_income_target = $monthlyNetIncomeTarget;
                } else {
                    $startDate = Carbon::today()->startOfDay();
                    $endDate = Carbon::today()->endOfDay();
                    $expected_income_target = $monthlyNetIncomeTarget / 30;
                }

                if ($year) {
                    $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
                    $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();
                    $expected_income_target = $monthlyNetIncomeTarget * 12;
                }
                break;
            case 'Custom Range':
                try {
                    if ($startDate !== '' && $endDate !== '') {
                        try {
                            $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
                            $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();
                        } catch (\Exception $e) {
                            $startDate = Carbon::parse($startDate)->startOfDay();
                            $endDate = Carbon::parse($endDate)->endOfDay();
                        }

                        // Adjust target based on range
                        $daysInRange = date_diff($startDate, $endDate)->format("%a") + 1;
                        $expected_income_target = ($monthlyNetIncomeTarget / 30) * $daysInRange;
                    } else {
                        // fallback today
                        $startDate = Carbon::today()->startOfDay();
                        $endDate = Carbon::today()->endOfDay();
                        $expected_income_target = $monthlyNetIncomeTarget / 30;
                    }
                } catch (\Exception $e) {
                    // fallback parsing error
                    $startDate = Carbon::today()->startOfDay();
                    $endDate = Carbon::today()->endOfDay();
                    $expected_income_target = $monthlyNetIncomeTarget / 30;
                }

                break;
            default:
                $startDate = Carbon::today()->startOfDay();
                $endDate = Carbon::today()->endOfDay();
                $expected_income_target = $monthlyNetIncomeTarget / 30;

                break;
        }


        // 🧠 Dynamically change grouping expression
        if ($selectedPeriod === 'This Year') {
            $groupExpr = "DATE_FORMAT(date, '%Y-%m')";
            $labelAlias = 'month';
            $orderExpr = 'DATE_FORMAT(date, "%Y-%m")';
        } else {
            $groupExpr = 'DATE(date)';
            $labelAlias = 'date';
            $orderExpr = 'DATE(date)';
        }

        // Step 1: Aggregate query
        $records = Transaction::query()
            ->select(
                DB::raw("$groupExpr as $labelAlias"),
                DB::raw("SUM(CASE WHEN transaction_type = 'Income' THEN amount ELSE 0 END) as total_income"),
                DB::raw("SUM(CASE WHEN transaction_type = 'Expense' THEN amount ELSE 0 END) as total_expense")
            )
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->groupBy(DB::raw($groupExpr))
            ->orderBy(DB::raw($orderExpr), 'asc')
            ->get();

        // Step 2: Map data for the frontend
        $data = $records->map(function ($record) use ($labelAlias) {
            return [
                'period' => $record->{$labelAlias},
                'income' => number_format((float) $record->total_income, 0, '.', ''),
                'expense' => number_format((float) $record->total_expense, 0, '.', ''),
                'net_income' => number_format((float) $record->total_income - (float) $record->total_expense, 0, '.', ''),
            ];
        });

        // Step 3: Apply search filtering (on aggregated results)
        if ($searchTerm !== '') {
            $data = $data->filter(function ($item) use ($searchTerm) {
                return str_contains($item['period'], $searchTerm)
                    || str_contains($item['income'], $searchTerm)
                    || str_contains($item['expense'], $searchTerm)
                    || str_contains($item['net_income'], $searchTerm);
            })->values();
        }

        // Step 4: Return JSON
        return response()->json([
            'data' => $data,
            'searchTerm' => $searchTerm,
            'period' => $selectedPeriod,
            'expected_income_target' => number_format($expected_income_target, 0, '.', ''),
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
            'datediff' => date_diff($startDate, $endDate)->format("%a") + 1,
            'monthlyNetIncomeTarget' => $monthlyNetIncomeTarget,

        ]);
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
