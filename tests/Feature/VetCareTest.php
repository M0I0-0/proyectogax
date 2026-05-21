<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Owner;
use App\Models\Pet;
use App\Models\Appointment;
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
}
