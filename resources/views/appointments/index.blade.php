<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight flex items-center gap-2">
                📅 Agenda de Citas
            </h2>
            @if(Auth::user()->role !== 'recepcionista')
                <a href="{{ route('appointments.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm rounded-xl transition-all shadow-md hover:shadow-lg hover:shadow-indigo-600/20">
                    <svg class="w-4 h-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Nueva Cita
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('message'))
                <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center gap-3">
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-semibold">{{ session('message') }}</span>
                </div>
            @endif

            {{-- Stats bar --}}
            @php
                $total     = $appointments->total();
                $pendiente = \App\Models\Appointment::where('status','pendiente')->count();
                $confirmada = \App\Models\Appointment::where('status','confirmada')->count();
                $hoy       = \App\Models\Appointment::whereDate('scheduled_at', today())->count();
            @endphp
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 border border-gray-100 dark:border-gray-800/50 shadow text-center">
                    <div class="text-3xl font-black text-gray-800 dark:text-white">{{ $total }}</div>
                    <div class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Total Citas</div>
                </div>
                <div class="bg-amber-50 dark:bg-amber-950/20 rounded-2xl p-5 border border-amber-200/60 dark:border-amber-800/30 shadow text-center">
                    <div class="text-3xl font-black text-amber-600 dark:text-amber-400">{{ $pendiente }}</div>
                    <div class="text-xs text-amber-500 font-bold uppercase tracking-wider mt-1">Pendientes</div>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-950/20 rounded-2xl p-5 border border-emerald-200/60 dark:border-emerald-800/30 shadow text-center">
                    <div class="text-3xl font-black text-emerald-600 dark:text-emerald-400">{{ $confirmada }}</div>
                    <div class="text-xs text-emerald-500 font-bold uppercase tracking-wider mt-1">Confirmadas</div>
                </div>
                <div class="bg-indigo-50 dark:bg-indigo-950/20 rounded-2xl p-5 border border-indigo-200/60 dark:border-indigo-800/30 shadow text-center">
                    <div class="text-3xl font-black text-indigo-600 dark:text-indigo-400">{{ $hoy }}</div>
                    <div class="text-xs text-indigo-500 font-bold uppercase tracking-wider mt-1">Hoy</div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800/50 overflow-hidden">
                @if($appointments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-950/50 text-gray-500 dark:text-gray-400 text-xs font-bold border-b border-gray-100 dark:border-gray-800/50">
                                    <th class="px-6 py-4">Fecha / Hora</th>
                                    <th class="px-6 py-4">Mascota</th>
                                    <th class="px-6 py-4">Propietario</th>
                                    <th class="px-6 py-4">Veterinario</th>
                                    <th class="px-6 py-4">Motivo</th>
                                    <th class="px-6 py-4">Estado</th>
                                    <th class="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50 text-sm">
                                @foreach($appointments as $appt)
                                    @php
                                        $statusColors = [
                                            'pendiente'  => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border-amber-100 dark:border-amber-500/20',
                                            'confirmada' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border-emerald-100 dark:border-emerald-500/20',
                                            'completada' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 border-blue-100 dark:border-blue-500/20',
                                            'cancelada'  => 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 border-rose-100 dark:border-rose-500/20',
                                        ];
                                        $color = $statusColors[$appt->status] ?? 'bg-gray-100 text-gray-600';
                                        $isPast = $appt->scheduled_at->isPast();
                                    @endphp
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-950/30 transition-colors {{ $isPast && $appt->status === 'pendiente' ? 'opacity-60' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-bold text-gray-800 dark:text-white text-sm">{{ $appt->scheduled_at->format('d/m/Y') }}</div>
                                            <div class="text-xs text-gray-400 font-medium">{{ $appt->scheduled_at->format('H:i') }} hrs</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('pets.show', $appt->pet) }}" class="font-bold text-indigo-600 dark:text-indigo-400 hover:underline">{{ $appt->pet->name }}</a>
                                            <div class="text-xs text-gray-400 capitalize">{{ $appt->pet->species }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-700 dark:text-gray-300 font-medium text-xs">{{ $appt->pet->owner->name ?? '—' }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-700 dark:text-gray-300 font-medium text-xs">{{ $appt->veterinarian->name }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-xs font-semibold text-gray-600 dark:text-gray-400">
                                                {{ \App\Models\Appointment::$reasons[$appt->reason] ?? $appt->reason }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $color }}">
                                                {{ \App\Models\Appointment::$statuses[$appt->status] ?? $appt->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            <a href="{{ route('appointments.show', $appt) }}" class="text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-bold text-xs me-3">Ver</a>
                                            @if(Auth::user()->role !== 'recepcionista')
                                                <a href="{{ route('appointments.edit', $appt) }}" class="text-gray-500 hover:text-amber-600 dark:hover:text-amber-400 transition-colors font-bold text-xs me-3">Editar</a>
                                                <form action="{{ route('appointments.destroy', $appt) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta cita?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-gray-500 hover:text-rose-600 dark:hover:text-rose-400 transition-colors font-bold text-xs">Eliminar</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800/50">
                        {{ $appointments->links() }}
                    </div>
                @else
                    <div class="py-20 text-center">
                        <div class="h-20 w-20 bg-indigo-50 dark:bg-indigo-950/20 text-indigo-300 rounded-full flex items-center justify-center text-4xl mx-auto shadow-inner mb-5">📅</div>
                        <h3 class="font-bold text-gray-800 dark:text-white text-lg mb-2">Sin Citas Programadas</h3>
                        <p class="text-sm text-gray-500 mb-6 max-w-sm mx-auto">No hay citas en el sistema todavía. ¡Crea la primera cita para comenzar a gestionar la agenda!</p>
                        @if(Auth::user()->role !== 'recepcionista')
                            <a href="{{ route('appointments.create') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm rounded-xl shadow-md transition-all">
                                + Programar Primera Cita
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
