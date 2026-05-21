<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight flex items-center gap-2">
            <a href="{{ route('pets.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            {{ __('Registrar Nueva Mascota') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800/50 overflow-hidden">
                <div class="p-8 border-b border-gray-100 dark:border-gray-800/50 bg-gray-50/50 dark:bg-gray-950/20">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Ficha de Identificación de la Mascota</h3>
                    <p class="text-xs text-gray-500 mt-1">Ingresa los datos generales del paciente veterinario.</p>
                </div>

                <form action="{{ route('pets.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Owner ID -->
                        <div class="space-y-2">
                            <label for="owner_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Dueño / Responsable</label>
                            <select name="owner_id" id="owner_id" class="w-full px-4 py-3 rounded-xl border @error('owner_id') border-rose-500 focus:ring-rose-500 focus:border-rose-500 @else border-gray-200 dark:border-gray-800 focus:ring-emerald-500 focus:border-emerald-500 @enderror bg-gray-50/50 dark:bg-gray-950/50 text-gray-800 dark:text-gray-100 transition-all focus:outline-none focus:ring-2" required>
                                <option value="" disabled selected>Selecciona un dueño...</option>
                                @foreach($owners as $owner)
                                    <option value="{{ $owner->id }}" {{ old('owner_id', request()->query('owner_id')) == $owner->id ? 'selected' : '' }}>
                                        {{ $owner->name }} ({{ $owner->phone }})
                                    </option>
                                @endforeach
                            </select>
                            @error('owner_id')
                                <p class="text-xs text-rose-500 font-semibold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Pet Name -->
                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Nombre de la Mascota</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full px-4 py-3 rounded-xl border @error('name') border-rose-500 focus:ring-rose-500 focus:border-rose-500 @else border-gray-200 dark:border-gray-800 focus:ring-emerald-500 focus:border-emerald-500 @enderror bg-gray-50/50 dark:bg-gray-950/50 text-gray-800 dark:text-gray-100 placeholder-gray-400 transition-all focus:outline-none focus:ring-2" placeholder="Ej. Toby" required>
                            @error('name')
                                <p class="text-xs text-rose-500 font-semibold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Species -->
                        <div class="space-y-2">
                            <label for="species" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Especie</label>
                            <select name="species" id="species" class="w-full px-4 py-3 rounded-xl border @error('species') border-rose-500 focus:ring-rose-500 focus:border-rose-500 @else border-gray-200 dark:border-gray-800 focus:ring-emerald-500 focus:border-emerald-500 @enderror bg-gray-50/50 dark:bg-gray-950/50 text-gray-800 dark:text-gray-100 transition-all focus:outline-none focus:ring-2" required>
                                <option value="" disabled selected>Selecciona especie...</option>
                                <option value="perro" {{ old('species') == 'perro' ? 'selected' : '' }}>Perro</option>
                                <option value="gato" {{ old('species') == 'gato' ? 'selected' : '' }}>Gato</option>
                                <option value="ave" {{ old('species') == 'ave' ? 'selected' : '' }}>Ave</option>
                                <option value="conejo" {{ old('species') == 'conejo' ? 'selected' : '' }}>Conejo</option>
                                <option value="otro" {{ old('species') == 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('species')
                                <p class="text-xs text-rose-500 font-semibold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Breed -->
                        <div class="space-y-2">
                            <label for="breed" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Raza</label>
                            <input type="text" name="breed" id="breed" value="{{ old('breed') }}" class="w-full px-4 py-3 rounded-xl border @error('breed') border-rose-500 focus:ring-rose-500 focus:border-rose-500 @else border-gray-200 dark:border-gray-800 focus:ring-emerald-500 focus:border-emerald-500 @enderror bg-gray-50/50 dark:bg-gray-950/50 text-gray-800 dark:text-gray-100 placeholder-gray-400 transition-all focus:outline-none focus:ring-2" placeholder="Ej. Golden Retriever" required>
                            @error('breed')
                                <p class="text-xs text-rose-500 font-semibold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Birthdate -->
                        <div class="space-y-2">
                            <label for="birthdate" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Fecha de Nacimiento</label>
                            <input type="date" name="birthdate" id="birthdate" max="{{ date('Y-m-d') }}" value="{{ old('birthdate') }}" class="w-full px-4 py-3 rounded-xl border @error('birthdate') border-rose-500 focus:ring-rose-500 focus:border-rose-500 @else border-gray-200 dark:border-gray-800 focus:ring-emerald-500 focus:border-emerald-500 @enderror bg-gray-50/50 dark:bg-gray-950/50 text-gray-800 dark:text-gray-100 transition-all focus:outline-none focus:ring-2" required>
                            @error('birthdate')
                                <p class="text-xs text-rose-500 font-semibold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Weight -->
                        <div class="space-y-2">
                            <label for="weight" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Peso (kg)</label>
                            <input type="number" name="weight" id="weight" step="0.01" min="0.01" value="{{ old('weight') }}" class="w-full px-4 py-3 rounded-xl border @error('weight') border-rose-500 focus:ring-rose-500 focus:border-rose-500 @else border-gray-200 dark:border-gray-800 focus:ring-emerald-500 focus:border-emerald-500 @enderror bg-gray-50/50 dark:bg-gray-950/50 text-gray-800 dark:text-gray-100 placeholder-gray-400 transition-all focus:outline-none focus:ring-2" placeholder="Ej. 12.5" required>
                            @error('weight')
                                <p class="text-xs text-rose-500 font-semibold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Photo Upload -->
                    <div class="space-y-2">
                        <label for="photo" class="block text-sm font-bold text-gray-700 dark:text-gray-300">Foto de la Mascota (Opcional)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-200 dark:border-gray-800/80 rounded-2xl bg-gray-50/50 dark:bg-gray-950/30">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                    <label for="photo" class="relative cursor-pointer rounded-md font-bold text-emerald-600 dark:text-emerald-400 hover:text-emerald-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-emerald-500">
                                        <span>Sube un archivo</span>
                                        <input id="photo" name="photo" type="file" class="sr-only">
                                    </label>
                                    <p class="pl-1">o arrastra y suelta</p>
                                </div>
                                <p class="text-3xs text-gray-400">PNG, JPG, WEBP de hasta 2MB</p>
                            </div>
                        </div>
                        @error('photo')
                            <p class="text-xs text-rose-500 font-semibold mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-4 pt-4 border-t border-gray-100 dark:border-gray-800/50">
                        <a href="{{ route('pets.index') }}" class="px-6 py-3 border border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-850 font-bold transition-all text-sm">
                            Cancelar
                        </a>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl shadow-lg shadow-emerald-500/20 font-bold transition-all hover:-translate-y-0.5 text-sm">
                            Guardar Mascota
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
