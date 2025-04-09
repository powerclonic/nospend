<?php

namespace Tests\Feature;

use App\Enums\Status;
use App\Jobs\CheckExpiredExpenses;
use App\Jobs\ProcessAutoPayExpenses;
use App\Jobs\ProcessRecurrentExpenses;
use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExpenseProcessingTest extends TestCase
{
    use RefreshDatabase;

    public function test_expense_status_change_to_paid_when_the_due_date_is_reached_and_auto_pay_is_enabled(): void
    {
        User::factory()
            ->hasExpenses(1, [
                'due_date' => Carbon::tomorrow(),
                'status' => Status::AWAITING_PAYMENT,
                'auto_pay' => true
            ])
            ->create();

        ProcessAutoPayExpenses::dispatch();

        $this->assertDatabaseHas('expenses', [
            'status' => Status::AWAITING_PAYMENT
        ]);

        $this->travel(1)->day();

        ProcessAutoPayExpenses::dispatch();

        $this->assertDatabaseHas('expenses', [
            'status' => Status::PAID
        ]);
    }

    public function test_expense_is_replicated_when_next_month_is_reached_and_recurrent_is_enabled()
    {
        User::factory()
            ->hasExpenses(1, [
                'due_date' => Carbon::today(),
                'recurrent' => true
            ])
            ->create();

        ProcessRecurrentExpenses::dispatch();

        $this->assertDatabaseCount('expenses', 1);

        $this->travel(1)->month();

        ProcessRecurrentExpenses::dispatch();

        $this->assertDatabaseCount('expenses', 2);
    }

    public function test_expense_is_not_replicated_if_repeat_for_reaches_zero()
    {
        User::factory()
            ->hasExpenses(1, [
                'due_date' => Carbon::today(),
                'recurrent' => true,
                'repeat_for' => 0
            ])
            ->create();

        ProcessRecurrentExpenses::dispatch();

        $this->assertDatabaseCount('expenses', 1);

        $this->travel(1)->month();

        ProcessRecurrentExpenses::dispatch();

        $this->assertDatabaseCount('expenses', 1);

        $this->assertDatabaseHas('expenses', [
            'repeat_for' => 0
        ]);
    }

    public function test_expense_is_replicated_if_repeat_for_is_higher_than_0_and_decreases_repeat_for_values_by_one()
    {
        User::factory()
            ->hasExpenses(1, [
                'due_date' => Carbon::today(),
                'recurrent' => true,
                'repeat_for' => 2
            ])
            ->create();

        ProcessRecurrentExpenses::dispatch();

        $this->assertDatabaseCount('expenses', 1);

        $this->travel(1)->month();

        ProcessRecurrentExpenses::dispatch();

        $this->assertDatabaseCount('expenses', 2);

        $this->assertDatabaseHas('expenses', [
            'repeat_for' => 1
        ]);
    }

    public function test_expense_status_changes_to_expired_after_due_date(): void
    {
        $user = User::factory()
            ->hasExpenses(1, [
                'due_date' => Carbon::today(),
                'status' => Status::AWAITING_PAYMENT
            ])
            ->create();

        $this->travel(1)->day();

        CheckExpiredExpenses::dispatch();

        $this->assertEquals($user->expenses->first()->status, Status::EXPIRED);
    }

    public function test_expense_status_remains_paid_after_due_date(): void
    {
        $user = User::factory()
            ->hasExpenses(1, [
                'due_date' => Carbon::today(),
                'status' => Status::PAID
            ])
            ->create();

        $this->travel(1)->day();

        CheckExpiredExpenses::dispatch();

        $this->assertEquals($user->expenses->first()->status, Status::PAID);
    }
}
