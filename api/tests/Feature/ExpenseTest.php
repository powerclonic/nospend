<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    public function test_fails_to_create_when_unauthenticated(): void
    {
        $response = $this->postJson(
            uri: '/api/expense',
            data: Expense::factory()->definition()
        );

        $response->assertStatus(401);
    }

    public function test_creates_expense_with_valid_input(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $response = $this->postJson(
            uri: '/api/expense',
            data: Expense::factory()->definition()
        );

        $response->assertStatus(201);
    }

    public function test_fails_to_create_with_invalid_input(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $response = $this->postJson(
            uri: '/api/expense',
            data: []
        );

        $response->assertStatus(422);
    }
}
