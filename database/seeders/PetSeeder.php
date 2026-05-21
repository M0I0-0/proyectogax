<?php

namespace Database\Seeders;

use App\Models\Owner;
use App\Models\Pet;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed 5 Owners
        $owners = [
            [
                'name' => 'Carlos Mendoza',
                'email' => 'carlos.mendoza@example.com',
                'phone' => '555-0199',
                'address' => 'Av. Las Flores 123, CDMX',
            ],
            [
                'name' => 'Ana Sofía Rodríguez',
                'email' => 'ana.rodriguez@example.com',
                'phone' => '555-0288',
                'address' => 'Calle Pino 45, Guadalajara',
            ],
            [
                'name' => 'Héctor Espinoza',
                'email' => 'hector.espinoza@example.com',
                'phone' => '555-0377',
                'address' => 'Residencial Campestre, Monterrey',
            ],
            [
                'name' => 'María José Del Campo',
                'email' => 'maria.delcampo@example.com',
                'phone' => '555-0466',
                'address' => 'Privada Del Sol 12, Puebla',
            ],
            [
                'name' => 'Luis Fernando Gómez',
                'email' => 'luis.gomez@example.com',
                'phone' => '555-0555',
                'address' => 'Calle de la Luna 789, Querétaro',
            ],
        ];

        $ownerModels = [];
        foreach ($owners as $ownerData) {
            $ownerModels[] = Owner::create($ownerData);
        }

        // Seed 10 Pets, assigned randomly to the owners
        $pets = [
            [
                'name' => 'Toby',
                'species' => 'perro',
                'breed' => 'Golden Retriever',
                'birthdate' => Carbon::now()->subYears(3)->subMonths(2)->format('Y-m-d'),
                'weight' => 28.50,
                'photo' => null,
            ],
            [
                'name' => 'Luna',
                'species' => 'gato',
                'breed' => 'Siamés',
                'birthdate' => Carbon::now()->subYears(2)->subMonths(5)->format('Y-m-d'),
                'weight' => 4.20,
                'photo' => null,
            ],
            [
                'name' => 'Max',
                'species' => 'perro',
                'breed' => 'Pastor Alemán',
                'birthdate' => Carbon::now()->subYears(5)->subMonths(1)->format('Y-m-d'),
                'weight' => 32.00,
                'photo' => null,
            ],
            [
                'name' => 'Rocky',
                'species' => 'conejo',
                'breed' => 'Cabeza de León',
                'birthdate' => Carbon::now()->subYear()->subMonths(3)->format('Y-m-d'),
                'weight' => 1.80,
                'photo' => null,
            ],
            [
                'name' => 'Bella',
                'species' => 'gato',
                'breed' => 'Persa',
                'birthdate' => Carbon::now()->subYears(4)->subMonths(6)->format('Y-m-d'),
                'weight' => 5.10,
                'photo' => null,
            ],
            [
                'name' => 'Lucas',
                'species' => 'ave',
                'breed' => 'Loro Huasteco',
                'birthdate' => Carbon::now()->subYears(6)->format('Y-m-d'),
                'weight' => 0.45,
                'photo' => null,
            ],
            [
                'name' => 'Coco',
                'species' => 'perro',
                'breed' => 'Poodle',
                'birthdate' => Carbon::now()->subYears(2)->subMonths(8)->format('Y-m-d'),
                'weight' => 6.50,
                'photo' => null,
            ],
            [
                'name' => 'Lola',
                'species' => 'gato',
                'breed' => 'Angora',
                'birthdate' => Carbon::now()->subYears(3)->subMonths(10)->format('Y-m-d'),
                'weight' => 4.00,
                'photo' => null,
            ],
            [
                'name' => 'Bruno',
                'species' => 'perro',
                'breed' => 'Pug',
                'birthdate' => Carbon::now()->subYear()->subMonths(1)->format('Y-m-d'),
                'weight' => 8.20,
                'photo' => null,
            ],
            [
                'name' => 'Misha',
                'species' => 'gato',
                'breed' => 'Mestizo',
                'birthdate' => Carbon::now()->subYears(5)->subMonths(4)->format('Y-m-d'),
                'weight' => 4.80,
                'photo' => null,
            ],
        ];

        foreach ($pets as $petData) {
            // Pick a random owner from seeded ones
            $randomOwner = $ownerModels[array_rand($ownerModels)];
            $petData['owner_id'] = $randomOwner->id;

            Pet::create($petData);
        }
    }
}
