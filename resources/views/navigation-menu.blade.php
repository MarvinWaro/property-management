<nav x-data="{ open: false }" class="bg-white dark:bg-gray-900 shadow-sm ">
    <!-- Main Header Bar -->
    <div class="bg-[#a01b1a] dark:bg-[#a01b1a] py-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Left Side (Logo & Title) -->
                <div class="flex items-center">
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                            <x-application-mark class="block h-9 w-auto" />
                            <div class="hidden md:block text-white">
                                <div class="text-md font-semibold leading-tight">COMMISSION ON HIGHER EDUCATION - REGIONAL OFFICE XII</div>
                                <div class="text-sm font-medium leading-tight text-blue-100">Inventory Management System (CIMS)</div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Right Side (User Controls) -->
                <div class="flex items-center space-x-4">
                    <!-- Dark Mode Toggle -->
                    <button id="theme-toggle" type="button"
                        class="p-2 text-white/90 hover:text-white hover:bg-white/10 rounded-lg focus:outline-none transition-all duration-200">
                        <svg id="theme-toggle-light-icon" class="hidden w-6 h-6" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 16 16" fill="currentColor">
                            <path d="M14.438 10.148c.19-.425-.321-.787-.748-.601A5.5 5.5 0 0 1 6.453 2.31c.186-.427-.176-.938-.6-.748a6.501 6.501 0 1 0 8.585 8.586Z" />
                        </svg>
                        <svg id="theme-toggle-dark-icon" class="hidden w-6 h-6" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 16 16" fill="currentColor">
                            <path d="M8 1a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 8 1ZM10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0ZM12.95 4.11a.75.75 0 1 0-1.06-1.06l-1.062 1.06a.75.75 0 0 0 1.061 1.062l1.06-1.061ZM15 8a.75.75 0 0 1-.75.75h-1.5a.75.75 0 0 1 0-1.5h1.5A.75.75 0 0 1 15 8ZM11.89 12.95a.75.75 0 0 0 1.06-1.06l-1.06-1.062a.75.75 0 0 0-1.062 1.061l1.061 1.06ZM8 12a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 8 12ZM5.172 11.89a.75.75 0 0 0-1.061-1.062L3.05 11.89a.75.75 0 1 0 1.06 1.06l1.06-1.06ZM4 8a.75.75 0 0 1-.75.75h-1.5a.75.75 0 0 1 0-1.5h1.5A.75.75 0 0 1 4 8ZM4.11 5.172A.75.75 0 0 0 5.173 4.11L4.11 3.05a.75.75 0 1 0-1.06 1.06l1.06 1.06Z" />
                        </svg>
                    </button>

                    <!-- Teams Dropdown (if applicable) -->
                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                        <div class="relative">
                            <x-dropdown align="right" width="60">
                                <x-slot name="trigger">
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white/90 hover:text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 active:bg-white/10 transition-all duration-200">
                                            {{ Auth::user()->currentTeam->name }}
                                            <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                            </svg>
                                        </button>
                                    </span>
                                </x-slot>
                                <!-- Team dropdown content here -->
                            </x-dropdown>
                        </div>
                    @endif

                    <!-- User Dropdown -->
                    <div class="relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    <button
                                        class="flex text-sm border-2 border-white/20 hover:border-white/40 rounded-full focus:outline-none focus:border-white transition-all duration-200">
                                        <img class="size-8 rounded-full object-cover"
                                            src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                    </button>
                                @else
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white/90 hover:text-white hover:bg-white/10 focus:outline-none transition-all duration-200">
                                            {{ Auth::user()->name }}
                                            <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                    </span>
                                @endif
                            </x-slot>

                            <x-slot name="content">
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-gray-500 dark:text-gray-400">
                                    {{ __('Manage Account') }}
                                </div>
                                <x-dropdown-link href="{{ route('profile.show') }}">
                                    {{ __('Profile') }}
                                </x-dropdown-link>
                                <x-dropdown-link href="{{ route('profile.show') }}#signature-section">
                                    {{ __('E-Signature') }}
                                </x-dropdown-link>

                                @if (in_array(auth()->user()->role, ['admin', 'cao']))
                                    <div class="border-t border-gray-200 dark:border-gray-600"></div>
                                    <x-dropdown-link href="{{ route('supply-transactions.index') }}">
                                        {{ __('Transactions') }}
                                    </x-dropdown-link>

                                    @php
                                        $isAssetsMode = request()->routeIs(['assets.dashboard', 'property.*', 'end_users.*', 'location.*']) ||
                                            (request()->routeIs('profile.show') && session('from_assets_mode', false));
                                    @endphp

                                    <x-dropdown-link href="{{ $isAssetsMode ? route('dashboard') : route('assets.dashboard') }}">
                                        {{ $isAssetsMode ? __('Supplies') : __('Assets | Properties') }}
                                    </x-dropdown-link>
                                @endif

                                <div class="border-t border-gray-200 dark:border-gray-600"></div>
                                <!-- Logout -->
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                        <span class="text-[#ce201f]">{{ __('Log Out') }}</span>
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="sm:hidden">
                        <button @click="open = ! open"
                            class="inline-flex items-center justify-center p-2 rounded-md text-white/90 hover:text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 focus:text-white transition-all duration-200">
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
        </div>
    </div>

    <!-- Navigation Tabs Section -->
    @php
        $isAssetsMode = request()->routeIs(['assets.dashboard', 'property.*', 'end_users.*', 'location.*']) ||
            (request()->routeIs('profile.show') && session('from_assets_mode', false));
    @endphp

    <div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Desktop Navigation Tabs -->
            <div class="hidden sm:flex space-x-8 -mb-px">
                @if (in_array(auth()->user()->role, ['admin', 'cao']) && !$isAssetsMode)
                    <!-- Supplies Mode Tabs -->
                    <a href="{{ route('dashboard') }}"
                       class="dark:text-gray-400 dark:hover:text-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200 {{ request()->routeIs('dashboard') ? 'border-[#ce201f] text-[#ce201f] dark:border-[#ce201f] dark:text-[#ce201f]' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300  border-transparent' }}">
                        <span class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span>Dashboard</span>
                        </span>
                    </a>

                    <a href="{{ route('supplies.index') }}"
                       class=" dark:text-gray-400 dark:hover:text-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200 {{ request()->routeIs('supplies.index') ? 'border-[#ce201f] text-[#ce201f] dark:border-[#ce201f] dark:text-[#ce201f]' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300 border-transparent' }}">
                        <span class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span>Supplies</span>
                        </span>
                    </a>

                    <a href="{{ route('stocks.index') }}"
                       class=" dark:text-gray-400 dark:hover:text-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200 {{ request()->routeIs('stocks.index') ? 'border-[#ce201f] text-[#ce201f] dark:border-[#ce201f] dark:text-[#ce201f]' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300 border-transparent' }}">
                        <span class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m3 0H4a1 1 0 00-1 1v14a1 1 0 001 1h16a1 1 0 001-1V5a1 1 0 00-1-1zm-8 2v2m0 4v2m0 4v2"></path>
                            </svg>
                            <span>Supply Stocks</span>
                        </span>
                    </a>

                    <!-- Replace the RIS navigation link in your navigation blade with this -->
                    <a href="{{ route('ris.index') }}"
                    class="relative dark:text-gray-400 dark:hover:text-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200 {{ request()->routeIs('ris.*') ? 'border-[#ce201f] text-[#ce201f] dark:border-[#ce201f] dark:text-[#ce201f]' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300 border-transparent' }}"
                    id="requisition-nav-link">
                        <span class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Requisitions (RIS)</span>
                        </span>

                        <!-- Badge Container for multiple badges -->
                        <div id="ris-badges-container" class="absolute -top-1 -right-1 flex space-x-1">
                            @php
                                $draftCount = \App\Models\RisSlip::where('status', 'draft')->count();
                                $approvedCount = \App\Models\RisSlip::where('status', 'approved')->count();
                            @endphp

                            @if($draftCount > 0)
                                <span id="ris-draft-badge" class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-[#ff3334] border-2 border-white rounded-full">
                                    {{ $draftCount > 99 ? '99+' : $draftCount }}
                                </span>
                            @endif

                            @if($approvedCount > 0)
                                <span id="ris-approved-badge" class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-[#6366f1] border-2 border-white rounded-full">
                                    {{ $approvedCount > 99 ? '99+' : $approvedCount }}
                                </span>
                            @endif
                        </div>
                    </a>

                    <a href="{{ route('rsmi.index') }}"
                        class=" dark:text-gray-400 dark:hover:text-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200 {{ request()->routeIs('rsmi.*') ? 'border-[#ce201f] text-[#ce201f] dark:border-[#ce201f] dark:text-[#ce201f]' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300 border-transparent' }}">
                        <span class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <span>RSMI</span>
                        </span>
                    </a>

                    @if (auth()->user()->hasRole('admin'))
                        <!-- Management Dropdown -->
                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <button type="button"
                                class=" dark:text-gray-400 dark:hover:text-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200 {{ request()->routeIs('supplier.index') || request()->routeIs('categories.index') || request()->routeIs('departments.index') || request()->routeIs('designations.index') ? 'border-[#ce201f] text-[#ce201f] dark:border-[#ce201f] dark:text-[#ce201f]' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300 border-transparent' }} flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>Management</span>
                                <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div x-show="open" x-transition class="absolute z-50 mt-1 w-48 rounded-lg shadow-lg origin-top-right right-0 border border-gray-200 dark:border-gray-600" style="display: none;">
                                <div class="rounded-lg ring-1 ring-black ring-opacity-5 py-1 bg-white dark:bg-gray-800">
                                    <a href="{{ route('supplier.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-[#ce201f] transition-all duration-150">
                                        Supplier
                                    </a>
                                    <a href="{{ route('categories.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-[#ce201f] transition-all duration-150">
                                        Categories
                                    </a>
                                    <a href="{{ route('departments.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-[#ce201f] transition-all duration-150">
                                        Division
                                    </a>
                                    <a href="{{ route('designations.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-[#ce201f] transition-all duration-150">
                                        Designation
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                @if (in_array(auth()->user()->role, ['admin', 'cao']) && $isAssetsMode)
                    <!-- Assets Mode Tabs -->
                    <a href="{{ route('assets.dashboard') }}"
                    class=" dark:text-gray-400 dark:hover:text-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200 {{ request()->routeIs('assets.dashboard') ? 'border-[#ce201f] text-[#ce201f] dark:border-[#ce201f] dark:text-[#ce201f]' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300 border-transparent' }}">
                        <span class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span>Assets Dashboard</span>
                        </span>
                    </a>

                    <a href="{{ route('property.index') }}"
                       class=" dark:text-gray-400 dark:hover:text-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200 {{ request()->routeIs('property.index') ? 'border-[#ce201f] text-[#ce201f] dark:border-[#ce201f] dark:text-[#ce201f]' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300 border-transparent' }}">
                        <span class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span>Property</span>
                        </span>
                    </a>

                    <a href="{{ route('location.index') }}"
                       class=" dark:text-gray-400 dark:hover:text-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200 {{ request()->routeIs('location.index') ? 'border-[#ce201f] text-[#ce201f] dark:border-[#ce201f] dark:text-[#ce201f]' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300 border-transparent' }}">
                        <span class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Location</span>
                        </span>
                    </a>
                @endif

                @if (auth()->user()->role === 'staff')
                    <!-- Staff Tabs -->
                    <a href="{{ route('staff.dashboard') }}"
                       class=" dark:hover:text-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200 {{ request()->routeIs('staff.dashboard') ? 'border-[#ce201f] text-[#ce201f] dark:border-[#ce201f] dark:text-[#ce201f]' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 border-transparent' }}">
                        <span class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span>Home</span>
                        </span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
        <div class="pt-2 pb-3 space-y-1">
            @if (!$isAssetsMode && in_array(auth()->user()->role, ['admin', 'cao']))
                <a href="{{ route('dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'border-[#ce201f] text-[#ce201f] bg-red-50 dark:bg-red-900/20' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700' }}">
                    Dashboard
                </a>
                <a href="{{ route('supplies.index') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-all duration-200 {{ request()->routeIs('supplies.index') ? 'border-[#ce201f] text-[#ce201f] bg-red-50 dark:bg-red-900/20' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700' }}">
                    Supplies
                </a>
                <a href="{{ route('stocks.index') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-all duration-200 {{ request()->routeIs('stocks.index') ? 'border-[#ce201f] text-[#ce201f] bg-red-50 dark:bg-red-900/20' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700' }}">
                    Supply Stocks
                </a>
                <a href="{{ route('ris.index') }}"
                class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-all duration-200 {{ request()->routeIs('ris.*') ? 'border-[#ce201f] text-[#ce201f] bg-red-50 dark:bg-red-900/20' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700' }}">
                    <span class="flex items-center justify-between">
                        <span>Requisitions (RIS)</span>
                        <span class="flex space-x-1">
                            @php
                                $draftCount = \App\Models\RisSlip::where('status', 'draft')->count();
                                $approvedCount = \App\Models\RisSlip::where('status', 'approved')->count();
                            @endphp

                            @if($draftCount > 0)
                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-[#ff3334] rounded-full">
                                    {{ $draftCount > 99 ? '99+' : $draftCount }}
                                </span>
                            @endif

                            @if($approvedCount > 0)
                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-[#6366f1] rounded-full">
                                    {{ $approvedCount > 99 ? '99+' : $approvedCount }}
                                </span>
                            @endif
                        </span>
                    </span>
                </a>
                <a href="{{ route('rsmi.index') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-all duration-200 {{ request()->routeIs('rsmi.*') ? 'border-[#ce201f] text-[#ce201f] bg-red-50 dark:bg-red-900/20' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700' }}">
                    RSMI
                </a>

                <!-- Management section for mobile -->
                <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800 dark:text-gray-200">Management</div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <a href="{{ route('supplier.index') }}" class="block pl-6 pr-4 py-2 border-l-4 text-base font-medium transition-all duration-200 {{ request()->routeIs('supplier.index') ? 'border-[#ce201f] text-[#ce201f] bg-red-50 dark:bg-red-900/20' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700' }}">
                            Supplier
                        </a>
                        <a href="{{ route('categories.index') }}" class="block pl-6 pr-4 py-2 border-l-4 text-base font-medium transition-all duration-200 {{ request()->routeIs('categories.index') ? 'border-[#ce201f] text-[#ce201f] bg-red-50 dark:bg-red-900/20' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700' }}">
                            Categories
                        </a>
                        <a href="{{ route('departments.index') }}" class="block pl-6 pr-4 py-2 border-l-4 text-base font-medium transition-all duration-200 {{ request()->routeIs('departments.index') ? 'border-[#ce201f] text-[#ce201f] bg-red-50 dark:bg-red-900/20' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700' }}">
                            Division
                        </a>
                        <a href="{{ route('designations.index') }}" class="block pl-6 pr-4 py-2 border-l-4 text-base font-medium transition-all duration-200 {{ request()->routeIs('designations.index') ? 'border-[#ce201f] text-[#ce201f] bg-red-50 dark:bg-red-900/20' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700' }}">
                            Designation
                        </a>
                    </div>
                </div>
            @endif

            @if ($isAssetsMode && in_array(auth()->user()->role, ['admin', 'cao']))
                <a href="{{ route('assets.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-all duration-200 {{ request()->routeIs('assets.dashboard') ? 'border-[#ce201f] text-[#ce201f] bg-red-50 dark:bg-red-900/20' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700' }}">
                    Assets Dashboard
                </a>
                <a href="{{ route('property.index') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-all duration-200 {{ request()->routeIs('property.index') ? 'border-[#ce201f] text-[#ce201f] bg-red-50 dark:bg-red-900/20' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700' }}">
                    Property
                </a>
                <a href="{{ route('location.index') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-all duration-200 {{ request()->routeIs('location.index') ? 'border-[#ce201f] text-[#ce201f] bg-red-50 dark:bg-red-900/20' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700' }}">
                    Location
                </a>
            @endif

            @if (auth()->user()->role === 'staff')
                <a href="{{ route('staff.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-all duration-200 {{ request()->routeIs('staff.dashboard') ? 'border-[#ce201f] text-[#ce201f] bg-red-50 dark:bg-red-900/20' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700' }}">
                    Home
                </a>
            @endif
        </div>
    </div>
</nav>
