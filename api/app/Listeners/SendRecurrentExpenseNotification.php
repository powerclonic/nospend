<?php

namespace App\Listeners;

use App\Events\RecurrentExpenseProcessed;
use App\Mail\RecurrentExpenseCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendRecurrentExpenseNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RecurrentExpenseProcessed $event): void
    {
        Mail::to($event->expense->user->email)
            ->queue(new RecurrentExpenseCreated($event->expense));
    }
}
