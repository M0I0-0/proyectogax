<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-purple-950 leading-tight flex items-center gap-2">
            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            {{ __('Panel de Consultas Veterinarias') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Greeting & Quick Stats Header -->
            <div class="bg-gradient-to-r from-purple-500 via-indigo-500 to-purple-600 rounded-[2.2rem] p-8 text-white shadow-sm relative overflow-hidden">
                <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-purple-300/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-white/20 text-purple-100 border border-white/20">
                            Rol: Veterinario Clínico
                        </span>
                        <h1 class="text-3xl font-black mt-3 tracking-tight">¡Hola, {{ Auth::user()->name }}!</h1>
                        <p class="text-purple-100/90 mt-1 font-semibold">Monitorea tus pacientes asignados, agenda de hoy e historial clínico veterinario.</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="bg-white/10 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/20 shadow-2xs text-center">
                            <span class="text-xs text-purple-100 block uppercase font-bold tracking-wider">Citas Hoy</span>
                            <span class="text-2xl font-black mt-1 block text-purple-200">0 Pendientes</span>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/20 shadow-2xs text-center">
                            <span class="text-xs text-purple-100 block uppercase font-bold tracking-wider">Mascotas Totales</span>
                            <span class="text-2xl font-black mt-1 block">0 Activas</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Options -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Quick Patients / Records management -->
                <div class="bg-white rounded-[2rem] p-8 shadow-3xs border border-[#e2d8f7] space-y-6">
                    <h3 class="text-lg font-black text-purple-950 flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-purple-500"></span>
                        Herramientas Clínicas
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="{{ route('pets.index') }}" class="group p-5 rounded-2xl border border-purple-100/50 hover:border-indigo-300 hover:bg-indigo-50/40 transition-all duration-200 shadow-3xs">
                            <div class="h-10 w-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h4 class="font-extrabold text-purple-950 group-hover:text-indigo-700 transition-colors">Historial Médico</h4>
                            <p class="text-xs text-gray-500 mt-1 font-semibold">Registra diagnósticos, tratamientos y recetas de consulta.</p>
                        </a>

                        <a href="{{ route('pets.index') }}" class="group p-5 rounded-2xl border border-purple-100/50 hover:border-purple-300 hover:bg-purple-50/40 transition-all duration-200 shadow-3xs">
                            <div class="h-10 w-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                            <h4 class="font-extrabold text-purple-950 group-hover:text-purple-700 transition-colors">Vacunación</h4>
                            <p class="text-xs text-gray-500 mt-1 font-semibold">Registra la aplicación de vacunas y próximas fechas sugeridas.</p>
                        </a>
                    </div>
                </div>

                <!-- Next Consultations -->
                <div class="bg-white rounded-[2rem] p-8 shadow-3xs border border-[#e2d8f7]">
                    <h3 class="text-lg font-black text-purple-950 mb-6 flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-purple-500"></span>
                        Próximas Citas Médicas
                    </h3>
                    
                    <div class="text-center py-8">
                        <div class="h-16 w-16 rounded-full bg-purple-50 flex items-center justify-center text-purple-400 mx-auto mb-4 border border-purple-100 shadow-3xs">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z" />
                            </svg>
                        </div>
                        <h4 class="font-extrabold text-purple-950">No hay consultas programadas</h4>
                        <p class="text-xs text-gray-500 mt-1 font-semibold">Las citas agendadas por recepción con tu nombre asignado aparecerán aquí.</p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
