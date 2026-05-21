<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Pet;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vet   = User::where('email', 'vet@vetcare.com')->first();
        $admin = User::where('email', 'admin@vetcare.com')->first();

        if (!$vet || !$admin) {
            $this->command->warn('Vets/Admins not found. Run UserSeeder first.');
            return;
        }

        $pets = Pet::all();

        if ($pets->isEmpty()) {
            $this->command->warn('No pets found. Run PetSeeder first.');
            return;
        }

        $reasons = [
            'consulta_general',
            'vacunacion',
            'revision_post_operatoria',
            'otro',
        ];

        // Create 2-3 appointments per pet (mix of past and future)
        foreach ($pets as $index => $pet) {
            // Past completed appointment
            Appointment::create([
                'pet_id'       => $pet->id,
                'user_id'      => ($index % 2 === 0) ? $vet->id : $admin->id,
                'scheduled_at' => Carbon::now()->subWeeks(rand(2, 8))->setHour(rand(9, 16))->setMinute(0)->setSecond(0),
                'reason'       => $reasons[array_rand($reasons)],
                'notes'        => 'Cita de control programada.',
                'status'       => 'completada',
                'reminder_sent' => true,
            ]);

            // Upcoming appointment (future)
            Appointment::create([
                'pet_id'       => $pet->id,
                'user_id'      => $vet->id,
                'scheduled_at' => Carbon::now()->addDays(rand(3, 30))->setHour(rand(9, 17))->setMinute(0)->setSecond(0),
                'reason'       => $reasons[array_rand($reasons)],
                'notes'        => null,
                'status'       => ($index % 3 === 0) ? 'confirmada' : 'pendiente',
                'reminder_sent' => false,
            ]);

            // A few pets get an extra appointment scheduled for tomorrow (to test reminders)
            if ($index < 3) {
                Appointment::create([
                    'pet_id'       => $pet->id,
                    'user_id'      => $admin->id,
                    'scheduled_at' => Carbon::now()->addHours(24)->setMinute(0)->setSecond(0),
                    'reason'       => 'vacunacion',
                    'notes'        => 'Refuerzo anual. Ayuno previo de 4 horas recomendado.',
                    'status'       => 'confirmada',
                    'reminder_sent' => false,
                ]);
            }
        }
    }
}
