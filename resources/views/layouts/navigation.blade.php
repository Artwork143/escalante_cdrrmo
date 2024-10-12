<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center">
                        <!-- Logo Image -->
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto" />
                        
                        <!-- Name next to logo -->
                        <span class="ml-3 text-xl font-semibold text-gray-800">
                            {{ config('app.name', 'Your App Name') }}
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Home') }}
                    </x-nav-link>
                </div>

                <!-- Generate Reports Dropdown -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex pt-4">
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 {{ request()->routeIs('medical_cases.index') || request()->routeIs('vehicular_accidents.index') ? 'border-b-indigo-400 border-b-2 hover:border-b-indigo-400' : 'hover:border-b-2 border-b-0 border-b-gray-100' }} border border-transparent text-sm leading-4 font-medium pb-[23px] text-gray-500 bg-white hover:border-b-gray-300 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <span>{{ __('Generate Reports') }}</span>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('medical_cases.index')">
                                {{ __('Medical Cases') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('vehicular_accidents.index')">
                                {{ __('Vehicular Accidents') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>

                </div>
                
                @if (Auth::user()->role == 0)
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('medical_cases.yearly')" :active="request()->routeIs('medical_cases.yearly')">
                        {{ __('Yearly Reports') }}
                    </x-nav-link>
                </div>
                @endif

                <!-- Conditional Navigation for Admin (Users Management) -->
                @if (Auth::user()->role == 0)
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('admin')" :active="request()->routeIs('admin')">
                        {{ __('Users') }}
                    </x-nav-link>
                </div>
                @endif
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 pt-4">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                    <button class="inline-flex items-center px-3 py-2 {{ request()->routeIs('profile.edit') ? 'border-b-indigo-400 border-b-2 hover:border-b-indigo-400' : 'hover:border-b-2 border-b-0 border-b-gray-100' }} border border-transparent text-sm leading-4 font-medium pb-[23px] text-gray-500 bg-white hover:border-b-gray-300 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

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

            <!-- Hamburger for Responsive Navigation -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- Add additional responsive menu items if needed -->
            <x-responsive-nav-link :href="route('medical_cases.index')" :active="request()->routeIs('medical_cases.index')">
                {{ __('Medical Cases') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('vehicular_accidents.index')" :active="request()->routeIs('vehicular_accidents.index')">
                {{ __('Vehicular Accidents') }}
            </x-responsive-nav-link>

            <!-- Admin Menu (conditional) -->
            @if (Auth::user()->role == 0)
            <x-responsive-nav-link :href="route('admin')" :active="request()->routeIs('admin')">
                {{ __('Users') }}
            </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
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