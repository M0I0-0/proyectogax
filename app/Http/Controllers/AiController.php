<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Services\GroqService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiController extends Controller
{
    /**
     * Analyze symptoms and return diagnostic suggestions.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function analyze(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'symptoms' => ['required', 'string', 'min:3'],
            'pet_id'   => ['nullable', 'exists:pets,id'],
            'species'  => ['nullable', 'string'],
            'breed'    => ['nullable', 'string'],
            'weight'   => ['nullable', 'numeric', 'min:0.01'],
        ]);

        $species = $validated['species'] ?? 'Desconocida';
        $breed = $validated['breed'] ?? 'Desconocida';
        $weight = $validated['weight'] ?? 10.0;

        if (!empty($validated['pet_id'])) {
            $pet = Pet::find($validated['pet_id']);
            if ($pet) {
                $species = $pet->species;
                $breed = $pet->breed;
                // Use input weight if provided, else use current pet weight
                $weight = $validated['weight'] ?? $pet->weight;
            }
        }

        $result = GroqService::analyzeSymptoms($species, $breed, (float)$weight, $validated['symptoms']);

        if ($result && isset($result['diagnosis']) && isset($result['treatment'])) {
            return response()->json([
                'success'   => true,
                'diagnosis' => $result['diagnosis'],
                'treatment' => $result['treatment'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se pudieron generar sugerencias de la IA. Por favor, verifica la configuración de tu GROQ_API_KEY o el estado del servicio.',
        ], 500);
    }
}
