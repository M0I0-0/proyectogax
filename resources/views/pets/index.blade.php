<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight flex items-center gap-2">
                🐾
                {{ __('Directorio de Mascotas') }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('pets.archived') }}" class="inline-flex items-center px-4 py-2 border border-rose-200 dark:border-rose-900/50 text-rose-600 dark:text-rose-400 font-semibold text-sm rounded-xl hover:bg-rose-50 dark:hover:bg-rose-950/20 transition-all shadow-sm">
                    <svg class="w-4.5 h-4.5 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Ver Archivadas
                </a>
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'recepcionista')
                    <a href="{{ route('pets.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold text-sm rounded-xl hover:shadow-lg hover:shadow-emerald-500/20 transform hover:-translate-y-0.5 transition-all duration-150">
                        <svg class="w-4.5 h-4.5 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Registrar Mascota
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center gap-3">
                    <svg class="h-5 w-5 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800/50 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800/50 flex justify-between items-center bg-gray-50/50 dark:bg-gray-950/20">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mascotas activas en el sistema</h3>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
                        Total: {{ $pets->total() }}
                    </span>
                </div>

                @if($pets->isEmpty())
                    <div class="text-center py-16">
                        <div class="h-16 w-16 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-400 mx-auto mb-4 border border-slate-200/20">
                            🐾
                        </div>
                        <h4 class="font-bold text-gray-700 dark:text-gray-300">No hay mascotas registradas</h4>
                        <p class="text-xs text-gray-500 mt-1">Registra una nueva mascota y asígnale un dueño.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/70 dark:bg-gray-950/50 text-gray-500 dark:text-gray-400 text-xs font-bold uppercase border-b border-gray-100 dark:border-gray-800/50">
                                    <th class="py-4 px-6">Foto</th>
                                    <th class="py-4 px-6">Nombre</th>
                                    <th class="py-4 px-6">Especie / Raza</th>
                                    <th class="py-4 px-6">Dueño</th>
                                    <th class="py-4 px-6">Detalles</th>
                                    <th class="py-4 px-6 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50 text-sm">
                                @foreach($pets as $pet)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/10 transition-colors">
                                        <td class="py-4 px-6">
                                            @if($pet->photo)
                                                <img src="{{ asset('storage/' . $pet->photo) }}" class="h-10 w-10 rounded-xl object-cover border border-gray-100 dark:border-gray-800/50 shadow-sm" alt="{{ $pet->name }}">
                                            @else
                                                <div class="h-10 w-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-500 flex items-center justify-center font-bold">
                                                    🐾
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 font-semibold text-gray-800 dark:text-gray-100">
                                            {{ $pet->name }}
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-2xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400 border border-indigo-200/50 dark:border-indigo-500/20 capitalize self-start">
                                                    {{ $pet->species }}
                                                </span>
                                                <span class="text-xs text-gray-500 mt-1 font-medium">{{ $pet->breed }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 font-medium text-gray-700 dark:text-gray-300">
                                            @if($pet->owner)
                                                <a href="{{ route('owners.show', $pet->owner) }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">
                                                    {{ $pet->owner->name }}
                                                </a>
                                            @else
                                                <span class="text-rose-500 font-semibold">Sin Dueño</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <div class="flex flex-col text-xs">
                                                <span class="text-gray-900 dark:text-gray-200 font-semibold">{{ $pet->weight }} kg</span>
                                                <span class="text-gray-400 text-3xs">{{ \Carbon\Carbon::parse($pet->birthdate)->age }} años de edad</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                                            <a href="{{ route('pets.show', $pet) }}" class="inline-flex items-center p-1.5 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg transition-colors" title="Ver Ficha">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'recepcionista')
                                                <a href="{{ route('pets.edit', $pet) }}" class="inline-flex items-center p-1.5 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:hover:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 rounded-lg transition-colors" title="Editar">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('pets.destroy', $pet) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de archivar a esta mascota?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center p-1.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/30 dark:hover:bg-rose-900/40 text-rose-600 dark:text-rose-400 rounded-lg transition-colors" title="Archivar (Soft Delete)">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
                    <div class="p-6 border-t border-gray-100 dark:border-gray-800/50">
                        {{ $pets->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
