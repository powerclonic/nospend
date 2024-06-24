<?php

namespace Tests\Feature;

use App\Enums\Status;
use App\Models\Expense;
use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_list_returns_the_correct_dashboard_data_structure_when_no_parameters_are_sent(): void
    {
        Sanctum::actingAs(
            User::factory()
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

        $response->assertStatus(200);
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

    public function test_list_fails_when_only_one_parameter_is_passed(): void
    {
        Sanctum::actingAs(
            User::factory()
                ->create()
        );

        $response = $this->getJson(
            uri: '/api/expenses?year=2024'
        );

        $response->assertStatus(422);
    }

    public function test_list_returns_the_correct_listing_when_all_parameters_are_sent(): void
    {
        Sanctum::actingAs(
            User::factory()
                ->hasExpenses(3, [
                    'due_date' => today()
                ])
                ->create()
        );

        $response = $this->call(
            method: 'GET',
            uri: '/api/expenses',
            parameters: [
                'year' => 2024,
                'month' => 6
            ]
        );

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->whereAllType([
                'data.0.id' => 'integer',
                'data.0.name' => 'string',
                'data.0.value' => 'double',
                'data.0.due_date' => 'string',
                'data.0.payment_method' => 'string|null',
                'data.0.payment_source' => 'string|null',
                'data.0.created_at' => 'string',
                'data.0.recurrent' => 'boolean',
                'data.0.category' => 'string|null'
            ])
        );
    }

    public function test_show_returns_not_found_when_a_invalid_expense_is_sent()
    {
        Sanctum::actingAs(
            User::factory()
                ->hasExpenses(1)
                ->create()
        );

        $response = $this->get(
            uri: '/api/expenses/2'
        );

        $response->assertStatus(404);
    }

    public function test_show_returns_the_correct_data_when_a_valid_expense_is_sent()
    {
        Sanctum::actingAs(
            User::factory()
                ->hasExpenses(1)
                ->create()
        );

        $response = $this->get(
            uri: '/api/expenses/1'
        );

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->whereAllType([
                'data.id' => 'integer',
                'data.name' => 'string',
                'data.value' => 'double',
                'data.due_date' => 'string',
                'data.payment_method' => 'string|null',
                'data.payment_source' => 'string|null',
                'data.created_at' => 'string',
                'data.recurrent' => 'boolean',
                'data.category' => 'string|null'
            ])
        );
    }
}
