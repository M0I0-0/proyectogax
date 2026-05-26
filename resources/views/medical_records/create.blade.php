<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-purple-950 leading-tight flex items-center gap-2">
            <a href="{{ route('pets.show', $pet) }}" class="text-purple-500 hover:text-purple-700 transition-colors flex items-center">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            {{ __('Registrar Nueva Consulta') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <!-- Patient Mini Summary Card -->
            <div class="bg-gradient-to-tr from-purple-50 via-indigo-50/50 to-purple-100/40 text-purple-950 rounded-[2rem] p-6 border border-[#e2d8f7] shadow-3xs mb-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-white border border-[#e2d8f7] overflow-hidden flex items-center justify-center shadow-3xs shrink-0">
                        @if($pet->photo)
                            <img src="{{ asset('storage/' . $pet->photo) }}" alt="{{ $pet->name }}" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('images/logos/logo_vetcare.jpg') }}" alt="Logo Fallback" class="w-full h-full object-cover opacity-80">
                        @endif
                    </div>
                    <div>
                        <span class="text-xs text-purple-700/75 font-extrabold uppercase tracking-wider block">Paciente en Consulta</span>
                        <h3 class="text-xl font-extrabold text-purple-950 mt-0.5">{{ $pet->name }}</h3>
                        <p class="text-xs text-gray-500 mt-1 font-semibold flex items-center gap-1.5 flex-wrap">
                            <span>{{ ucfirst($pet->species) }} ({{ $pet->breed }})</span>
                            <span class="text-purple-300">&bull;</span>
                            <span>Dueño:</span>
                            <span class="font-extrabold text-purple-700 inline-flex items-center gap-1">
                                <svg class="h-3.5 w-3.5 text-purple-500 shrink-0 stroke-[2.2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ $pet->owner->name }}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="text-right hidden sm:block">
                    <span class="text-xs text-purple-700/75 font-extrabold block">Peso Actual</span>
                    <span class="text-2xl font-black text-purple-950">{{ number_format($pet->weight, 2) }} kg</span>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white border border-[#e2d8f7] shadow-3xs rounded-[2rem] overflow-hidden">
                <div class="p-8 border-b border-[#e2d8f7]/50 bg-purple-50/10">
                    <h3 class="text-lg font-extrabold text-purple-950">Datos de la Consulta Médica</h3>
                    <p class="text-xs text-purple-700/60 mt-1">Completa los detalles clínicos, el diagnóstico profesional y el tratamiento/receta para el paciente.</p>
                </div>

                <form action="{{ route('pets.medical-records.store', $pet) }}" method="POST" class="p-8 space-y-6">
                    @csrf

                    <!-- Weight at Visit -->
                    <div class="space-y-2">
                        <label for="weight_at_visit" class="block text-xs font-bold text-purple-950/80 uppercase tracking-wider mb-1">Peso en la Consulta (kg) *</label>
                        <div class="relative rounded-2xl shadow-3xs">
                            <input type="number" step="0.01" min="0.01" max="999.99" name="weight_at_visit" id="weight_at_visit" value="{{ old('weight_at_visit', $pet->weight) }}" class="w-full px-4 py-3 rounded-2xl border @error('weight_at_visit') border-rose-300 focus:ring-rose-400 @else border-[#e2d8f7] focus:ring-purple-400 focus:border-transparent @enderror bg-[#fcfbfe]/50 text-purple-950 text-sm focus:outline-none focus:ring-2 font-semibold transition-all" placeholder="Ej. 12.50" required>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-purple-700 font-extrabold text-sm">
                                kg
                            </div>
                        </div>
                        @error('weight_at_visit')
                            <p class="text-xs text-rose-500 font-semibold mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                {{ $message }}
                            </p>
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
                    <div class="space-y-2">
                        <label for="diagnosis" class="block text-xs font-bold text-purple-950/80 uppercase tracking-wider mb-1">Diagnóstico *</label>
                        <textarea name="diagnosis" id="diagnosis" rows="4" class="w-full px-4 py-3 rounded-2xl border @error('diagnosis') border-rose-300 focus:ring-rose-400 @else border-[#e2d8f7] focus:ring-purple-400 focus:border-transparent @enderror bg-[#fcfbfe]/50 text-purple-950 text-sm focus:outline-none focus:ring-2 font-semibold transition-all resize-none" placeholder="Describe los síntomas observados, hallazgos clínicos y el diagnóstico médico final..." required>{{ old('diagnosis') }}</textarea>
                        @error('diagnosis')
                            <p class="text-xs text-rose-500 font-semibold mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Treatment -->
                    <div class="space-y-2">
                        <label for="treatment" class="block text-xs font-bold text-purple-950/80 uppercase tracking-wider mb-1">Tratamiento y Receta *</label>
                        <textarea name="treatment" id="treatment" rows="4" class="w-full px-4 py-3 rounded-2xl border @error('treatment') border-rose-300 focus:ring-rose-400 @else border-[#e2d8f7] focus:ring-purple-400 focus:border-transparent @enderror bg-[#fcfbfe]/50 text-purple-950 text-sm focus:outline-none focus:ring-2 font-semibold transition-all resize-none" placeholder="Indica el tratamiento, medicamentos prescritos, dosis, frecuencia recomendada y cuidados..." required>{{ old('treatment') }}</textarea>
                        @error('treatment')
                            <p class="text-xs text-rose-500 font-semibold mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="pt-5 border-t border-[#e2d8f7]/50 flex items-center justify-between gap-3">
                        <a href="{{ route('pets.show', $pet) }}" class="inline-flex items-center px-4 py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200/50 font-bold text-sm rounded-2xl transition-all shadow-sm">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 via-indigo-500 to-purple-600 hover:from-purple-600 hover:to-indigo-600 text-white font-bold text-sm rounded-2xl hover:shadow-lg hover:shadow-purple-500/20 transform hover:-translate-y-0.5 transition-all duration-150 shadow-sm">
                            <svg class="w-4 h-4 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Guardar Consulta
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

            const symptoms = symptomsInput.value.trim();
            if (!symptoms) {
                alert('Por favor, ingresa los síntomas antes de analizarlos.');
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
                        pet_id: "{{ $pet->id }}",
                        weight: document.getElementById('weight_at_visit').value || "{{ $pet->weight }}"
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
