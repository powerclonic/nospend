<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserExpenseTest extends TestCase
{
    use RefreshDatabase;

    public function test_expense_method_returns_all_user_expenses(): void
    {
        $user = User::factory()
            ->hasExpenses(5)
            ->create();

        $expenses = $user->expenses()->get();

        $this->assertCount(5, $expenses);
    }

    public function test_expenses_month_method_returns_all_user_expenses_in_the_current_month(): void
    {
        $user = User::factory()
            ->hasExpenses(2, fn () => ['due_date' => now()])
            ->hasExpenses(3, fn () => ['due_date' => now()->subMonths(2)])
            ->create();

        $expenses = $user->expensesMonth(
            now()->month,
            now()->year
        )->get();

        $this->assertCount(2, $expenses);
    }

    public function test_expenses_month_method_returns_all_user_expenses_in_the_current_day(): void
    {
        $user = User::factory()
            ->hasExpenses(2, fn () => ['due_date' => now()])
            ->hasExpenses(3, fn () => ['due_date' => now()->subDays(1)])
            ->create();

        $expenses = $user->expensesToday()->get();

        $this->assertCount(2, $expenses);
    }
}
