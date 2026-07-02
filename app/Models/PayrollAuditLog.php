<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollAuditLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'payroll_run_id',
        'action',
        'old_value',
        'new_value',
        'performed_by',
    ];

    /**
     * Relationship with PayrollRun.
     */
    public function payrollRun()
    {
        return $this->belongsTo(PayrollRun::class);
    }

    /**
     * Relationship with User who performed the action.
     */
    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
