<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvanceRecovery extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'salary_advance_id',
        'payroll_run_id',
        'amount_recovered',
        'recovery_date',
        'recovered_by',
        'notes',
    ];

    /**
     * Relationship with SalaryAdvance.
     */
    public function salaryAdvance()
    {
        return $this->belongsTo(SalaryAdvance::class);
    }

    /**
     * Relationship with PayrollRun (optional).
     */
    public function payrollRun()
    {
        return $this->belongsTo(PayrollRun::class);
    }

    /**
     * Relationship with User who recovered the advance.
     */
    public function recoveredBy()
    {
        return $this->belongsTo(User::class, 'recovered_by');
    }
}
