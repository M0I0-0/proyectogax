<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pet_id'       => ['required', 'exists:pets,id'],
            'user_id'      => ['required', 'exists:users,id'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'reason'       => ['required', 'in:consulta_general,vacunacion,cirugia,revision_post_operatoria,urgencia,otro'],
            'notes'        => ['nullable', 'string', 'max:1000'],
            'status'       => ['sometimes', 'in:pendiente,confirmada,completada,cancelada'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'pet_id.required'       => 'Debes seleccionar una mascota.',
            'pet_id.exists'         => 'La mascota seleccionada no existe.',
            'user_id.required'      => 'Debes asignar un veterinario a la cita.',
            'user_id.exists'        => 'El veterinario seleccionado no existe.',
            'scheduled_at.required' => 'La fecha y hora de la cita son obligatorias.',
            'scheduled_at.after'    => 'La cita debe ser programada en el futuro.',
            'reason.required'       => 'Debes seleccionar el motivo de la cita.',
            'reason.in'             => 'El motivo seleccionado no es válido.',
            'notes.max'             => 'Las notas no pueden superar los 1000 caracteres.',
            'status.in'             => 'El estado seleccionado no es válido.',
        ];
    }
}
