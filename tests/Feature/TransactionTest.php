<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class TransactionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $employee;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->employee = Employee::factory()->create();
    }

    /** @test */
    public function user_can_view_income_transactions_page()
    {
        $this->actingAs($this->user)
            ->get(route('transactions.income'))
            ->assertStatus(200)
            ->assertSee('Income Transactions');
    }

    /** @test */
    public function user_can_view_expense_transactions_page()
    {
        $this->actingAs($this->user)
            ->get(route('transactions.expense'))
            ->assertStatus(200)
            ->assertSee('Expense Transactions');
    }

    /** @test */
    public function user_can_create_income_transaction()
    {
        $transactionData = [
            'employee_id' => $this->employee->employee_id,
            'customer_name' => 'John Doe',
            'amount' => 50000,
            'transaction_type' => 'Income',
            'payment_method' => 'Cash',
            'service_offered' => 'HairCut',
            'date' => now()->format('Y-m-d'),
            'notes' => 'Test transaction',
        ];

        $this->actingAs($this->user)
            ->post(route('transactions.store'), $transactionData)
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('transactions', [
            'customer_name' => 'John Doe',
            'amount' => 50000,
            'transaction_type' => 'Income',
        ]);
    }

    /** @test */
    public function user_can_create_expense_transaction()
    {
        $transactionData = [
            'employee_id' => $this->employee->employee_id,
            'amount' => 30000,
            'transaction_type' => 'Expense',
            'payment_method' => 'Bank',
            'expense_type' => 'Rent',
            'date' => now()->format('Y-m-d'),
            'notes' => 'Monthly rent',
        ];

        $this->actingAs($this->user)
            ->post(route('transactions.store'), $transactionData)
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('transactions', [
            'amount' => 30000,
            'transaction_type' => 'Expense',
            'service_description' => 'Rent',
        ]);
    }

    /** @test */
    public function user_can_view_transaction_details()
    {
        $transaction = Transaction::factory()->create([
            'employee_id' => $this->employee->employee_id,
            'recorded_by' => $this->user->id,
        ]);

        $this->actingAs($this->user)
            ->get(route('transactions.details', $transaction->id))
            ->assertStatus(200)
            ->assertSee($transaction->transaction_id);
    }

    /** @test */
    public function user_can_edit_transaction()
    {
        $transaction = Transaction::factory()->create([
            'employee_id' => $this->employee->employee_id,
            'recorded_by' => $this->user->id,
            'amount' => 50000,
        ]);

        $this->actingAs($this->user)
            ->get(route('transactions.edit', $transaction->id))
            ->assertStatus(200)
            ->assertSee('Edit Transaction');
    }

    /** @test */
    public function user_can_update_transaction()
    {
        $transaction = Transaction::factory()->create([
            'employee_id' => $this->employee->employee_id,
            'recorded_by' => $this->user->id,
            'amount' => 50000,
            'transaction_type' => 'Income',
        ]);

        $updateData = [
            'employee_id' => $this->employee->employee_id,
            'customer_name' => 'Updated Customer',
            'amount' => 75000,
            'transaction_type' => 'Income',
            'payment_method' => 'MobileMoney',
            'service_offered' => 'HairStyling',
            'date' => now()->format('Y-m-d'),
        ];

        $this->actingAs($this->user)
            ->put(route('transactions.update', $transaction->id), $updateData)
            ->assertRedirect(route('transactions.income'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'customer_name' => 'Updated Customer',
            'amount' => 75000,
        ]);
    }

    /** @test */
    public function user_can_delete_transaction()
    {
        $transaction = Transaction::factory()->create([
            'employee_id' => $this->employee->employee_id,
            'recorded_by' => $this->user->id,
        ]);

        $this->actingAs($this->user)
            ->delete(route('transactions.delete', $transaction->id))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('transactions', [
            'id' => $transaction->id,
        ]);
    }

    /** @test */
    public function transaction_requires_valid_employee()
    {
        $transactionData = [
            'employee_id' => 99999, // Non-existent employee
            'amount' => 50000,
            'transaction_type' => 'Income',
            'payment_method' => 'Cash',
            'date' => now()->format('Y-m-d'),
        ];

        $this->actingAs($this->user)
            ->post(route('transactions.store'), $transactionData)
            ->assertSessionHasErrors('employee_id');
    }

    /** @test */
    public function transaction_requires_positive_amount()
    {
        $transactionData = [
            'employee_id' => $this->employee->employee_id,
            'amount' => -100,
            'transaction_type' => 'Income',
            'payment_method' => 'Cash',
            'date' => now()->format('Y-m-d'),
        ];

        $this->actingAs($this->user)
            ->post(route('transactions.store'), $transactionData)
            ->assertSessionHasErrors('amount');
    }

    /** @test */
    public function transaction_date_field_is_used_not_created_at()
    {
        $specificDate = '2024-01-15';
        
        $transactionData = [
            'employee_id' => $this->employee->employee_id,
            'amount' => 50000,
            'transaction_type' => 'Income',
            'payment_method' => 'Cash',
            'service_offered' => 'HairCut',
            'date' => $specificDate,
        ];

        $this->actingAs($this->user)
            ->post(route('transactions.store'), $transactionData);

        $transaction = Transaction::latest()->first();
        
        $this->assertEquals($specificDate, $transaction->date->format('Y-m-d'));
    }

    /** @test */
    public function guest_cannot_access_transactions()
    {
        $this->get(route('transactions.income'))
            ->assertRedirect(route('login'));
    }
}
