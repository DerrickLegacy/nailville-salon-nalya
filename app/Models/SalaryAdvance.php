<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryAdvance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_id',
        'amount',
        'reason',
        'request_date',
        'approved_by',
        'approval_date',
        'status',
        'remaining_balance',
    ];

    /**
     * Relationship with Employee.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    /**
     * Relationship with User who approved the advance.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Relationship with AdvanceRecovery (one advance has many recoveries).
     */
    public function recoveries()
    {
        return $this->hasMany(AdvanceRecovery::class);
    }
}
