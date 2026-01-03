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
        /* -------------------------------------------------
     | 1. Read inputs & defaults
     -------------------------------------------------*/
        $reportType  = $request->get('report_type', 'Income');
        $range       = $request->get('range', 'Today');
        $employeeId  = $request->get('employee_id');

        $totalDays   = 1;
        $rangeLabel  = 'Today';
        $expectedTarget = 0;

        /* -------------------------------------------------
     | 2. Monthly target
     -------------------------------------------------*/
        $monthlyTarget = (float) AppSetting::where(
            'key',
            $reportType === 'Income'
                ? 'monthly_income_target'
                : 'monthly_expenses_target'
        )->value('value');

        /* -------------------------------------------------
     | 3. Base query (SINGLE source of truth)
     -------------------------------------------------*/
        $baseQuery = Transaction::where('transaction_type', $reportType);

        if ($employeeId) {
            $baseQuery->where('employee_id', $employeeId);
        }

        /* -------------------------------------------------
     | 4. Date range handling
     -------------------------------------------------*/
        switch ($range) {

            case 'This Week':
                $start = Carbon::now()->startOfWeek();
                $end   = Carbon::now()->endOfWeek();
                $totalDays = 7;
                $rangeLabel = 'This Week';
                $expectedTarget = $monthlyTarget / 4;
                break;

            case 'This Month':
                $start = Carbon::now()->startOfMonth();
                $end   = Carbon::now()->endOfMonth();
                $totalDays = Carbon::now()->daysInMonth;
                $rangeLabel = 'This Month';
                $expectedTarget = $monthlyTarget;
                break;

            case 'This Year':
                $start = Carbon::now()->startOfYear();
                $end   = Carbon::now()->endOfYear();
                $totalDays = Carbon::now()->isLeapYear() ? 366 : 365;
                $rangeLabel = 'This Year';
                $expectedTarget = $monthlyTarget * 12;
                break;
            case 'All Time':
                // earliest transaction date
                $firstTransactionDate = Transaction::where('transaction_type', $reportType)
                    ->min('date');

                if ($firstTransactionDate) {
                    $start = Carbon::parse($firstTransactionDate)->startOfDay();
                    $end   = Carbon::now()->endOfDay();

                    $totalDays = $start->diffInDays($end) + 1;
                    $rangeLabel = 'Since Business Started';

                    // Expected target based on number of months passed
                    $months = max(1, $start->diffInMonths($end));
                    $expectedTarget = $monthlyTarget * $months;
                } else {
                    // No data fallback
                    $start = Carbon::today();
                    $end = Carbon::today();
                    $totalDays = 1;
                    $rangeLabel = 'Since Business Started';
                    $expectedTarget = 0;
                }
                break;

            case 'Filter':
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $start = Carbon::parse($request->start_date)->startOfDay();
                    $end   = Carbon::parse($request->end_date)->endOfDay();

                    $totalDays = $start->diffInDays($end) + 1;
                    $rangeLabel = $start->format('M j, Y') . ' - ' . $end->format('M j, Y');
                    $expectedTarget = ($monthlyTarget / 30) * $totalDays;
                } else {
                    $start = Carbon::today();
                    $end   = Carbon::today();
                    $rangeLabel = 'Today';
                    $expectedTarget = $monthlyTarget / 30;
                }
                break;

            default: // Today
                $start = Carbon::today();
                $end   = Carbon::today();
                $rangeLabel = 'Today';
                $expectedTarget = $monthlyTarget / 30;
                break;
        }

        // Apply date filter ONCE
        $baseQuery->whereBetween('date', [$start, $end]);

        /* -------------------------------------------------
        | 5. Fetch transactions
        -------------------------------------------------*/
        $transactions = (clone $baseQuery)
            ->orderBy('date', 'desc')
            ->get();

        /* -------------------------------------------------
        | 6. Totals & goals
        -------------------------------------------------*/
        $dailyGoal = (float) ApplicationConfigurationSetting::get('daily_expected_income', 800000);
        $dailyGoalTotal = $dailyGoal * $totalDays;
        $totalIncome = $transactions->sum('amount');

        $dailyPercentage = $dailyGoalTotal > 0
            ? min(($totalIncome / $dailyGoalTotal) * 100, 100)
            : 0;

        /* -------------------------------------------------
        | 7. Grouping
        -------------------------------------------------*/
        if (in_array($range, ['This Year', 'All Time'])) {
            $groupedByPeriod = $transactions
                ->groupBy(fn($t) => Carbon::parse($t->date)->format('Y-M'))
                ->map(fn($g) => $g->sum('amount'));
        } else {
            $groupedByPeriod = $transactions
                ->groupBy(fn($t) => Carbon::parse($t->date)->format('Y-m-d'))
                ->map(fn($g) => $g->sum('amount'));
        }

        $groupedByService = $transactions
            ->groupBy('service_description')
            ->map(function ($items, $serviceId) {
                $service = \App\Models\Service::find($serviceId);
                return [
                    'service_id'   => $service->id ?? $serviceId,
                    'service_name' => $service->name ?? 'Unknown',
                    'service_code' => $service->service_code ?? null,
                    'category'     => $service->category ?? null,
                    'total_amount' => $items->sum('amount'),
                ];
            });

        /* -------------------------------------------------
            | 8. Employee analytics (optional)
            -------------------------------------------------*/
        $selectedEmpData = null;

        if ($employeeId) {
            $employeeTx = (clone $baseQuery)->get();
            $employee = Employee::find($employeeId);

            $rankings = Transaction::select('employee_id', DB::raw('SUM(amount) total'))
                ->whereBetween('date', [$start, $end])
                ->where('transaction_type', $reportType)
                ->groupBy('employee_id')
                ->orderByDesc('total')
                ->pluck('employee_id')
                ->toArray();

            $selectedEmpData = [
                'employee_id' => $employeeId,
                'name' => $employee ? $employee->first_name . ' ' . $employee->last_name : 'Unknown',
                'expertise' => $employee->job_title ?? $employee->department ?? 'N/A',
                'performance_positions' => $employeeTx->count(),
                'total_income' => $employeeTx->sum('amount'),
                'rank' => array_search($employeeId, $rankings) !== false
                    ? array_search($employeeId, $rankings) + 1
                    : null,
                'range_label' => $rangeLabel,
            ];
        }

        /* -------------------------------------------------
     | 9. Response
     -------------------------------------------------*/
        return response()->json([
            'range_label' => $rangeLabel,
            'total_income' => $totalIncome,
            'grouped' => $groupedByService,
            'grouped_by_period' => $groupedByPeriod,
            'daily_goal' => $dailyGoalTotal,
            'daily_percentage' => round($dailyPercentage),
            'total_days' => $totalDays,
            'selectedEmpData' => $selectedEmpData,
            'monthlyIncomeTarget' => $monthlyTarget,
            'expected_income_target' => $expectedTarget,
            'report_type' => $reportType,
        ]);
    }


    public function EmployerContribution(Request $request)
    {
        $range       = $request->input('range', 'Today');
        $employee_id = $request->input('employee_id', null);
        $report_type = $request->input('report_type', 'Income');

        /*
        |--------------------------------------------------------------------------
        | Base Query (Single Source of Truth)
        |--------------------------------------------------------------------------
        */
        $baseQuery = Transaction::query()
            ->where('transaction_type', $report_type);

        /*
        |--------------------------------------------------------------------------
        | Resolve Date Range
        |--------------------------------------------------------------------------
        */
        $rangeLabel = 'Today';

        switch ($range) {

            case 'This Week':
                $startDate  = Carbon::now()->startOfWeek()->startOfDay();
                $endDate    = Carbon::now()->endOfWeek()->endOfDay();
                $rangeLabel = 'This Week';
                break;

            case 'This Month':
                $startDate  = Carbon::now()->startOfMonth()->startOfDay();
                $endDate    = Carbon::now()->endOfMonth()->endOfDay();
                $rangeLabel = 'This Month';
                break;

            case 'This Year':
                $startDate  = Carbon::now()->startOfYear()->startOfDay();
                $endDate    = Carbon::now()->endOfYear()->endOfDay();
                $rangeLabel = 'This Year';
                break;

            case 'All Time':
                // Get earliest transaction date (employee-aware)
                $firstQuery = clone $baseQuery;

                if ($employee_id) {
                    $firstQuery->where('employee_id', $employee_id);
                }

                $firstDate = $firstQuery->min('date');

                if ($firstDate) {
                    $startDate = Carbon::parse($firstDate)->startOfDay();
                } else {
                    $startDate = Carbon::today()->startOfDay();
                }

                $endDate    = Carbon::now()->endOfDay();
                $rangeLabel = 'Since Business Started';
                break;

            case 'Filter':
                $startInput = $request->input('start_date');
                $endInput   = $request->input('end_date');

                if ($startInput && $endInput) {
                    $startDate = Carbon::parse($startInput)->startOfDay();
                    $endDate   = Carbon::parse($endInput)->endOfDay();
                    $rangeLabel = $startDate->format('M j, Y') . ' - ' . $endDate->format('M j, Y');
                } else {
                    $startDate = Carbon::today()->startOfDay();
                    $endDate   = Carbon::today()->endOfDay();
                    $rangeLabel = 'Today';
                }
                break;

            default: // Today
                $startDate = Carbon::today()->startOfDay();
                $endDate   = Carbon::today()->endOfDay();
                $rangeLabel = 'Today';
                break;
        }

        /*
    |--------------------------------------------------------------------------
    | Apply Filters (IMPORTANT: BEFORE JOIN)
    |--------------------------------------------------------------------------
    */
        $baseQuery->whereBetween('date', [$startDate, $endDate]);

        if ($employee_id) {
            $baseQuery->where('employee_id', $employee_id);
        }

        /*
    |--------------------------------------------------------------------------
    | Aggregate Employer Contributions
    |--------------------------------------------------------------------------
    */
        $topEmployers = $baseQuery
            ->join('employees', 'transactions.employee_id', '=', 'employees.employee_id')
            ->select(
                DB::raw("CONCAT(employees.first_name, ' ', employees.last_name) AS label"),
                DB::raw('COUNT(transactions.id) AS invoice_count'),
                DB::raw('SUM(transactions.amount) AS total_amount')
            )
            ->groupBy(
                'transactions.employee_id',
                'employees.first_name',
                'employees.last_name'
            )
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();

        /*
    |--------------------------------------------------------------------------
    | Format Response
    |--------------------------------------------------------------------------
    */
        $data = $topEmployers->map(function ($row) {
            return [
                'Employee'    => $row->label,
                'Invoices'    => (int) $row->invoice_count,
                'totalIncome' => number_format((float) $row->total_amount, 2),
            ];
        });

        /*
    |--------------------------------------------------------------------------
    | JSON Response
    |--------------------------------------------------------------------------
    */
        return response()->json([
            'status'      => 'success',
            'range_label' => $rangeLabel,
            'start_date'  => $startDate->toDateString(),
            'end_date'    => $endDate->toDateString(),
            'data'        => $data,
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

                $month = $request->input('month'); // e.g. 11
                $year = $request->input('year', date('Y')); // default current year if not provided

                // If a month is selected (with or without year)
                if ($month) {
                    $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
                    $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();

                    $expected_income_target = $monthlyNetIncomeTarget;
                }
                // If only a year is selected
                elseif ($year) {
                    $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
                    $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();

                    $expected_income_target = $monthlyNetIncomeTarget * 12;
                }
                // Otherwise (default: today)
                else {
                    $startDate = Carbon::today()->startOfDay();
                    $endDate = Carbon::today()->endOfDay();

                    $expected_income_target = $monthlyNetIncomeTarget / 30;
                }

                break;

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
