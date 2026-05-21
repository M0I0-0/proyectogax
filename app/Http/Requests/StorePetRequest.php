<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePetRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'owner_id' => ['required', 'exists:owners,id'],
            'name' => ['required', 'string', 'max:255'],
            'species' => ['required', 'string', 'max:100'],
            'breed' => ['required', 'string', 'max:100'],
            'birthdate' => ['required', 'date', 'before_or_equal:today'],
            'weight' => ['required', 'numeric', 'min:0.01'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'owner_id' => 'dueño',
            'name' => 'nombre',
            'species' => 'especie',
            'breed' => 'raza',
            'birthdate' => 'fecha de nacimiento',
            'weight' => 'peso',
            'photo' => 'foto',
        ];
    }
}
