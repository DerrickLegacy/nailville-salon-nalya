<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // If your table is 'transactions', you don't need to specify $table
    // $table = 'transactions';

    protected $fillable = [
        'employee_id',
        'recorded_by',
        'transaction_id',
        'receipt_id',
        'customer_name',
        'amount',
        'transaction_type',
        'payment_method',
        'service_description',
        'notes',
        'created_at',
        'updated_at'
    ];
    public $timestamps = true; // make sure this is enabled

    protected $dates = ['created_at', 'updated_at'];

    // Relationship to Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    // Relationship to User who recorded the transaction
    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by', 'id');
    }
}
