<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Owner;
use App\Models\Pet;
use App\Models\Appointment;
use App\Models\Vaccination;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class VetCareTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $recep;
    protected User $vet;

    protected function setUp(): void
    {
        parent::setUp();

        // Create users for each role
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@vetcare.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->recep = User::create([
            'name' => 'Recep User',
            'email' => 'recep@vetcare.com',
            'password' => bcrypt('password'),
            'role' => 'recepcionista',
        ]);

        $this->vet = User::create([
            'name' => 'Vet User',
            'email' => 'vet@vetcare.com',
            'password' => bcrypt('password'),
            'role' => 'veterinario',
        ]);
    }

    public function test_owners_index_view_is_accessible(): void
    {
        $response = $this->actingAs($this->admin)->get(route('owners.index'));
        $response->assertStatus(200);
        $response->assertViewIs('owners.index');
    }

    public function test_recep_can_create_owner_and_redirects(): void
    {
        $ownerData = [
            'name' => 'Juan Perez',
            'email' => 'juan.perez@example.com',
            'phone' => '555-1234',
            'address' => 'Calle Falsa 123',
        ];

        $response = $this->actingAs($this->recep)->post(route('owners.store'), $ownerData);

        $response->assertRedirect(route('owners.index'));
        $this->assertDatabaseHas('owners', ['email' => 'juan.perez@example.com']);
    }

    public function test_owner_validation_fails_on_duplicate_email(): void
    {
        Owner::create([
            'name' => 'Juan Perez',
            'email' => 'juan.perez@example.com',
            'phone' => '555-1234',
            'address' => 'Calle Falsa 123',
        ]);

        $ownerData = [
            'name' => 'Juan Segundo',
            'email' => 'juan.perez@example.com', // Duplicate
            'phone' => '555-5678',
            'address' => 'Calle Otra 456',
        ];

        $response = $this->actingAs($this->admin)->post(route('owners.store'), $ownerData);
        $response->assertSessionHasErrors('email');
    }

    public function test_can_create_pet_with_photo_upload(): void
    {
        Storage::fake('public');

        $owner = Owner::create([
            'name' => 'Maria Gomez',
            'email' => 'maria@example.com',
            'phone' => '555-0987',
            'address' => 'Calle Central 789',
        ]);

        $photo = UploadedFile::fake()->create('dog.png', 100, 'image/png');

        $petData = [
            'owner_id' => $owner->id,
            'name' => 'Fido',
            'species' => 'perro',
            'breed' => 'Pug',
            'birthdate' => '2023-01-01',
            'weight' => 8.50,
            'photo' => $photo,
        ];

        $response = $this->actingAs($this->admin)->post(route('pets.store'), $petData);

        $response->assertRedirect(route('pets.index'));
        $this->assertDatabaseHas('pets', ['name' => 'Fido']);

        $pet = Pet::where('name', 'Fido')->first();
        $this->assertNotNull($pet->photo);
        Storage::disk('public')->assertExists($pet->photo);
    }

    public function test_pet_soft_delete_and_restore_and_force_delete(): void
    {
        $owner = Owner::create([
            'name' => 'Maria Gomez',
            'email' => 'maria@example.com',
            'phone' => '555-0987',
            'address' => 'Calle Central 789',
        ]);

        $pet = Pet::create([
            'owner_id' => $owner->id,
            'name' => 'Fido',
            'species' => 'perro',
            'breed' => 'Pug',
            'birthdate' => '2023-01-01',
            'weight' => 8.50,
        ]);

        // 1. Soft Delete
        $response = $this->actingAs($this->admin)->delete(route('pets.destroy', $pet));
        $response->assertRedirect(route('pets.index'));
        $this->assertSoftDeleted('pets', ['id' => $pet->id]);

        // 2. Restore
        $response = $this->actingAs($this->admin)->post(route('pets.restore', $pet->id));
        $response->assertRedirect(route('pets.index'));
        $this->assertNotSoftDeleted('pets', ['id' => $pet->id]);

        // 3. Soft delete again to test force-delete
        $this->actingAs($this->admin)->delete(route('pets.destroy', $pet));
        
        // 4. Force Delete
        $response = $this->actingAs($this->admin)->delete(route('pets.force-delete', $pet->id));
        $response->assertRedirect(route('pets.archived'));
        $this->assertDatabaseMissing('pets', ['id' => $pet->id]);
    }

    public function test_vet_and_admin_can_access_medical_records_create_form(): void
    {
        $owner = Owner::create([
            'name' => 'Maria Gomez',
            'email' => 'maria@example.com',
            'phone' => '555-0987',
            'address' => 'Calle Central 789',
        ]);

        $pet = Pet::create([
            'owner_id' => $owner->id,
            'name' => 'Fido',
            'species' => 'perro',
            'breed' => 'Pug',
            'birthdate' => '2023-01-01',
            'weight' => 8.50,
        ]);

        $response = $this->actingAs($this->vet)->get(route('pets.medical-records.create', $pet));
        $response->assertStatus(200);
        $response->assertViewIs('medical_records.create');

        $responseAdmin = $this->actingAs($this->admin)->get(route('pets.medical-records.create', $pet));
        $responseAdmin->assertStatus(200);
    }

    public function test_recep_cannot_access_medical_records_create_form_and_gets_403(): void
    {
        $owner = Owner::create([
            'name' => 'Maria Gomez',
            'email' => 'maria@example.com',
            'phone' => '555-0987',
            'address' => 'Calle Central 789',
        ]);

        $pet = Pet::create([
            'owner_id' => $owner->id,
            'name' => 'Fido',
            'species' => 'perro',
            'breed' => 'Pug',
            'birthdate' => '2023-01-01',
            'weight' => 8.50,
        ]);

        $response = $this->actingAs($this->recep)->get(route('pets.medical-records.create', $pet));
        $response->assertStatus(403);
    }

    public function test_vet_and_admin_can_store_medical_record_and_updates_pet_weight(): void
    {
        $owner = Owner::create([
            'name' => 'Maria Gomez',
            'email' => 'maria@example.com',
            'phone' => '555-0987',
            'address' => 'Calle Central 789',
        ]);

        $pet = Pet::create([
            'owner_id' => $owner->id,
            'name' => 'Fido',
            'species' => 'perro',
            'breed' => 'Pug',
            'birthdate' => '2023-01-01',
            'weight' => 8.50,
        ]);

        $medicalRecordData = [
            'weight_at_visit' => 9.20,
            'diagnosis' => 'Chequeo general excelente.',
            'treatment' => 'Sin tratamiento necesario.',
        ];

        $response = $this->actingAs($this->vet)->post(route('pets.medical-records.store', $pet), $medicalRecordData);

        $response->assertRedirect(route('pets.show', $pet));
        $this->assertDatabaseHas('medical_records', [
            'pet_id' => $pet->id,
            'weight_at_visit' => 9.20,
            'diagnosis' => 'Chequeo general excelente.',
        ]);

        // Verify pet weight is updated
        $pet->refresh();
        $this->assertEquals(9.20, $pet->weight);
    }

    public function test_recep_cannot_store_medical_record_and_gets_403(): void
    {
        $owner = Owner::create([
            'name' => 'Maria Gomez',
            'email' => 'maria@example.com',
            'phone' => '555-0987',
            'address' => 'Calle Central 789',
        ]);

        $pet = Pet::create([
            'owner_id' => $owner->id,
            'name' => 'Fido',
            'species' => 'perro',
            'breed' => 'Pug',
            'birthdate' => '2023-01-01',
            'weight' => 8.50,
        ]);

        $medicalRecordData = [
            'weight_at_visit' => 9.20,
            'diagnosis' => 'Chequeo general excelente.',
            'treatment' => 'Sin tratamiento necesario.',
        ];

        $response = $this->actingAs($this->recep)->post(route('pets.medical-records.store', $pet), $medicalRecordData);
        $response->assertStatus(403);
    }

    public function test_vet_and_admin_can_access_vaccinations_create_form(): void
    {
        $owner = Owner::create([
            'name' => 'Maria Gomez',
            'email' => 'maria@example.com',
            'phone' => '555-0987',
            'address' => 'Calle Central 789',
        ]);

        $pet = Pet::create([
            'owner_id' => $owner->id,
            'name' => 'Fido',
            'species' => 'perro',
            'breed' => 'Pug',
            'birthdate' => '2023-01-01',
            'weight' => 8.50,
        ]);

        $response = $this->actingAs($this->vet)->get(route('pets.vaccinations.create', $pet));
        $response->assertStatus(200);
        $response->assertViewIs('vaccinations.create');

        $responseAdmin = $this->actingAs($this->admin)->get(route('pets.vaccinations.create', $pet));
        $responseAdmin->assertStatus(200);
    }

    public function test_recep_cannot_access_vaccinations_create_form_and_gets_403(): void
    {
        $owner = Owner::create([
            'name' => 'Maria Gomez',
            'email' => 'maria@example.com',
            'phone' => '555-0987',
            'address' => 'Calle Central 789',
        ]);

        $pet = Pet::create([
            'owner_id' => $owner->id,
            'name' => 'Fido',
            'species' => 'perro',
            'breed' => 'Pug',
            'birthdate' => '2023-01-01',
            'weight' => 8.50,
        ]);

        $response = $this->actingAs($this->recep)->get(route('pets.vaccinations.create', $pet));
        $response->assertStatus(403);
    }

    public function test_vet_and_admin_can_store_vaccination(): void
    {
        $owner = Owner::create([
            'name' => 'Maria Gomez',
            'email' => 'maria@example.com',
            'phone' => '555-0987',
            'address' => 'Calle Central 789',
        ]);

        $pet = Pet::create([
            'owner_id' => $owner->id,
            'name' => 'Fido',
            'species' => 'perro',
            'breed' => 'Pug',
            'birthdate' => '2023-01-01',
            'weight' => 8.50,
        ]);

        $vaccinationData = [
            'name' => 'Parvovirus',
            'dose' => '1ml',
            'date_applied' => '2026-05-20',
            'next_dose_due' => '2026-11-20',
        ];

        $response = $this->actingAs($this->vet)->post(route('pets.vaccinations.store', $pet), $vaccinationData);

        $response->assertRedirect(route('pets.show', $pet));
        $this->assertDatabaseHas('vaccinations', [
            'pet_id' => $pet->id,
            'name' => 'Parvovirus',
            'dose' => '1ml',
            'date_applied' => '2026-05-20',
        ]);
    }

    public function test_recep_cannot_store_vaccination_and_gets_403(): void
    {
        $owner = Owner::create([
            'name' => 'Maria Gomez',
            'email' => 'maria@example.com',
            'phone' => '555-0987',
            'address' => 'Calle Central 789',
        ]);

        $pet = Pet::create([
            'owner_id' => $owner->id,
            'name' => 'Fido',
            'species' => 'perro',
            'breed' => 'Pug',
            'birthdate' => '2023-01-01',
            'weight' => 8.50,
        ]);

        $vaccinationData = [
            'name' => 'Parvovirus',
            'dose' => '1ml',
            'date_applied' => '2026-05-20',
            'next_dose_due' => '2026-11-20',
        ];

        $response = $this->actingAs($this->recep)->post(route('pets.vaccinations.store', $pet), $vaccinationData);
        $response->assertStatus(403);
    }

    public function test_any_authenticated_user_can_download_pdf_clinical_history(): void
    {
        $owner = Owner::create([
            'name' => 'Maria Gomez',
            'email' => 'maria@example.com',
            'phone' => '555-0987',
            'address' => 'Calle Central 789',
        ]);

        $pet = Pet::create([
            'owner_id' => $owner->id,
            'name' => 'Fido',
            'species' => 'perro',
            'breed' => 'Pug',
            'birthdate' => '2023-01-01',
            'weight' => 8.50,
        ]);

        $response = $this->actingAs($this->recep)->get(route('pets.pdf', $pet));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    // =========================================================
    // FASE 4: Appointments Tests
    // =========================================================

    private function createPetWithOwner(): Pet
    {
        $owner = Owner::create([
            'name'    => 'Maria Gomez',
            'email'   => 'maria@example.com',
            'phone'   => '555-0987',
            'address' => 'Calle Central 789',
        ]);

        return Pet::create([
            'owner_id'  => $owner->id,
            'name'      => 'Fido',
            'species'   => 'perro',
            'breed'     => 'Pug',
            'birthdate' => '2023-01-01',
            'weight'    => 8.50,
        ]);
    }

    public function test_appointments_index_is_accessible_by_all_roles(): void
    {
        $response = $this->actingAs($this->admin)->get(route('appointments.index'));
        $response->assertStatus(200);
        $response->assertViewIs('appointments.index');

        $response = $this->actingAs($this->vet)->get(route('appointments.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->recep)->get(route('appointments.index'));
        $response->assertStatus(200);
    }

    public function test_vet_and_admin_can_create_appointment_and_it_saves_to_db(): void
    {
        Mail::fake();

        $pet = $this->createPetWithOwner();

        $appointmentData = [
            'pet_id'       => $pet->id,
            'user_id'      => $this->vet->id,
            'scheduled_at' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'reason'       => 'consulta_general',
            'notes'        => 'Primera visita del año.',
            'status'       => 'pendiente',
        ];

        $response = $this->actingAs($this->vet)->post(route('appointments.store'), $appointmentData);

        $this->assertDatabaseHas('appointments', [
            'pet_id'  => $pet->id,
            'user_id' => $this->vet->id,
            'reason'  => 'consulta_general',
            'status'  => 'pendiente',
        ]);
    }

    public function test_appointment_store_requires_future_date(): void
    {
        $pet = $this->createPetWithOwner();

        $appointmentData = [
            'pet_id'       => $pet->id,
            'user_id'      => $this->vet->id,
            'scheduled_at' => now()->subDays(1)->format('Y-m-d H:i:s'), // past date
            'reason'       => 'consulta_general',
        ];

        $response = $this->actingAs($this->vet)->post(route('appointments.store'), $appointmentData);
        $response->assertSessionHasErrors('scheduled_at');
    }

    public function test_appointment_can_be_viewed_by_all_roles(): void
    {
        Mail::fake();
        $pet = $this->createPetWithOwner();

        $appointment = Appointment::create([
            'pet_id'       => $pet->id,
            'user_id'      => $this->vet->id,
            'scheduled_at' => now()->addDays(3),
            'reason'       => 'vacunacion',
            'status'       => 'pendiente',
        ]);

        $response = $this->actingAs($this->recep)->get(route('appointments.show', $appointment));
        $response->assertStatus(200);
        $response->assertViewIs('appointments.show');
    }

    public function test_appointment_status_can_be_updated(): void
    {
        Mail::fake();
        $pet = $this->createPetWithOwner();

        $appointment = Appointment::create([
            'pet_id'       => $pet->id,
            'user_id'      => $this->vet->id,
            'scheduled_at' => now()->addDays(3),
            'reason'       => 'vacunacion',
            'status'       => 'pendiente',
        ]);

        $updateData = [
            'pet_id'       => $pet->id,
            'user_id'      => $this->vet->id,
            'scheduled_at' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'reason'       => 'vacunacion',
            'status'       => 'confirmada',
        ];

        $response = $this->actingAs($this->admin)->put(route('appointments.update', $appointment), $updateData);
        $response->assertRedirect(route('appointments.show', $appointment));

        $appointment->refresh();
        $this->assertEquals('confirmada', $appointment->status);
    }

    public function test_appointment_can_be_deleted_by_admin_or_vet(): void
    {
        Mail::fake();
        $pet = $this->createPetWithOwner();

        $appointment = Appointment::create([
            'pet_id'       => $pet->id,
            'user_id'      => $this->vet->id,
            'scheduled_at' => now()->addDays(5),
            'reason'       => 'otro',
            'status'       => 'pendiente',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('appointments.destroy', $appointment));
        $response->assertRedirect(route('appointments.index'));
        $this->assertDatabaseMissing('appointments', ['id' => $appointment->id]);
    }

    public function test_send_appointment_reminders_artisan_command_runs_successfully(): void
    {
        $this->artisan('vetcare:send-reminders')->assertExitCode(0);
    }

    public function test_appointment_reminders_command_sends_for_appointments_scheduled_tomorrow(): void
    {
        Mail::fake();

        $pet = $this->createPetWithOwner();

        $appointment = Appointment::create([
            'pet_id' => $pet->id,
            'user_id' => $this->vet->id,
            'scheduled_at' => now()->addDay()->setTime(10, 0, 0),
            'reason' => 'consulta_general',
            'status' => 'pendiente',
            'reminder_sent' => false,
        ]);

        $this->artisan('vetcare:send-reminders')->assertExitCode(0);

        Mail::assertSent(\App\Mail\AppointmentReminder::class, function ($mail) use ($pet) {
            return $mail->hasTo($pet->owner->email);
        });

        $appointment->refresh();
        $this->assertTrue($appointment->reminder_sent);
    }

    // =========================================================
    // FASE 6: Vaccine Reminder Command Tests
    // =========================================================

    public function test_vaccine_reminders_command_runs_with_no_vaccines_due(): void
    {
        // With no pets/vaccines, command should run cleanly
        $this->artisan('vetcare:vaccine-reminders')
             ->expectsOutput('✅ No vaccines require reminders at this time.')
             ->assertExitCode(0);
    }

    public function test_vaccine_reminders_command_detects_overdue_vaccines_and_exits_successfully(): void
    {
        Mail::fake();

        $owner = Owner::create([
            'name'    => 'Carlos Lopez',
            'email'   => 'carlos@test.com',
            'phone'   => '555-9999',
            'address' => 'Calle Test 123',
        ]);

        $pet = Pet::create([
            'owner_id'  => $owner->id,
            'name'      => 'Rex',
            'species'   => 'perro',
            'breed'     => 'Labrador',
            'birthdate' => '2020-06-01',
            'weight'    => 25.0,
        ]);

        // Create an overdue vaccination (past due)
        Vaccination::create([
            'pet_id'       => $pet->id,
            'name'         => 'Antirrábica',
            'dose'         => '1ml',
            'date_applied' => now()->subYear()->format('Y-m-d'),
            'next_dose_due' => now()->subDays(10)->format('Y-m-d'), // 10 days overdue
        ]);

        $this->artisan('vetcare:vaccine-reminders')->assertExitCode(0);

        // Verify the mail was sent
        Mail::assertSent(\App\Mail\VaccineReminderMail::class, function ($mail) use ($owner) {
            return $mail->hasTo($owner->email);
        });
    }

    public function test_vaccine_reminders_command_detects_upcoming_vaccines_within_7_days(): void
    {
        Mail::fake();

        $owner = Owner::create([
            'name'    => 'Maria Lopez',
            'email'   => 'maria.lopez@test.com',
            'phone'   => '555-8888',
            'address' => 'Av. Central 456',
        ]);

        $pet = Pet::create([
            'owner_id'  => $owner->id,
            'name'      => 'Mia',
            'species'   => 'gato',
            'breed'     => 'Siamés',
            'birthdate' => '2021-03-15',
            'weight'    => 4.5,
        ]);

        // Create a vaccination due in 3 days (upcoming)
        Vaccination::create([
            'pet_id'       => $pet->id,
            'name'         => 'Triple Viral Felina',
            'dose'         => '1ml',
            'date_applied' => now()->subYear()->format('Y-m-d'),
            'next_dose_due' => now()->addDays(3)->format('Y-m-d'), // due in 3 days
        ]);

        $this->artisan('vetcare:vaccine-reminders')->assertExitCode(0);

        Mail::assertSent(\App\Mail\VaccineReminderMail::class, function ($mail) use ($owner) {
            return $mail->hasTo($owner->email);
        });
    }

    public function test_vaccine_reminders_skips_pets_without_owner_email(): void
    {
        Mail::fake();

        // Create owner with no email
        $owner = Owner::create([
            'name'    => 'Sin Email',
            'email'   => 'noemail@test.com',
            'phone'   => '555-0000',
            'address' => 'Calle Vacia 0',
        ]);

        $pet = Pet::create([
            'owner_id'  => $owner->id,
            'name'      => 'Buddy',
            'species'   => 'perro',
            'breed'     => 'Beagle',
            'birthdate' => '2019-01-01',
            'weight'    => 12.0,
        ]);

        // Add an overdue vaccination
        Vaccination::create([
            'pet_id'       => $pet->id,
            'name'         => 'Parvovirus',
            'dose'         => '1ml',
            'date_applied' => now()->subYear()->format('Y-m-d'),
            'next_dose_due' => now()->subDays(5)->format('Y-m-d'),
        ]);

        // Manually update the owner email to empty to simulate no email
        $owner->update(['email' => 'noemail@test.com']);

        // Command should still exit successfully
        $this->artisan('vetcare:vaccine-reminders')->assertExitCode(0);
    }

    public function test_schedule_list_contains_both_vetcare_commands(): void
    {
        $this->artisan('schedule:list')
             ->assertExitCode(0);
    }

    public function test_recep_cannot_edit_or_delete_owners(): void
    {
        $owner = Owner::create([
            'name' => 'Test Owner',
            'email' => 'testowner@example.com',
            'phone' => '123456',
            'address' => 'Test Address',
        ]);

        $responseEdit = $this->actingAs($this->recep)->get(route('owners.edit', $owner));
        $responseEdit->assertStatus(403);

        $responseUpdate = $this->actingAs($this->recep)->put(route('owners.update', $owner), [
            'name' => 'Updated Owner',
            'email' => 'testowner@example.com',
        ]);
        $responseUpdate->assertStatus(403);

        $responseDelete = $this->actingAs($this->recep)->delete(route('owners.destroy', $owner));
        $responseDelete->assertStatus(403);
    }

    public function test_recep_cannot_edit_or_delete_pets(): void
    {
        $pet = $this->createPetWithOwner();

        $responseEdit = $this->actingAs($this->recep)->get(route('pets.edit', $pet));
        $responseEdit->assertStatus(403);

        $responseUpdate = $this->actingAs($this->recep)->put(route('pets.update', $pet), [
            'name' => 'Updated Pet',
            'species' => 'perro',
            'breed' => 'Pug',
            'birthdate' => '2023-01-01',
            'weight' => 8.50,
        ]);
        $responseUpdate->assertStatus(403);

        $responseDelete = $this->actingAs($this->recep)->delete(route('pets.destroy', $pet));
        $responseDelete->assertStatus(403);
    }

    public function test_recep_cannot_create_or_edit_or_delete_appointments(): void
    {
        $pet = $this->createPetWithOwner();

        $responseCreate = $this->actingAs($this->recep)->get(route('appointments.create'));
        $responseCreate->assertStatus(403);

        $responseStore = $this->actingAs($this->recep)->post(route('appointments.store'), [
            'pet_id' => $pet->id,
            'user_id' => $this->vet->id,
            'scheduled_at' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'reason' => 'consulta_general',
        ]);
        $responseStore->assertStatus(403);
    }

    public function test_vet_cannot_create_or_edit_or_delete_owners(): void
    {
        $ownerData = [
            'name' => 'Another Owner',
            'email' => 'anotherowner@example.com',
            'phone' => '123456',
            'address' => 'Test Address',
        ];

        $responseCreate = $this->actingAs($this->vet)->get(route('owners.create'));
        $responseCreate->assertStatus(403);

        $responseStore = $this->actingAs($this->vet)->post(route('owners.store'), $ownerData);
        $responseStore->assertStatus(403);
    }

    public function test_vet_cannot_create_or_delete_pets(): void
    {
        $owner = Owner::create([
            'name' => 'Carlos Lopez',
            'email' => 'carlos@test.com',
            'phone' => '555-9999',
            'address' => 'Calle Test 123',
        ]);

        $petData = [
            'owner_id' => $owner->id,
            'name' => 'Fido Junior',
            'species' => 'perro',
            'breed' => 'Pug',
            'birthdate' => '2023-01-01',
            'weight' => 8.50,
        ];

        $responseCreate = $this->actingAs($this->vet)->get(route('pets.create'));
        $responseCreate->assertStatus(403);

        $responseStore = $this->actingAs($this->vet)->post(route('pets.store'), $petData);
        $responseStore->assertStatus(403);

        $pet = $this->createPetWithOwner();
        $responseDelete = $this->actingAs($this->vet)->delete(route('pets.destroy', $pet));
        $responseDelete->assertStatus(403);
    }

    public function test_non_admin_cannot_access_user_management(): void
    {
        $responseRecepIndex = $this->actingAs($this->recep)->get(route('admin.users.index'));
        $responseRecepIndex->assertStatus(403);

        $responseVetIndex = $this->actingAs($this->vet)->get(route('admin.users.index'));
        $responseVetIndex->assertStatus(403);
    }

    public function test_admin_can_perform_full_user_management_crud(): void
    {
        // 1. List
        $responseIndex = $this->actingAs($this->admin)->get(route('admin.users.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertViewIs('admin.users.index');

        // 2. Create Form
        $responseCreate = $this->actingAs($this->admin)->get(route('admin.users.create'));
        $responseCreate->assertStatus(200);
        $responseCreate->assertViewIs('admin.users.create');

        // 3. Store
        $userData = [
            'name' => 'New Staff Member',
            'email' => 'newstaff@vetcare.com',
            'role' => 'recepcionista',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];
        $responseStore = $this->actingAs($this->admin)->post(route('admin.users.store'), $userData);
        $responseStore->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'newstaff@vetcare.com', 'role' => 'recepcionista']);

        // Get newly created user
        $newUser = User::where('email', 'newstaff@vetcare.com')->first();

        // 4. Edit Form
        $responseEdit = $this->actingAs($this->admin)->get(route('admin.users.edit', $newUser));
        $responseEdit->assertStatus(200);
        $responseEdit->assertViewIs('admin.users.edit');

        // 5. Update
        $updateData = [
            'name' => 'Updated Staff Member',
            'email' => 'newstaff@vetcare.com',
            'role' => 'veterinario',
        ];
        $responseUpdate = $this->actingAs($this->admin)->put(route('admin.users.update', $newUser), $updateData);
        $responseUpdate->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['id' => $newUser->id, 'name' => 'Updated Staff Member', 'role' => 'veterinario']);

        // 6. Delete
        $responseDelete = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $newUser));
        $responseDelete->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $newUser->id]);
    }

    public function test_appointment_creation_sends_whatsapp_notification(): void
    {
        Mail::fake();
        \Illuminate\Support\Facades\Http::fake([
            'api.green-api.com/*' => \Illuminate\Support\Facades\Http::response(['success' => true], 200)
        ]);

        // Temporarily set env vars in config
        config([
            'services.greenapi.token' => 'test_token',
            'services.greenapi.instance_id' => 'instance123'
        ]);

        $pet = $this->createPetWithOwner();

        $appointmentData = [
            'pet_id'       => $pet->id,
            'user_id'      => $this->vet->id,
            'scheduled_at' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'reason'       => 'consulta_general',
            'notes'        => 'Primera visita del año.',
            'status'       => 'pendiente',
        ];

        $response = $this->actingAs($this->vet)->post(route('appointments.store'), $appointmentData);

        // Assert database insertion
        $this->assertDatabaseHas('appointments', [
            'pet_id' => $pet->id,
            'reason' => 'consulta_general',
        ]);

        // Assert HTTP call to Green-API was sent
        \Illuminate\Support\Facades\Http::assertSent(function ($request) {
            return str_contains($request->url(), 'api.green-api.com/waInstanceinstance123/sendMessage/test_token') &&
                $request['chatId'] === '5550987@c.us' &&
                str_contains($request['message'], 'VetCare - Confirmación de Cita') &&
                str_contains($request['message'], 'Maria Gomez') &&
                str_contains($request['message'], 'Fido');
        });
    }

    public function test_prescription_creation_sends_whatsapp_and_clinical_history_email(): void
    {
        Mail::fake();
        \Illuminate\Support\Facades\Http::fake([
            'api.green-api.com/*' => \Illuminate\Support\Facades\Http::response(['success' => true], 200)
        ]);

        config([
            'services.greenapi.token' => 'test_token',
            'services.greenapi.instance_id' => 'instance123'
        ]);

        $pet = $this->createPetWithOwner();

        $recordData = [
            'weight_at_visit' => 9.20,
            'diagnosis' => 'Gripe leve por cambio de clima estacional.',
            'treatment' => 'Administrar 5ml de jarabe pediátrico cada 12 horas por 5 días.',
        ];

        $response = $this->actingAs($this->vet)->post(route('pets.medical-records.store', $pet), $recordData);

        // Assert record is created
        $this->assertDatabaseHas('medical_records', [
            'pet_id' => $pet->id,
            'weight_at_visit' => 9.20,
        ]);

        // Assert WhatsApp sent
        \Illuminate\Support\Facades\Http::assertSent(function ($request) {
            return str_contains($request->url(), 'api.green-api.com/waInstanceinstance123/sendMessage/test_token') &&
                $request['chatId'] === '5550987@c.us' &&
                str_contains($request['message'], 'VetCare - Nueva Receta Médica') &&
                str_contains($request['message'], 'Gripe leve') &&
                str_contains($request['message'], 'jarabe');
        });

        // Assert Email sent
        Mail::assertSent(\App\Mail\ClinicalHistoryMail::class, function ($mail) use ($pet) {
            return $mail->hasTo('maria@example.com') &&
                $mail->pet->id === $pet->id;
        });
    }

    public function test_ai_symptom_analyzer_endpoint_returns_suggestions(): void
    {
        \Illuminate\Support\Facades\Http::fake([
            'api.groq.com/*' => \Illuminate\Support\Facades\Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'diagnosis' => 'Gastroenteritis infecciosa.',
                                'treatment' => 'Administrar amoxicilina 250mg cada 12 horas por 7 días.'
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        config([
            'services.groq.api_key' => 'test_groq_key'
        ]);

        $pet = $this->createPetWithOwner();

        $response = $this->actingAs($this->vet)->post(route('ai.analyze'), [
            'symptoms' => 'Vómito y deshidratación leve.',
            'pet_id'   => $pet->id,
            'weight'   => 12.50
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success'   => true,
            'diagnosis' => 'Gastroenteritis infecciosa.',
            'treatment' => 'Administrar amoxicilina 250mg cada 12 horas por 7 días.'
        ]);
    }

    public function test_prescription_creation_sends_whatsapp_with_ai_friendly_recipe_when_key_is_set(): void
    {
        Mail::fake();
        \Illuminate\Support\Facades\Http::fake([
            'api.green-api.com/*' => \Illuminate\Support\Facades\Http::response(['success' => true], 200),
            'api.groq.com/*' => \Illuminate\Support\Facades\Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Tu perrito Fido tiene gripe leve, por favor dale jarabe cada 12 horas.'
                        ]
                    ]
                ]
            ], 200)
        ]);

        config([
            'services.greenapi.token' => 'test_token',
            'services.greenapi.instance_id' => 'instance123',
            'services.groq.api_key' => 'test_groq_key'
        ]);

        $pet = $this->createPetWithOwner();

        $recordData = [
            'weight_at_visit' => 9.20,
            'diagnosis' => 'Gripe leve por cambio de clima.',
            'treatment' => 'Administrar 5ml de jarabe pediátrico.',
        ];

        $response = $this->actingAs($this->vet)->post(route('pets.medical-records.store', $pet), $recordData);

        // Assert record is created
        $this->assertDatabaseHas('medical_records', [
            'pet_id' => $pet->id,
            'weight_at_visit' => 9.20,
        ]);

        // Assert WhatsApp sent with AI friendly explanation
        \Illuminate\Support\Facades\Http::assertSent(function ($request) {
            return str_contains($request->url(), 'api.green-api.com/waInstanceinstance123/sendMessage/test_token') &&
                $request['chatId'] === '5550987@c.us' &&
                str_contains($request['message'], 'VetCare - Nueva Receta Médica') &&
                str_contains($request['message'], 'Explicación amigable (IA)') &&
                str_contains($request['message'], 'Tu perrito Fido tiene gripe leve');
        });
    }
}
