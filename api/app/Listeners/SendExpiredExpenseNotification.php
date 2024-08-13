<?php

namespace App\Listeners;

use App\Events\ExpenseMarkedAsExpired;
use App\Mail\ExpenseExpired;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendExpiredExpenseNotification
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
    public function handle(ExpenseMarkedAsExpired $event): void
    {
        Mail::to($event->expense->user->email)
            ->queue(new ExpenseExpired($event->expense));
    }
}
