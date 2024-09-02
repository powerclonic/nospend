<?php

namespace App\Jobs;

use App\Enums\Status;
use App\Events\RecurrentExpenseProcessed;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessRecurrentExpenses implements ShouldQueue
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
        $recurrentExpenses = Expense::where('recurrent', true)
            ->whereMonth(
                'due_date',
                today()->subMonth()->month
            )
            ->get();

        $recurrentExpenses->each(function (Expense $item) {
            $replicated = $item->replicate()
                ->fill([
                    'due_date' => $item->due_date->addMonth(),
                    'status' => Status::AWAITING_PAYMENT
                ]);

            $replicated->save();

            RecurrentExpenseProcessed::dispatch($replicated);
        });
    }
}
