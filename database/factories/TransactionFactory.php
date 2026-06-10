<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        $transactionType = $this->faker->randomElement(['Income', 'Expense']);
        
        return [
            'employee_id' => Employee::factory(),
            'recorded_by' => User::factory(),
            'transaction_id' => strtoupper(Str::random(10)),
            'receipt_id' => 'RCP-' . $this->faker->unique()->numberBetween(1000, 9999),
            'customer_name' => $this->faker->name(),
            'amount' => $this->faker->randomFloat(2, 5000, 500000),
            'transaction_type' => $transactionType,
            'payment_method' => $this->faker->randomElement(['Cash', 'MobileMoney', 'Card', 'Bank', 'Other']),
            'service_description' => $transactionType === 'Income' 
                ? $this->faker->randomElement(['HairCut', 'HairStyling', 'Nails', 'Facial', 'Makeup'])
                : $this->faker->randomElement(['Rent', 'Salaries', 'Utilities', 'BeautyProducts', 'Marketing']),
            'notes' => $this->faker->optional()->sentence(),
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function income(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_type' => 'Income',
            'service_description' => $this->faker->randomElement(['HairCut', 'HairStyling', 'Nails', 'Facial', 'Makeup']),
        ]);
    }

    public function expense(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_type' => 'Expense',
            'service_description' => $this->faker->randomElement(['Rent', 'Salaries', 'Utilities', 'BeautyProducts', 'Marketing']),
        ]);
    }
}
