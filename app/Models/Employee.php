<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    /**
     * The primary key for the table.
     */
    protected $primaryKey = 'employee_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'national_id_number',
        'marital_status',
        'email',
        'phone_number',
        'address',
        'city',
        'country',
        'employee_code',
        'job_title',
        'department',
        'employment_type',
        'hire_date',
        'contract_end_date',
        'salary',
        'bank_account_number',
        'bank_name',
        'tin_number',
        'nssf_number',
        'work_status',
        'shift',
        'work_location',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
    ];

    /**
     * Relationship with User (an employee may be linked to a user account).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship with Transactions (one employee may serve many transactions).
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'employee_id', 'employee_id');
    }

    /**
     * Accessor for full name.
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
