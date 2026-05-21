<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ====================================================================
// VetCare Task Scheduling
// ====================================================================
// To activate the Laravel scheduler, add this line to your server cron:
//   * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
//
// To configure Mailtrap SMTP, update your .env file:
//   MAIL_MAILER=smtp
//   MAIL_HOST=sandbox.smtp.mailtrap.io
//   MAIL_PORT=2525
//   MAIL_USERNAME=<your-mailtrap-username>
//   MAIL_PASSWORD=<your-mailtrap-password>
//   MAIL_FROM_ADDRESS="noreply@vetcare.com"
//   MAIL_FROM_NAME="VetCare Sistema"
// ====================================================================

// Send appointment reminders every day at 08:00 AM
// Finds appointments in the 23-25hr window that haven't been reminded yet
Schedule::command('vetcare:send-reminders')->dailyAt('08:00');

// Send vaccine reminders every Monday at 09:00 AM
// Finds pets with overdue or upcoming vaccines (within 7 days) and notifies owners
Schedule::command('vetcare:vaccine-reminders')->weeklyOn(1, '09:00');
