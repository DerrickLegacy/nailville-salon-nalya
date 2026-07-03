<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollRun extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_id',
        'payroll_month',
        'payroll_type',
        'total_sales',
        'commission_rate',
        'gross_salary',
        'total_deductions',
        'net_salary',
        'status',
        'notes',
        'created_by',
        'updated_by',
        'payment_date',
    ];

    /**
     * Relationship with Employee.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    /**
     * Relationship with User who created the payroll.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship with User who last updated the payroll.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Relationship with PayrollDeduction (one payroll has many deductions).
     */
    public function deductions()
    {
        return $this->hasMany(PayrollDeduction::class);
    }

    /**
     * Relationship with SalaryAdvance recoveries linked to this payroll.
     */
    public function advanceRecoveries()
    {
        return $this->hasMany(AdvanceRecovery::class);
    }

    /**
     * Relationship with PayrollPayment (one payroll has one payment).
     */
    public function payment()
    {
        return $this->hasOne(PayrollPayment::class);
    }

    /**
     * Relationship with PayrollAdjustment (one payroll has many adjustments).
     */
    public function adjustments()
    {
        return $this->hasMany(PayrollAdjustment::class);
    }

    /**
     * Relationship with PayrollAuditLog (one payroll has many audit logs).
     */
    public function auditLogs()
    {
        return $this->hasMany(PayrollAuditLog::class);
    }
}
