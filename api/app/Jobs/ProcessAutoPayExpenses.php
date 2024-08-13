<?php

namespace App\Jobs;

use App\Enums\Status;
use App\Events\ExpensePaid;
use App\Models\Expense;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessAutoPayExpenses implements ShouldQueue
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
        /** @var Collection $recurrentExpenses */
        $todayExpenses = Expense::where('auto_pay', true)
            ->where('due_date', today())
            ->get();

        $todayExpenses->each(function (Expense $item) {
            $item->update(['status' => Status::PAID]);

            ExpensePaid::dispatch($item);
        });
    }
}
