<nav x-data="{ open: false }" class="bg-[#dc3546] dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 px-12">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center justify-between w-full">
                <!-- Left Side (Logo & Navigation Links) -->
                <div class="flex items-center">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}">
                            <x-application-mark class="block h-9 w-auto" />
                        </a>
                    </div>

                    @php
                        // "Assets mode" vs. "Supplies mode" logic
                        // Adjust route checks or session logic as needed for your app
                        $isAssetsMode =
                            request()->routeIs(['assets.dashboard', 'property.*', 'end_users.*', 'location.*']) ||
                            (request()->routeIs('profile.show') && session('from_assets_mode', false));
                    @endphp

                    <!-- NAV BAR LINKS ONLY (for example within <nav> ... ) -->

                    <!-- Replace the four separate nav links with this dropdown -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <!-- Admin: 'Supplies mode' -->
                        @if (auth()->user()->role === 'admin' && !$isAssetsMode)
                            <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('supplies.index') }}" :active="request()->routeIs('supplies.index')">
                                {{ __('Supplies') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('stocks.index') }}" :active="request()->routeIs('stocks.index')">
                                {{ __('Supply Stocks') }}
                            </x-nav-link>
                            {{-- <x-nav-link href="{{ route('supply-transactions.index') }}" :active="request()->routeIs('supply-transactions.*')">
                                {{ __('Transactions') }}
                            </x-nav-link> --}}
                            <x-nav-link href="{{ route('ris.index') }}" :active="request()->routeIs('ris.*')" class="relative" id="requisition-nav-link">
                                {{ __('Requisitions (RIS)') }}
                                @if(isset($pendingRisCount) && $pendingRisCount > 0)
                                    <span id="ris-notification-badge" class="absolute inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-yellow-500 border-2 border-white rounded-full -top-2 -right-2 dark:border-gray-900">
                                        {{ $pendingRisCount > 99 ? '99+' : $pendingRisCount }}
                                    </span>
                                @else
                                    <span id="ris-notification-badge" class="absolute inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-yellow-500 border-2 border-white rounded-full -top-2 -right-2 dark:border-gray-900 hidden"></span>
                                @endif
                            </x-nav-link>
                            <x-nav-link href="{{ route('rsmi.index') }}" :active="request()->routeIs('rsmi.*')">
                                {{ __('RSMI') }}
                            </x-nav-link>
                            <!-- Management Dropdown -->
                            <div class="relative" x-data="{ open: false }" @mouseenter="open = true"
                                @mouseleave="open = false">
                                <button type="button"
                                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('supplier.index') || request()->routeIs('categories.index') || request()->routeIs('departments.index') || request()->routeIs('designations.index') ? 'border-white text-white' : 'border-transparent text-white hover:text-gray-200 hover:border-gray-200' }}">
                                    <span>{{ __('Management') }}</span>
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right right-0"
                                    style="display: none;">
                                    <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                        <a href="{{ route('supplier.index') }}"
                                            class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out {{ request()->routeIs('supplier.index') ? 'bg-gray-100' : '' }}">
                                            {{ __('Supplier') }}
                                        </a>
                                        <a href="{{ route('categories.index') }}"
                                            class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out {{ request()->routeIs('categories.index') ? 'bg-gray-100' : '' }}">
                                            {{ __('Categories') }}
                                        </a>
                                        <a href="{{ route('departments.index') }}"
                                            class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out {{ request()->routeIs('departments.index') ? 'bg-gray-100' : '' }}">
                                            {{ __('Division') }}
                                        </a>
                                        <a href="{{ route('designations.index') }}"
                                            class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out {{ request()->routeIs('designations.index') ? 'bg-gray-100' : '' }}">
                                            {{ __('Designation') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Admin: 'Assets mode' -->
                        @if (auth()->user()->role === 'admin' && $isAssetsMode)
                            <x-nav-link href="{{ route('assets.dashboard') }}" :active="request()->routeIs('assets.dashboard')">
                                {{ __('Assets Dashboard') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('property.index') }}" :active="request()->routeIs('property.index')">
                                {{ __('Property') }}
                            </x-nav-link>
                            <x-nav-link href="{{ route('location.index') }}" :active="request()->routeIs('location.index')">
                                {{ __('Location') }}
                            </x-nav-link>
                        @endif

                        <!-- Staff-only links -->
                        @if (auth()->user()->role === 'staff')
                            <x-nav-link href="{{ route('staff.dashboard') }}" :active="request()->routeIs('staff.dashboard')">
                                {{ __('Home') }}
                            </x-nav-link>
                            <!-- Add other staff links here, if needed -->
                        @endif
                    </div>


                </div>

                <!-- Right Side (Dark Mode Toggle Button) -->
                <div class="flex items-center">
                    <button id="theme-toggle" type="button"
                        class="p-2 text-white hover:text-gray-200 focus:outline-none transition duration-150 ease-in-out">
                        <svg id="theme-toggle-light-icon" class="hidden w-6 h-6" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 16 16" fill="currentColor">
                            <path
                                d="M14.438 10.148c.19-.425-.321-.787-.748-.601A5.5 5.5 0 0 1 6.453 2.31c.186-.427-.176-.938-.6-.748a6.501 6.501 0 1 0 8.585 8.586Z" />
                        </svg>
                        <svg id="theme-toggle-dark-icon" class="hidden w-6 h-6" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 16 16" fill="currentColor">
                            <path
                                d="M8 1a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 8 1ZM10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0ZM12.95 4.11a.75.75 0 1 0-1.06-1.06l-1.062 1.06a.75.75 0 0 0 1.061 1.062l1.06-1.061ZM15 8a.75.75 0 0 1-.75.75h-1.5a.75.75 0 0 1 0-1.5h1.5A.75.75 0 0 1 15 8ZM11.89 12.95a.75.75 0 0 0 1.06-1.06l-1.06-1.062a.75.75 0 0 0-1.062 1.061l1.061 1.06ZM8 12a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 8 12ZM5.172 11.89a.75.75 0 0 0-1.061-1.062L3.05 11.89a.75.75 0 1 0 1.06 1.06l1.06-1.06ZM4 8a.75.75 0 0 1-.75.75h-1.5a.75.75 0 0 1 0-1.5h1.5A.75.75 0 0 1 4 8ZM4.11 5.172A.75.75 0 0 0 5.173 4.11L4.11 3.05a.75.75 0 1 0-1.06 1.06l1.06 1.06Z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-transparent hover:text-gray-200 focus:outline-none focus:bg-red-700 active:bg-red-700 transition ease-in-out duration-150">
                                        {{ Auth::user()->currentTeam->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-gray-200 dark:border-gray-600"></div>

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

                @php
                    // Determine if we're in "Assets mode" vs. "Supplies mode"
                    $isAssetsMode =
                        request()->routeIs(['assets.dashboard', 'property.*', 'end_users.*', 'location.*']) ||
                        (request()->routeIs('profile.show') && session('from_assets_mode', false));
                                    @endphp

                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button
                                    class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-white transition">
                                    <img class="size-8 rounded-full object-cover"
                                        src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-transparent hover:text-gray-200 focus:outline-none transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}
                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Everyone sees this "Manage Account" label -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <!-- Everyone sees their Profile -->
                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- E-Signature Management (available to everyone) -->
                            <x-dropdown-link href="{{ route('profile.show') }}#signature-section">
                                {{ __('E-Signature') }}
                            </x-dropdown-link>

                            <div class="border-t border-gray-200 dark:border-gray-600"></div>

                            @if (auth()->user()->role === 'admin')
                                <x-dropdown-link href="{{ route('supply-transactions.index') }}">
                                    {{ __('Transactions') }}
                                </x-dropdown-link>
                            @endif

                            <!-- Only admins see the toggle to switch between "Supplies" and "Assets" -->
                            @if (auth()->user()->role === 'admin')
                                <x-dropdown-link
                                    href="{{ $isAssetsMode ? route('dashboard') : route('assets.dashboard') }}">
                                    {{ $isAssetsMode ? __('Supplies') : __('Assets | Properties') }}
                                </x-dropdown-link>

                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                        {{ __('API Tokens') }}
                                    </x-dropdown-link>
                                @endif
                            @endif

                            <div class="border-t border-gray-200 dark:border-gray-600"></div>

                            <!-- Everyone sees Logout -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                    <span class="text-red-400">{{ __('Log Out') }}</span>
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-gray-200 hover:bg-red-700 focus:outline-none focus:bg-red-700 focus:text-white transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-[#dc3546]">
        <div class="pt-2 pb-3 space-y-1">
            @php
                $isAssetsMode =
                    request()->routeIs(['assets.dashboard', 'property.*', 'end_users.*', 'location.*']) ||
                    (request()->routeIs('profile.show') && session('from_assets_mode', false));
            @endphp

            @if (!$isAssetsMode && auth()->user()->role === 'admin')
                <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('supplies.index') }}" :active="request()->routeIs('supplies.index')">
                    {{ __('Supplies') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('stocks.index') }}" :active="request()->routeIs('stocks.index')">
                    {{ __('Supply Stocks') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('ris.index') }}" :active="request()->routeIs('ris.*')">
                    {{ __('Requisitions (RIS)') }}
                </x-responsive-nav-link>
                <!-- Management section for mobile -->
                <div class="block px-4 py-2 text-xs text-gray-200">
                    {{ __('Management') }}
                </div>
                <x-responsive-nav-link href="{{ route('supplier.index') }}" :active="request()->routeIs('supplier.index')">
                    {{ __('Supplier') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('categories.index') }}" :active="request()->routeIs('categories.index')">
                    {{ __('Categories') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('departments.index') }}" :active="request()->routeIs('departments.index')">
                    {{ __('Division') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('designations.index') }}" :active="request()->routeIs('designations.index')">
                    {{ __('Designation') }}
                </x-responsive-nav-link>
            @endif

            @if ($isAssetsMode && auth()->user()->role === 'admin')
                <x-responsive-nav-link href="{{ route('assets.dashboard') }}" :active="request()->routeIs('assets.dashboard')">
                    {{ __('Assets Dashboard') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="{{ route('property.index') }}" :active="request()->routeIs('property.index')">
                    {{ __('Property') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="{{ route('location.index') }}" :active="request()->routeIs('location.index')">
                    {{ __('Location') }}
                </x-responsive-nav-link>
            @endif

            @if (auth()->user()->role === 'staff')
                <x-responsive-nav-link href="{{ route('staff.dashboard') }}" :active="request()->routeIs('staff.dashboard')">
                    {{ __('Home') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-red-600">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                            alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-200">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- E-Signature Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}#signature-section">
                    {{ __('E-Signature') }}
                </x-responsive-nav-link>

                @if (auth()->user()->role === 'admin')
                    <x-responsive-nav-link href="{{ route('supply-transactions.index') }}">
                        {{ __('Transactions') }}
                    </x-responsive-nav-link>

                    <!-- Remove :active from Supplies/Assets | Properties to match dropdown behavior -->
                    <x-responsive-nav-link
                        href="{{ request()->routeIs(['assets.dashboard', 'property.*', 'end_users.*', 'location.*']) ? route('dashboard') : route('assets.dashboard') }}">
                        {{ request()->routeIs(['assets.dashboard', 'property.*', 'end_users.*', 'location.*']) ? __('Supplies') : __('Assets | Properties') }}
                    </x-responsive-nav-link>
                @endif

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                        <span class="text-red-400">{{ __('Log Out') }}</span>
                    </x-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-red-600"></div>

                    <div class="block px-4 py-2 text-xs text-gray-200">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                        :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-responsive-nav-link>
                    @endcan

                    <!-- Team Switcher -->
                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="border-t border-red-600"></div>

                        <div class="block px-4 py-2 text-xs text-gray-200">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="responsive-nav-link" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>

</nav>
