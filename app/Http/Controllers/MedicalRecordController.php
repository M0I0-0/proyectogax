<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Http\Requests\StoreMedicalRecordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\WhatsAppService;
use App\Mail\ClinicalHistoryMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class MedicalRecordController extends Controller
{
    /**
     * Show the form for creating a new medical record.
     */
    public function create(Pet $pet): View
    {
        return view('medical_records.create', compact('pet'));
    }

    /**
     * Store a newly created medical record in storage.
     */
    public function store(StoreMedicalRecordRequest $request, Pet $pet): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        $pet->medicalRecords()->create($validated);

        // Update the pet's current weight in its profile to reflect the latest checkup
        $pet->update(['weight' => $validated['weight_at_visit']]);

        // Send notifications to the owner
        $this->sendPrescriptionNotifications($pet, $validated);

        return redirect()->route('pets.show', $pet)
            ->with('status', 'success')
            ->with('message', 'La consulta médica ha sido registrada exitosamente. Se ha enviado el historial clínico por correo y la receta por WhatsApp al propietario.');
    }

    /**
     * Show the form for creating a new general medical record (prescription).
     */
    public function createGeneral(): View
    {
        $pets = Pet::with('owner')->orderBy('name')->get();
        return view('medical_records.create_general', compact('pets'));
    }

    /**
     * Store a newly created general medical record (prescription) in storage.
     */
    public function storeGeneral(\Illuminate\Http\Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pet_id' => ['required', 'exists:pets,id'],
            'weight_at_visit' => ['required', 'numeric', 'min:0.01', 'max:999.99'],
            'diagnosis' => ['required', 'string', 'min:5'],
            'treatment' => ['required', 'string', 'min:5'],
        ], [
            'pet_id.required' => 'La mascota es obligatoria.',
            'pet_id.exists' => 'La mascota seleccionada no es válida.',
            'weight_at_visit.required' => 'El peso en la visita es obligatorio.',
            'weight_at_visit.numeric' => 'El peso debe ser un valor numérico.',
            'weight_at_visit.min' => 'El peso debe ser de al menos 0.01 kg.',
            'weight_at_visit.max' => 'El peso es demasiado alto.',
            'diagnosis.required' => 'El diagnóstico es obligatorio.',
            'diagnosis.min' => 'El diagnóstico debe tener al menos 5 caracteres.',
            'treatment.required' => 'El tratamiento/receta es obligatorio.',
            'treatment.min' => 'El tratamiento debe tener al menos 5 caracteres.',
        ]);

        $pet = Pet::findOrFail($validated['pet_id']);
        $validated['user_id'] = auth()->id();

        $pet->medicalRecords()->create($validated);

        // Update the pet's current weight
        $pet->update(['weight' => $validated['weight_at_visit']]);

        // Send notifications to the owner
        $this->sendPrescriptionNotifications($pet, $validated);

        return redirect()->route('pets.show', $pet)
            ->with('status', 'success')
            ->with('message', 'La consulta médica y receta han sido registradas exitosamente. Se ha enviado el historial clínico por correo y la receta por WhatsApp al propietario.');
    }

    /**
     * Send WhatsApp (UltraMsg) and Email (with clinical history PDF) notifications to the pet owner.
     *
     * @param Pet $pet
     * @param array $recordData
     * @return void
     */
    protected function sendPrescriptionNotifications(Pet $pet, array $recordData): void
    {
        // 1. Eager load relations to ensure the PDF is fully populated (including the new medical records)
        $pet->load(['owner', 'medicalRecords.veterinarian', 'vaccinations']);

        $owner = $pet->owner;
        if (!$owner) {
            return;
        }

        // --- WhatsApp Notification (Green-API) ---
        if (!empty($owner->phone)) {
            try {
                $ownerName = $owner->name;
                $petName = $pet->name;
                $weight = $recordData['weight_at_visit'];
                $diagnosis = $recordData['diagnosis'];
                $treatment = $recordData['treatment'];

                $friendlyExplanation = null;
                if (config('services.groq.api_key')) {
                    $friendlyExplanation = \App\Services\GroqService::generateFriendlyRecipe($petName, $diagnosis, $treatment);
                }

                $messageBody = "📋 *VetCare - Nueva Receta Médica* 🩺\n\n" .
                    "Hola *{$ownerName}*, te enviamos la receta del chequeo médico de *{$petName}*:\n\n" .
                    "⚖️ *Peso registrado:* {$weight} kg\n" .
                    "🩺 *Diagnóstico:* {$diagnosis}\n" .
                    "💊 *Tratamiento / Receta:* \n{$treatment}\n\n";

                if (!empty($friendlyExplanation)) {
                    $messageBody .= "✨ *Explicación amigable (IA):* \n{$friendlyExplanation}\n\n";
                }

                $messageBody .= "_Le deseamos una pronta recuperación a su mascota. También hemos enviado el historial clínico completo en PDF a su correo electrónico._";

                WhatsAppService::sendMessage($owner->phone, $messageBody);
            } catch (\Exception $e) {
                Log::error("Failed to send WhatsApp prescription notification: " . $e->getMessage());
            }
        }

        // --- Email Notification with clinical history PDF ---
        if (!empty($owner->email)) {
            try {
                // Generate PDF in-memory using Barryvdh\DomPDF\Facade\Pdf
                $pdf = Pdf::loadView('pets.pdf', compact('pet'));
                $pdfData = $pdf->output();

                // Send the ClinicalHistoryMail with PDF attachment
                Mail::to($owner->email)->send(new ClinicalHistoryMail($pet, $pdfData));
            } catch (\Exception $e) {
                Log::error("Failed to send clinical history email notification: " . $e->getMessage());
            }
        }
    }
}
