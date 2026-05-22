<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-purple-950 leading-tight flex items-center gap-2">
            <a href="{{ route('pets.show', $pet) }}" class="text-purple-500 hover:text-purple-700 transition-colors flex items-center">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            {{ __('Registrar Vacuna') }}
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
                        <span class="text-xs text-purple-700/75 font-extrabold uppercase tracking-wider block">Inmunización de Paciente</span>
                        <h3 class="text-xl font-extrabold text-purple-950 mt-0.5">{{ $pet->name }}</h3>
                        <p class="text-xs text-gray-500 mt-1 font-semibold">
                            {{ ucfirst($pet->species) }} ({{ $pet->breed }}) &bull; Dueño: <span class="font-extrabold text-purple-700">👤 {{ $pet->owner->name }}</span>
                        </p>
                    </div>
                </div>
                <div class="text-right hidden sm:block">
                    <span class="text-xs text-purple-700/75 font-extrabold block">Especie</span>
                    <span class="text-lg font-black text-purple-950 uppercase tracking-wider">{{ $pet->species }}</span>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white border border-[#e2d8f7] shadow-3xs rounded-[2rem] overflow-hidden">
                <div class="p-8 border-b border-[#e2d8f7]/50 bg-purple-50/10">
                    <h3 class="text-lg font-extrabold text-purple-950">Datos de Vacunación</h3>
                    <p class="text-xs text-purple-700/60 mt-1">Registra los datos de la dosis aplicada en la cartilla de vacunación digital.</p>
                </div>

                <form action="{{ route('pets.vaccinations.store', $pet) }}" method="POST" class="p-8 space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Vaccine Name -->
                        <div class="space-y-2">
                            <label for="name" class="block text-xs font-bold text-purple-950/80 uppercase tracking-wider mb-1">Vacuna / Nombre *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full px-4 py-3 rounded-2xl border @error('name') border-rose-300 focus:ring-rose-400 @else border-[#e2d8f7] focus:ring-purple-400 focus:border-transparent @enderror bg-[#fcfbfe]/50 text-purple-950 text-sm focus:outline-none focus:ring-2 font-semibold transition-all" placeholder="Ej. Rabia, Triple Felina" required>
                            @error('name')
                                <p class="text-xs text-rose-500 font-semibold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Dose -->
                        <div class="space-y-2">
                            <label for="dose" class="block text-xs font-bold text-purple-950/80 uppercase tracking-wider mb-1">Dosis / Aplicación *</label>
                            <input type="text" name="dose" id="dose" value="{{ old('dose') }}" class="w-full px-4 py-3 rounded-2xl border @error('dose') border-rose-300 focus:ring-rose-400 @else border-[#e2d8f7] focus:ring-purple-400 focus:border-transparent @enderror bg-[#fcfbfe]/50 text-purple-950 text-sm focus:outline-none focus:ring-2 font-semibold transition-all" placeholder="Ej. 1ra Dosis, Refuerzo Anual" required>
                            @error('dose')
                                <p class="text-xs text-rose-500 font-semibold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Date Applied -->
                        <div class="space-y-2">
                            <label for="date_applied" class="block text-xs font-bold text-purple-950/80 uppercase tracking-wider mb-1">Fecha de Aplicación *</label>
                            <input type="date" max="{{ date('Y-m-d') }}" name="date_applied" id="date_applied" value="{{ old('date_applied', date('Y-m-d')) }}" class="w-full px-4 py-3 rounded-2xl border @error('date_applied') border-rose-300 focus:ring-rose-400 @else border-[#e2d8f7] focus:ring-purple-400 focus:border-transparent @enderror bg-[#fcfbfe]/50 text-purple-950 text-sm focus:outline-none focus:ring-2 font-semibold transition-all" required>
                            @error('date_applied')
                                <p class="text-xs text-rose-500 font-semibold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Next Dose Due -->
                        <div class="space-y-2">
                            <label for="next_dose_due" class="block text-xs font-bold text-purple-950/80 uppercase tracking-wider mb-1">Próximo Refuerzo (Opcional)</label>
                            <input type="date" name="next_dose_due" id="next_dose_due" min="{{ date('Y-m-d') }}" value="{{ old('next_dose_due') }}" class="w-full px-4 py-3 rounded-2xl border @error('next_dose_due') border-rose-300 focus:ring-rose-400 @else border-[#e2d8f7] focus:ring-purple-400 focus:border-transparent @enderror bg-[#fcfbfe]/50 text-purple-950 text-sm focus:outline-none focus:ring-2 font-semibold transition-all">
                            @error('next_dose_due')
                                <p class="text-xs text-rose-500 font-semibold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="pt-5 border-t border-[#e2d8f7]/50 flex items-center justify-between gap-3">
                        <a href="{{ route('pets.show', $pet) }}" class="inline-flex items-center px-4 py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200/50 font-bold text-sm rounded-2xl transition-all shadow-sm">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 via-indigo-500 to-purple-600 hover:from-purple-600 hover:to-indigo-600 text-white font-bold text-sm rounded-2xl hover:shadow-lg hover:shadow-purple-500/20 transform hover:-translate-y-0.5 transition-all duration-150 shadow-sm">
                            <svg class="w-4.5 h-4.5 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Registrar Vacuna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
