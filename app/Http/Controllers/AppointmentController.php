<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\NotificationLog;
use App\Models\Pet;
use App\Models\User;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Mail\AppointmentConfirmation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    /**
     * Display a listing of all appointments.
     */
    public function index(): View
    {
        $appointments = Appointment::with(['pet.owner', 'veterinarian'])
            ->orderBy('scheduled_at')
            ->paginate(15);

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create(): View
    {
        $pets = Pet::with('owner')->orderBy('name')->get();
        $vets = User::whereIn('role', ['veterinario', 'admin'])->orderBy('name')->get();
        $reasons = Appointment::$reasons;
        $statuses = Appointment::$statuses;

        return view('appointments.create', compact('pets', 'vets', 'reasons', 'statuses'));
    }

    /**
     * Store a newly created appointment in storage and send confirmation email.
     */
    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $data['status'] ?? 'pendiente';

        $appointment = Appointment::create($data);

        // Eager-load relationships needed for the email
        $appointment->load(['pet.owner', 'veterinarian']);

        // Send confirmation email to the pet owner if they have an email
        $ownerEmail = $appointment->pet->owner->email ?? null;
        if ($ownerEmail) {
            try {
                Mail::to($ownerEmail)->send(new AppointmentConfirmation($appointment));

                // Log the notification
                NotificationLog::create([
                    'appointment_id'  => $appointment->id,
                    'type'            => 'confirmation',
                    'recipient_email' => $ownerEmail,
                    'status'          => 'sent',
                ]);
            } catch (\Exception $e) {
                // Log the failure but don't break the flow
                NotificationLog::create([
                    'appointment_id'  => $appointment->id,
                    'type'            => 'confirmation',
                    'recipient_email' => $ownerEmail,
                    'status'          => 'failed',
                    'notes'           => $e->getMessage(),
                ]);
            }
        }

        return redirect()->route('appointments.show', $appointment)
            ->with('status', 'success')
            ->with('message', 'Cita registrada exitosamente. Se ha enviado un correo de confirmación al propietario.');
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment): View
    {
        $appointment->load(['pet.owner', 'veterinarian', 'notificationLogs']);
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     */
    public function edit(Appointment $appointment): View
    {
        $pets = Pet::with('owner')->orderBy('name')->get();
        $vets = User::whereIn('role', ['veterinario', 'admin'])->orderBy('name')->get();
        $reasons = Appointment::$reasons;
        $statuses = Appointment::$statuses;

        return view('appointments.edit', compact('appointment', 'pets', 'vets', 'reasons', 'statuses'));
    }

    /**
     * Update the specified appointment in storage.
     */
    public function update(UpdateAppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        $appointment->update($request->validated());

        return redirect()->route('appointments.show', $appointment)
            ->with('status', 'success')
            ->with('message', 'Cita actualizada exitosamente.');
    }

    /**
     * Remove the specified appointment from storage.
     */
    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('status', 'success')
            ->with('message', 'Cita cancelada y eliminada correctamente.');
    }
}
