<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('appointments.show', $appointment) }}" class="text-purple-500 hover:text-purple-700 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-extrabold text-2xl text-purple-950 leading-tight">
                ✏️ Editar Cita #{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-[#e2d8f7] shadow-3xs rounded-[2rem] p-8">

                <div class="mb-6">
                    <h3 class="text-lg font-extrabold text-purple-950">Actualizar Cita Veterinaria</h3>
                    <p class="text-xs text-purple-700/60 mt-1">Puedes cambiar el estado de la cita, reprogramar la fecha/hora, reasignar el veterinario u otros detalles.</p>
                </div>

                @if($errors->any())
                    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl mb-6">
                        <ul class="list-disc list-inside space-y-1 text-sm font-semibold">
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
                            <label for="pet_id" class="block text-xs font-bold text-purple-950/80 uppercase tracking-wider mb-2">Mascota *</label>
                            <select id="pet_id" name="pet_id" required class="w-full rounded-2xl bg-[#fcfbfe]/50 border border-[#e2d8f7] text-purple-950 text-sm px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all font-semibold">
                                @foreach($pets as $pet)
                                    <option value="{{ $pet->id }}" {{ old('pet_id', $appointment->pet_id) == $pet->id ? 'selected' : '' }}>
                                        🐾 {{ $pet->name }} — 👤 Dueño: {{ $pet->owner->name ?? 'Sin propietario' }} ({{ ucfirst($pet->species) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('pet_id') <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        {{-- Veterinarian --}}
                        <div class="sm:col-span-2">
                            <label for="user_id" class="block text-xs font-bold text-purple-950/80 uppercase tracking-wider mb-2">Veterinario Asignado *</label>
                            <select id="user_id" name="user_id" required class="w-full rounded-2xl bg-[#fcfbfe]/50 border border-[#e2d8f7] text-purple-950 text-sm px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all font-semibold">
                                @foreach($vets as $vet)
                                    <option value="{{ $vet->id }}" {{ old('user_id', $appointment->user_id) == $vet->id ? 'selected' : '' }}>
                                        🥼 {{ $vet->name }} ({{ ucfirst($vet->role) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id') <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        {{-- Scheduled At --}}
                        <div>
                            <label for="scheduled_at" class="block text-xs font-bold text-purple-950/80 uppercase tracking-wider mb-2">Fecha y Hora *</label>
                            <input type="datetime-local" id="scheduled_at" name="scheduled_at"
                                value="{{ old('scheduled_at', \Carbon\Carbon::parse($appointment->scheduled_at)->format('Y-m-d\TH:i')) }}"
                                required
                                class="w-full rounded-2xl bg-[#fcfbfe]/50 border border-[#e2d8f7] text-purple-950 text-sm px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all font-semibold">
                            @error('scheduled_at') <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        {{-- Reason --}}
                        <div>
                            <label for="reason" class="block text-xs font-bold text-purple-950/80 uppercase tracking-wider mb-2">Motivo *</label>
                            <select id="reason" name="reason" required class="w-full rounded-2xl bg-[#fcfbfe]/50 border border-[#e2d8f7] text-purple-950 text-sm px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all font-semibold">
                                @foreach($reasons as $key => $label)
                                    <option value="{{ $key }}" {{ old('reason', $appointment->reason) === $key ? 'selected' : '' }}>📋 {{ $label }}</option>
                                @endforeach
                            </select>
                            @error('reason') <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="status" class="block text-xs font-bold text-purple-950/80 uppercase tracking-wider mb-2">Estado *</label>
                            <select id="status" name="status" required class="w-full rounded-2xl bg-[#fcfbfe]/50 border border-[#e2d8f7] text-purple-950 text-sm px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all font-semibold">
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $appointment->status) === $key ? 'selected' : '' }}>🔔 {{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status') <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        {{-- Notes --}}
                        <div class="sm:col-span-2">
                            <label for="notes" class="block text-xs font-bold text-purple-950/80 uppercase tracking-wider mb-2">Notas Adicionales (opcional)</label>
                            <textarea id="notes" name="notes" rows="3"
                                placeholder="Instrucciones especiales, síntomas previos, observaciones..."
                                class="w-full rounded-2xl bg-[#fcfbfe]/50 border border-[#e2d8f7] text-purple-950 text-sm px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all resize-none font-semibold">{{ old('notes', $appointment->notes) }}</textarea>
                            @error('notes') <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-5 border-t border-[#e2d8f7]/50">
                        <a href="{{ route('appointments.show', $appointment) }}" class="inline-flex items-center px-4 py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200/50 font-bold text-sm rounded-2xl transition-all shadow-sm">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 via-indigo-500 to-purple-600 hover:from-purple-600 hover:to-indigo-600 text-white font-bold text-sm rounded-2xl hover:shadow-lg hover:shadow-purple-500/20 transform hover:-translate-y-0.5 transition-all duration-150 shadow-sm">
                            <svg class="w-4.5 h-4.5 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
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
