<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollDeduction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'payroll_run_id',
        'deduction_name',
        'amount',
        'reason',
        'entered_by',
        'notes',
    ];

    /**
     * Relationship with PayrollRun.
     */
    public function payrollRun()
    {
        return $this->belongsTo(PayrollRun::class);
    }

    /**
     * Relationship with User who entered the deduction.
     */
    public function enteredBy()
    {
        return $this->belongsTo(User::class, 'entered_by');
    }
}
