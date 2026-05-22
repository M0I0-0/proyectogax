<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-purple-950 leading-tight flex items-center gap-2">
            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            {{ __('Panel de Administración') }}
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
                            Rol: Administrador del Sistema
                        </span>
                        <h1 class="text-3xl font-black mt-3 tracking-tight">¡Bienvenido de vuelta, {{ Auth::user()->name }}!</h1>
                        <p class="text-purple-100/90 mt-1 font-semibold">Aquí tienes el estado actual y el rendimiento de la veterinaria **VetCare** hoy.</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="bg-white/10 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/20 shadow-2xs text-center">
                            <span class="text-xs text-purple-100 block uppercase font-bold tracking-wider">Usuarios Activos</span>
                            <span class="text-2xl font-black mt-1 block">3</span>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/20 shadow-2xs text-center">
                            <span class="text-xs text-purple-100 block uppercase font-bold tracking-wider">Estado Sistema</span>
                            <span class="text-2xl font-black mt-1 block text-purple-200">100% OK</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- User card -->
                <div class="bg-white rounded-[2rem] p-6 shadow-3xs border border-[#e2d8f7] hover:shadow-2xs hover:-translate-y-0.5 transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Roles de Usuario</h3>
                            <p class="text-2xl font-black text-purple-950 mt-1">3 Registrados</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-purple-50/50 text-xs text-gray-500 flex justify-between font-semibold">
                        <span>1 Admin</span>
                        <span>1 Veterinario</span>
                        <span>1 Recepcionista</span>
                    </div>
                </div>

                <!-- Database status -->
                <div class="bg-white rounded-[2rem] p-6 shadow-3xs border border-[#e2d8f7] hover:shadow-2xs hover:-translate-y-0.5 transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Base de Datos</h3>
                            <p class="text-2xl font-black text-purple-950 mt-1">SQLite / Local</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-purple-50/50 text-xs text-gray-500 flex justify-between items-center font-semibold">
                        <span>Host: 127.0.0.1</span>
                        <span class="px-2 py-0.5 rounded-md bg-purple-100 text-purple-700 font-bold">Online</span>
                    </div>
                </div>

                <!-- System Logs -->
                <div class="bg-white rounded-[2rem] p-6 shadow-3xs border border-[#e2d8f7] hover:shadow-2xs hover:-translate-y-0.5 transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-violet-100 text-violet-600 flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Seguridad y Logs</h3>
                            <p class="text-2xl font-black text-purple-950 mt-1">Seguro</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-purple-50/50 text-xs text-gray-500 flex justify-between items-center font-semibold">
                        <span>CheckRole Middleware</span>
                        <span class="px-2 py-0.5 rounded-md bg-violet-100 text-violet-700 font-bold">Activo</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Tools -->
            <div class="bg-white rounded-[2rem] p-8 shadow-3xs border border-[#e2d8f7]">
                <h3 class="text-lg font-black text-purple-950 mb-6 flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-purple-500"></span>
                    Acciones de Control Administrativo
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <a href="#" class="group p-5 rounded-2xl border border-purple-100/50 hover:border-indigo-300 hover:bg-indigo-50/40 transition-all duration-200 shadow-3xs">
                        <div class="h-10 w-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <h4 class="font-extrabold text-purple-950 group-hover:text-indigo-700 transition-colors">Gestionar Usuarios</h4>
                        <p class="text-xs text-gray-500 mt-1 font-semibold">Crea, edita o suspende cuentas de veterinarios y recepcionistas.</p>
                    </a>

                    <a href="#" class="group p-5 rounded-2xl border border-purple-100/50 hover:border-purple-300 hover:bg-purple-50/40 transition-all duration-200 shadow-3xs">
                        <div class="h-10 w-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            </svg>
                        </div>
                        <h4 class="font-extrabold text-purple-950 group-hover:text-purple-700 transition-colors">Configuración Global</h4>
                        <p class="text-xs text-gray-500 mt-1 font-semibold">Configura parámetros del sistema, emails SMTP y copias de seguridad.</p>
                    </a>

                    <a href="#" class="group p-5 rounded-2xl border border-purple-100/50 hover:border-violet-300 hover:bg-violet-50/40 transition-all duration-200 shadow-3xs">
                        <div class="h-10 w-10 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h4 class="font-extrabold text-purple-950 group-hover:text-violet-700 transition-colors">Reportes y Auditoría</h4>
                        <p class="text-xs text-gray-500 mt-1 font-semibold">Audita el historial de notificaciones enviadas y logs del cron job.</p>
                    </a>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
