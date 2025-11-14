<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Transaction;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = [
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

        $transaction = new Transaction();
        
        $this->assertEquals($fillable, $transaction->getFillable());
    }

    /** @test */
    public function it_casts_date_to_datetime()
    {
        $transaction = Transaction::factory()->create([
            'date' => '2024-01-15',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $transaction->date);
    }

    /** @test */
    public function it_casts_amount_to_decimal()
    {
        $transaction = Transaction::factory()->create([
            'amount' => 50000.50,
        ]);

        $this->assertEquals('50000.50', $transaction->amount);
    }

    /** @test */
    public function it_belongs_to_employee()
    {
        $employee = Employee::factory()->create();
        $transaction = Transaction::factory()->create([
            'employee_id' => $employee->employee_id,
        ]);

        $this->assertInstanceOf(Employee::class, $transaction->employee);
        $this->assertEquals($employee->employee_id, $transaction->employee->employee_id);
    }

    /** @test */
    public function it_belongs_to_user_who_recorded_it()
    {
        $user = User::factory()->create();
        $transaction = Transaction::factory()->create([
            'recorded_by' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $transaction->recordedBy);
        $this->assertEquals($user->id, $transaction->recordedBy->id);
    }

    /** @test */
    public function it_can_be_income_type()
    {
        $transaction = Transaction::factory()->income()->create();

        $this->assertEquals('Income', $transaction->transaction_type);
    }

    /** @test */
    public function it_can_be_expense_type()
    {
        $transaction = Transaction::factory()->expense()->create();

        $this->assertEquals('Expense', $transaction->transaction_type);
    }
}
