<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


    public function test_user_can_view_reports_page(): void
    {
        $response = $this->get('/reports/income-expense');

        $response->assertStatus(200);
        $response->assertSee('Income & Expense Report');
    }
}
