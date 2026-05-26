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

    // User Management CRUD
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->names('admin.users');
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
    Route::middleware('role:admin,recepcionista')->group(function () {
        Route::get('owners/create', [OwnerController::class, 'create'])->name('owners.create');
        Route::post('owners', [OwnerController::class, 'store'])->name('owners.store');
    });
    Route::middleware('role:admin')->group(function () {
        Route::get('owners/{owner}/edit', [OwnerController::class, 'edit'])->name('owners.edit');
        Route::put('owners/{owner}', [OwnerController::class, 'update'])->name('owners.update');
        Route::patch('owners/{owner}', [OwnerController::class, 'update']);
        Route::delete('owners/{owner}', [OwnerController::class, 'destroy'])->name('owners.destroy');
    });
    Route::get('owners', [OwnerController::class, 'index'])->name('owners.index');
    Route::get('owners/{owner}', [OwnerController::class, 'show'])->name('owners.show');

    // Pets CRUD with Soft Deletes
    Route::middleware('role:admin')->group(function () {
        Route::get('pets/archived', [PetController::class, 'archived'])->name('pets.archived');
        Route::post('pets/{id}/restore', [PetController::class, 'restore'])->name('pets.restore');
        Route::delete('pets/{id}/force-delete', [PetController::class, 'forceDelete'])->name('pets.force-delete');
        Route::delete('pets/{pet}', [PetController::class, 'destroy'])->name('pets.destroy');
    });

    Route::middleware('role:admin,recepcionista')->group(function () {
        Route::get('pets/create', [PetController::class, 'create'])->name('pets.create');
        Route::post('pets', [PetController::class, 'store'])->name('pets.store');
    });

    Route::middleware('role:admin,veterinario')->group(function () {
        Route::get('pets/{pet}/edit', [PetController::class, 'edit'])->name('pets.edit');
        Route::put('pets/{pet}', [PetController::class, 'update'])->name('pets.update');
        Route::patch('pets/{pet}', [PetController::class, 'update']);
    });

    Route::get('pets', [PetController::class, 'index'])->name('pets.index');
    Route::get('pets/{pet}', [PetController::class, 'show'])->name('pets.show');
    Route::get('pets/{pet}/pdf', [PetController::class, 'downloadPdf'])->name('pets.pdf');

    // Phase 3: Medical Records & Vaccinations (Only Admin and Veterinario can create)
    Route::middleware('role:admin,veterinario')->group(function () {
        Route::get('pets/{pet}/medical-records/create', [MedicalRecordController::class, 'create'])->name('pets.medical-records.create');
        Route::post('pets/{pet}/medical-records', [MedicalRecordController::class, 'store'])->name('pets.medical-records.store');
        Route::get('pets/{pet}/vaccinations/create', [VaccinationController::class, 'create'])->name('pets.vaccinations.create');
        Route::post('pets/{pet}/vaccinations', [VaccinationController::class, 'store'])->name('pets.vaccinations.store');
        Route::get('prescriptions/create', [MedicalRecordController::class, 'createGeneral'])->name('prescriptions.create');
        Route::post('prescriptions', [MedicalRecordController::class, 'storeGeneral'])->name('prescriptions.store');
        
        // AI Assistant Route
        Route::post('ai/analyze', [\App\Http\Controllers\AiController::class, 'analyze'])->name('ai.analyze');
    });

    // Phase 4: Appointments — Restricted to admin/veterinario for modification
    Route::middleware('role:admin,veterinario')->group(function () {
        Route::get('appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('appointments', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::get('appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
        Route::put('appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
        Route::patch('appointments/{appointment}', [AppointmentController::class, 'update']);
        Route::delete('appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    });

    Route::get('appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
});

require __DIR__.'/auth.php';
