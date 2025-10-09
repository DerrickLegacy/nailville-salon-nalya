<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Service;
use App\Models\ApplicationConfigurationSetting;
use App\Models\Employee;
use Carbon\Carbon;


class ReportController extends Controller
{
    public function income(Request $request)
    {

        // $query = Transaction::where('transaction_type', 'Income');
        $services = Service::where('status', 'Active')->orderBy('name')->get();
        $employees = Employee::all()->map(fn($e) => [
            'id' => $e->employee_id,
            'name' => $e->full_name,
        ]);

        return view('pages.reports.income', compact('services', 'employees'));
    }

    public function ajax_data(Request $request)
    {
        $range = $request->get('range', 'today');
        $dateSelect = $request->get('dateSelect'); // e.g. "2025-10-01 to 2025-10-09"
        $totalNumberOfDays = 1;

        $query = Transaction::where('transaction_type', 'Income');

        // Determine filter
        if ($request->filled('employee_id')) {
            $employee_id = $request->get('employee_id');
            if ($employee_id) {
                $query->where('employee_id', $employee_id);
            }
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            try {
                $start = Carbon::createFromFormat('M j, Y', $request->start_date)->startOfDay();
                $end = Carbon::createFromFormat('M j, Y', $request->end_date)->endOfDay();

                $totalNumberOfDays = $start->diffInDays($end) + 1;
                $query->whereBetween('created_at', [$start, $end]);
                $rangeLabel = $start->format('M j, Y') . ' - ' . $end->format('M j, Y');
            } catch (\Exception $e) {
                // fallback to today if parsing fails
                $query->whereDate('created_at', Carbon::today());
                $rangeLabel = 'Today';
            }
        } else {
            // ✅ Predefined ranges
            switch ($range) {
                case 'This Week':
                    $totalNumberOfDays = 7;
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    $rangeLabel = 'This Week';
                    break;

                case 'This Month':
                    $totalNumberOfDays = Carbon::now()->daysInMonth;
                    $query->whereMonth('created_at', Carbon::now()->month)
                        ->whereYear('created_at', Carbon::now()->year);
                    $rangeLabel = 'This Month';
                    break;

                case 'This Year':
                    $totalNumberOfDays = Carbon::now()->isLeapYear() ? 366 : 365;
                    $query->whereYear('created_at', Carbon::now()->year);
                    $rangeLabel = 'This Year';
                    break;

                default: // Today
                    $query->whereDate('created_at', Carbon::today());
                    $rangeLabel = 'Today';
                    break;
            }
        }

        // Execute query
        $incomes = $query->orderBy('created_at', 'desc')->get();

        // Goals and totals
        $daily_goal = (float) ApplicationConfigurationSetting::get('daily_expected_income', 800000);
        $daily_goal_total = $daily_goal * $totalNumberOfDays;
        $total_income = $incomes->sum('amount');

        // Progress percentage
        $daily_percentage = $daily_goal_total > 0
            ? min(($total_income / $daily_goal_total) * 100, 100)
            : 0;

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
            'daily_percentage' => round($daily_percentage, 2),
            'total_days' => $totalNumberOfDays,
        ];

        return response()->json($data);
    }


    public function expense()
    {
        return view('pages.reports.expense');
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
