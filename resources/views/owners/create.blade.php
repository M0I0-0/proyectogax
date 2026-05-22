<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-purple-950 leading-tight flex items-center gap-2.5">
            <a href="{{ route('owners.index') }}" class="inline-flex items-center justify-center h-10 w-10 bg-purple-50/80 hover:bg-purple-50 text-purple-700 rounded-full transition-all border border-[#e2d8f7] shadow-2xs" title="Regresar al listado">
                <svg class="h-5 w-5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <span>👤 {{ __('Registrar Nuevo Dueño') }}</span>
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2rem] border border-[#e2d8f7] shadow-3xs overflow-hidden">
                <div class="p-6 sm:p-8 border-b border-[#e2d8f7] bg-purple-50/10">
                    <h3 class="text-lg font-extrabold text-purple-950">Información de Contacto del Dueño</h3>
                    <p class="text-xs text-gray-500 mt-1 font-semibold">Completa todos los campos para registrar un nuevo dueño en VetCare.</p>
                </div>

                <form action="{{ route('owners.store') }}" method="POST" class="p-6 sm:p-8 space-y-5">
                    @csrf

                    <!-- Name -->
                    <div class="space-y-1.5">
                        <label for="name" class="block text-xs sm:text-sm font-extrabold text-purple-950">Nombre Completo</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full px-4 py-3 rounded-xl border @error('name') border-rose-400 focus:ring-rose-500/20 focus:border-rose-500 @else border-[#e2d8f7] focus:ring-purple-500/20 focus:border-purple-500 @enderror bg-white text-purple-950 placeholder-purple-300 transition-all focus:outline-none focus:ring-2 font-semibold" placeholder="Ej. Juan Pérez" required>
                        @error('name')
                            <p class="text-xs text-rose-600 font-extrabold mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Email -->
                        <div class="space-y-1.5">
                            <label for="email" class="block text-xs sm:text-sm font-extrabold text-purple-950">Correo Electrónico</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full px-4 py-3 rounded-xl border @error('email') border-rose-400 focus:ring-rose-500/20 focus:border-rose-500 @else border-[#e2d8f7] focus:ring-purple-500/20 focus:border-purple-500 @enderror bg-white text-purple-950 placeholder-purple-300 transition-all focus:outline-none focus:ring-2 font-semibold" placeholder="juan.perez@ejemplo.com" required>
                            @error('email')
                                <p class="text-xs text-rose-600 font-extrabold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="space-y-1.5">
                            <label for="phone" class="block text-xs sm:text-sm font-extrabold text-purple-950">Teléfono</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="w-full px-4 py-3 rounded-xl border @error('phone') border-rose-400 focus:ring-rose-500/20 focus:border-rose-500 @else border-[#e2d8f7] focus:ring-purple-500/20 focus:border-purple-500 @enderror bg-white text-purple-955 placeholder-purple-300 transition-all focus:outline-none focus:ring-2 font-semibold" placeholder="+56 9 1234 5678" required>
                            @error('phone')
                                <p class="text-xs text-rose-600 font-extrabold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="space-y-1.5">
                        <label for="address" class="block text-xs sm:text-sm font-extrabold text-purple-950">Dirección</label>
                        <textarea name="address" id="address" rows="4" class="w-full px-4 py-3 rounded-xl border @error('address') border-rose-400 focus:ring-rose-500/20 focus:border-rose-500 @else border-[#e2d8f7] focus:ring-purple-500/20 focus:border-purple-500 @enderror bg-white text-purple-950 placeholder-purple-300 transition-all focus:outline-none focus:ring-2 font-semibold resize-none" placeholder="Ingresa la dirección completa del dueño..." required>{{ old('address') }}</textarea>
                        @error('address')
                            <p class="text-xs text-rose-600 font-extrabold mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-[#e2d8f7]">
                        <a href="{{ route('owners.index') }}" class="px-5 py-3 border border-[#e2d8f7] text-purple-700 font-extrabold rounded-xl hover:bg-purple-50 transition-all text-sm shadow-2xs">
                            Cancelar
                        </a>
                        <button type="submit" class="px-5 py-3 bg-gradient-to-r from-purple-500 via-indigo-500 to-purple-600 hover:from-purple-600 hover:to-indigo-600 text-white font-extrabold rounded-xl shadow-md transition-all text-sm hover:-translate-y-0.5">
                            Guardar Dueño
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
