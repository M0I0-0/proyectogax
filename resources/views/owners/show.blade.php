<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight flex items-center gap-2">
                <a href="{{ route('owners.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                {{ __('Perfil del Dueño') }}
            </h2>
            <div class="flex gap-3">
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'recepcionista')
                    <a href="{{ route('owners.edit', $owner) }}" class="inline-flex items-center px-4 py-2 border border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-300 font-semibold text-sm rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors shadow-sm">
                        <svg class="w-4.5 h-4.5 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar Datos
                    </a>
                    <a href="{{ route('pets.create', ['owner_id' => $owner->id]) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold text-sm rounded-xl hover:shadow-lg hover:shadow-emerald-500/20 transform hover:-translate-y-0.5 transition-all duration-150">
                        <svg class="w-4.5 h-4.5 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Asociar Mascota
                    </a>
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

            <!-- Owner Card Info -->
            <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 shadow-xl border border-gray-100 dark:border-gray-800/50 flex flex-col md:flex-row gap-8 relative overflow-hidden">
                <div class="absolute -right-24 -bottom-24 w-80 h-80 bg-gradient-to-tr from-emerald-500/5 to-teal-500/5 rounded-full blur-3xl"></div>
                
                <!-- Avatar circle representing owner -->
                <div class="h-20 w-20 rounded-2xl bg-gradient-to-tr from-emerald-400 to-teal-600 flex items-center justify-center text-white text-3xl font-extrabold shadow-lg shadow-emerald-500/20 self-start shrink-0">
                    {{ strtoupper(substr($owner->name, 0, 2)) }}
                </div>
                
                <div class="space-y-4 flex-1">
                    <div>
                        <h3 class="text-2xl font-extrabold text-gray-800 dark:text-white tracking-tight">{{ $owner->name }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Cliente de VetCare</p>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-4 border-t border-gray-100 dark:border-gray-800/50">
                        <div class="space-y-0.5">
                            <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Correo Electrónico</span>
                            <span class="block text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $owner->email }}</span>
                        </div>
                        <div class="space-y-0.5">
                            <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Teléfono de Contacto</span>
                            <span class="block text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $owner->phone }}</span>
                        </div>
                        <div class="space-y-0.5">
                            <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Dirección Postal</span>
                            <span class="block text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $owner->address }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pets section -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                    Mascotas Asociadas
                </h3>

                @if($owner->pets->isEmpty())
                    <div class="bg-white dark:bg-gray-900 rounded-3xl p-12 shadow-xl border border-gray-100 dark:border-gray-800/50 text-center">
                        <div class="h-16 w-16 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-400 mx-auto mb-4 border border-slate-200/20">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-700 dark:text-gray-300">Este dueño no tiene mascotas registradas</h4>
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'recepcionista')
                            <p class="text-xs text-gray-500 mt-1 mb-6">Asocia una mascota para gestionar sus consultas médicas y vacunas.</p>
                            <a href="{{ route('pets.create', ['owner_id' => $owner->id]) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold text-sm rounded-xl hover:shadow-lg shadow-sm">
                                Registrar Mascota
                            </a>
                        @endif
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($owner->pets as $pet)
                            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-xl border border-gray-100 dark:border-gray-800/50 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                                <div class="space-y-4">
                                    <div class="flex items-center gap-4">
                                        <!-- Pet photo -->
                                        @if($pet->photo)
                                            <img src="{{ asset('storage/' . $pet->photo) }}" class="h-14 w-14 rounded-2xl object-cover border border-gray-100 dark:border-gray-800/50 shadow-sm" alt="{{ $pet->name }}">
                                        @else
                                            <div class="h-14 w-14 rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-500 flex items-center justify-center font-bold">
                                                🐾
                                            </div>
                                        @endif
                                        <div>
                                            <h4 class="font-bold text-lg text-gray-800 dark:text-white leading-snug">{{ $pet->name }}</h4>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-2xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400 border border-indigo-200/50 dark:border-indigo-500/20 capitalize">
                                                {{ $pet->species }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-y-2 text-xs pt-3 border-t border-gray-50 dark:border-gray-800/30 text-gray-600 dark:text-gray-400">
                                        <div>
                                            <span class="block text-2xs text-gray-400 font-bold uppercase">Raza</span>
                                            <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $pet->breed }}</span>
                                        </div>
                                        <div>
                                            <span class="block text-2xs text-gray-400 font-bold uppercase">Peso</span>
                                            <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $pet->weight }} kg</span>
                                        </div>
                                        <div class="col-span-2">
                                            <span class="block text-2xs text-gray-400 font-bold uppercase">Edad</span>
                                            <span class="font-semibold text-gray-700 dark:text-gray-300">
                                                {{ \Carbon\Carbon::parse($pet->birthdate)->age }} años 
                                                <span class="text-3xs text-gray-400">({{ \Carbon\Carbon::parse($pet->birthdate)->format('d/m/Y') }})</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 pt-4 border-t border-gray-50 dark:border-gray-800/30 flex justify-end gap-2">
                                    <a href="{{ route('pets.show', $pet) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-bold rounded-xl transition-colors">
                                        Ver Ficha
                                    </a>
                                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'recepcionista')
                                        <a href="{{ route('pets.edit', $pet) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:hover:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 text-xs font-bold rounded-xl transition-colors">
                                            Editar
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
