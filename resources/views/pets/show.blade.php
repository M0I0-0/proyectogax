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
                <!-- PDF Download Button -->
                <a href="{{ route('pets.pdf', $pet) }}" class="inline-flex items-center px-4 py-2 bg-emerald-650 hover:bg-emerald-600 dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white font-bold text-sm rounded-xl transition-all shadow-md hover:shadow-lg hover:shadow-emerald-600/20">
                    <svg class="w-4.5 h-4.5 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Descargar Historial (PDF)
                </a>

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

            <!-- FASE 3: Historial Clínico & Cartilla de Vacunación con Agenda de Citas -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Clinical Logs and Tabs (Left - cols 2) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 shadow-xl border border-gray-100 dark:border-gray-800/50">
                        
                        <!-- Tab Selector -->
                        <div class="border-b border-gray-200 dark:border-gray-800 flex items-center justify-between pb-1 flex-wrap gap-4">
                            <nav class="flex space-x-6" aria-label="Tabs">
                                <button onclick="switchTab('consultas')" id="tab-consultas-btn" class="border-emerald-500 text-emerald-600 dark:text-emerald-400 font-bold border-b-2 py-4 px-1 text-sm transition-all focus:outline-none flex items-center gap-2">
                                    📋 Historial de Consultas
                                </button>
                                <button onclick="switchTab('vacunas')" id="tab-vacunas-btn" class="border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-bold border-b-2 py-4 px-1 text-sm transition-all focus:outline-none flex items-center gap-2">
                                    💉 Cartilla de Vacunas
                                </button>
                            </nav>

                            <!-- Role-Protected Action Buttons -->
                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'veterinario')
                                <div>
                                    <a href="{{ route('pets.medical-records.create', $pet) }}" id="action-consulta-btn" class="inline-flex items-center px-4 py-2 bg-indigo-650 hover:bg-indigo-600 dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white font-bold text-xs rounded-xl shadow-md transition-all">
                                        + Nueva Consulta
                                    </a>
                                    <a href="{{ route('pets.vaccinations.create', $pet) }}" id="action-vacuna-btn" class="inline-flex items-center px-4 py-2 bg-emerald-650 hover:bg-emerald-600 dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-md transition-all hidden">
                                        + Registrar Vacuna
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- TAB CONTENT 1: MEDICAL RECORDS -->
                        <div id="tab-consultas-content" class="pt-6 space-y-6">
                            @if($pet->medicalRecords->count() > 0)
                                <div class="flow-root">
                                    <ul role="list" class="-mb-8">
                                        @foreach($pet->medicalRecords as $record)
                                            <li>
                                                <div class="relative pb-8">
                                                    @if(!$loop->last)
                                                        <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-800" aria-hidden="true"></span>
                                                    @endif
                                                    <div class="relative flex items-start space-x-3">
                                                        <div class="relative">
                                                            <div class="h-10 w-10 rounded-full bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-500/20 text-indigo-500 flex items-center justify-center font-bold text-md shadow-sm">
                                                                🩺
                                                            </div>
                                                        </div>
                                                        <div class="min-w-0 flex-1 bg-gray-50/50 dark:bg-gray-950/20 p-5 rounded-2xl border border-gray-100 dark:border-gray-800/40">
                                                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-1.5 mb-3">
                                                                <div>
                                                                    <p class="text-sm font-black text-gray-850 dark:text-white">Consulta Médica</p>
                                                                    <p class="text-xs text-gray-400 mt-0.5">Atendido por: <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $record->veterinarian->name }}</span></p>
                                                                </div>
                                                                <div class="text-left sm:text-right">
                                                                    <span class="text-xs font-bold text-gray-400 block">{{ \Carbon\Carbon::parse($record->created_at)->format('d/m/Y H:i') }}</span>
                                                                    <span class="inline-flex items-center mt-1 px-2.5 py-0.5 rounded-full text-3xs font-extrabold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-500/15">
                                                                        {{ number_format($record->weight_at_visit, 2) }} kg
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                                                                <div>
                                                                    <span class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider block mb-1">Diagnóstico</span>
                                                                    <p class="bg-white dark:bg-gray-950 p-3 rounded-xl border border-gray-100 dark:border-gray-850 text-xs font-medium leading-relaxed shadow-3xs">
                                                                        {{ $record->diagnosis }}
                                                                    </p>
                                                                </div>
                                                                <div>
                                                                    <span class="text-xs font-black text-indigo-400 dark:text-indigo-500 uppercase tracking-wider block mb-1">Tratamiento / Receta</span>
                                                                    <p class="bg-indigo-50/30 dark:bg-indigo-950/20 p-3 rounded-xl border border-indigo-100/50 dark:border-indigo-900/10 text-xs font-bold text-indigo-850 dark:text-indigo-300 leading-relaxed shadow-3xs">
                                                                        💊 {{ $record->treatment }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <div class="py-12 text-center">
                                    <div class="h-16 w-16 bg-gray-50 dark:bg-gray-850 text-gray-300 rounded-full flex items-center justify-center text-3xl mx-auto shadow-inner mb-4">📋</div>
                                    <h4 class="font-bold text-gray-850 dark:text-white text-md">Sin Consultas Médicas</h4>
                                    <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">No se han registrado consultas médicas en el historial clínico de esta mascota aún.</p>
                                </div>
                            @endif
                        </div>

                        <!-- TAB CONTENT 2: VACCINATIONS -->
                        <div id="tab-vacunas-content" class="pt-6 space-y-6 hidden">
                            @if($pet->vaccinations->count() > 0)
                                <div class="overflow-x-auto rounded-2xl border border-gray-100 dark:border-gray-800/40">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-gray-50 dark:bg-gray-950/50 text-gray-500 dark:text-gray-400 text-xs font-bold border-b border-gray-100 dark:border-gray-800/50">
                                                <th class="px-6 py-4">Fecha Aplicada</th>
                                                <th class="px-6 py-4">Vacuna</th>
                                                <th class="px-6 py-4">Dosis</th>
                                                <th class="px-6 py-4 text-right">Próximo Refuerzo</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50 text-sm text-gray-700 dark:text-gray-300">
                                            @foreach($pet->vaccinations as $vaccine)
                                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-950/30 transition-colors">
                                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-gray-400">
                                                        {{ \Carbon\Carbon::parse($vaccine->date_applied)->format('d/m/Y') }}
                                                    </td>
                                                    <td class="px-6 py-4 font-black text-gray-850 dark:text-white">
                                                        💉 {{ $vaccine->name }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-bold">
                                                        {{ $vaccine->dose }}
                                                    </td>
                                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                                        @if($vaccine->next_dose_due)
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-3xs font-extrabold bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-100 dark:border-amber-500/20">
                                                                {{ \Carbon\Carbon::parse($vaccine->next_dose_due)->format('d/m/Y') }}
                                                            </span>
                                                        @else
                                                            <span class="text-xs text-gray-400 font-medium">N/A</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="py-12 text-center">
                                    <div class="h-16 w-16 bg-gray-50 dark:bg-gray-850 text-gray-300 rounded-full flex items-center justify-center text-3xl mx-auto shadow-inner mb-4">💉</div>
                                    <h4 class="font-bold text-gray-850 dark:text-white text-md">Sin Vacunas Registradas</h4>
                                    <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">No se han registrado aplicaciones de vacunas en la cartilla de esta mascota aún.</p>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

                <!-- Appointments Sidebar (Right - col 1) -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 border border-gray-100 dark:border-gray-800/40 relative shadow-xl">
                        <div class="flex items-center justify-between mb-5">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-500 flex items-center justify-center text-lg">
                                    📅
                                </div>
                                <div>
                                    <h4 class="font-extrabold text-sm text-gray-800 dark:text-white">Agenda de Citas</h4>
                                    <p class="text-2xs text-gray-400">Próximas visitas</p>
                                </div>
                            </div>
                            @if(Auth::user()->role !== 'recepcionista')
                                <a href="{{ route('appointments.create') }}?pet={{ $pet->id }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                </a>
                            @endif
                        </div>

                        @php
                            $upcomingAppts = $pet->appointments->where('scheduled_at', '>=', now())->where('status', '!=', 'cancelada')->take(4);
                        @endphp

                        @if($upcomingAppts->count() > 0)
                            <div class="space-y-3">
                                @foreach($upcomingAppts as $appt)
                                    @php
                                        $apptColors = [
                                            'pendiente'  => 'bg-amber-50 dark:bg-amber-950/20 border-amber-200/60 dark:border-amber-800/30',
                                            'confirmada' => 'bg-emerald-50 dark:bg-emerald-950/20 border-emerald-200/60 dark:border-emerald-800/30',
                                            'completada' => 'bg-blue-50 dark:bg-blue-950/20 border-blue-200/60 dark:border-blue-800/30',
                                        ];
                                        $apptBadge = [
                                            'pendiente'  => 'text-amber-600 dark:text-amber-400',
                                            'confirmada' => 'text-emerald-600 dark:text-emerald-400',
                                            'completada' => 'text-blue-600 dark:text-blue-400',
                                        ];
                                        $apptColor = $apptColors[$appt->status] ?? 'bg-gray-50 dark:bg-gray-800 border-gray-200/60';
                                        $apptBadgeColor = $apptBadge[$appt->status] ?? 'text-gray-500';
                                    @endphp
                                    <a href="{{ route('appointments.show', $appt) }}" class="block p-3 rounded-2xl border {{ $apptColor }} hover:opacity-80 transition-opacity">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="text-xs font-extrabold text-gray-800 dark:text-white">
                                                    {{ \Carbon\Carbon::parse($appt->scheduled_at)->format('d/m/Y') }}
                                                </div>
                                                <div class="text-2xs text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($appt->scheduled_at)->format('H:i') }} · {{ \App\Models\Appointment::$reasons[$appt->reason] ?? $appt->reason }}</div>
                                            </div>
                                            <span class="text-2xs font-bold {{ $apptBadgeColor }}">
                                                {{ \App\Models\Appointment::$statuses[$appt->status] ?? $appt->status }}
                                            </span>
                                        </div>
                                        <div class="text-2xs text-gray-500 mt-1">Dr. {{ $appt->veterinarian->name }}</div>
                                    </a>
                                @endforeach
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800/30">
                                <a href="{{ route('appointments.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs font-bold rounded-xl transition-colors shadow-sm">
                                    Ver todas las citas
                                    <svg class="w-3.5 h-3.5 ms-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                </a>
                            </div>
                        @else
                            <div class="py-6 text-center">
                                <div class="h-12 w-12 bg-gray-50 dark:bg-gray-850 text-gray-300 rounded-full flex items-center justify-center text-2xl mx-auto shadow-inner mb-3">📅</div>
                                <p class="text-xs text-gray-500 font-medium">Sin citas próximas</p>
                                @if(Auth::user()->role !== 'recepcionista')
                                    <a href="{{ route('appointments.create') }}" class="mt-3 inline-flex items-center text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                                        + Programar cita
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Switch Tab Client-Side Javascript -->
    <script>
        function switchTab(tab) {
            const consultasTab = document.getElementById('tab-consultas-content');
            const vacunasTab = document.getElementById('tab-vacunas-content');
            const consultasBtn = document.getElementById('tab-consultas-btn');
            const vacunasBtn = document.getElementById('tab-vacunas-btn');
            
            const actionConsultaBtn = document.getElementById('action-consulta-btn');
            const actionVacunaBtn = document.getElementById('action-vacuna-btn');

            if (tab === 'consultas') {
                consultasTab.classList.remove('hidden');
                vacunasTab.classList.add('hidden');
                
                consultasBtn.className = "border-emerald-500 text-emerald-600 dark:text-emerald-400 font-bold border-b-2 py-4 px-1 text-sm transition-all focus:outline-none flex items-center gap-2";
                vacunasBtn.className = "border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-bold border-b-2 py-4 px-1 text-sm transition-all focus:outline-none flex items-center gap-2";
                
                if (actionConsultaBtn) actionConsultaBtn.classList.remove('hidden');
                if (actionVacunaBtn) actionVacunaBtn.classList.add('hidden');
            } else {
                consultasTab.classList.add('hidden');
                vacunasTab.classList.remove('hidden');
                
                consultasBtn.className = "border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-bold border-b-2 py-4 px-1 text-sm transition-all focus:outline-none flex items-center gap-2";
                vacunasBtn.className = "border-emerald-500 text-emerald-600 dark:text-emerald-400 font-bold border-b-2 py-4 px-1 text-sm transition-all focus:outline-none flex items-center gap-2";
                
                if (actionConsultaBtn) actionConsultaBtn.classList.add('hidden');
                if (actionVacunaBtn) actionVacunaBtn.classList.remove('hidden');
            }
        }
    </script>
</x-app-layout>
