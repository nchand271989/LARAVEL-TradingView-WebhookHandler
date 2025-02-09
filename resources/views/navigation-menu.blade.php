<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 pt-2 fixed w-full top-0 left-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="z-50">
                    <div class="shrink-0 space-x-2 flex items-center">
                        <x-application-logo class="block h-[50px] w-auto" />
                        <span class="mt-4 text-xl">
                            <span class="text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400">Trading</span> BOT
                        </span>
                    </div>
                </a>

                <nav class="hidden mx-4 p-5 sm:flex">
                    <ul class="flex space-x-8">
                        <li>
                            <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                        </li>
                        <li>
                            <x-nav-link href="{{ route('webhooks.index') }}" :active="request()->routeIs('webhooks.index')">
                                {{ __('Webhooks') }}
                            </x-nav-link>
                        </li>
                        <li>
                            <x-nav-link href="{{ route('strategies.index') }}" :active="request()->routeIs('strategies.index')">
                                {{ __('Strategies') }}
                            </x-nav-link>
                        </li>
                        <li class="relative group">
                            <x-nav-link href="" :active="in_array(Route::currentRouteName(), ['markets.assets', 'currencies.index', 'exchanges.index'])">
                                {{ __('Markets & Assets') }}
                            </x-nav-link>
                            <ul class="absolute left-0 hidden bg-white text-black shadow-lg group-hover:block w-40">
                                <li>
                                    <x-nav-dropdown-link href="{{ route('currencies.index') }}" :active="request()->routeIs('currencies.index')">
                                        {{ __('Currencies') }}
                                    </x-nav-dropdown-link>
                                </li>
                                <li>
                                    <x-nav-dropdown-link href="{{ route('exchanges.index') }}" :active="request()->routeIs('exchanges.index')">
                                        {{ __('Exchanges') }}
                                    </x-nav-dropdown-link>
                                </li>
                            </ul>
                        </li>
                        @if (session('is_admin'))
                        <li>
                            <x-nav-link href="" :active="request()->routeIs('users')">
                                {{ __('Users') }}
                            </x-nav-link>
                        </li>
                        @endif
                    </ul>
                </nav>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->currentTeam->name }}
                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>
                                    <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>
                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-gray-200"></div>
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}
                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200"></div>

                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden relative z-50">
                <button @click="open = !open; $nextTick(() => {
                    const menu = document.querySelector('.responsive-nav');
                    if (open) {
                        menu.style.transform = 'translateX(0)';
                    } else {
                        menu.style.transform = 'translateX(100%)';
                    }
                })" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <!-- Hamburger icon (when menu is closed) -->
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        
                        <!-- Close icon (when menu is open) -->
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="responsive-nav sm:hidden fixed inset-0 bg-zinc-50 z-40 transform transition-transform duration-300 ease-in-out" style="transform: translateX(100%);">
        <!-- Responsive Settings Options -->
        <a href="{{ route('profile.show') }}">
        <div class="mt-[4.5rem] pt-4 pb-4 border-t border-gray-200">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
        </div>
        </a>
        <hr />
        <div class="pt-5 pb-2 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="/" :active="request()->routeIs('webhooks')">
                {{ __('Webhooks') }}
            </x-nav-link>
            <x-responsive-nav-link href="{{ route('strategies.index') }}" :active="request()->routeIs('strategies.index')">
                {{ __('Strategies') }}
            </x-nav-link>
            <x-responsive-sub-menu-nav-link href="" :active="in_array(Route::currentRouteName(), ['markets.assets', 'currencies.index', 'exchanges.index'])">
                {{ __('Markets & Assets') }}
                <div class="px-4">
                    <x-responsive-nav-link href="{{ route('currencies.index') }}" :active="request()->routeIs('currencies.index')">
                        {{ __('Currencies') }}
                    </x-nav-link>
                    <x-responsive-nav-link href="/" :active="request()->routeIs('exchanges')">
                        {{ __('Exchanges') }}
                    </x-nav-link>
                </div>
            </x-responsive-sub-menu-nav-link>
            @if (session('is_admin'))
                <x-responsive-nav-link href="/" :active="request()->routeIs('users')">
                    {{ __('Users') }}
                </x-nav-link>
            @endif
        </div>
        <hr/>
        <div class="mt-2 space-y-1">
                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                        <span class="">{{ __('Log Out') }}</span>
                    </x-dropdown-link>
                </form>
            </div>
        
    </div>
</nav>
