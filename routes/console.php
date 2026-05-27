<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



// Send appointment reminders every day at 08:00 AM
// Finds appointments in the 23-25hr window that haven't been reminded yet
Schedule::command('vetcare:send-reminders')->dailyAt('08:00');

// Send vaccine reminders every Monday at 09:00 AM
// Finds pets with overdue or upcoming vaccines (within 7 days) and notifies owners
Schedule::command('vetcare:vaccine-reminders')->weeklyOn(1, '09:00');
