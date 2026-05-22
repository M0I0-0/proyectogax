<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h2 class="font-extrabold text-2xl text-purple-950 leading-tight flex items-center gap-2.5">
                <a href="{{ route('pets.index') }}" class="inline-flex items-center justify-center h-10 w-10 bg-purple-50/80 hover:bg-purple-50 text-purple-700 rounded-full transition-all border border-[#e2d8f7] shadow-2xs" title="Regresar al listado">
                    <svg class="h-5 w-5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <span class="flex items-center gap-2">
                    📂 {{ __('Expediente Médico') }}: <span class="text-purple-750">{{ $pet->name }}</span>
                </span>
            </h2>
            <div class="flex flex-wrap gap-2.5">
                <!-- PDF Download Button -->
                <a href="{{ route('pets.pdf', $pet) }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-purple-500 via-indigo-500 to-purple-600 text-white font-extrabold text-xs sm:text-sm rounded-2xl transition-all shadow-md hover:shadow-lg hover:shadow-purple-500/20 hover:-translate-y-0.5">
                    <svg class="w-4.5 h-4.5 me-2 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Descargar Historial (PDF)
                </a>

                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'recepcionista')
                    <a href="{{ route('pets.edit', $pet) }}" class="inline-flex items-center px-4 py-2.5 bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200/50 font-extrabold text-xs sm:text-sm rounded-2xl transition-all shadow-2xs hover:-translate-y-0.5">
                        <svg class="w-4.5 h-4.5 me-2 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar Ficha
                    </a>
                    <form action="{{ route('pets.destroy', $pet) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de archivar esta mascota?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/50 font-extrabold text-xs sm:text-sm rounded-2xl transition-all shadow-2xs hover:-translate-y-0.5">
                            <svg class="w-4.5 h-4.5 me-2 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Archivar Mascota
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-purple-50 border border-purple-200 text-purple-700 rounded-2xl flex items-center gap-3 shadow-2xs">
                    <svg class="h-5 w-5 text-purple-500 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-extrabold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Pet Card -->
                <div class="lg:col-span-2 bg-white rounded-[2rem] p-6 sm:p-8 shadow-3xs border border-[#e2d8f7] relative overflow-hidden flex flex-col md:flex-row gap-6 sm:gap-8">
                    <div class="absolute -right-24 -bottom-24 w-80 h-80 bg-gradient-to-tr from-purple-500/5 to-indigo-500/5 rounded-full blur-3xl"></div>

                    <!-- Pet Photo / Avatar -->
                    <div class="shrink-0 self-start mx-auto md:mx-0">
                        @if($pet->photo)
                            <div class="bg-gradient-to-tr from-purple-200 to-indigo-300 p-1.5 rounded-[2.2rem] shadow-sm transform hover:scale-[1.02] transition-transform duration-350">
                                <img src="{{ asset('storage/' . $pet->photo) }}" class="h-32 w-32 md:h-36 md:w-36 rounded-[1.8rem] object-cover border-4 border-white shadow-2xs" alt="{{ $pet->name }}">
                            </div>
                        @else
                            <div class="h-32 w-32 md:h-36 md:w-36 rounded-[2rem] bg-gradient-to-br from-purple-50 to-indigo-100 text-purple-600 flex items-center justify-center text-6xl font-extrabold shadow-sm border border-[#e2d8f7]">
                                @if(strtolower($pet->species) === 'perro') 🐶
                                @elseif(strtolower($pet->species) === 'gato') 🐱
                                @elseif(strtolower($pet->species) === 'ave') 🦜
                                @elseif(strtolower($pet->species) === 'conejo') 🐰
                                @else 🐾
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Pet Details -->
                    <div class="space-y-5 flex-1 text-center md:text-left">
                        <div>
                            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-3 justify-center md:justify-start">
                                <h3 class="text-3xl font-extrabold text-purple-950 tracking-tight leading-none">{{ $pet->name }}</h3>
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-extrabold bg-purple-50 text-purple-700 border border-[#e2d8f7] capitalize self-center shadow-2xs">
                                    @if(strtolower($pet->species) === 'perro') 🐶 Perro
                                    @elseif(strtolower($pet->species) === 'gato') 🐱 Gato
                                    @elseif(strtolower($pet->species) === 'ave') 🦜 Ave
                                    @elseif(strtolower($pet->species) === 'conejo') 🐰 Conejo
                                    @else 🐾 {{ ucfirst($pet->species) }}
                                    @endif
                                </span>
                            </div>
                            <p class="text-2xs text-purple-700/60 mt-2 font-extrabold tracking-wider uppercase">Ficha Médica ID: #{{ str_pad($pet->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4 border-t border-purple-100/50">
                            <div class="space-y-0.5">
                                <span class="text-xs text-purple-700/70 font-bold uppercase tracking-wider block">Raza</span>
                                <span class="text-sm font-extrabold text-purple-950">🧬 {{ $pet->breed }}</span>
                            </div>
                            <div class="space-y-0.5">
                                <span class="text-xs text-purple-700/70 font-bold uppercase tracking-wider block">Peso Corporal</span>
                                <span class="text-sm font-extrabold text-purple-950">⚖️ {{ $pet->weight }} kg</span>
                            </div>
                            <div class="space-y-0.5 col-span-2 sm:col-span-1">
                                <span class="text-xs text-purple-700/70 font-bold uppercase tracking-wider block">Edad</span>
                                <span class="text-sm font-extrabold text-purple-950">🎂 {{ \Carbon\Carbon::parse($pet->birthdate)->age }} años</span>
                                <span class="text-3xs text-gray-500 font-semibold block">({{ \Carbon\Carbon::parse($pet->birthdate)->format('d/m/Y') }})</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Owner Card -->
                <div class="bg-white rounded-[2rem] p-6 shadow-3xs border border-[#e2d8f7] relative overflow-hidden flex flex-col justify-between">
                    <div class="absolute -right-24 -bottom-24 w-60 h-60 bg-gradient-to-tr from-purple-500/5 to-indigo-500/5 rounded-full blur-3xl"></div>

                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="h-11 w-11 rounded-2xl bg-gradient-to-tr from-purple-500 to-indigo-600 flex items-center justify-center text-white text-md font-black shadow-md shadow-purple-500/25">
                                {{ strtoupper(substr($pet->owner?->name ?? 'C', 0, 2)) }}
                            </div>
                            <div>
                                <h4 class="font-extrabold text-sm text-purple-950 leading-snug">Propietario</h4>
                                <p class="text-3xs text-gray-500 uppercase tracking-wider font-extrabold">Responsable asignado</p>
                            </div>
                        </div>

                        <div class="space-y-2 pt-3 border-t border-purple-100/50 text-xs">
                            @if($pet->owner)
                                <div class="flex justify-between py-1">
                                    <span class="text-purple-700/80 font-semibold">Nombre:</span>
                                    <span class="font-extrabold text-purple-950">{{ $pet->owner->name }}</span>
                                </div>
                                <div class="flex justify-between py-1">
                                    <span class="text-purple-700/80 font-semibold">Teléfono:</span>
                                    <span class="font-extrabold text-purple-950">📞 {{ $pet->owner->phone }}</span>
                                </div>
                                <div class="flex justify-between py-1">
                                    <span class="text-purple-700/80 font-semibold">Email:</span>
                                    <span class="font-extrabold text-purple-950 truncate max-w-[160px]" title="{{ $pet->owner->email }}">✉️ {{ $pet->owner->email }}</span>
                                </div>
                            @else
                                <div class="py-4 text-center text-rose-500 font-extrabold">
                                    ⚠️ Sin Propietario Asociado
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($pet->owner)
                        <div class="mt-4 pt-3 border-t border-purple-100/50">
                            <a href="{{ route('owners.show', $pet->owner) }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-extrabold rounded-xl transition-all shadow-2xs border border-purple-200/50">
                                Ver Ficha del Propietario
                                <svg class="w-3.5 h-3.5 ms-1.5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Medical Records & Vaccinations & Calendar Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Clinical Logs and Tabs -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-3xs border border-[#e2d8f7]">
                        
                        <!-- Tab Selector -->
                        <div class="border-b border-purple-100 flex items-center justify-between pb-1 flex-wrap gap-4">
                            <nav class="flex space-x-6" aria-label="Tabs">
                                <button onclick="switchTab('consultas')" id="tab-consultas-btn" class="border-purple-600 text-purple-700 font-extrabold border-b-2 py-4 px-1 text-sm transition-all focus:outline-none flex items-center gap-2">
                                    📋 Historial de Consultas
                                </button>
                                <button onclick="switchTab('vacunas')" id="tab-vacunas-btn" class="border-transparent text-purple-600/60 hover:text-purple-800 font-extrabold border-b-2 py-4 px-1 text-sm transition-all focus:outline-none flex items-center gap-2">
                                    💉 Cartilla de Vacunas
                                </button>
                            </nav>

                            <!-- Role-Protected Action Buttons -->
                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'veterinario')
                                <div>
                                    <a href="{{ route('pets.medical-records.create', $pet) }}" id="action-consulta-btn" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 via-indigo-500 to-purple-600 text-white font-extrabold text-xs rounded-xl shadow-sm transition-all hover:-translate-y-0.5">
                                        + Nueva Consulta
                                    </a>
                                    <a href="{{ route('pets.vaccinations.create', $pet) }}" id="action-vacuna-btn" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 via-indigo-500 to-purple-600 text-white font-extrabold text-xs rounded-xl shadow-sm transition-all hover:-translate-y-0.5 hidden">
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
                                                        <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-purple-100" aria-hidden="true"></span>
                                                    @endif
                                                    <div class="relative flex items-start space-x-3">
                                                        <div class="relative">
                                                            <div class="h-10 w-10 rounded-full bg-purple-50 border border-[#e2d8f7] text-purple-600 flex items-center justify-center font-bold text-md shadow-2xs">
                                                                🩺
                                                            </div>
                                                        </div>
                                                        <div class="min-w-0 flex-1 bg-white border border-[#e2d8f7] p-5 rounded-2xl shadow-3xs hover:shadow-2xs transition-shadow">
                                                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-3">
                                                                <div>
                                                                    <p class="text-sm font-extrabold text-purple-950">Consulta Médica</p>
                                                                    <p class="text-xs text-gray-500 mt-0.5 font-semibold">Atendido por: <span class="text-purple-700 font-extrabold">👨‍⚕️ {{ $record->veterinarian->name }}</span></p>
                                                                </div>
                                                                <div class="text-left sm:text-right">
                                                                    <span class="text-xs font-bold text-gray-400 block">{{ \Carbon\Carbon::parse($record->created_at)->format('d/m/Y H:i') }}</span>
                                                                    <span class="inline-flex items-center mt-1 px-2.5 py-0.5 rounded-full text-3xs font-black bg-purple-50 text-purple-700 border border-[#e2d8f7]">
                                                                        ⚖️ {{ number_format($record->weight_at_visit, 2) }} kg
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="space-y-3 text-sm text-purple-955">
                                                                <div>
                                                                    <span class="text-xs font-black text-purple-700/60 uppercase tracking-wider block mb-1">Diagnóstico</span>
                                                                    <p class="bg-purple-50/10 p-3 rounded-xl border border-purple-100/50 text-xs font-medium leading-relaxed">
                                                                        {{ $record->diagnosis }}
                                                                    </p>
                                                                </div>
                                                                <div>
                                                                    <span class="text-xs font-black text-purple-700/60 uppercase tracking-wider block mb-1">Tratamiento / Receta</span>
                                                                    <p class="bg-amber-50/40 p-3 rounded-xl border border-amber-100 text-xs font-extrabold text-amber-900 leading-relaxed">
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
                                    <div class="h-16 w-16 bg-purple-50/50 text-purple-400 rounded-full flex items-center justify-center text-3xl mx-auto shadow-inner mb-4">📋</div>
                                    <h4 class="font-extrabold text-purple-950 text-md">Sin Consultas Médicas</h4>
                                    <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">No se han registrado consultas médicas en el historial clínico de esta mascota aún.</p>
                                </div>
                            @endif
                        </div>

                        <!-- TAB CONTENT 2: VACCINATIONS -->
                        <div id="tab-vacunas-content" class="pt-6 space-y-6 hidden">
                            @if($pet->vaccinations->count() > 0)
                                <div class="overflow-x-auto rounded-2xl border border-purple-100 shadow-3xs">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-purple-50/40 text-purple-950 text-xs font-extrabold border-b border-purple-100">
                                                <th class="px-6 py-4">Fecha Aplicada</th>
                                                <th class="px-6 py-4">Vacuna</th>
                                                <th class="px-6 py-4">Dosis</th>
                                                <th class="px-6 py-4 text-right">Próximo Refuerzo</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-purple-50 text-sm text-purple-900 bg-white">
                                            @foreach($pet->vaccinations as $vaccine)
                                                <tr class="hover:bg-purple-50/10 transition-colors">
                                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-extrabold text-purple-600/60">
                                                        {{ \Carbon\Carbon::parse($vaccine->date_applied)->format('d/m/Y') }}
                                                    </td>
                                                    <td class="px-6 py-4 font-extrabold text-purple-950">
                                                        💉 {{ $vaccine->name }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-extrabold">
                                                        {{ $vaccine->dose }}
                                                    </td>
                                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                                        @if($vaccine->next_dose_due)
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-3xs font-black bg-amber-50 text-amber-700 border border-amber-100/70 shadow-3xs">
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
                                    <div class="h-16 w-16 bg-purple-50/50 text-purple-400 rounded-full flex items-center justify-center text-3xl mx-auto shadow-inner mb-4">💉</div>
                                    <h4 class="font-extrabold text-purple-950 text-md">Sin Vacunas Registradas</h4>
                                    <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">No se han registrado aplicaciones de vacunas en la cartilla de esta mascota aún.</p>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

                <!-- Appointments Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[2rem] p-6 border border-[#e2d8f7] relative shadow-3xs">
                        <div class="flex items-center justify-between mb-5">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg shadow-2xs">
                                    📅
                                </div>
                                <div>
                                    <h4 class="font-extrabold text-sm text-purple-950">Agenda de Citas</h4>
                                    <p class="text-2xs text-gray-500">Próximas visitas</p>
                                </div>
                            </div>
                            @if(Auth::user()->role !== 'recepcionista')
                                <a href="{{ route('appointments.create') }}?pet={{ $pet->id }}" class="text-purple-600 hover:text-purple-800 hover:scale-105 transition-all" title="Programar Cita">
                                    <svg class="w-5.5 h-5.5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                            'pendiente'  => 'bg-amber-50/50 border-amber-200 text-amber-900 hover:bg-amber-50',
                                            'confirmada' => 'bg-indigo-50/50 border-[#e2d8f7] text-indigo-900 hover:bg-indigo-50',
                                            'completada' => 'bg-purple-50/50 border-purple-200 text-purple-900 hover:bg-purple-50',
                                        ];
                                        $apptBadge = [
                                            'pendiente'  => 'text-amber-700 bg-amber-100/60 px-2 py-0.5 rounded-md',
                                            'confirmada' => 'text-indigo-700 bg-indigo-100/60 px-2 py-0.5 rounded-md',
                                            'completada' => 'text-purple-700 bg-purple-100/60 px-2 py-0.5 rounded-md',
                                        ];
                                        $apptColor = $apptColors[$appt->status] ?? 'bg-purple-50/30 border-purple-100 text-purple-900';
                                        $apptBadgeStyle = $apptBadge[$appt->status] ?? 'text-purple-650';
                                    @endphp
                                    <a href="{{ route('appointments.show', $appt) }}" class="block p-3.5 rounded-2xl border {{ $apptColor }} transition-all shadow-3xs hover:shadow-2xs">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="text-xs font-black">
                                                    {{ \Carbon\Carbon::parse($appt->scheduled_at)->format('d/m/Y') }}
                                                </div>
                                                <div class="text-2xs text-gray-500 mt-0.5 font-bold">{{ \Carbon\Carbon::parse($appt->scheduled_at)->format('H:i') }} · {{ \App\Models\Appointment::$reasons[$appt->reason] ?? $appt->reason }}</div>
                                            </div>
                                            <span class="text-3xs font-black uppercase tracking-wider {{ $apptBadgeStyle }}">
                                                {{ \App\Models\Appointment::$statuses[$appt->status] ?? $appt->status }}
                                            </span>
                                        </div>
                                        <div class="text-2xs text-purple-955/60 font-semibold mt-2">Dr. {{ $appt->veterinarian->name }}</div>
                                    </a>
                                @endforeach
                            </div>
                            <div class="mt-4 pt-4 border-t border-purple-100/50">
                                <a href="{{ route('appointments.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-purple-50/50 hover:bg-purple-50 text-purple-700 text-xs font-extrabold rounded-xl transition-all shadow-2xs border border-purple-200/50">
                                    Ver todas las citas
                                    <svg class="w-3.5 h-3.5 ms-1.5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        @else
                            <div class="py-8 text-center border border-dashed border-[#e2d8f7] rounded-2xl bg-purple-50/10">
                                <div class="h-12 w-12 bg-purple-50 text-purple-400 rounded-full flex items-center justify-center text-2xl mx-auto shadow-inner mb-3">📅</div>
                                <p class="text-xs text-gray-500 font-extrabold">Sin citas próximas</p>
                                @if(Auth::user()->role !== 'recepcionista')
                                    <a href="{{ route('appointments.create') }}?pet={{ $pet->id }}" class="mt-2.5 inline-flex items-center text-xs font-extrabold text-purple-650 hover:text-purple-850 hover:underline">
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
                
                consultasBtn.className = "border-purple-600 text-purple-700 font-extrabold border-b-2 py-4 px-1 text-sm transition-all focus:outline-none flex items-center gap-2";
                vacunasBtn.className = "border-transparent text-purple-600/60 hover:text-purple-800 font-extrabold border-b-2 py-4 px-1 text-sm transition-all focus:outline-none flex items-center gap-2";
                
                if (actionConsultaBtn) actionConsultaBtn.classList.remove('hidden');
                if (actionVacunaBtn) actionVacunaBtn.classList.add('hidden');
            } else {
                consultasTab.classList.add('hidden');
                vacunasTab.classList.remove('hidden');
                
                consultasBtn.className = "border-transparent text-purple-600/60 hover:text-purple-800 font-extrabold border-b-2 py-4 px-1 text-sm transition-all focus:outline-none flex items-center gap-2";
                vacunasBtn.className = "border-purple-600 text-purple-700 font-extrabold border-b-2 py-4 px-1 text-sm transition-all focus:outline-none flex items-center gap-2";
                
                if (actionConsultaBtn) actionConsultaBtn.classList.add('hidden');
                if (actionVacunaBtn) actionVacunaBtn.classList.remove('hidden');
            }
        }
    </script>
</x-app-layout>
