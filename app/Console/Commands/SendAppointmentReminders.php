<?php

namespace App\Console\Commands;

use App\Mail\AppointmentReminder;
use App\Models\Appointment;
use App\Models\NotificationLog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vetcare:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send 24-hour reminder emails for upcoming appointments';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Send one daily batch for all appointments happening tomorrow.
        // This matches the scheduler configuration that runs every day at 08:00.
        $start = Carbon::tomorrow()->startOfDay();
        $end   = Carbon::tomorrow()->endOfDay();

        $appointments = Appointment::with(['pet.owner', 'veterinarian'])
            ->whereBetween('scheduled_at', [$start, $end])
            ->whereIn('status', ['pendiente', 'confirmada'])
            ->where('reminder_sent', false)
            ->get();

        if ($appointments->isEmpty()) {
            $this->info('No appointments require reminders at this time.');
            return self::SUCCESS;
        }

        $this->info("Found {$appointments->count()} appointment(s) to remind...");
        $sent    = 0;
        $failed  = 0;

        foreach ($appointments as $appointment) {
            $ownerEmail = $appointment->pet->owner->email ?? null;

            if (!$ownerEmail) {
                $this->warn("  ⚠ Appointment #{$appointment->id}: Owner has no email. Skipping.");
                continue;
            }

            try {
                Mail::to($ownerEmail)->send(new AppointmentReminder($appointment));

                // Mark as reminder sent
                $appointment->update(['reminder_sent' => true]);

                // Log the notification
                NotificationLog::create([
                    'appointment_id'  => $appointment->id,
                    'type'            => 'reminder',
                    'recipient_email' => $ownerEmail,
                    'status'          => 'sent',
                ]);

                $this->info("  ✓ Reminder sent to {$ownerEmail} for appointment #{$appointment->id} ({$appointment->pet->name} on {$appointment->scheduled_at->format('d/m/Y H:i')}).");
                $sent++;
            } catch (\Exception $e) {
                NotificationLog::create([
                    'appointment_id'  => $appointment->id,
                    'type'            => 'reminder',
                    'recipient_email' => $ownerEmail,
                    'status'          => 'failed',
                    'notes'           => $e->getMessage(),
                ]);

                $this->error("  ✗ Failed to send reminder for appointment #{$appointment->id}: " . $e->getMessage());
                $failed++;
            }
        }

        $this->info("Done. Sent: {$sent} | Failed: {$failed}");
        return self::SUCCESS;
    }
}
