<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight flex items-center gap-2">
                <a href="{{ route('pets.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                {{ __('Expediente Médico') }}: {{ $pet->name }}
            </h2>
            <div class="flex gap-3">
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'recepcionista')
                    <a href="{{ route('pets.edit', $pet) }}" class="inline-flex items-center px-4 py-2 border border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-300 font-semibold text-sm rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors shadow-sm">
                        <svg class="w-4.5 h-4.5 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar Ficha
                    </a>
                    <form action="{{ route('pets.destroy', $pet) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de archivar esta mascota?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-rose-200 dark:border-rose-900/50 text-rose-600 dark:text-rose-400 font-semibold text-sm rounded-xl hover:bg-rose-50 dark:hover:bg-rose-950/20 transition-all shadow-sm">
                            <svg class="w-4.5 h-4.5 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Archivar Mascota
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center gap-3">
                    <svg class="h-5 w-5 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Mascot Card -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-3xl p-8 shadow-xl border border-gray-100 dark:border-gray-800/50 relative overflow-hidden flex flex-col md:flex-row gap-8">
                    <div class="absolute -right-24 -bottom-24 w-80 h-80 bg-gradient-to-tr from-indigo-500/5 to-purple-500/5 rounded-full blur-3xl"></div>

                    <!-- Pet Photo / Avatar -->
                    <div class="shrink-0 self-start">
                        @if($pet->photo)
                            <img src="{{ asset('storage/' . $pet->photo) }}" class="h-32 w-32 md:h-40 md:w-40 rounded-3xl object-cover border-4 border-white dark:border-gray-800 shadow-md transform hover:scale-102 transition-transform duration-350" alt="{{ $pet->name }}">
                        @else
                            <div class="h-32 w-32 md:h-40 md:w-40 rounded-3xl bg-gradient-to-br from-indigo-100 to-indigo-200 dark:from-indigo-950/40 dark:to-indigo-900/40 text-indigo-500 flex items-center justify-center text-6xl font-extrabold shadow-sm border border-gray-100 dark:border-gray-800">
                                🐾
                            </div>
                        @endif
                    </div>

                    <!-- Pet Details -->
                    <div class="space-y-6 flex-1">
                        <div>
                            <div class="flex items-center gap-3">
                                <h3 class="text-3xl font-extrabold text-gray-800 dark:text-white tracking-tight leading-none">{{ $pet->name }}</h3>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400 border border-indigo-200/50 dark:border-indigo-500/20 capitalize shadow-2xs">
                                    {{ $pet->species }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 font-medium">Ficha Médica ID: #{{ str_pad($pet->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 pt-6 border-t border-gray-100 dark:border-gray-800/50">
                            <div class="space-y-0.5">
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Raza</span>
                                <span class="block text-sm font-bold text-gray-700 dark:text-gray-200">{{ $pet->breed }}</span>
                            </div>
                            <div class="space-y-0.5">
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Peso Corporal</span>
                                <span class="block text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ $pet->weight }} kg</span>
                            </div>
                            <div class="space-y-0.5 col-span-2 sm:col-span-1">
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Fecha de Nacimiento</span>
                                <span class="block text-sm font-bold text-gray-700 dark:text-gray-200">
                                    {{ \Carbon\Carbon::parse($pet->birthdate)->format('d/m/Y') }}
                                </span>
                                <span class="text-3xs text-gray-400 font-medium block">({{ \Carbon\Carbon::parse($pet->birthdate)->age }} años de edad)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Owner Card -->
                <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 shadow-xl border border-gray-100 dark:border-gray-800/50 relative overflow-hidden flex flex-col justify-between">
                    <div class="absolute -right-24 -bottom-24 w-60 h-60 bg-gradient-to-tr from-emerald-500/5 to-teal-500/5 rounded-full blur-3xl"></div>

                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-emerald-400 to-teal-600 flex items-center justify-center text-white text-md font-bold shadow-md shadow-emerald-500/20">
                                {{ strtoupper(substr($pet->owner?->name ?? 'C', 0, 2)) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-md text-gray-800 dark:text-white leading-snug">Propietario</h4>
                                <p class="text-3xs text-gray-400 uppercase tracking-wider">Responsable asignado</p>
                            </div>
                        </div>

                        <div class="space-y-3 pt-4 border-t border-gray-100 dark:border-gray-800/30 text-xs">
                            @if($pet->owner)
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Nombre:</span>
                                    <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $pet->owner->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Teléfono:</span>
                                    <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $pet->owner->phone }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Email:</span>
                                    <span class="font-semibold text-gray-800 dark:text-gray-200 truncate max-w-[150px]" title="{{ $pet->owner->email }}">{{ $pet->owner->email }}</span>
                                </div>
                            @else
                                <div class="py-4 text-center text-rose-500 font-bold">
                                    ⚠️ Sin Propietario Asociado
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($pet->owner)
                        <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800/30">
                            <a href="{{ route('owners.show', $pet->owner) }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs font-bold rounded-xl transition-colors shadow-2xs">
                                Ver Ficha del Propietario
                                <svg class="w-3.5 h-3.5 ms-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Future Modules Placeholders (Phase 3 & 4) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Historial Clínico Card -->
                <div class="bg-white/60 dark:bg-gray-900/60 backdrop-blur-md rounded-3xl p-6 border border-gray-100 dark:border-gray-800/40 relative group overflow-hidden">
                    <div class="absolute top-4 right-4">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-3xs font-extrabold bg-indigo-500/10 text-indigo-500 border border-indigo-500/20 shadow-2xs">
                            Fase 3
                        </span>
                    </div>
                    <div class="space-y-4">
                        <div class="h-10 w-10 rounded-xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center text-xl">
                            📋
                        </div>
                        <div>
                            <h4 class="font-extrabold text-base text-gray-800 dark:text-white flex items-center gap-1.5">
                                Historial Clínico
                            </h4>
                            <p class="text-2xs text-gray-400 mt-0.5">Consultas y Recetas</p>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed font-medium">
                            El registro de consultas médicas, diagnósticos detallados, tratamientos y emisión de recetas en formato digital estará disponible en la **Fase 3**.
                        </p>
                        <div class="pt-2">
                            <span class="inline-flex items-center text-3xs font-bold text-indigo-500 gap-1 opacity-70 group-hover:opacity-100 transition-opacity">
                                Próximamente
                                <svg class="w-3 h-3 animate-ping" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Vacunas Card -->
                <div class="bg-white/60 dark:bg-gray-900/60 backdrop-blur-md rounded-3xl p-6 border border-gray-100 dark:border-gray-800/40 relative group overflow-hidden">
                    <div class="absolute top-4 right-4">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-3xs font-extrabold bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 shadow-2xs">
                            Fase 3
                        </span>
                    </div>
                    <div class="space-y-4">
                        <div class="h-10 w-10 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center text-xl">
                            💉
                        </div>
                        <div>
                            <h4 class="font-extrabold text-base text-gray-800 dark:text-white flex items-center gap-1.5">
                                Control de Vacunas
                            </h4>
                            <p class="text-2xs text-gray-400 mt-0.5">Inmunización y Dosis</p>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed font-medium">
                            La cartilla digital de inmunizaciones, control de dosis aplicadas, laboratorio y próximas fechas programadas de vacunación estará disponible en la **Fase 3**.
                        </p>
                        <div class="pt-2">
                            <span class="inline-flex items-center text-3xs font-bold text-emerald-500 gap-1 opacity-70 group-hover:opacity-100 transition-opacity">
                                Próximamente
                                <svg class="w-3 h-3 animate-ping" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Citas Card -->
                <div class="bg-white/60 dark:bg-gray-900/60 backdrop-blur-md rounded-3xl p-6 border border-gray-100 dark:border-gray-800/40 relative group overflow-hidden">
                    <div class="absolute top-4 right-4">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-3xs font-extrabold bg-amber-500/10 text-amber-500 border border-amber-500/20 shadow-2xs">
                            Fase 4
                        </span>
                    </div>
                    <div class="space-y-4">
                        <div class="h-10 w-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-xl">
                            📅
                        </div>
                        <div>
                            <h4 class="font-extrabold text-base text-gray-800 dark:text-white flex items-center gap-1.5">
                                Agenda de Citas
                            </h4>
                            <p class="text-2xs text-gray-400 mt-0.5">Recordatorios Automáticos</p>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed font-medium">
                            La gestión de turnos veterinarios, recordatorios automatizados por correo electrónico a propietarios y panel interactivo de citas estará disponible en la **Fase 4**.
                        </p>
                        <div class="pt-2">
                            <span class="inline-flex items-center text-3xs font-bold text-amber-500 gap-1 opacity-70 group-hover:opacity-100 transition-opacity">
                                Próximamente
                                <svg class="w-3 h-3 animate-ping" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                            </span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
