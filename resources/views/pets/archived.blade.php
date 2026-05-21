<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight flex items-center gap-2">
                <a href="{{ route('pets.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                📂
                {{ __('Papelera de Mascotas (Archivadas)') }}
            </h2>
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
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mascotas archivadas temporalmente</h3>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400">
                        Papelera: {{ $pets->total() }}
                    </span>
                </div>

                @if($pets->isEmpty())
                    <div class="text-center py-16">
                        <div class="h-16 w-16 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-400 mx-auto mb-4 border border-slate-200/20">
                            📂
                        </div>
                        <h4 class="font-bold text-gray-700 dark:text-gray-300">La papelera está vacía</h4>
                        <p class="text-xs text-gray-500 mt-1">No hay mascotas archivadas en el sistema.</p>
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
                                                <img src="{{ asset('storage/' . $pet->photo) }}" class="h-10 w-10 rounded-xl object-cover border border-gray-100 dark:border-gray-800/50 shadow-sm grayscale" alt="{{ $pet->name }}">
                                            @else
                                                <div class="h-10 w-10 rounded-xl bg-gray-100 dark:bg-gray-850 text-gray-400 flex items-center justify-center font-bold">
                                                    🐾
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 font-semibold text-gray-800 dark:text-gray-100">
                                            {{ $pet->name }}
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-2xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400 border border-gray-200/50 dark:border-gray-700/20 capitalize self-start">
                                                    {{ $pet->species }}
                                                </span>
                                                <span class="text-xs text-gray-500 mt-1 font-medium">{{ $pet->breed }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 font-medium text-gray-700 dark:text-gray-300">
                                            @if($pet->owner)
                                                {{ $pet->owner->name }}
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
                                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'recepcionista')
                                                <!-- Restore Button -->
                                                <form action="{{ route('pets.restore', $pet->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/30 dark:hover:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 text-xs font-bold rounded-xl transition-colors" title="Restaurar Mascota">
                                                        <svg class="w-4 h-4 me-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 6.09" />
                                                        </svg>
                                                        Restaurar
                                                    </button>
                                                </form>

                                                <!-- Force Delete Button -->
                                                <form action="{{ route('pets.force-delete', $pet->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar PERMANENTEMENTE a esta mascota? Esta acción no se puede deshacer.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/30 dark:hover:bg-rose-900/40 text-rose-600 dark:text-rose-400 text-xs font-bold rounded-xl transition-colors" title="Eliminar Permanente">
                                                        <svg class="w-4 h-4 me-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Eliminar Permanente
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-400 italic">Sin permisos</span>
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
