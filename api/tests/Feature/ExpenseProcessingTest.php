<?php

namespace Tests\Feature;

use App\Enums\Status;
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
}
