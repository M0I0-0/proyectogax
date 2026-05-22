<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-purple-950 leading-tight flex items-center gap-2">
            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            {{ __('Recepción y Gestión de Citas') }}
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
                            Rol: Recepción de Pacientes
                        </span>
                        <h1 class="text-3xl font-black mt-3 tracking-tight">¡Hola, {{ Auth::user()->name }}!</h1>
                        <p class="text-purple-100/90 mt-1 font-semibold">Registra dueños, mascotas y programa citas veterinarias de forma rápida y eficiente.</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="bg-white/10 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/20 shadow-2xs text-center">
                            <span class="text-xs text-purple-100 block uppercase font-bold tracking-wider">Citas Agendadas</span>
                            <span class="text-2xl font-black mt-1 block">0 Activas</span>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/20 shadow-2xs text-center">
                            <span class="text-xs text-purple-100 block uppercase font-bold tracking-wider">Último Ingreso</span>
                            <span class="text-2xl font-black mt-1 block text-purple-200">Hoy</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reception Options -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Quick Management Tasks -->
                <div class="bg-white rounded-[2rem] p-8 shadow-3xs border border-[#e2d8f7] space-y-6">
                    <h3 class="text-lg font-black text-purple-950 flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-purple-500"></span>
                        Acceso Rápido Recepción
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="{{ route('owners.create') }}" class="group p-5 rounded-2xl border border-purple-100/50 hover:border-purple-300 hover:bg-purple-50/40 transition-all duration-200 shadow-3xs">
                            <div class="h-10 w-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <h4 class="font-extrabold text-purple-950 group-hover:text-purple-700 transition-colors">Registrar Dueño</h4>
                            <p class="text-xs text-gray-500 mt-1 font-semibold">Ingresa los datos de contacto del dueño de la mascota.</p>
                        </a>

                        <a href="{{ route('pets.create') }}" class="group p-5 rounded-2xl border border-purple-100/50 hover:border-indigo-300 hover:bg-indigo-50/40 transition-all duration-200 shadow-3xs">
                            <div class="h-10 w-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <h4 class="font-extrabold text-purple-950 group-hover:text-indigo-700 transition-colors">Registrar Mascota</h4>
                            <p class="text-xs text-gray-500 mt-1 font-semibold">Ingresa los datos principales de una nueva mascota paciente.</p>
                        </a>

                        <a href="{{ route('appointments.create') }}" class="group p-5 rounded-2xl border border-purple-100/50 hover:border-violet-300 hover:bg-violet-50/40 transition-all duration-200 sm:col-span-2 shadow-3xs">
                            <div class="h-10 w-10 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z" />
                                </svg>
                            </div>
                            <h4 class="font-extrabold text-purple-950 group-hover:text-violet-700 transition-colors">Agendar Nueva Cita</h4>
                            <p class="text-xs text-gray-500 mt-1 font-semibold">Programa una cita para consulta veterinaria y asígnale un médico.</p>
                        </a>
                    </div>
                </div>

                <!-- Daily appointments -->
                <div class="bg-white rounded-[2rem] p-8 shadow-3xs border border-[#e2d8f7]">
                    <h3 class="text-lg font-black text-purple-950 mb-6 flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-purple-500"></span>
                        Citas del Día
                    </h3>
                    
                    <div class="text-center py-12">
                        <div class="h-16 w-16 rounded-full bg-purple-50 flex items-center justify-center text-purple-400 mx-auto mb-4 border border-purple-100 shadow-3xs">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="font-extrabold text-purple-950">Agenda libre por hoy</h4>
                        <p class="text-xs text-gray-500 mt-1 font-semibold">Las citas que se programen para el día de hoy aparecerán listadas aquí.</p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
