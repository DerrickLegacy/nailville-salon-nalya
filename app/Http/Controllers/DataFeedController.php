<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;

class DataFeedController extends Controller
{
    /**
     * Fetch today vs yesterday hourly data
     */
    public function getDashboardIncome(Request $request)
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Query transactions for today and yesterday
        $transactions = Transaction::selectRaw('
                HOUR(created_at) as hour,
                SUM(CASE WHEN DATE(created_at) = ? THEN amount ELSE 0 END) as today_total,
                SUM(CASE WHEN DATE(created_at) = ? THEN amount ELSE 0 END) as yesterday_total
            ', [$today->toDateString(), $yesterday->toDateString()])
            ->whereBetween('created_at', [$yesterday->startOfDay(), $today->endOfDay()])
            ->where('transaction_type', 'Income')  // <-- fixed here

            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Prepare labels and data for chart
        $labels = $transactions->pluck('hour')->map(fn($h) => $h . ':00');
        $todayData = $transactions->pluck('today_total');
        $yesterdayData = $transactions->pluck('yesterday_total');

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Today',
                    'data' => $todayData,
                    'backgroundColor' => '#3b82f6',
                ],
                [
                    'label' => 'Yesterday',
                    'data' => $yesterdayData,
                    'backgroundColor' => '#ef4444',
                ]
            ]
        ]);
    }

    public function getDashboardExpense(Request $request)
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Query transactions for today and yesterday
        $transactions = Transaction::selectRaw('
            HOUR(created_at) as hour,
            SUM(CASE WHEN DATE(created_at) = ? THEN amount ELSE 0 END) as today_total,
            SUM(CASE WHEN DATE(created_at) = ? THEN amount ELSE 0 END) as yesterday_total
        ', [$today->toDateString(), $yesterday->toDateString()])
            ->whereBetween('created_at', [$yesterday->startOfDay(), $today->endOfDay()])
            ->where('transaction_type', 'Expense')  // <-- fixed here
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Prepare labels and data for chart
        $labels = $transactions->pluck('hour')->map(fn($h) => $h . ':00');
        $todayData = $transactions->pluck('today_total');
        $yesterdayData = $transactions->pluck('yesterday_total');

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Today',
                    'data' => $todayData,
                    'backgroundColor' => '#3b82f6',
                ],
                [
                    'label' => 'Yesterday',
                    'data' => $yesterdayData,
                    'backgroundColor' => '#ef4444',
                ]
            ]
        ]);
    }

    public function getDashboardNet(Request $request)
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Query transactions grouped by hour for net (Income - Expense)
        $transactions = Transaction::selectRaw('
            HOUR(created_at) as hour,
            SUM(CASE WHEN DATE(created_at) = ? AND transaction_type = "Income" THEN amount ELSE 0 END) -
            SUM(CASE WHEN DATE(created_at) = ? AND transaction_type = "Expense" THEN amount ELSE 0 END) as today_net,
            SUM(CASE WHEN DATE(created_at) = ? AND transaction_type = "Income" THEN amount ELSE 0 END) -
            SUM(CASE WHEN DATE(created_at) = ? AND transaction_type = "Expense" THEN amount ELSE 0 END) as yesterday_net
        ', [
            $today->toDateString(),
            $today->toDateString(),
            $yesterday->toDateString(),
            $yesterday->toDateString()
        ])
            ->whereBetween('created_at', [$yesterday->startOfDay(), $today->endOfDay()])
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Prepare labels and data for chart
        $labels = $transactions->pluck('hour')->map(fn($h) => $h . ':00');
        $todayData = $transactions->pluck('today_net');
        $yesterdayData = $transactions->pluck('yesterday_net');

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Today Net',
                    'data' => $todayData,
                    'backgroundColor' => '#3b82f6',
                ],
                [
                    'label' => 'Yesterday Net',
                    'data' => $yesterdayData,
                    'backgroundColor' => '#ef4444',
                ]
            ]
        ]);
    }
}
