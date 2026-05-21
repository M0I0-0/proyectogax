<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Owner;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $pets = Pet::with('owner')->latest()->paginate(10);
        return view('pets.index', compact('pets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $owners = Owner::orderBy('name')->get();
        return view('pets.create', compact('owners'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePetRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('pets', 'public');
            $data['photo'] = $path;
        }

        Pet::create($data);

        return redirect()->route('pets.index')
            ->with('success', 'Mascota registrada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pet $pet): View
    {
        $pet->load(['owner', 'medicalRecords.veterinarian', 'vaccinations', 'appointments.veterinarian']);
        return view('pets.show', compact('pet'));
    }

    /**
     * Download the clinical history of the pet in PDF format.
     */
    public function downloadPdf(Pet $pet): \Illuminate\Http\Response
    {
        $pet->load(['owner', 'medicalRecords.veterinarian', 'vaccinations']);
        
        $pdf = Pdf::loadView('pets.pdf', compact('pet'));
        
        $filename = 'historial_clinico_' . strtolower(str_replace(' ', '_', $pet->name)) . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pet $pet): View
    {
        $owners = Owner::orderBy('name')->get();
        return view('pets.edit', compact('pet', 'owners'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePetRequest $request, Pet $pet): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($pet->photo) {
                Storage::disk('public')->delete($pet->photo);
            }
            $path = $request->file('photo')->store('pets', 'public');
            $data['photo'] = $path;
        }

        $pet->update($data);

        return redirect()->route('pets.show', $pet)
            ->with('success', 'Mascota actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage (Soft Delete).
     */
    public function destroy(Pet $pet): RedirectResponse
    {
        $pet->delete();

        return redirect()->route('pets.index')
            ->with('success', 'Mascota archivada exitosamente.');
    }

    /**
     * Display a listing of the soft-deleted resources.
     */
    public function archived(): View
    {
        $pets = Pet::onlyTrashed()->with('owner')->latest()->paginate(10);
        return view('pets.archived', compact('pets'));
    }

    /**
     * Restore the specified soft-deleted resource.
     */
    public function restore($id): RedirectResponse
    {
        $pet = Pet::onlyTrashed()->findOrFail($id);
        $pet->restore();

        return redirect()->route('pets.index')
            ->with('success', 'Mascota restaurada exitosamente.');
    }

    /**
     * Permanently delete the specified soft-deleted resource.
     */
    public function forceDelete($id): RedirectResponse
    {
        $pet = Pet::onlyTrashed()->findOrFail($id);

        if ($pet->photo) {
            Storage::disk('public')->delete($pet->photo);
        }

        $pet->forceDelete();

        return redirect()->route('pets.archived')
            ->with('success', 'Mascota eliminada permanentemente.');
    }
}
