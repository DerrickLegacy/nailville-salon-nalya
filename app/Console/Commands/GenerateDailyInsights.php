<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateDailyInsights extends Command
{
    protected $signature = 'insights:daily';
    protected $description = 'Generate daily business insights and notifications';

    public function handle()
    {
        $today = \Carbon\Carbon::today();
        $yesterday = \Carbon\Carbon::yesterday();
        
        // Get today's data
        $todayIncome = \App\Models\Transaction::where('transaction_type', 'Income')
            ->whereDate('date', $today)
            ->sum('amount');
            
        $todayExpense = \App\Models\Transaction::where('transaction_type', 'Expense')
            ->whereDate('date', $today)
            ->sum('amount');
            
        $todayNet = $todayIncome - $todayExpense;
        
        $todayIncomeCount = \App\Models\Transaction::where('transaction_type', 'Income')
            ->whereDate('date', $today)
            ->count();
            
        $todayExpenseCount = \App\Models\Transaction::where('transaction_type', 'Expense')
            ->whereDate('date', $today)
            ->count();
        
        // Get yesterday's data for comparison
        $yesterdayIncome = \App\Models\Transaction::where('transaction_type', 'Income')
            ->whereDate('date', $yesterday)
            ->sum('amount');
        
        // Calculate percentage change
        $incomeChange = $yesterdayIncome > 0 
            ? (($todayIncome - $yesterdayIncome) / $yesterdayIncome) * 100 
            : 0;
        
        // Create daily summary notification
        \App\Models\Notification::create([
            'type' => 'daily',
            'title' => 'Daily Business Summary - ' . $today->format('M d, Y'),
            'message' => "Today's Performance:\n" .
                        "💰 Income: UGX " . number_format($todayIncome) . " ({$todayIncomeCount} transactions)\n" .
                        "💸 Expenses: UGX " . number_format($todayExpense) . " ({$todayExpenseCount} transactions)\n" .
                        "📊 Net Income: UGX " . number_format($todayNet) . "\n" .
                        "📈 Change from yesterday: " . number_format($incomeChange, 1) . "%",
            'data' => [
                'income' => $todayIncome,
                'expense' => $todayExpense,
                'net' => $todayNet,
                'income_count' => $todayIncomeCount,
                'expense_count' => $todayExpenseCount,
                'change_percentage' => $incomeChange,
            ],
            'priority' => 'medium',
            'category' => 'insight',
        ]);
        
        $this->info('Daily insights generated successfully!');
        return 0;
    }
}
