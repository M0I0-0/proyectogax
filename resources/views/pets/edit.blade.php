<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-purple-950 leading-tight flex items-center gap-2.5">
            <a href="{{ route('pets.show', $pet) }}" class="inline-flex items-center justify-center h-10 w-10 bg-purple-50/80 hover:bg-purple-50 text-purple-700 rounded-full transition-all border border-[#e2d8f7] shadow-2xs" title="Regresar a la ficha">
                <svg class="h-5 w-5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <span>📝 {{ __('Editar Mascota') }}: <span class="text-purple-700">{{ $pet->name }}</span></span>
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2rem] shadow-3xs border border-[#e2d8f7] overflow-hidden">
                <div class="p-6 sm:p-8 border-b border-[#e2d8f7] bg-purple-50/10">
                    <h3 class="text-lg font-extrabold text-purple-950">Actualizar Ficha de la Mascota</h3>
                    <p class="text-xs text-gray-500 mt-1 font-semibold">Modifica los datos del paciente según sea necesario.</p>
                </div>

                <form action="{{ route('pets.update', $pet) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Owner ID -->
                        <div class="space-y-1.5">
                            <label for="owner_id" class="block text-xs sm:text-sm font-extrabold text-purple-950">Dueño / Responsable</label>
                            <select name="owner_id" id="owner_id" class="w-full px-4 py-3 rounded-xl border @error('owner_id') border-rose-400 focus:ring-rose-500/20 focus:border-rose-500 @else border-[#e2d8f7] focus:ring-purple-500/20 focus:border-purple-500 @enderror bg-white text-purple-950 transition-all focus:outline-none focus:ring-2 font-semibold" required>
                                <option value="" disabled>Selecciona un dueño...</option>
                                @foreach($owners as $owner)
                                    <option value="{{ $owner->id }}" {{ old('owner_id', $pet->owner_id) == $owner->id ? 'selected' : '' }}>
                                        {{ $owner->name }} ({{ $owner->phone }})
                                    </option>
                                @endforeach
                            </select>
                            @error('owner_id')
                                <p class="text-xs text-rose-600 font-extrabold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Pet Name -->
                        <div class="space-y-1.5">
                            <label for="name" class="block text-xs sm:text-sm font-extrabold text-purple-950">Nombre de la Mascota</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $pet->name) }}" class="w-full px-4 py-3 rounded-xl border @error('name') border-rose-400 focus:ring-rose-500/20 focus:border-rose-500 @else border-[#e2d8f7] focus:ring-purple-500/20 focus:border-purple-500 @enderror bg-white text-purple-950 placeholder-purple-300 transition-all focus:outline-none focus:ring-2 font-semibold" placeholder="Ej. Toby" required>
                            @error('name')
                                <p class="text-xs text-rose-600 font-extrabold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Species -->
                        <div class="space-y-1.5">
                            <label for="species" class="block text-xs sm:text-sm font-extrabold text-purple-950">Especie</label>
                            <select name="species" id="species" class="w-full px-4 py-3 rounded-xl border @error('species') border-rose-400 focus:ring-rose-500/20 focus:border-rose-500 @else border-[#e2d8f7] focus:ring-purple-500/20 focus:border-purple-500 @enderror bg-white text-purple-950 transition-all focus:outline-none focus:ring-2 font-semibold" required>
                                <option value="" disabled>Selecciona especie...</option>
                                <option value="perro" {{ old('species', $pet->species) == 'perro' ? 'selected' : '' }}>Perro 🐶</option>
                                <option value="gato" {{ old('species', $pet->species) == 'gato' ? 'selected' : '' }}>Gato 🐱</option>
                                <option value="ave" {{ old('species', $pet->species) == 'ave' ? 'selected' : '' }}>Ave 🦜</option>
                                <option value="conejo" {{ old('species', $pet->species) == 'conejo' ? 'selected' : '' }}>Conejo 🐰</option>
                                <option value="otro" {{ old('species', $pet->species) == 'otro' ? 'selected' : '' }}>Otro 🐾</option>
                            </select>
                            @error('species')
                                <p class="text-xs text-rose-600 font-extrabold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Breed -->
                        <div class="space-y-1.5">
                            <label for="breed" class="block text-xs sm:text-sm font-extrabold text-purple-950">Raza</label>
                            <input type="text" name="breed" id="breed" value="{{ old('breed', $pet->breed) }}" class="w-full px-4 py-3 rounded-xl border @error('breed') border-rose-400 focus:ring-rose-500/20 focus:border-rose-500 @else border-[#e2d8f7] focus:ring-purple-500/20 focus:border-purple-500 @enderror bg-white text-purple-950 placeholder-purple-300 transition-all focus:outline-none focus:ring-2 font-semibold" placeholder="Ej. Golden Retriever" required>
                            @error('breed')
                                <p class="text-xs text-rose-600 font-extrabold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Birthdate -->
                        <div class="space-y-1.5">
                            <label for="birthdate" class="block text-xs sm:text-sm font-extrabold text-purple-950">Fecha de Nacimiento</label>
                            <input type="date" name="birthdate" id="birthdate" max="{{ date('Y-m-d') }}" value="{{ old('birthdate', \Carbon\Carbon::parse($pet->birthdate)->format('Y-m-d')) }}" class="w-full px-4 py-3 rounded-xl border @error('birthdate') border-rose-400 focus:ring-rose-500/20 focus:border-rose-500 @else border-[#e2d8f7] focus:ring-purple-500/20 focus:border-purple-500 @enderror bg-white text-purple-950 transition-all focus:outline-none focus:ring-2 font-semibold" required>
                            @error('birthdate')
                                <p class="text-xs text-rose-600 font-extrabold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Weight -->
                        <div class="space-y-1.5">
                            <label for="weight" class="block text-xs sm:text-sm font-extrabold text-purple-950">Peso (kg)</label>
                            <input type="number" name="weight" id="weight" step="0.01" min="0.01" value="{{ old('weight', $pet->weight) }}" class="w-full px-4 py-3 rounded-xl border @error('weight') border-rose-400 focus:ring-rose-500/20 focus:border-rose-500 @else border-[#e2d8f7] focus:ring-purple-500/20 focus:border-purple-500 @enderror bg-white text-purple-950 placeholder-purple-300 transition-all focus:outline-none focus:ring-2 font-semibold" placeholder="Ej. 12.5" required>
                            @error('weight')
                                <p class="text-xs text-rose-600 font-extrabold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Photo Upload with Preview -->
                    <div class="space-y-3">
                        <label class="block text-xs sm:text-sm font-extrabold text-purple-950">Foto de la Mascota</label>
                        
                        <div class="flex items-center gap-5 flex-col sm:flex-row">
                            @if($pet->photo)
                                <div class="relative group self-center">
                                    <div class="bg-gradient-to-tr from-purple-200 to-indigo-300 p-0.5 rounded-[1.2rem] shadow-sm">
                                        <img src="{{ asset('storage/' . $pet->photo) }}" class="h-20 w-20 rounded-[1.1rem] object-cover border-2 border-white shadow-2xs transition-all group-hover:scale-105" alt="Foto actual">
                                    </div>
                                    <span class="absolute -top-2.5 -right-2.5 px-2 py-0.5 rounded-full text-[3xs] font-black bg-purple-600 text-white shadow-sm border border-[#e2d8f7]">Actual</span>
                                </div>
                            @else
                                <div class="h-20 w-20 rounded-2xl bg-purple-50 text-purple-500 border border-dashed border-[#e2d8f7] flex items-center justify-center font-bold text-2xl shadow-3xs self-center">
                                    🐾
                                </div>
                            @endif

                            <div class="flex-1 w-full">
                                <div class="flex justify-center px-6 pt-4 pb-5 border-2 border-dashed border-[#e2d8f7] hover:border-purple-300 rounded-[1.5rem] bg-purple-50/10 hover:bg-purple-50/20 transition-all">
                                    <div class="space-y-1.5 text-center">
                                        <svg class="mx-auto h-9 w-9 text-purple-600/40" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-purple-950 justify-center">
                                            <label for="photo" class="relative cursor-pointer rounded-md font-extrabold text-purple-600 hover:text-purple-800 focus-within:outline-none focus-within:ring-2 focus-within:ring-purple-500">
                                                <span>Sube una nueva foto</span>
                                                <input id="photo" name="photo" type="file" class="sr-only">
                                            </label>
                                            <p class="pl-1 font-semibold">o arrastra</p>
                                        </div>
                                        <p class="text-3xs text-gray-500 font-semibold">Dejar vacío para conservar la foto actual</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @error('photo')
                            <p class="text-xs text-rose-600 font-extrabold mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-[#e2d8f7]">
                        <a href="{{ route('pets.show', $pet) }}" class="px-5 py-3 border border-[#e2d8f7] text-purple-700 font-extrabold rounded-xl hover:bg-purple-50 transition-all text-sm shadow-2xs">
                            Cancelar
                        </a>
                        <button type="submit" class="px-5 py-3 bg-gradient-to-r from-purple-500 via-indigo-500 to-purple-600 hover:from-purple-600 hover:to-indigo-600 text-white font-extrabold rounded-xl shadow-md transition-all text-sm hover:-translate-y-0.5">
                            Actualizar Mascota
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
