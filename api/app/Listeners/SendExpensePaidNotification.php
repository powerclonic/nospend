<?php

namespace App\Listeners;

use App\Events\ExpensePaid;
use App\Mail\ExpensePaid as MailExpensePaid;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendExpensePaidNotification
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
    public function handle(ExpensePaid $event): void
    {
        Mail::to($event->expense->user->email)
            ->queue(new MailExpensePaid($event->expense));
    }
}
