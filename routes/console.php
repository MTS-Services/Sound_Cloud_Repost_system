<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:repost-request-expire')->everyMinute();
Schedule::command('app:update-real-followers-daily')->daily();
Schedule::command('app:top-performance-source-weekly-update')->weekly();

