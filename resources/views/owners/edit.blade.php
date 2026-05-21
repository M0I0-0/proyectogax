<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight flex items-center gap-2">
            <a href="{{ route('owners.show', $owner) }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            {{ __('Editar Dueño: ' . $owner->name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800/50 overflow-hidden">
                <div class="p-8 border-b border-gray-100 dark:border-gray-800/50 bg-gray-50/50 dark:bg-gray-950/20">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Actualizar Información de Contacto</h3>
                    <p class="text-xs text-gray-500 mt-1">Edita los campos necesarios. El email debe seguir siendo único en el sistema.</p>
                </div>

                <form action="{{ route('owners.update', $owner) }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Nombre Completo</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $owner->name) }}" class="w-full px-4 py-3 rounded-xl border @error('name') border-rose-500 focus:ring-rose-500 focus:border-rose-500 @else border-gray-200 dark:border-gray-800 focus:ring-emerald-500 focus:border-emerald-500 @enderror bg-gray-50/50 dark:bg-gray-950/50 text-gray-800 dark:text-gray-100 placeholder-gray-400 transition-all focus:outline-none focus:ring-2" placeholder="Ej. Juan Pérez" required>
                        @error('name')
                            <p class="text-xs text-rose-500 font-semibold mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Correo Electrónico</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $owner->email) }}" class="w-full px-4 py-3 rounded-xl border @error('email') border-rose-500 focus:ring-rose-500 focus:border-rose-500 @else border-gray-200 dark:border-gray-800 focus:ring-emerald-500 focus:border-emerald-500 @enderror bg-gray-50/50 dark:bg-gray-950/50 text-gray-800 dark:text-gray-100 placeholder-gray-400 transition-all focus:outline-none focus:ring-2" placeholder="juan.perez@ejemplo.com" required>
                            @error('email')
                                <p class="text-xs text-rose-500 font-semibold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="space-y-2">
                            <label for="phone" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Teléfono</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $owner->phone) }}" class="w-full px-4 py-3 rounded-xl border @error('phone') border-rose-500 focus:ring-rose-500 focus:border-rose-500 @else border-gray-200 dark:border-gray-800 focus:ring-emerald-500 focus:border-emerald-500 @enderror bg-gray-50/50 dark:bg-gray-950/50 text-gray-800 dark:text-gray-100 placeholder-gray-400 transition-all focus:outline-none focus:ring-2" placeholder="+56 9 1234 5678" required>
                            @error('phone')
                                <p class="text-xs text-rose-500 font-semibold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="space-y-2">
                        <label for="address" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Dirección</label>
                        <textarea name="address" id="address" rows="4" class="w-full px-4 py-3 rounded-xl border @error('address') border-rose-500 focus:ring-rose-500 focus:border-rose-500 @else border-gray-200 dark:border-gray-800 focus:ring-emerald-500 focus:border-emerald-500 @enderror bg-gray-50/50 dark:bg-gray-950/50 text-gray-800 dark:text-gray-100 placeholder-gray-400 transition-all focus:outline-none focus:ring-2 resize-none" placeholder="Ingresa la dirección completa del dueño..." required>{{ old('address', $owner->address) }}</textarea>
                        @error('address')
                            <p class="text-xs text-rose-500 font-semibold mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-4 pt-4 border-t border-gray-100 dark:border-gray-800/50">
                        <a href="{{ route('owners.show', $owner) }}" class="px-6 py-3 border border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-850 font-bold transition-all text-sm">
                            Cancelar
                        </a>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl shadow-lg shadow-emerald-500/20 font-bold transition-all hover:-translate-y-0.5 text-sm">
                            Actualizar Dueño
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
