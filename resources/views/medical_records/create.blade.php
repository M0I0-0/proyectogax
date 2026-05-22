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
                    <div class="w-16 h-16 rounded-2xl bg-white border border-[#e2d8f7] flex items-center justify-center text-3xl shadow-3xs font-semibold">
                        @if($pet->photo)
                            <img src="{{ asset('storage/' . $pet->photo) }}" alt="{{ $pet->name }}" class="w-full h-full object-cover rounded-2xl">
                        @else
                            @switch(strtolower($pet->species))
                                @case('perro') 🐶 @break
                                @case('gato') 🐱 @break
                                @case('conejo') 🐰 @break
                                @case('ave') 🦜 @break
                                @default 🐾
                            @endswitch
                        @endif
                    </div>
                    <div>
                        <span class="text-xs text-purple-700/75 font-extrabold uppercase tracking-wider block">Paciente en Consulta</span>
                        <h3 class="text-xl font-extrabold text-purple-950 mt-0.5">{{ $pet->name }}</h3>
                        <p class="text-xs text-gray-500 mt-1 font-semibold">
                            {{ ucfirst($pet->species) }} ({{ $pet->breed }}) &bull; Dueño: <span class="font-extrabold text-purple-700">👤 {{ $pet->owner->name }}</span>
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
                            <svg class="w-4.5 h-4.5 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Guardar Consulta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
