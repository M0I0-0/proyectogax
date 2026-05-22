<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-extrabold text-2xl text-teal-800 leading-tight flex items-center gap-2">
                🐾 {{ __('Directorio de Mascotas') }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('pets.archived') }}" class="inline-flex items-center px-4 py-2 bg-rose-50 border border-rose-200 text-rose-600 font-bold text-sm rounded-2xl hover:bg-rose-100 transition-all shadow-sm">
                    <svg class="w-4.5 h-4.5 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Ver Archivadas
                </a>
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'recepcionista')
                    <a href="{{ route('pets.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-teal-400 to-emerald-500 text-white font-bold text-sm rounded-2xl hover:shadow-lg hover:shadow-teal-500/20 transform hover:-translate-y-0.5 transition-all duration-150">
                        <svg class="w-4.5 h-4.5 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Registrar Mascota
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-[2rem] shadow-sm border border-teal-100/60 overflow-hidden">
                <div class="p-6 border-b border-teal-50/50 flex justify-between items-center bg-teal-50/10">
                    <h3 class="text-sm font-bold text-teal-700 uppercase tracking-wider">Mascotas activas en el sistema</h3>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-teal-50 text-teal-700 border border-teal-100">
                        Total: {{ $pets->total() }}
                    </span>
                </div>

                @if($pets->isEmpty())
                    <div class="text-center py-16">
                        <div class="h-16 w-16 rounded-full bg-orange-50 flex items-center justify-center text-orange-500 mx-auto mb-4 border border-orange-100">
                            🐾
                        </div>
                        <h4 class="font-extrabold text-teal-800">No hay mascotas registradas</h4>
                        <p class="text-xs text-gray-500 mt-1">Registra una nueva mascota y asígnale un dueño.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-orange-50/30 text-teal-800 text-xs font-extrabold uppercase border-b border-teal-50">
                                    <th class="py-4 px-6">Foto</th>
                                    <th class="py-4 px-6">Nombre</th>
                                    <th class="py-4 px-6">Especie / Raza</th>
                                    <th class="py-4 px-6">Dueño</th>
                                    <th class="py-4 px-6">Detalles</th>
                                    <th class="py-4 px-6 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-teal-50/30 text-sm">
                                @foreach($pets as $pet)
                                    <tr class="hover:bg-[#fbfbf8]/50 transition-colors">
                                        <td class="py-4 px-6">
                                            @if($pet->photo)
                                                <img src="{{ asset('storage/' . $pet->photo) }}" class="h-11 w-11 rounded-2xl object-cover border border-teal-100 shadow-sm" alt="{{ $pet->name }}">
                                            @else
                                                <div class="h-11 w-11 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-lg border border-teal-100/30">
                                                    🐾
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 font-extrabold text-teal-900 text-base">
                                            {{ $pet->name }}
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-teal-50 text-teal-700 border border-teal-100/50 capitalize self-start shadow-2xs">
                                                    @if(strtolower($pet->species) === 'perro') 🐶 Perro
                                                    @elseif(strtolower($pet->species) === 'gato') 🐱 Gato
                                                    @elseif(strtolower($pet->species) === 'ave') 🦜 Ave
                                                    @elseif(strtolower($pet->species) === 'conejo') 🐰 Conejo
                                                    @else 🐾 {{ ucfirst($pet->species) }}
                                                    @endif
                                                </span>
                                                <span class="text-xs text-gray-500 mt-1.5 font-semibold">🧬 {{ $pet->breed }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 font-semibold">
                                            @if($pet->owner)
                                                <a href="{{ route('owners.show', $pet->owner) }}" class="text-teal-600 hover:text-teal-800 hover:underline flex items-center gap-1.5">
                                                    👤 {{ $pet->owner->name }}
                                                </a>
                                            @else
                                                <span class="text-rose-500 font-bold">Sin Dueño</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <div class="flex flex-col text-xs">
                                                <span class="text-teal-800 font-bold">⚖️ {{ $pet->weight }} kg</span>
                                                <span class="text-gray-500 font-semibold text-2xs mt-1">🎂 {{ \Carbon\Carbon::parse($pet->birthdate)->age }} años de edad</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                                            <a href="{{ route('pets.show', $pet) }}" class="inline-flex items-center p-2 bg-teal-50/50 hover:bg-teal-50 text-teal-700 rounded-xl transition-all shadow-2xs" title="Ver Ficha">
                                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'recepcionista')
                                                <a href="{{ route('pets.edit', $pet) }}" class="inline-flex items-center p-2 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-xl transition-all shadow-2xs" title="Editar">
                                                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('pets.destroy', $pet) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de archivar a esta mascota?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition-all shadow-2xs" title="Archivar (Soft Delete)">
                                                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-6 border-t border-teal-50/30">
                        {{ $pets->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
