<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollPayment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'payroll_run_id',
        'amount',
        'payment_method',
        'transaction_reference',
        'payment_date',
        'paid_by',
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
     * Relationship with User who made the payment.
     */
    public function paidBy()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }
}
