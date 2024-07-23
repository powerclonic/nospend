<?php

use App\Jobs\ProcessAutoPayExpenses;
use App\Jobs\ProcessRecurrentExpenses;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::job(ProcessRecurrentExpenses::class)->monthly();

Schedule::job(ProcessAutoPayExpenses::class)->daily();
