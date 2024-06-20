<?php

namespace Tests\Feature;

use App\Enums\Status;
use App\Models\Expense;
use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    public function test_fails_to_create_when_unauthenticated(): void
    {
        $response = $this->postJson(
            uri: '/api/expenses',
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
            uri: '/api/expenses',
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
            uri: '/api/expenses',
            data: []
        );

        $response->assertStatus(422);
    }

    public function test_fails_to_return_dashboard_data_when_unauthenticated(): void
    {
        $response = $this->getJson(
            uri: '/api/expenses'
        );

        $response->assertStatus(401);
    }

    public function test_returns_the_correct_dashboard_data_structure(): void
    {
        Sanctum::actingAs(
            $user = User::factory()
                ->hasExpenses(2, [
                    'due_date' => today()->subDay()
                ])
                ->hasExpenses(3, [
                    'due_date' => today(),
                    'status' => Status::AWAITING_PAYMENT
                ])
                ->hasExpenses(3, [
                    'due_date' => today(),
                    'status' => Status::PAID
                ])
                ->create()
        );

        $response = $this->getJson(
            uri: '/api/expenses'
        );

        $response->dump();

        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->whereAllType([
                'data.name' => 'string',
                'data.today_expenses' => 'array',
                'data.month_statistics.expenses_quantity' => 'integer|double',
                'data.month_statistics.expenses_total_value' => 'integer|double',
                'data.month_statistics.expenses_total_paid' => 'integer|double',
                'data.month_statistics.expenses_total_unpaid' => 'integer|double',
                'data.month_statistics.expenses_total_not_recurrent' => 'integer|double',
            ])
        );
    }
}
