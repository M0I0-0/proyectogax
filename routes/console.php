<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// VetCare: Send appointment reminders every day at 08:00 AM
// Make sure to activate the Laravel scheduler in your server cron:
// * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
Schedule::command('vetcare:send-reminders')->dailyAt('08:00');
