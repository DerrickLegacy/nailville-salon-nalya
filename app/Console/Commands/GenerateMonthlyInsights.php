<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateMonthlyInsights extends Command
{
    protected $signature = 'insights:monthly';
    protected $description = 'Generate monthly business insights and notifications';

    public function handle()
    {
        $startOfMonth = \Carbon\Carbon::now()->startOfMonth();
        $endOfMonth = \Carbon\Carbon::now()->endOfMonth();
        
        // Monthly totals
        $monthIncome = \App\Models\Transaction::where('transaction_type', 'Income')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');
            
        $monthExpense = \App\Models\Transaction::where('transaction_type', 'Expense')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');
            
        $monthNet = $monthIncome - $monthExpense;
        
        // Transaction counts
        $incomeCount = \App\Models\Transaction::where('transaction_type', 'Income')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->count();
            
        $expenseCount = \App\Models\Transaction::where('transaction_type', 'Expense')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->count();
        
        // Get targets from app_settings
        $incomeTarget = \App\Models\AppSetting::where('key', 'monthly_income_target')->value('value') ?? 0;
        $expenseTarget = \App\Models\AppSetting::where('key', 'monthly_expenses_target')->value('value') ?? 0;
        $netTarget = \App\Models\AppSetting::where('key', 'monthly_net_income_target')->value('value') ?? 0;
        
        // Calculate achievement percentages
        $incomeAchievement = $incomeTarget > 0 ? ($monthIncome / $incomeTarget) * 100 : 0;
        $expenseStatus = $expenseTarget > 0 ? ($monthExpense / $expenseTarget) * 100 : 0;
        $netAchievement = $netTarget > 0 ? ($monthNet / $netTarget) * 100 : 0;
        
        // Top performing employee
        $topEmployee = \App\Models\Transaction::where('transaction_type', 'Income')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->selectRaw('employee_id, SUM(amount) as total')
            ->groupBy('employee_id')
            ->orderByDesc('total')
            ->with('employee')
            ->first();
        
        $topEmployeeName = $topEmployee && $topEmployee->employee 
            ? $topEmployee->employee->first_name . ' ' . $topEmployee->employee->last_name
            : 'N/A';
        $topEmployeeAmount = $topEmployee ? $topEmployee->total : 0;
        
        // Create monthly summary
        \App\Models\Notification::create([
            'type' => 'monthly',
            'title' => 'Monthly Report - ' . \Carbon\Carbon::now()->format('F Y'),
            'message' => "Monthly Performance Summary:\n\n" .
                        "💰 Total Income: UGX " . number_format($monthIncome) . " ({$incomeCount} transactions)\n" .
                        "   Target: UGX " . number_format($incomeTarget) . " (" . number_format($incomeAchievement, 1) . "% achieved)\n\n" .
                        "💸 Total Expenses: UGX " . number_format($monthExpense) . " ({$expenseCount} transactions)\n" .
                        "   Budget: UGX " . number_format($expenseTarget) . " (" . number_format($expenseStatus, 1) . "% used)\n\n" .
                        "📊 Net Income: UGX " . number_format($monthNet) . "\n" .
                        "   Target: UGX " . number_format($netTarget) . " (" . number_format($netAchievement, 1) . "% achieved)\n\n" .
                        "🏆 Top Performer: {$topEmployeeName} (UGX " . number_format($topEmployeeAmount) . ")",
            'data' => [
                'income' => $monthIncome,
                'expense' => $monthExpense,
                'net' => $monthNet,
                'income_target' => $incomeTarget,
                'expense_target' => $expenseTarget,
                'net_target' => $netTarget,
                'income_achievement' => $incomeAchievement,
                'expense_status' => $expenseStatus,
                'net_achievement' => $netAchievement,
                'top_employee' => $topEmployeeName,
                'top_employee_amount' => $topEmployeeAmount,
            ],
            'priority' => 'high',
            'category' => 'insight',
        ]);
        
        // Check for goal achievements/misses
        if ($incomeAchievement >= 100) {
            \App\Models\Notification::create([
                'type' => 'goal_achieved',
                'title' => '🎉 Income Goal Achieved!',
                'message' => "Congratulations! You've achieved " . number_format($incomeAchievement, 1) . "% of your monthly income target!",
                'data' => ['achievement' => $incomeAchievement],
                'priority' => 'high',
                'category' => 'goal',
            ]);
        } elseif ($incomeAchievement < 80 && \Carbon\Carbon::now()->day > 25) {
            \App\Models\Notification::create([
                'type' => 'goal_alert',
                'title' => '⚠️ Income Goal Alert',
                'message' => "You're at " . number_format($incomeAchievement, 1) . "% of your income target with few days left in the month.",
                'data' => ['achievement' => $incomeAchievement],
                'priority' => 'high',
                'category' => 'alert',
            ]);
        }
        
        $this->info('Monthly insights generated successfully!');
        return 0;
    }
}
