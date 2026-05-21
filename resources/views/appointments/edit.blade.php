<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('appointments.show', $appointment) }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                ✏️ Editar Cita #{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 shadow-xl border border-gray-100 dark:border-gray-800/50">

                <div class="mb-6">
                    <h3 class="text-lg font-extrabold text-gray-800 dark:text-white">Actualizar Cita Veterinaria</h3>
                    <p class="text-sm text-gray-500 mt-1">Puedes cambiar el estado, fecha, veterinario asignado u otros detalles de la cita.</p>
                </div>

                @if($errors->any())
                    <div class="p-4 bg-rose-500/10 border border-rose-500/30 text-rose-600 dark:text-rose-400 rounded-2xl mb-6">
                        <ul class="list-disc list-inside space-y-1 text-sm font-medium">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('appointments.update', $appointment) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        {{-- Pet --}}
                        <div class="sm:col-span-2">
                            <label for="pet_id" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Mascota *</label>
                            <select id="pet_id" name="pet_id" required class="w-full rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-white text-sm px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                @foreach($pets as $pet)
                                    <option value="{{ $pet->id }}" {{ old('pet_id', $appointment->pet_id) == $pet->id ? 'selected' : '' }}>
                                        {{ $pet->name }} — {{ $pet->owner->name ?? 'Sin propietario' }} ({{ ucfirst($pet->species) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('pet_id') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        {{-- Veterinarian --}}
                        <div class="sm:col-span-2">
                            <label for="user_id" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Veterinario Asignado *</label>
                            <select id="user_id" name="user_id" required class="w-full rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-white text-sm px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                @foreach($vets as $vet)
                                    <option value="{{ $vet->id }}" {{ old('user_id', $appointment->user_id) == $vet->id ? 'selected' : '' }}>
                                        {{ $vet->name }} ({{ ucfirst($vet->role) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        {{-- Scheduled At --}}
                        <div>
                            <label for="scheduled_at" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Fecha y Hora *</label>
                            <input type="datetime-local" id="scheduled_at" name="scheduled_at"
                                value="{{ old('scheduled_at', \Carbon\Carbon::parse($appointment->scheduled_at)->format('Y-m-d\TH:i')) }}"
                                required
                                class="w-full rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-white text-sm px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                            @error('scheduled_at') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        {{-- Reason --}}
                        <div>
                            <label for="reason" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Motivo *</label>
                            <select id="reason" name="reason" required class="w-full rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-white text-sm px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                @foreach($reasons as $key => $label)
                                    <option value="{{ $key }}" {{ old('reason', $appointment->reason) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('reason') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="status" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Estado *</label>
                            <select id="status" name="status" required class="w-full rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-white text-sm px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $appointment->status) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        {{-- Notes --}}
                        <div class="sm:col-span-2">
                            <label for="notes" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Notas Adicionales (opcional)</label>
                            <textarea id="notes" name="notes" rows="3"
                                placeholder="Instrucciones especiales, síntomas previos, observaciones..."
                                class="w-full rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-white text-sm px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none">{{ old('notes', $appointment->notes) }}</textarea>
                            @error('notes') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-800">
                        <a href="{{ route('appointments.show', $appointment) }}" class="text-sm font-semibold text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-400 text-white font-bold text-sm rounded-xl shadow-md hover:shadow-lg hover:shadow-amber-500/20 transition-all">
                            <svg class="w-4 h-4 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
