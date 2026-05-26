<nav x-data="{ open: false }" class="bg-white/95 backdrop-blur-md border-b border-[#e2d8f7] sticky top-0 z-50 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-purple-500 to-indigo-600 p-0.5 flex items-center justify-center shadow-md shadow-purple-200/40 transform group-hover:scale-105 transition-transform duration-200">
                            <img src="{{ asset('images/logos/logo_vetcare.jpg') }}" class="h-full w-full rounded-[0.55rem] object-cover" alt="VetCare Logo">
                        </div>
                        <span class="font-extrabold text-2xl text-purple-950 tracking-tight group-hover:text-purple-700 transition-colors duration-200">VetCare</span>
                    </a>
                </div>
 
                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    @php
                        $role = Auth::user()->role;
                    @endphp
 
                    <!-- Admin Links -->
                    @if($role === 'admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Panel Admin') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            {{ __('Personal') }}
                        </x-nav-link>
                        <x-nav-link :href="route('prescriptions.create')" :active="request()->routeIs('prescriptions.*')">
                            {{ __('Recetas') }}
                        </x-nav-link>
                        <x-nav-link :href="route('owners.index')" :active="request()->routeIs('owners.*')">
                            {{ __('Dueños') }}
                        </x-nav-link>
                        <x-nav-link :href="route('pets.index')" :active="request()->routeIs('pets.*')">
                            {{ __('Mascotas') }}
                        </x-nav-link>
                        <x-nav-link :href="route('appointments.index')" :active="request()->routeIs('appointments.*')">
                            {{ __('Citas') }}
                        </x-nav-link>
                    @endif
 
                    <!-- Veterinario Links -->
                    @if($role === 'veterinario')
                        <x-nav-link :href="route('vet.dashboard')" :active="request()->routeIs('vet.dashboard')">
                            {{ __('Consultas Vet') }}
                        </x-nav-link>
                        <x-nav-link :href="route('prescriptions.create')" :active="request()->routeIs('prescriptions.*')">
                            {{ __('Recetas') }}
                        </x-nav-link>
                        <x-nav-link :href="route('pets.index')" :active="request()->routeIs('pets.*')">
                            {{ __('Mis Mascotas') }}
                        </x-nav-link>
                        <x-nav-link :href="route('appointments.index')" :active="request()->routeIs('appointments.*')">
                            {{ __('Agenda Citas') }}
                        </x-nav-link>
                    @endif
 
                    <!-- Recepcionista Links -->
                    @if($role === 'recepcionista')
                        <x-nav-link :href="route('recep.dashboard')" :active="request()->routeIs('recep.dashboard')">
                            {{ __('Recepción') }}
                        </x-nav-link>
                        <x-nav-link :href="route('owners.index')" :active="request()->routeIs('owners.*')">
                            {{ __('Dueños') }}
                        </x-nav-link>
                        <x-nav-link :href="route('pets.index')" :active="request()->routeIs('pets.*')">
                            {{ __('Mascotas') }}
                        </x-nav-link>
                        <x-nav-link :href="route('appointments.index')" :active="request()->routeIs('appointments.*')">
                            {{ __('Citas') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>
 
            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Role Badge -->
                <div class="me-3">
                    @if($role === 'admin')
                        <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/50 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-indigo-400 me-1.5 animate-pulse"></span>
                            Administrador
                        </span>
                    @elseif($role === 'veterinario')
                        <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200/50 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-purple-400 me-1.5 animate-pulse"></span>
                            Veterinario
                        </span>
                    @else
                        <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-violet-50 text-violet-700 border border-violet-200/50 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-violet-400 me-1.5 animate-pulse"></span>
                            Recepcionista
                        </span>
                    @endif
                </div>
 
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-[#e2d8f7] text-sm leading-4 font-bold rounded-2xl text-purple-950 bg-purple-50/50 hover:bg-purple-50 hover:text-purple-700 focus:outline-none transition ease-in-out duration-150 shadow-2xs">
                            <div class="flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>{{ Auth::user()->name }}</span>
                            </div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-50 dark:bg-gray-950 border-t border-gray-100 dark:border-gray-800">
        <div class="pt-2 pb-3 space-y-1">
            @if($role === 'admin')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Panel Admin') }}
                </x-responsive-nav-link>
            @elseif($role === 'veterinario')
                <x-responsive-nav-link :href="route('vet.dashboard')" :active="request()->routeIs('vet.dashboard')">
                    {{ __('Consultas Vet') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('recep.dashboard')" :active="request()->routeIs('recep.dashboard')">
                    {{ __('Recepción') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-800">
            <div class="px-4 flex justify-between items-center">
                <div>
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <div>
                    @if($role === 'admin')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/50 shadow-sm capitalize">
                            {{ $role }}
                        </span>
                    @elseif($role === 'veterinario')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200/50 shadow-sm capitalize">
                            {{ $role }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-violet-50 text-violet-700 border border-violet-200/50 shadow-sm capitalize">
                            {{ $role }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
