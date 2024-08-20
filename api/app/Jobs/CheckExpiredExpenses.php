<?php

namespace App\Jobs;

use App\Enums\Status;
use App\Events\ExpenseMarkedAsExpired;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckExpiredExpenses implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $yesterdayExpenses = Expense::where('due_date', Carbon::yesterday())
            ->where('status', Status::AWAITING_PAYMENT)
            ->get();

        $yesterdayExpenses->each(function (Expense $item) {
            $item->update(['status' => Status::EXPIRED]);

            ExpenseMarkedAsExpired::dispatch($item);
        });
    }
}
