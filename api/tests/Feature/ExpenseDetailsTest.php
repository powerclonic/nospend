<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ExpenseDetailsTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_user_can_view_expense_details(): void
    {
        Sanctum::actingAs(
            User::factory()
                ->hasExpenses(10, [
                    'due_date' => today()->subDay()
                ])
                ->create()
        );

        $response = $this->getJson('/api/expenses/details');

        $response->dump();

        $response->assertStatus(200);
    }

    public function test_expense_details_are_cached(): void
    {
        $user = User::factory()
            ->hasExpenses(10, [
                'due_date' => today()->subDay()
            ])
            ->create();

        Sanctum::actingAs($user);

        $this->getJson('/api/expenses/details');

        $this->assertTrue(Cache::has('expense_details_' . $user->id));
    }

    public function test_cached_expense_details_are_returned(): void
    {
        $user = User::factory()->create();
        $cachedDetails = [
            'category' => ['Food', 'Transport'],
            'payment_source' => ['Bank', 'Cash'],
            'payment_method' => ['Credit Card', 'Debit Card'],
        ];

        Cache::put('expense_details_' . $user->id, $cachedDetails);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/expenses/details');

        $response->assertStatus(200)
            ->assertJson($cachedDetails);
    }

    public function test_expense_details_are_unique(): void
    {
        $user = User::factory()
            ->hasExpenses(10, [
                'category' => 'Food',
                'payment_source' => 'Bank',
                'payment_method' => 'Credit Card',
                'due_date' => today()->subDay()
            ])
            ->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/expenses/details');

        $response->assertStatus(200)
            ->assertJson([
                'category' => ['Food'],
                'payment_source' => ['Bank'],
                'payment_method' => ['Credit Card'],
            ]);
    }
}
