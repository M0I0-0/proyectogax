<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    /**
     * Get suggestions for diagnosis and treatment based on symptoms.
     *
     * @param string $species
     * @param string $breed
     * @param float $weight
     * @param string $symptoms
     * @return array|null
     */
    public static function analyzeSymptoms(string $species, string $breed, float $weight, string $symptoms): ?array
    {
        $apiKey = config('services.groq.api_key');

        if (!$apiKey) {
            Log::warning('Groq API Key not configured in services.');
            return null;
        }

        try {
            $prompt = "Actúa como un médico veterinario experto. Analiza la siguiente consulta para una mascota:\n" .
                "- Especie: {$species}\n" .
                "- Raza: {$breed}\n" .
                "- Peso: {$weight} kg\n" .
                "- Síntomas observados: {$symptoms}\n\n" .
                "Genera sugerencias profesionales de diagnóstico y tratamiento médico (incluyendo medicamentos, dosis y cuidados).\n" .
                "Responde estrictamente en formato JSON válido con las siguientes claves:\n" .
                "{\n" .
                "  \"diagnosis\": \"una sugerencia de diagnóstico breve y claro para el veterinario\",\n" .
                "  \"treatment\": \"una sugerencia detallada de tratamiento, medicamentos, dosis recomendadas y frecuencia (basada en el peso de {$weight} kg)\"\n" .
                "}\n" .
                "IMPORTANTE: No añadas ninguna explicación adicional, texto introductorio o Markdown fuera del JSON. Devuelve únicamente el objeto JSON.";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    ['role' => 'system', 'content' => 'Eres un asistente clínico veterinario de IA. Solo hablas en formato JSON.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.3,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? '';
                
                // Clean any markdown backticks if returned by the LLM
                $cleanContent = preg_replace('/```json|```/', '', $content);
                $cleanContent = trim($cleanContent);

                $decoded = json_decode($cleanContent, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    return $decoded;
                }

                Log::error('Failed to parse Groq response JSON: ' . $cleanContent);
            } else {
                Log::error('Groq API error response: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Exception encountered when calling Groq: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Generate an empathetic, friendly explanation of a recipe for the owner.
     *
     * @param string $petName
     * @param string $diagnosis
     * @param string $treatment
     * @return string|null
     */
    public static function generateFriendlyRecipe(string $petName, string $diagnosis, string $treatment): ?string
    {
        $apiKey = config('services.groq.api_key');

        if (!$apiKey) {
            return null;
        }

        try {
            $prompt = "Actúa como un veterinario muy cariñoso y empático. Explícale al dueño de la mascota de forma muy sencilla, amigable y comprensible qué significa el diagnóstico y cómo debe aplicar el tratamiento.\n" .
                "- Mascota: {$petName}\n" .
                "- Diagnóstico: {$diagnosis}\n" .
                "- Tratamiento prescrito: {$treatment}\n\n" .
                "Redacta un texto corto (máximo 4-5 líneas) y empático en español para enviarlo por WhatsApp. Usa viñetas claras si hay más de un medicamento. Dirígete de manera cercana al dueño e incluye buenos deseos para {$petName}.";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    ['role' => 'system', 'content' => 'Eres un veterinario amigable y empático. Tu objetivo es explicar la receta técnica al dueño en un tono cálido y comprensible.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return trim($data['choices'][0]['message']['content'] ?? '');
            }
        } catch (\Exception $e) {
            Log::error('Exception encountered when calling Groq friendly recipe: ' . $e->getMessage());
        }

        return null;
    }
}
