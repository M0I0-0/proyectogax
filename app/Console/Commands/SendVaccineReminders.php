<?php

namespace App\Console\Commands;

use App\Mail\VaccineReminderMail;
use App\Models\Owner;
use App\Models\Pet;
use App\Models\Vaccination;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendVaccineReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vetcare:vaccine-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send vaccine reminder emails to pet owners for overdue or upcoming vaccines (within 7 days)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $today       = Carbon::today();
        $windowEnd   = Carbon::today()->addDays(7);   // upcoming: due within next 7 days
        $sent        = 0;
        $failed      = 0;
        $skipped     = 0;

        $this->info('🔍 Scanning vaccination records for overdue or upcoming doses...');

        // Get all pets that have vaccinations with next_dose_due set
        $pets = Pet::with(['owner', 'vaccinations'])
            ->whereHas('vaccinations', function ($q) use ($windowEnd) {
                $q->whereNotNull('next_dose_due')
                  ->where('next_dose_due', '<=', $windowEnd);
            })
            ->get();

        if ($pets->isEmpty()) {
            $this->info('✅ No vaccines require reminders at this time.');
            return self::SUCCESS;
        }

        $this->info("Found {$pets->count()} pet(s) with pending vaccine reminders.");

        foreach ($pets as $pet) {
            $ownerEmail = $pet->owner->email ?? null;

            if (!$ownerEmail) {
                $this->warn("  ⚠ {$pet->name} (ID: {$pet->id}): Owner has no email address. Skipping.");
                $skipped++;
                continue;
            }

            // Separate vaccines into overdue (past due) and upcoming (within 7 days)
            $overdueVaccinations = $pet->vaccinations->filter(function ($v) use ($today) {
                return $v->next_dose_due && Carbon::parse($v->next_dose_due)->lt($today);
            });

            $upcomingVaccinations = $pet->vaccinations->filter(function ($v) use ($today, $windowEnd) {
                $due = $v->next_dose_due ? Carbon::parse($v->next_dose_due) : null;
                return $due && $due->gte($today) && $due->lte($windowEnd);
            });

            if ($overdueVaccinations->isEmpty() && $upcomingVaccinations->isEmpty()) {
                $skipped++;
                continue;
            }

            try {
                Mail::to($ownerEmail)->send(new VaccineReminderMail(
                    $pet,
                    $overdueVaccinations,
                    $upcomingVaccinations
                ));

                $overdueCount  = $overdueVaccinations->count();
                $upcomingCount = $upcomingVaccinations->count();
                $this->info(
                    "  ✓ Reminder sent to {$ownerEmail} for {$pet->name}: " .
                    "{$overdueCount} vencida(s), {$upcomingCount} próxima(s)."
                );
                $sent++;
            } catch (\Exception $e) {
                $this->error("  ✗ Failed to send reminder for {$pet->name}: " . $e->getMessage());
                $failed++;
            }
        }

        $this->newLine();
        $this->info("✅ Done. Sent: {$sent} | Failed: {$failed} | Skipped (no email / no vaccines due): {$skipped}");
        return self::SUCCESS;
    }
}
