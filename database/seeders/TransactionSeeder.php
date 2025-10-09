<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory;
use Carbon\Carbon;


class TransactionSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        // Get valid employee IDs from employees table
        $employeeIds = DB::table('employees')->pluck('employee_id')->toArray();

        // Get valid user IDs for recorded_by
        $userIds = DB::table('users')->pluck('id')->toArray();

        if (empty($userIds)) {
            $this->command->error('No users found in users table. Please seed users first.');
            return;
        }

        $records = [];
        $startId = DB::table('transactions')->max('id') + 1; // continue from last ID

        // -------- Generate data for yesterday and today --------
        foreach (['yesterday', 'today'] as $day) {
            $date = $day === 'yesterday' ? Carbon::yesterday() : Carbon::today();

            // Generate transactions for each hour (0–23)
            for ($hour = 0; $hour < 1000; $hour++) {
                // Add multiple records per hour for realism
                for ($i = 0; $i < 5; $i++) {
                    $num = $startId++;
                    $records[] = [
                        'employee_id' => !empty($employeeIds) ? $faker->randomElement($employeeIds) : null,
                        'recorded_by' => $faker->randomElement($userIds),
                        'transaction_id' => 'TXN' . str_pad($num, 5, '0', STR_PAD_LEFT),
                        'receipt_id' => 'RCP' . str_pad($num, 5, '0', STR_PAD_LEFT),
                        'customer_name' => $faker->name,
                        'amount' => $faker->numberBetween(20000, 2000000),
                        'transaction_type' => $faker->randomElement(['Income', 'Expense']),
                        'payment_method' => $faker->randomElement(['Cash', 'MobileMoney', 'Card', 'Bank', 'Other']),
                        'service_description' => 'Generated test data',
                        'notes' => null,
                        'created_at' => $date->copy()->hour($hour)->minute(rand(0, 59))->second(rand(0, 59)),
                        'updated_at' => Carbon::now(),
                    ];
                }
            }
        }

        // Insert in chunks for efficiency
        foreach (array_chunk($records, 100) as $chunk) {
            DB::table('transactions')->insert($chunk);
        }

        $this->command->info('✅ Inserted ' . count($records) . ' transaction records for today & yesterday!');
    }
}
