<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function user_can_view_employees_list()
    {
        Employee::factory()->count(3)->create();

        $this->actingAs($this->user)
            ->get('/employees') // Adjust route as needed
            ->assertStatus(200);
    }

    /** @test */
    public function employee_has_transactions()
    {
        $employee = Employee::factory()->create();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Collection::class,
            $employee->transactions
        );
    }
}
