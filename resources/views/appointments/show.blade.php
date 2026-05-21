<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight flex items-center gap-2">
                <a href="{{ route('appointments.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                Cita #{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}
            </h2>
            @if(Auth::user()->role !== 'recepcionista')
                <div class="flex gap-3">
                    <a href="{{ route('appointments.edit', $appointment) }}" class="inline-flex items-center px-4 py-2 border border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-300 font-semibold text-sm rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors shadow-sm">
                        ✏️ Editar Cita
                    </a>
                    <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta cita permanentemente?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-rose-200 dark:border-rose-900/50 text-rose-600 dark:text-rose-400 font-semibold text-sm rounded-xl hover:bg-rose-50 dark:hover:bg-rose-950/20 transition-all shadow-sm">
                            🗑 Eliminar
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('message'))
                <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center gap-3">
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-semibold">{{ session('message') }}</span>
                </div>
            @endif

            @php
                $statusColors = [
                    'pendiente'  => ['bg' => 'bg-amber-500/10', 'text' => 'text-amber-500', 'border' => 'border-amber-500/20'],
                    'confirmada' => ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-500', 'border' => 'border-emerald-500/20'],
                    'completada' => ['bg' => 'bg-blue-500/10', 'text' => 'text-blue-500', 'border' => 'border-blue-500/20'],
                    'cancelada'  => ['bg' => 'bg-rose-500/10', 'text' => 'text-rose-500', 'border' => 'border-rose-500/20'],
                ];
                $sc = $statusColors[$appointment->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-500', 'border' => 'border-gray-200'];
            @endphp

            {{-- Main appointment card --}}
            <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 shadow-xl border border-gray-100 dark:border-gray-800/50">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-6">
                    <div class="space-y-1">
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-500 flex items-center justify-center text-2xl">
                                📅
                            </div>
                            <div>
                                <h3 class="text-2xl font-extrabold text-gray-800 dark:text-white">
                                    {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('d/m/Y') }}
                                </h3>
                                <p class="text-sm text-gray-400 font-bold">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('H:i') }} hrs · {{ \App\Models\Appointment::$reasons[$appointment->reason] ?? $appointment->reason }}</p>
                            </div>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-extrabold border {{ $sc['bg'] }} {{ $sc['text'] }} {{ $sc['border'] }}">
                        {{ \App\Models\Appointment::$statuses[$appointment->status] ?? $appointment->status }}
                    </span>
                </div>

                <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-6 pt-6 border-t border-gray-100 dark:border-gray-800/50">
                    {{-- Pet --}}
                    <div>
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block mb-2">Mascota</span>
                        <a href="{{ route('pets.show', $appointment->pet) }}" class="font-extrabold text-indigo-600 dark:text-indigo-400 hover:underline text-base">
                            {{ $appointment->pet->name }}
                        </a>
                        <p class="text-xs text-gray-500 mt-0.5 capitalize">{{ $appointment->pet->species }} · {{ $appointment->pet->breed }}</p>
                    </div>
                    {{-- Vet --}}
                    <div>
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block mb-2">Veterinario</span>
                        <p class="font-bold text-gray-800 dark:text-white">{{ $appointment->veterinarian->name }}</p>
                        <p class="text-xs text-gray-500 mt-0.5 capitalize">{{ $appointment->veterinarian->role }}</p>
                    </div>
                    {{-- Owner --}}
                    <div>
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block mb-2">Propietario</span>
                        @if($appointment->pet->owner)
                            <a href="{{ route('owners.show', $appointment->pet->owner) }}" class="font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                                {{ $appointment->pet->owner->name }}
                            </a>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $appointment->pet->owner->phone }}</p>
                        @else
                            <span class="text-gray-500 text-sm">Sin propietario</span>
                        @endif
                    </div>
                </div>

                @if($appointment->notes)
                    <div class="mt-6 p-4 bg-amber-50/50 dark:bg-amber-950/10 border border-amber-100 dark:border-amber-900/20 rounded-2xl">
                        <span class="text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider block mb-2">📝 Notas</span>
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $appointment->notes }}</p>
                    </div>
                @endif

                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800/30 flex items-center gap-4">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Recordatorio enviado:</span>
                    @if($appointment->reminder_sent)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">✅ Sí</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400 border border-gray-200 dark:border-gray-700">⏳ Pendiente</span>
                    @endif
                    <span class="text-xs text-gray-400">· Creada {{ \Carbon\Carbon::parse($appointment->created_at)->diffForHumans() }}</span>
                </div>
            </div>

            {{-- Notification logs --}}
            @if($appointment->notificationLogs->count() > 0)
                <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 shadow-xl border border-gray-100 dark:border-gray-800/50">
                    <h4 class="font-extrabold text-gray-800 dark:text-white mb-5 flex items-center gap-2">
                        <span class="h-8 w-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-500 flex items-center justify-center text-sm">📧</span>
                        Registro de Notificaciones
                    </h4>
                    <div class="space-y-3">
                        @foreach($appointment->notificationLogs as $log)
                            <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50/50 dark:bg-gray-950/20 border border-gray-100 dark:border-gray-800/40 text-sm">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                        {{ $log->status === 'sent' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 border border-rose-200 dark:border-rose-500/20' }}">
                                        {{ $log->status === 'sent' ? '✅ Enviado' : '❌ Fallido' }}
                                    </span>
                                    <span class="font-bold text-gray-700 dark:text-gray-300 capitalize">{{ $log->type }}</span>
                                    <span class="text-gray-400">→ {{ $log->recipient_email }}</span>
                                </div>
                                <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
