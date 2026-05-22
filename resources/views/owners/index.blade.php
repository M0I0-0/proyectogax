<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-extrabold text-2xl text-purple-950 leading-tight flex items-center gap-2">
                👥 {{ __('Directorio de Dueños') }}
            </h2>
            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'recepcionista')
                <a href="{{ route('owners.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 via-indigo-500 to-purple-600 text-white font-bold text-sm rounded-2xl hover:shadow-lg hover:shadow-purple-500/20 transform hover:-translate-y-0.5 transition-all duration-150">
                    <svg class="w-5 h-5 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Registrar Dueño
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 bg-purple-50 border border-purple-200 text-purple-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <svg class="h-5 w-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-[2rem] shadow-3xs border border-[#e2d8f7] overflow-hidden">
                <div class="p-6 border-b border-[#e2d8f7] flex justify-between items-center bg-purple-50/10">
                    <h3 class="text-sm font-bold text-purple-950 uppercase tracking-wider">Todos los clientes registrados</h3>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-purple-50 text-purple-700 border border-[#e2d8f7]">
                        Total: {{ $owners->total() }}
                    </span>
                </div>

                @if($owners->isEmpty())
                    <div class="text-center py-16">
                        <div class="h-16 w-16 rounded-full bg-purple-50 flex items-center justify-center text-purple-500 mx-auto mb-4 border border-[#e2d8f7]">
                            👤
                        </div>
                        <h4 class="font-extrabold text-purple-950">No hay dueños registrados</h4>
                        <p class="text-xs text-gray-500 mt-1 font-semibold">Registra un nuevo dueño para poder asociarle mascotas.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-purple-50/20 text-purple-950 text-xs font-extrabold uppercase border-b border-[#e2d8f7]">
                                    <th class="py-4 px-6">Nombre</th>
                                    <th class="py-4 px-6">Contacto</th>
                                    <th class="py-4 px-6">Dirección</th>
                                    <th class="py-4 px-6 text-center">Mascotas</th>
                                    <th class="py-4 px-6 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-purple-100/50 text-sm">
                                @foreach($owners as $owner)
                                    <tr class="hover:bg-purple-50/20 transition-colors">
                                        <td class="py-4 px-6 font-extrabold text-purple-950 text-base">
                                            {{ $owner->name }}
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex flex-col">
                                                <span class="text-purple-950 font-bold">✉️ {{ $owner->email }}</span>
                                                <span class="text-xs text-gray-500 mt-1 font-semibold">📞 {{ $owner->phone }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-gray-600 max-w-xs truncate font-semibold">
                                            📍 {{ $owner->address }}
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-purple-50 text-purple-700 border border-[#e2d8f7] shadow-3xs">
                                                🐾 {{ $owner->pets_count }} mascota{{ $owner->pets_count !== 1 ? 's' : '' }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                                            <a href="{{ route('owners.show', $owner) }}" class="inline-flex items-center p-2 bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200/50 rounded-xl transition-all shadow-3xs" title="Ver Detalle">
                                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'recepcionista')
                                                <a href="{{ route('owners.edit', $owner) }}" class="inline-flex items-center p-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200/50 rounded-xl transition-all shadow-3xs" title="Editar">
                                                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('owners.destroy', $owner) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar a este dueño? Se eliminarán todas sus mascotas asociadas.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center p-2 bg-violet-50 hover:bg-violet-100 text-violet-600 border border-violet-200/50 rounded-xl transition-all shadow-3xs" title="Eliminar">
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
                    <div class="p-6 border-t border-[#e2d8f7]">
                        {{ $owners->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
