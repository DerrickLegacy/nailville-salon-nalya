<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PayrollRun;
use App\Models\PayrollDeduction;
use App\Models\Employee;
use App\Models\Notification;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    /**
     * Display a listing of payroll runs.
     */
    public function index()
    {
        $payrolls = PayrollRun::with(['employee', 'createdBy'])->latest()->paginate(20);
        return view('payrolls.index', compact('payrolls'));
    }

    /**
     * Show the form for creating a new payroll run.
     */
    public function create()
    {
        $employees = Employee::where('work_status', 'Active')
            ->where('payroll_type', 'commission')
            ->get();
        return view('payrolls.create', compact('employees'));
    }

    /**
     * Store a new deduction for a payroll run.
     */
    public function storeDeduction(Request $request, PayrollRun $payroll)
    {
        $validated = $request->validate([
            'deduction_name' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $deduction = $payroll->deductions()->create([
            'deduction_name' => $validated['deduction_name'],
            'amount' => $validated['amount'],
            'reason' => $validated['reason'],
            'entered_by' => Auth::id(),
            'notes' => $validated['notes'],
        ]);

        // Update payroll totals
        $totalDeductions = $payroll->deductions()->sum('amount');
        $payroll->update([
            'total_deductions' => $totalDeductions,
            'net_salary' => $payroll->gross_salary - $totalDeductions,
        ]);

        // Log the action
        $payroll->auditLogs()->create([
            'action' => 'deduction_added',
            'new_value' => json_encode($deduction),
            'performed_by' => Auth::id(),
        ]);

        // Create notification
        Notification::create([
            'type' => 'payroll',
            'title' => 'Deduction Added to Payroll',
            'message' => "A deduction of {$validated['deduction_name']} ({$validated['amount']}) has been added to the payroll run for {$payroll->employee->first_name} {$payroll->employee->last_name}.",
            'data' => ['payroll_run_id' => $payroll->id, 'deduction_id' => $deduction->id],
            'priority' => 'medium',
            'category' => 'system',
            'is_read' => false,
        ]);

        return back()->with('success', 'Deduction added successfully!');
    }

    /**
     * Delete a deduction from a payroll run.
     */
    public function destroyDeduction(PayrollRun $payroll, PayrollDeduction $deduction)
    {
        $deduction->delete();

        // Update payroll totals
        $totalDeductions = $payroll->deductions()->sum('amount');
        $payroll->update([
            'total_deductions' => $totalDeductions,
            'net_salary' => $payroll->gross_salary - $totalDeductions,
        ]);

        // Log the action
        $payroll->auditLogs()->create([
            'action' => 'deduction_deleted',
            'old_value' => json_encode($deduction),
            'performed_by' => Auth::id(),
        ]);

        // Create notification
        Notification::create([
            'type' => 'payroll',
            'title' => 'Deduction Removed from Payroll',
            'message' => "The deduction {$deduction->deduction_name} has been removed from the payroll run for {$payroll->employee->first_name} {$payroll->employee->last_name}.",
            'data' => ['payroll_run_id' => $payroll->id, 'deduction_id' => $deduction->id],
            'priority' => 'medium',
            'category' => 'system',
            'is_read' => false,
        ]);

        return back()->with('success', 'Deduction removed successfully!');
    }

    /**
     * Store a newly created payroll run in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'payroll_month' => 'required|date',
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);
        $payrollMonth = Carbon::parse($validated['payroll_month'])->startOfMonth();

        // Check for existing payroll run for the same employee and month
        $existingPayroll = PayrollRun::where('employee_id', $validated['employee_id'])
            ->whereYear('payroll_month', $payrollMonth->year)
            ->whereMonth('payroll_month', $payrollMonth->month)
            ->first();

        if ($existingPayroll) {
            return back()->withErrors(['payroll_month' => 'A payroll run already exists for this employee and month.'])
                ->withInput();
        }

        // Calculate total sales for the month
        $totalSales = $this->calculateTotalSales($employee, $payrollMonth);

        // Calculate gross salary based on payroll type
        $grossSalary = $this->calculateGrossSalary($employee, $totalSales);

        $payroll = PayrollRun::create([
            'employee_id' => $validated['employee_id'],
            'payroll_month' => $payrollMonth->toDateString(),
            'payroll_type' => $employee->payroll_type,
            'total_sales' => $totalSales,
            'commission_rate' => $employee->commission_rate,
            'gross_salary' => $grossSalary,
            'total_deductions' => 0,
            'net_salary' => $grossSalary,
            'status' => 'draft',
            'created_by' => Auth::id(),
        ]);

        // Log the action
        $payroll->auditLogs()->create([
            'action' => 'created',
            'performed_by' => Auth::id(),
        ]);

        // Create notification
        Notification::create([
            'type' => 'payroll',
            'title' => 'Payroll Run Created',
            'message' => "Payroll run for {$employee->first_name} {$employee->last_name} for {$payrollMonth->format('F Y')} has been created.",
            'data' => ['payroll_run_id' => $payroll->id],
            'priority' => 'medium',
            'category' => 'system',
            'is_read' => false,
        ]);

        return redirect()->route('payrolls.show', $payroll)
            ->with('success', 'Payroll created successfully.');
    }

    /**
     * Display the specified payroll run.
     */
    public function show(PayrollRun $payroll)
    {
        $payroll->load(['employee', 'deductions', 'payment', 'adjustments', 'auditLogs']);
        return view('payrolls.show', compact('payroll'));
    }

    /**
     * Calculate total sales for an employee in a given month.
     */
    protected function calculateTotalSales(Employee $employee, Carbon $month)
    {
        return $employee->transactions()
            ->where('transaction_type', 'Income')
            ->whereMonth('date', $month->month)
            ->whereYear('date', $month->year)
            ->sum('amount');
    }

    /**
     * Calculate gross salary based on payroll type.
     */
    protected function calculateGrossSalary(Employee $employee, $totalSales)
    {
        switch ($employee->payroll_type) {
            case 'commission':
                return $totalSales * ($employee->commission_rate / 100);
            case 'fixed':
                return $employee->salary;
            case 'hybrid':
                // For hybrid, you can implement your own logic here
                return $employee->salary + ($totalSales * ($employee->commission_rate / 100));
            default:
                return $totalSales * 0.6; // Default to 60% commission
        }
    }

    /**
     * Recalculate payroll run.
     */
    public function recalculate(PayrollRun $payroll)
    {
        // Only allow recalculating if payroll is draft or pending approval
        if (!in_array($payroll->status, ['draft', 'pending_approval'])) {
            return back()->withErrors(['payroll' => 'Cannot recalculate a payroll that is not in draft or pending approval status.']);
        }

        $employee = $payroll->employee;
        $payrollMonth = Carbon::parse($payroll->payroll_month);

        // Calculate new total sales
        $totalSales = $this->calculateTotalSales($employee, $payrollMonth);

        // Calculate new gross salary
        $grossSalary = $this->calculateGrossSalary($employee, $totalSales);

        // Calculate new net salary
        $netSalary = $grossSalary - $payroll->total_deductions;

        $oldValues = [
            'total_sales' => $payroll->total_sales,
            'gross_salary' => $payroll->gross_salary,
            'net_salary' => $payroll->net_salary,
        ];

        // Update payroll
        $payroll->update([
            'total_sales' => $totalSales,
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary,
            'updated_by' => Auth::id(),
        ]);

        // Log the recalculation
        $payroll->auditLogs()->create([
            'action' => 'recalculated',
            'old_value' => json_encode($oldValues),
            'new_value' => json_encode([
                'total_sales' => $totalSales,
                'gross_salary' => $grossSalary,
                'net_salary' => $netSalary,
            ]),
            'performed_by' => Auth::id(),
        ]);

        // Create notification
        Notification::create([
            'type' => 'payroll',
            'title' => 'Payroll Recalculated',
            'message' => "Payroll run for {$employee->first_name} {$employee->last_name} for {$payrollMonth->format('F Y')} has been recalculated.",
            'data' => ['payroll_run_id' => $payroll->id],
            'priority' => 'medium',
            'category' => 'system',
            'is_read' => false,
        ]);

        return back()->with('success', 'Payroll recalculated successfully.');
    }

    /**
     * Update payroll run status.
     */
    public function updateStatus(Request $request, PayrollRun $payroll)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,pending_approval,approved,paid,cancelled,reversed',
        ]);

        $oldStatus = $payroll->status;
        $newStatus = $validated['status'];
        $payroll->update([
            'status' => $newStatus,
            'updated_by' => Auth::id(),
        ]);

        // Log status change
        $payroll->auditLogs()->create([
            'action' => 'status_changed',
            'old_value' => $oldStatus,
            'new_value' => $newStatus,
            'performed_by' => Auth::id(),
        ]);

        // Handle expense transaction
        if ($newStatus === 'paid' && $oldStatus !== 'paid') {
            // Create expense transaction
            $transaction = Transaction::create([
                'employee_id' => $payroll->employee_id,
                'recorded_by' => Auth::id(),
                'transaction_id' => 'PR-' . $payroll->id . '-' . time(),
                'receipt_id' => null,
                'customer_name' => null,
                'amount' => $payroll->net_salary,
                'transaction_type' => 'Expense',
                'payment_method' => 'other',
                'service_description' => "Salary payment for {$payroll->employee->first_name} {$payroll->employee->last_name} - " . \Carbon\Carbon::parse($payroll->payroll_month)->format('F Y'),
                'notes' => "Payroll Run ID: {$payroll->id}",
                'date' => now(),
            ]);

            // Store transaction ID in payroll (we can add a column later, or just use notes for now)
            $payroll->update(['notes' => "Expense Transaction ID: {$transaction->id}"]);
        } elseif ($oldStatus === 'paid' && $newStatus !== 'paid') {
            // Delete expense transaction
            $transaction = Transaction::where('notes', 'like', "%Payroll Run ID: {$payroll->id}%")
                ->where('transaction_type', 'Expense')
                ->first();
            if ($transaction) {
                $transaction->delete();
            }
        }

        // Create notification for status change
        $statusText = ucwords(str_replace('_', ' ', $newStatus));
        Notification::create([
            'type' => 'payroll',
            'title' => 'Payroll Status Updated',
            'message' => "Payroll run for {$payroll->employee->first_name} {$payroll->employee->last_name} has been {$statusText}.",
            'data' => ['payroll_run_id' => $payroll->id, 'old_status' => $oldStatus, 'new_status' => $newStatus],
            'priority' => $newStatus === 'paid' ? 'high' : 'medium',
            'category' => 'system',
            'is_read' => false,
        ]);

        return back()->with('success', 'Payroll status updated successfully.');
    }
}
