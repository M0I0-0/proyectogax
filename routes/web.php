<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\VaccinationController;
use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'veterinario') {
        return redirect()->route('vet.dashboard');
    } else {
        return redirect()->route('recep.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Veterinario Routes (Admin can also access)
Route::middleware(['auth', 'role:admin,veterinario'])->prefix('vet')->group(function () {
    Route::get('/dashboard', function () {
        return view('vet.dashboard');
    })->name('vet.dashboard');
});

// Recepcionista Routes (Admin can also access)
Route::middleware(['auth', 'role:admin,recepcionista'])->prefix('recep')->group(function () {
    Route::get('/dashboard', function () {
        return view('recep.dashboard');
    })->name('recep.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Owners CRUD
    Route::resource('owners', OwnerController::class);

    // Pets CRUD with Soft Deletes
    Route::get('pets/archived', [PetController::class, 'archived'])->name('pets.archived');
    Route::post('pets/{id}/restore', [PetController::class, 'restore'])->name('pets.restore');
    Route::delete('pets/{id}/force-delete', [PetController::class, 'forceDelete'])->name('pets.force-delete');
    Route::resource('pets', PetController::class);

    // Phase 3: Medical Records & Vaccinations (Only Admin and Veterinario can create)
    Route::middleware('role:admin,veterinario')->group(function () {
        Route::get('pets/{pet}/medical-records/create', [MedicalRecordController::class, 'create'])->name('pets.medical-records.create');
        Route::post('pets/{pet}/medical-records', [MedicalRecordController::class, 'store'])->name('pets.medical-records.store');
        Route::get('pets/{pet}/vaccinations/create', [VaccinationController::class, 'create'])->name('pets.vaccinations.create');
        Route::post('pets/{pet}/vaccinations', [VaccinationController::class, 'store'])->name('pets.vaccinations.store');
    });

    // PDF Clinical History Export (Accessible by all roles)
    Route::get('pets/{pet}/pdf', [PetController::class, 'downloadPdf'])->name('pets.pdf');

    // Phase 4: Appointments — Accessible by all roles (create/update restricted at view level for recepcionista)
    Route::resource('appointments', AppointmentController::class);
});

require __DIR__.'/auth.php';
