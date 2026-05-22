<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-extrabold text-2xl text-teal-950 leading-tight flex items-center gap-2.5">
                <a href="{{ route('owners.index') }}" class="inline-flex items-center justify-center h-10 w-10 bg-teal-50/80 hover:bg-teal-50 text-teal-700 rounded-full transition-all border border-teal-100/30 shadow-2xs" title="Regresar al listado">
                    <svg class="h-5 w-5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <span>👤 {{ __('Perfil del Dueño') }}</span>
            </h2>
            <div class="flex gap-2.5">
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'recepcionista')
                    <a href="{{ route('owners.edit', $owner) }}" class="inline-flex items-center px-4 py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200/50 font-extrabold text-xs sm:text-sm rounded-2xl transition-all shadow-2xs hover:-translate-y-0.5">
                        <svg class="w-4.5 h-4.5 me-2 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar Datos
                    </a>
                    <a href="{{ route('pets.create', ['owner_id' => $owner->id]) }}" class="inline-flex items-center px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-xs sm:text-sm rounded-2xl transition-all shadow-md shadow-teal-600/10 hover:shadow-lg hover:shadow-teal-600/20 hover:-translate-y-0.5">
                        <svg class="w-4.5 h-4.5 me-2 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Asociar Mascota
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-250 text-emerald-800 rounded-2xl flex items-center gap-3 shadow-2xs">
                    <svg class="h-5 w-5 text-emerald-600 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-extrabold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Owner Card Info -->
            <div class="bg-white/80 rounded-[2rem] p-6 sm:p-8 shadow-xl shadow-teal-900/5 border border-teal-100/60 flex flex-col md:flex-row gap-6 sm:gap-8 relative overflow-hidden">
                <div class="absolute -right-24 -bottom-24 w-80 h-80 bg-gradient-to-tr from-teal-500/5 to-emerald-500/5 rounded-full blur-3xl"></div>
                
                <!-- Avatar circle representing owner -->
                <div class="h-20 w-20 rounded-3xl bg-gradient-to-tr from-teal-400 to-emerald-600 flex items-center justify-center text-white text-3xl font-black shadow-lg shadow-teal-500/25 self-start shrink-0">
                    {{ strtoupper(substr($owner->name, 0, 2)) }}
                </div>
                
                <div class="space-y-4 flex-1">
                    <div>
                        <h3 class="text-2xl font-extrabold text-teal-950 tracking-tight">{{ $owner->name }}</h3>
                        <p class="text-xs text-teal-600/60 font-bold uppercase tracking-wider mt-0.5">Cliente Distinguido VetCare</p>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-4 border-t border-teal-50/50">
                        <div class="space-y-0.5">
                            <span class="text-xs text-teal-600/70 font-bold uppercase tracking-wider block">Correo Electrónico</span>
                            <span class="text-sm font-extrabold text-teal-950">✉️ {{ $owner->email }}</span>
                        </div>
                        <div class="space-y-0.5">
                            <span class="text-xs text-teal-600/70 font-bold uppercase tracking-wider block">Teléfono de Contacto</span>
                            <span class="text-sm font-extrabold text-teal-950">📞 {{ $owner->phone }}</span>
                        </div>
                        <div class="space-y-0.5">
                            <span class="text-xs text-teal-600/70 font-bold uppercase tracking-wider block">Dirección Postal</span>
                            <span class="text-sm font-extrabold text-teal-905">📍 {{ $owner->address }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pets section -->
            <div class="space-y-4 animate-fade-in">
                <h3 class="text-lg font-extrabold text-teal-950 flex items-center gap-2">
                    <span class="h-3 w-3 rounded-full bg-teal-500 shadow-2xs"></span>
                    Mascotas Asociadas
                </h3>

                @if($owner->pets->isEmpty())
                    <div class="bg-white/80 rounded-[2rem] p-12 shadow-xl shadow-teal-900/5 border border-teal-100/60 text-center">
                        <div class="h-16 w-16 rounded-full bg-teal-50 text-teal-400 flex items-center justify-center text-3xl mx-auto mb-4 shadow-inner">🐾</div>
                        <h4 class="font-extrabold text-teal-900">Este dueño no tiene mascotas registradas</h4>
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'recepcionista')
                            <p class="text-xs text-teal-600/60 mt-1 mb-6">Asocia una mascota para gestionar sus consultas médicas y vacunas.</p>
                            <a href="{{ route('pets.create', ['owner_id' => $owner->id]) }}" class="inline-flex items-center px-5 py-3 bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-xs sm:text-sm rounded-2xl transition-all shadow-md shadow-teal-600/10 hover:-translate-y-0.5">
                                Registrar Mascota
                            </a>
                        @endif
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($owner->pets as $pet)
                            <div class="bg-white/80 rounded-[2rem] p-6 shadow-xl shadow-teal-900/5 border border-teal-100/60 hover:shadow-2xl hover:shadow-teal-900/10 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                                <div class="space-y-4">
                                    <div class="flex items-center gap-4">
                                        <!-- Pet photo -->
                                        @if($pet->photo)
                                            <div class="bg-gradient-to-tr from-teal-200 to-emerald-300 p-0.5 rounded-[1.2rem] shadow-3xs">
                                                <img src="{{ asset('storage/' . $pet->photo) }}" class="h-14 w-14 rounded-[1.1rem] object-cover border-2 border-white shadow-2xs" alt="{{ $pet->name }}">
                                            </div>
                                        @else
                                            <div class="h-14 w-14 rounded-2xl bg-teal-50 text-teal-500 flex items-center justify-center font-bold text-2xl shadow-3xs">
                                                @if(strtolower($pet->species) === 'perro') 🐶
                                                @elseif(strtolower($pet->species) === 'gato') 🐱
                                                @elseif(strtolower($pet->species) === 'ave') 🦜
                                                @elseif(strtolower($pet->species) === 'conejo') 🐰
                                                @else 🐾
                                                @endif
                                            </div>
                                        @endif
                                        <div>
                                            <h4 class="font-extrabold text-lg text-teal-950 leading-snug">{{ $pet->name }}</h4>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-3xs font-extrabold bg-teal-50 text-teal-700 border border-teal-100/50 capitalize mt-0.5 shadow-3xs">
                                                @if(strtolower($pet->species) === 'perro') 🐶 Perro
                                                @elseif(strtolower($pet->species) === 'gato') 🐱 Gato
                                                @elseif(strtolower($pet->species) === 'ave') 🦜 Ave
                                                @elseif(strtolower($pet->species) === 'conejo') 🐰 Conejo
                                                @else 🐾 {{ ucfirst($pet->species) }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-y-2 text-xs pt-3 border-t border-teal-50/30 text-teal-900">
                                        <div>
                                            <span class="block text-3xs text-teal-600/70 font-bold uppercase tracking-wider">Raza</span>
                                            <span class="font-extrabold text-teal-950">🧬 {{ $pet->breed }}</span>
                                        </div>
                                        <div>
                                            <span class="block text-3xs text-teal-600/70 font-bold uppercase tracking-wider">Peso</span>
                                            <span class="font-extrabold text-teal-950">⚖️ {{ $pet->weight }} kg</span>
                                        </div>
                                        <div class="col-span-2">
                                            <span class="block text-3xs text-teal-600/70 font-bold uppercase tracking-wider">Edad</span>
                                            <span class="font-extrabold text-teal-950">
                                                🎂 {{ \Carbon\Carbon::parse($pet->birthdate)->age }} años 
                                                <span class="text-3xs text-teal-650 font-semibold">({{ \Carbon\Carbon::parse($pet->birthdate)->format('d/m/Y') }})</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 pt-3 border-t border-teal-50/30 flex justify-end gap-2">
                                    <a href="{{ route('pets.show', $pet) }}" class="inline-flex items-center px-3.5 py-2 bg-teal-50/50 hover:bg-teal-50 text-teal-700 text-xs font-extrabold rounded-xl transition-all shadow-2xs border border-teal-100/30">
                                        Ver Ficha
                                    </a>
                                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'recepcionista')
                                        <a href="{{ route('pets.edit', $pet) }}" class="inline-flex items-center px-3.5 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-extrabold rounded-xl transition-all shadow-2xs border border-amber-200/50">
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
