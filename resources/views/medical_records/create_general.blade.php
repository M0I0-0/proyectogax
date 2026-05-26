<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-extrabold text-2xl text-purple-950 leading-tight flex items-center gap-2">
                <svg class="h-6 w-6 text-purple-600 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>{{ __('Nueva Receta y Consulta Médica') }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.2rem] shadow-3xs border border-[#e2d8f7] overflow-hidden">
                <div class="p-6 border-b border-[#e2d8f7] bg-purple-50/10 flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center shadow-3xs">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-purple-950 text-base">Crear Receta y Diagnóstico</h3>
                        <p class="text-xs text-gray-500 font-semibold mt-0.5">Selecciona el paciente con su dueño para registrar el diagnóstico y tratamiento.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('prescriptions.store') }}" class="p-8 space-y-6">
                    @csrf

                    <!-- Select Pet / Owner -->
                    <div>
                        <label for="pet_id" class="block text-xs font-black text-purple-950 uppercase tracking-wider mb-2">Seleccionar Mascota (Paciente)</label>
                        <select id="pet_id" name="pet_id" required
                            class="w-full px-4 py-3 rounded-2xl border border-purple-150 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 text-sm font-semibold text-purple-950 transition-colors shadow-3xs">
                            <option value="" disabled selected>Selecciona un paciente de la lista...</option>
                            @foreach($pets as $pet)
                                <option value="{{ $pet->id }}" {{ old('pet_id') == $pet->id ? 'selected' : '' }}>
                                    🐾 {{ $pet->name }} ({{ ucfirst($pet->species) }}) — Propietario: {{ $pet->owner ? $pet->owner->name : 'Sin Dueño' }}
                                </option>
                            @endforeach
                        </select>
                        @error('pet_id')
                            <p class="text-rose-500 text-xs mt-2 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Weight at Visit -->
                    <div>
                        <label for="weight_at_visit" class="block text-xs font-black text-purple-950 uppercase tracking-wider mb-2">Peso en la consulta (kg)</label>
                        <input id="weight_at_visit" type="number" step="0.01" name="weight_at_visit" value="{{ old('weight_at_visit') }}" required placeholder="Ej. 12.50"
                            class="w-full px-4 py-3 rounded-2xl border border-purple-150 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 text-sm font-semibold text-purple-950 transition-colors shadow-3xs" />
                        @error('weight_at_visit')
                            <p class="text-rose-500 text-xs mt-2 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- AI Assistant Section -->
                    <div class="p-5 bg-gradient-to-r from-purple-500/10 to-indigo-500/10 border border-purple-200/50 rounded-2xl space-y-3 shadow-3xs">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <span class="text-xl">✨</span>
                                <div>
                                    <h4 class="text-xs font-black text-purple-950 uppercase tracking-wider">Asistente Clínico IA (Groq)</h4>
                                    <p class="text-3xs text-purple-700 font-bold mt-0.5">Ingresa los síntomas observados para generar sugerencias.</p>
                                </div>
                            </div>
                            <button type="button" id="btn-ai-analyze" class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white text-xs font-black rounded-xl shadow-xs transition-all hover:scale-105" onclick="analyzeSymptoms()">
                                <svg class="w-3.5 h-3.5 me-1.5 animate-spin hidden" id="ai-spinner" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span id="ai-btn-text">Generar Sugerencias</span>
                            </button>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="ai_symptoms" class="block text-3xs font-black text-purple-700/60 uppercase tracking-wider">Síntomas / Motivo de consulta</label>
                            <input type="text" id="ai_symptoms" class="w-full px-3 py-2.5 rounded-xl border border-purple-200 bg-white text-purple-955 text-xs font-semibold focus:outline-none focus:ring-1 focus:ring-purple-400 shadow-3xs" placeholder="Ej: Vómito, letargo y falta de apetito desde hace 24 horas.">
                        </div>

                        <!-- Suggestions Output Box (Hidden by default) -->
                        <div id="ai-suggestions-box" class="hidden p-4 bg-white border border-purple-150 rounded-xl space-y-3 mt-3 shadow-3xs">
                            <div class="space-y-1">
                                <span class="text-3xs font-black text-purple-700 uppercase tracking-wider block">Diagnóstico sugerido</span>
                                <p id="ai-suggested-diagnosis" class="text-xs text-purple-955 font-semibold bg-purple-50/20 p-2.5 rounded-lg border border-purple-50/50 leading-relaxed"></p>
                            </div>
                            <div class="space-y-1">
                                <span class="text-3xs font-black text-purple-700 uppercase tracking-wider block">Tratamiento sugerido</span>
                                <p id="ai-suggested-treatment" class="text-xs text-purple-955 font-bold bg-amber-50/20 p-2.5 rounded-lg border border-amber-50/50 leading-relaxed"></p>
                            </div>
                            <div class="flex justify-end gap-2 pt-2 border-t border-purple-50/50">
                                <button type="button" class="px-3.5 py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-black rounded-xl border border-purple-200/50 transition-all shadow-3xs" onclick="useAiSuggestions()">
                                    ✍️ Usar en mi Receta
                                </button>
                            </div>
                        </div>
                        
                        <div id="ai-error-box" class="hidden p-3.5 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold rounded-xl">
                            Ocurrió un error al contactar al asistente de IA. Por favor verifica tus credenciales de Groq en el archivo .env.
                        </div>
                    </div>

                    <!-- Diagnosis -->
                    <div>
                        <label for="diagnosis" class="block text-xs font-black text-purple-950 uppercase tracking-wider mb-2">Diagnóstico y Notas Médicas</label>
                        <textarea id="diagnosis" name="diagnosis" rows="4" required placeholder="Describe las observaciones clínicas y diagnóstico..."
                            class="w-full px-4 py-3 rounded-2xl border border-purple-150 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 text-sm font-semibold text-purple-950 transition-colors shadow-3xs">{{ old('diagnosis') }}</textarea>
                        @error('diagnosis')
                            <p class="text-rose-500 text-xs mt-2 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Treatment / Prescription -->
                    <div>
                        <label for="treatment" class="block text-xs font-black text-purple-950 uppercase tracking-wider mb-2">Tratamiento y Receta (Medicamentos)</label>
                        <textarea id="treatment" name="treatment" rows="4" required placeholder="Ej. Amoxicilina 250mg c/12h por 7 días..."
                            class="w-full px-4 py-3 rounded-2xl border border-purple-150 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 text-sm font-semibold text-purple-950 transition-colors shadow-3xs">{{ old('treatment') }}</textarea>
                        @error('treatment')
                            <p class="text-rose-500 text-xs mt-2 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="pt-6 border-t border-purple-50/50 flex justify-end gap-3">
                        <a href="{{ route('dashboard') }}"
                            class="px-5 py-2.5 bg-purple-50 border border-purple-200/50 hover:bg-purple-100 text-purple-700 font-bold text-sm rounded-2xl transition-all shadow-3xs">
                            Volver
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-purple-500 via-indigo-500 to-purple-600 hover:shadow-lg hover:shadow-purple-500/20 text-white font-bold text-sm rounded-2xl transform hover:-translate-y-0.5 transition-all duration-150">
                            Registrar Receta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Groq AI Assistant Script -->
    <script>
        async function analyzeSymptoms() {
            const symptomsInput = document.getElementById('ai_symptoms');
            const spinner = document.getElementById('ai-spinner');
            const btnText = document.getElementById('ai-btn-text');
            const suggestionsBox = document.getElementById('ai-suggestions-box');
            const errorBox = document.getElementById('ai-error-box');
            const btn = document.getElementById('btn-ai-analyze');
            const petSelect = document.getElementById('pet_id');

            const symptoms = symptomsInput.value.trim();
            if (!symptoms) {
                alert('Por favor, ingresa los síntomas antes de analizarlos.');
                return;
            }

            const petId = petSelect.value;
            if (!petId) {
                alert('Por favor, selecciona un paciente de la lista primero.');
                return;
            }

            // Show spinner and disable button
            spinner.classList.remove('hidden');
            btnText.innerText = 'Analizando...';
            btn.disabled = true;
            suggestionsBox.classList.add('hidden');
            errorBox.classList.add('hidden');

            try {
                const response = await fetch("{{ route('ai.analyze') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        symptoms: symptoms,
                        pet_id: petId,
                        weight: document.getElementById('weight_at_visit').value
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    document.getElementById('ai-suggested-diagnosis').innerText = data.diagnosis;
                    document.getElementById('ai-suggested-treatment').innerText = data.treatment;
                    suggestionsBox.classList.remove('hidden');
                } else {
                    errorBox.innerText = data.message || 'Error al obtener respuesta de la IA. Por favor, verifica tu GROQ_API_KEY en el archivo .env.';
                    errorBox.classList.remove('hidden');
                }
            } catch (error) {
                console.error(error);
                errorBox.innerText = 'No se pudo conectar con el servidor. Verifica tu conexión de red o la configuración del servidor.';
                errorBox.classList.remove('hidden');
            } finally {
                spinner.classList.add('hidden');
                btnText.innerText = 'Generar Sugerencias';
                btn.disabled = false;
            }
        }

        function useAiSuggestions() {
            const diagnosis = document.getElementById('ai-suggested-diagnosis').innerText;
            const treatment = document.getElementById('ai-suggested-treatment').innerText;

            document.getElementById('diagnosis').value = diagnosis;
            document.getElementById('treatment').value = treatment;
            
            // Scroll down to fields
            document.getElementById('diagnosis').scrollIntoView({ behavior: 'smooth' });
        }
    </script>
</x-app-layout>
