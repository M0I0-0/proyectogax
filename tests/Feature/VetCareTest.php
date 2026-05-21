<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Owner;
use App\Models\Pet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
}
