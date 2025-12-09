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
        'date',
        'created_at',
        'updated_at'
    ];
    public $timestamps = true;

    protected $casts = [
        'date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

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

    // Relationship to Service (if applicable)
    // Note: service_description stores the service name, not an ID
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_description', 'id');
    }
}
