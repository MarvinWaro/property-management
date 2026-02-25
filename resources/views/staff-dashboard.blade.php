<x-app-layout>

    <!-- Replace the header section in your staff-dashboard.blade.php with this cleaner version -->

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-5">
            @if(isset($isAdminUserMode) && $isAdminUserMode)
                {{ __('Welcome Back!') }} <span class="text-sm text-blue-600 dark:text-blue-400 font-normal">(User Mode)</span>
            @else
                {{ __('Welcome Back!') }}
            @endif
        </h2>


            <!-- Remove the big blue banner - we'll keep it minimal -->
            <!-- Only show a small info notice if needed -->
            @if(isset($isAdminUserMode) && $isAdminUserMode)
                <div class="my-4">
                    <div class="bg-blue-50 dark:bg-blue-900/50 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                        <div class="flex items-center">
                            <svg class="h-4 w-4 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm text-blue-700 dark:text-blue-200">
                                You can request supplies like a staff member. Your requests will follow the normal approval process.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
    </x-slot>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-100 dark:bg-gray-900">
                <div class="mx-12 mx-auto py-8">
                    <div class="grid grid-cols-4 sm:grid-cols-12 gap-6">

                        @if ($forceChangePassword)
                            <script>
                                Swal.fire({
                                    title: 'Change Your Password',
                                    text: 'You still have the default password (12345678). Please change it now.',
                                    icon: 'warning',
                                    showCancelButton: false,
                                    confirmButtonText: 'Change Password'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Redirect them to the password change page
                                        window.location.href = "{{ route('user.force-change-password') }}";
                                    }
                                });
                            </script>
                        @endif

                        <!-- Left sidebar with profile and navigation -->
                        <!-- Left sidebar with profile and navigation -->
                        <div class="col-span-4 sm:col-span-3">
                            <!-- Minimal Profile Card -->
                            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 mb-6">
                                <div class="flex flex-col items-center text-center">
                                    <!-- Profile Photo -->
                                    <div class="relative mb-4">
                                        <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
                                            class="w-24 h-24 object-cover rounded-full border-2 border-gray-200 dark:border-gray-600">
                                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
                                    </div>

                                    <!-- User Info -->
                                    <div class="space-y-1">
                                        <h1 class="text-lg font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</h1>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ optional(Auth::user()->designation)->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-500">{{ optional(Auth::user()->department)->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Minimal Navigation Menu -->
                            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">Profile Navigation</h3>
                                </div>

                                @php
                                    // Get counts for the current user
                                    $userId = Auth::id();

                                    // Count approved requests by this user
                                    $approvedRequestsCount = \App\Models\RisSlip::where('requested_by', $userId)
                                        ->where('status', 'approved')
                                        ->count();

                                    // Count issued supplies waiting for this user to receive
                                    $pendingReceiptCount = \App\Models\RisSlip::where('received_by', $userId)
                                        ->where('status', 'posted')
                                        ->whereNull('received_at')
                                        ->count();
                                @endphp

                                <nav id="profile-nav" class="p-2">
                                    <a href="#"
                                        class="profile-nav-link flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-[#ce201f] dark:hover:text-[#ce201f] transition-all duration-200 border-l-4 border-transparent"
                                        data-target="requests">
                                        <div class="flex items-center space-x-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span>Requests</span>
                                        </div>
                                        @if($approvedRequestsCount > 0)
                                            <span id="user-approved-badge" class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-[#6366f1] rounded-full">
                                                {{ $approvedRequestsCount > 99 ? '99+' : $approvedRequestsCount }}
                                            </span>
                                        @endif
                                    </a>

                                    <a href="#"
                                        class="profile-nav-link flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-[#ce201f] dark:hover:text-[#ce201f] transition-all duration-200 border-l-4 border-transparent"
                                        data-target="received-supplies">
                                        <div class="flex items-center space-x-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                            <span>Received Supplies</span>
                                        </div>
                                        @if($pendingReceiptCount > 0)
                                            <span id="user-pending-badge" class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-[#f59e0b] rounded-full animate-pulse">
                                                {{ $pendingReceiptCount > 99 ? '99+' : $pendingReceiptCount }}
                                            </span>
                                        @endif
                                    </a>

                                    <a href="#"
                                        class="profile-nav-link flex items-center space-x-3 px-3 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-[#ce201f] dark:hover:text-[#ce201f] transition-all duration-200 border-l-4 border-transparent"
                                        data-target="properties">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <span>Properties</span>
                                    </a>
                                </nav>
                            </div>
                        </div>

                        <style>
                            /* Active state styling */
                            .profile-nav-link.active {
                                background-color: #fef2f2;
                                color: #ce201f;
                                border-left-color: #ce201f;
                            }

                            .dark .profile-nav-link.active {
                                background-color: #1f2937;
                                color: #ce201f;
                            }

                            /* Custom focus states */
                            .profile-nav-link:focus {
                                outline: none;
                                box-shadow: 0 0 0 2px #ce201f33;
                            }
                        </style>

                        <!-- Main content area -->
                        <div class="details col-span-4 sm:col-span-9">

                            <!-- Enhanced My Requests Section -->
                            <div id="requests"
                                class="content-section bg-white dark:bg-gray-800 shadow rounded-lg p-6 hidden">
                                <!-- Alert Messages -->
                                @if (session('success'))
                                    <div id="flashMessage"
                                        class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                                        role="alert">
                                        <span class="font-medium">Success!</span> {{ session('success') }}
                                    </div>
                                    <script>
                                        setTimeout(() => {
                                            document.getElementById('flashMessage').style.display = 'none';
                                        }, 3000);
                                    </script>
                                @endif

                                <!-- Header with Button and Search -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 space-y-4 sm:space-y-0">
                                    <div>
                                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">My Requests</h2>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Track and manage your supply requisition requests</p>
                                    </div>

                                    <button id="openRequestModal"
                                        class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-[#ce201f] hover:bg-[#a01b1a] focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 rounded-lg transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Request Supply
                                    </button>
                                </div>

                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 mb-6">
                                    <div class="flex flex-wrap gap-4 items-center justify-between">
                                        <!-- Status Filters - Clickable -->
                                        <div class="flex flex-wrap gap-3">
                                            <a href="{{ route('staff.dashboard') }}"
                                            class="inline-flex items-center px-3 py-1.5 text-sm rounded-lg transition-all duration-200 {{ !request('status') ? 'bg-[#ce201f] text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                                <span class="font-medium">Total:</span>
                                                <span class="ml-1 font-semibold">{{ $totalRequests ?? 0 }}</span>
                                            </a>

                                            <a href="{{ route('staff.dashboard', ['status' => 'draft']) }}"
                                            class="inline-flex items-center px-3 py-1.5 text-sm rounded-lg transition-all duration-200 {{ request('status') === 'draft' ? 'bg-[#ce201f] text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                                <span class="w-2 h-2 mr-2 rounded-full bg-yellow-500"></span>
                                                <span>Pending:</span>
                                                <span class="ml-1 font-semibold">{{ $pendingCount ?? 0 }}</span>
                                            </a>

                                            <a href="{{ route('staff.dashboard', ['status' => 'approved']) }}"
                                            class="inline-flex items-center px-3 py-1.5 text-sm rounded-lg transition-all duration-200 {{ request('status') === 'approved' ? 'bg-[#ce201f] text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                                <span class="w-2 h-2 mr-2 rounded-full bg-blue-500"></span>
                                                <span>Approved:</span>
                                                <span class="ml-1 font-semibold">{{ $approvedCount ?? 0 }}</span>
                                            </a>

                                            <a href="{{ route('staff.dashboard', ['status' => 'pending-receipt']) }}"
                                            class="inline-flex items-center px-3 py-1.5 text-sm rounded-lg transition-all duration-200 {{ request('status') === 'pending-receipt' ? 'bg-[#ce201f] text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                                <span class="w-2 h-2 mr-2 rounded-full bg-orange-500"></span>
                                                <span>Pending Receipt:</span>
                                                <span class="ml-1 font-semibold">{{ $pendingReceiptCount ?? 0 }}</span>
                                            </a>

                                            <a href="{{ route('staff.dashboard', ['status' => 'completed']) }}"
                                            class="inline-flex items-center px-3 py-1.5 text-sm rounded-lg transition-all duration-200 {{ request('status') === 'completed' ? 'bg-[#ce201f] text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                                <span class="w-2 h-2 mr-2 rounded-full bg-green-500"></span>
                                                <span>Completed:</span>
                                                <span class="ml-1 font-semibold">{{ $completedCount ?? 0 }}</span>
                                            </a>

                                            <a href="{{ route('staff.dashboard', ['status' => 'declined']) }}"
                                            class="inline-flex items-center px-3 py-1.5 text-sm rounded-lg transition-all duration-200 {{ request('status') === 'declined' ? 'bg-[#ce201f] text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                                <span class="w-2 h-2 mr-2 rounded-full bg-red-500"></span>
                                                <span>Declined:</span>
                                                <span class="ml-1 font-semibold">{{ $declinedCount ?? 0 }}</span>
                                            </a>
                                        </div>

                                        <!-- Search Form -->
                                        <form method="GET" action="{{ route('staff.dashboard') }}"
                                            class="w-full max-w-sm flex items-center space-x-2">
                                            @if(request('status'))
                                                <input type="hidden" name="status" value="{{ request('status') }}">
                                            @endif

                                            <div class="relative flex-grow">
                                                <input type="text" name="search" id="request-search-input"
                                                    value="{{ request()->get('search') }}"
                                                    placeholder="Search by RIS number..."
                                                    class="px-4 py-2 w-full border text-sm border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-[#ce201f] focus:border-[#ce201f] transition-all duration-200" />

                                                <!-- Clear Button -->
                                                <button type="button" id="clearRequestButton"
                                                    onclick="clearRequestSearch()" style="display: none;"
                                                    class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-[#ce201f] focus:outline-none transition-colors duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <line x1="18" x2="6" y1="6" y2="18" />
                                                        <line x1="6" x2="18" y1="6" y2="18" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <!-- Search Button -->
                                            <button type="submit"
                                                class="px-3 py-2 text-sm text-white bg-[#ce201f] rounded-lg hover:bg-[#a01b1a] focus:ring-1 focus:outline-none focus:ring-[#ce201f]/30 flex items-center transition-all duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Requests Table -->
                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700">
                                                <tr>
                                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">RIS No</th>
                                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                                    <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                @forelse($myRequests as $request)
                                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                                {{ $request->ris_no }}
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex flex-col">
                                                                <span class="text-sm text-gray-900 dark:text-gray-300">{{ $request->ris_date->format('M d, Y') }}</span>
                                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $request->created_at->format('h:i A') }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            @if ($request->status === 'draft')
                                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded dark:bg-yellow-900/20 dark:text-yellow-300">
                                                                    <span class="relative flex h-2 w-2 mr-1.5">
                                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-500 opacity-75"></span>
                                                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-yellow-500"></span>
                                                                    </span>
                                                                    Pending
                                                                </span>
                                                            @elseif($request->status === 'approved')
                                                                <span class="bg-[#6366f1]/10 text-[#6366f1] text-xs font-medium inline-flex items-center px-2.5 py-1 rounded dark:bg-[#6366f1]/20 dark:text-[#818cf8]">
                                                                    <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                    Approved
                                                                </span>
                                                            @elseif($request->status === 'posted' && !$request->received_at)
                                                                <span class="bg-orange-100 text-orange-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded dark:bg-orange-900/20 dark:text-orange-300">
                                                                    <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z"/>
                                                                    </svg>
                                                                    Issued - Pending Receipt
                                                                </span>
                                                            @elseif($request->status === 'posted' && $request->received_at)
                                                                <span class="bg-[#10b981]/10 text-[#10b981] text-xs font-medium inline-flex items-center px-2.5 py-1 rounded dark:bg-[#10b981]/20 dark:text-[#34d399]">
                                                                    <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                    Completed
                                                                </span>
                                                            @elseif($request->status === 'declined')
                                                                <span class="bg-red-100 text-red-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded dark:bg-red-900/20 dark:text-red-300">
                                                                    <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                    Declined
                                                                </span>
                                                                @if($request->decline_reason)
                                                                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ Str::limit($request->decline_reason, 50) }}</p>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                                            <a href="{{ route('ris.show', $request) }}"
                                                                class="inline-flex items-center justify-center w-8 h-8 text-[#10b981] dark:text-[#34d399] hover:bg-[#10b981]/10 dark:hover:bg-[#10b981]/20 rounded-lg transition-all duration-200"
                                                                data-tooltip-target="tooltip-view-request-{{ $loop->index }}"
                                                                data-tooltip-placement="top">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                                    <circle cx="12" cy="12" r="3"></circle>
                                                                </svg>
                                                            </a>
                                                            <div id="tooltip-view-request-{{ $loop->index }}" role="tooltip"
                                                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                                View Details
                                                                <div class="tooltip-arrow" data-popper-arrow></div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="px-6 py-12 text-center">
                                                            <div class="flex flex-col items-center justify-center">
                                                                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                                                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                    </svg>
                                                                </div>
                                                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No requests found</h3>
                                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                                    @if(request('status') || request('search'))
                                                                        Try adjusting your filters or search term.
                                                                    @else
                                                                        Get started by clicking the "Request Supply" button
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Pagination -->
                                @if($myRequests->hasPages())
                                    <div class="mt-4">
                                        {{ $myRequests->links() }}
                                    </div>
                                @endif

                                <!-- JavaScript - ONLY for clear button, NO filtering -->
                                <script>
                                    function toggleClearRequestButton() {
                                        const input = document.getElementById('request-search-input');
                                        const clearBtn = document.getElementById('clearRequestButton');
                                        if (input && clearBtn) {
                                            clearBtn.style.display = input.value.trim().length > 0 ? 'flex' : 'none';
                                        }
                                    }

                                    function clearRequestSearch() {
                                        const input = document.getElementById('request-search-input');
                                        if (input) {
                                            input.value = '';
                                            const clearBtn = document.getElementById('clearRequestButton');
                                            if (clearBtn) {
                                                clearBtn.style.display = 'none';
                                            }

                                            const currentUrl = new URL(window.location.href);
                                            const status = currentUrl.searchParams.get('status');

                                            if (status) {
                                                window.location.href = window.location.pathname + '?status=' + status;
                                            } else {
                                                window.location.href = window.location.pathname;
                                            }
                                        }
                                    }

                                    document.addEventListener('DOMContentLoaded', function() {
                                        toggleClearRequestButton();

                                        const searchInput = document.getElementById('request-search-input');
                                        if (searchInput) {
                                            // ONLY toggle clear button - NO filtering
                                            searchInput.addEventListener('input', toggleClearRequestButton);

                                            // Enter key submits form
                                            searchInput.addEventListener('keypress', function(e) {
                                                if (e.key === 'Enter') {
                                                    e.preventDefault();
                                                    const form = this.closest('form');
                                                    if (form) {
                                                        form.submit();
                                                    }
                                                }
                                            });
                                        }
                                    });
                                </script>

                                <style>
                                    /* Hover effects for stat filters */
                                    .flex.flex-wrap.gap-3 a {
                                        cursor: pointer;
                                        position: relative;
                                        overflow: hidden;
                                    }

                                    .flex.flex-wrap.gap-3 a::before {
                                        content: '';
                                        position: absolute;
                                        top: 0;
                                        left: -100%;
                                        width: 100%;
                                        height: 100%;
                                        background: rgba(255, 255, 255, 0.1);
                                        transition: left 0.3s ease;
                                    }

                                    .flex.flex-wrap.gap-3 a:hover::before {
                                        left: 0;
                                    }

                                    /* Active filter indicator */
                                    .flex.flex-wrap.gap-3 a.bg-\[\#ce201f\] {
                                        box-shadow: 0 2px 4px rgba(206, 32, 31, 0.2);
                                    }
                                </style>

                                <!-- RIS Request Modal with Only Items Section Scrollable -->
                                <div id="requestModal"
                                    class="fixed inset-0 z-50 overflow-y-auto -webkit-overflow-scrolling-touch bg-black bg-opacity-50
                                        flex items-start sm:items-center justify-center hidden p-1 sm:p-4">
                                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl
                                            w-full max-w-5xl mx-3 mt-8 sm:mt-0 sm:max-h-[90vh] flex flex-col pb-8 sm:pb-0">
                                        <!-- Fixed Header -->
                                        <div class="flex items-center justify-between p-3 sm:p-4 border-b dark:border-gray-700 shrink-0">
                                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">
                                                <span class="hidden sm:inline">Create Requisition and Issue Slip</span>
                                                <span class="sm:hidden">Create RIS</span>
                                            </h3>
                                            <button id="closeRequestModal"
                                                class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 p-1">
                                                <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>

                                        <form action="{{ route('ris.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                                            @csrf

                                            <!-- Scrollable Content Area -->
                                            <div class="flex-1 overflow-y-auto">
                                                <div class="p-3 sm:p-6">
                                                    <!-- Header Information -->
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 mb-4 sm:mb-6">
                                                        <div class="sm:col-span-2 lg:col-span-1">
                                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Entity Name</label>
                                                            <input type="text" name="entity_name" value="CHEDRO 12"
                                                                class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-[#45be63] focus:border-[#45be63]"
                                                                required>
                                                        </div>

                                                        <!-- Hidden Fund Cluster field that admin will fill later -->
                                                        <input type="hidden" name="fund_cluster" value="">

                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Division</label>
                                                            <select name="division"
                                                                class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-[#45be63] focus:border-[#45be63]"
                                                                required>
                                                                @foreach ($departments as $department)
                                                                    <option value="{{ $department->id }}"
                                                                        {{ Auth::user()->department_id == $department->id ? 'selected' : '' }}>
                                                                        {{ $department->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Office</label>
                                                            <input type="text" name="office"
                                                                class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-[#45be63] focus:border-[#45be63]">
                                                        </div>

                                                        <div class="sm:col-span-2 lg:col-span-1">
                                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                <span class="hidden sm:inline">Responsibility Center Code</span>
                                                                <span class="sm:hidden">Center Code</span>
                                                            </label>
                                                            <input type="text" name="responsibility_center_code"
                                                                class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-[#45be63] focus:border-[#45be63]">
                                                        </div>

                                                        <div class="sm:col-span-2">
                                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Purpose</label>
                                                            <textarea name="purpose" rows="2"
                                                                class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-[#45be63] focus:border-[#45be63] resize-none"
                                                                required></textarea>
                                                        </div>
                                                    </div>

                                                    <!-- Items Header with Controls -->
                                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                                                        <h4 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">
                                                            <span class="hidden sm:inline">Select Items to Request</span>
                                                            <span class="sm:hidden">Select Items</span>
                                                        </h4>

                                                        <div class="flex items-center w-full sm:w-auto gap-2">
                                                            <div class="relative flex-1 sm:flex-none sm:w-48">
                                                                <input type="text" id="item-search"
                                                                    placeholder="Search items..."
                                                                    class="w-full pl-8 sm:pl-10 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-[#45be63] focus:border-[#45be63]">
                                                                <div class="absolute inset-y-0 left-0 pl-2 sm:pl-3 flex items-center pointer-events-none">
                                                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                                    </svg>
                                                                </div>
                                                            </div>

                                                            <button type="button" id="viewCartBtn"
                                                                class="px-2 sm:px-3 py-2 bg-[#ce201f] text-white rounded-md hover:bg-[#a01b1a] flex items-center shadow-sm whitespace-nowrap text-sm">
                                                                <svg class="h-4 w-4 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                                </svg>
                                                                <span class="hidden sm:inline">View Selected</span>
                                                                <span class="ml-1">(<span id="itemCount">0</span>)</span>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Supply Items -->
                                                    <div class="mb-6">
                                                        <!-- Product Grid View with Fund Cluster Information -->
                                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4" id="products-grid">
                                                            @foreach ($stocks as $stock)
                                                                <div class="product-card border dark:border-gray-700 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200"
                                                                    data-supply-id="{{ $stock->supply_id }}"
                                                                    data-name="{{ $stock->supply->item_name }}"
                                                                    data-description="{{ $stock->supply->description ?? '' }}"
                                                                    data-available="{{ $stock->available_for_request }}"
                                                                    data-fund-cluster="{{ $stock->fund_cluster }}">
                                                                    <div class="p-3 sm:p-4 flex flex-col h-full">
                                                                        <div class="flex-1">
                                                                            <h5 class="font-medium text-gray-900 dark:text-white mb-2 line-clamp-2 text-sm sm:text-base leading-tight">
                                                                                {{ $stock->supply->item_name }}</h5>
                                                                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-1">
                                                                                <span class="font-medium">Stock No:</span> {{ $stock->supply->stock_no ?? 'N/A' }}</p>
                                                                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-3 line-clamp-2">
                                                                                <span class="font-medium">Description:</span> {{ $stock->supply->description ?? 'N/A' }}
                                                                            </p>
                                                                            <div class="flex flex-col gap-2 mb-3">
                                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium w-fit {{ $stock->quantity_on_hand > 10 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : ($stock->quantity_on_hand > 0 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300') }}">
                                                                                    {{ $stock->available_for_request }} available
                                                                                </span>
                                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 w-fit">
                                                                                    Fund: {{ $stock->fund_cluster ?: 'Unspecified' }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="flex items-center gap-2 pt-2 border-t dark:border-gray-700">
                                                                            <div class="flex items-center border dark:border-gray-600 rounded-md bg-white dark:bg-gray-800">
                                                                                <button type="button"
                                                                                    class="quantity-btn minus px-2 py-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 disabled:opacity-50 transition-colors"
                                                                                    data-action="decrease"
                                                                                    {{ $stock->quantity_on_hand <= 0 ? 'disabled' : '' }}>
                                                                                    <svg class="h-3 w-3 sm:h-4 sm:w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                                                    </svg>
                                                                                </button>
                                                                                <input type="number"
                                                                                    class="quantity-input w-12 sm:w-16 text-center text-sm py-1.5 border-none dark:bg-gray-800 dark:text-white focus:ring-0 focus:outline-none"
                                                                                    value="0" min="0"
                                                                                    max="{{ $stock->quantity_on_hand }}"
                                                                                    {{ $stock->quantity_on_hand <= 0 ? 'disabled' : '' }}>
                                                                                <button type="button"
                                                                                    class="quantity-btn plus px-2 py-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 disabled:opacity-50 transition-colors"
                                                                                    data-action="increase"
                                                                                    {{ $stock->quantity_on_hand <= 0 ? 'disabled' : '' }}>
                                                                                    <svg class="h-3 w-3 sm:h-4 sm:w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"></path>
                                                                                    </svg>
                                                                                </button>
                                                                            </div>
                                                                            <button type="button"
                                                                                class="add-to-cart flex-1 px-3 py-1.5 bg-[#ce201f] text-white text-xs sm:text-sm rounded hover:bg-[#a01b1a] flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                                                                {{ $stock->quantity_on_hand <= 0 ? 'disabled' : '' }}>
                                                                                <span class="hidden sm:inline">Add to Request</span>
                                                                                <span class="sm:hidden">Add</span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>

                                                        <!-- Selected Itemsss View (Initially Hidden) -->
                                                        <div id="selected-items-container" class="hidden mt-6">
                                                            <div class="bg-gray-50 dark:bg-gray-700 border dark:border-gray-600 rounded-lg p-3 sm:p-4">
                                                                <h5 class="font-medium text-gray-900 dark:text-white mb-3 text-sm sm:text-base">Selected Items</h5>
                                                                <div class="overflow-x-auto">
                                                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="px-2 sm:px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Item</th>
                                                                                <th class="px-2 sm:px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                                                    <span class="hidden sm:inline">Quantity</span>
                                                                                    <span class="sm:hidden">Qty</span>
                                                                                </th>
                                                                                <th class="px-2 sm:px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="selected-items-list" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                                            <!-- Selected items will be added here via JavaScript -->
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Hidden container for form submission -->
                                                        <div id="request-items-container" class="hidden">
                                                            <!-- Form inputs will be added here dynamically -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Fixed Footer -->
                                            <div class="px-3 sm:px-6 py-3 sm:py-4 border-t dark:border-gray-700 flex flex-col sm:flex-row justify-end gap-2 sm:gap-0 shrink-0 bg-gray-50 dark:bg-gray-800">
                                                <button type="button" id="cancelRequest"
                                                    class="w-full sm:w-auto px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mr-3 transition-colors">
                                                    Cancel
                                                </button>
                                                <button type="submit"
                                                    class="w-full sm:w-auto px-4 py-2 bg-[#ce201f] border border-transparent rounded-md text-sm font-medium text-white hover:bg-[#a01b1a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#ce201f] transition-colors">
                                                    Submit Request
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Replace the existing script section in your staff dashboard with this updated version -->
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        // Modal Controls
                                        const requestModal = document.getElementById('requestModal');
                                        const openRequestModal = document.getElementById('openRequestModal');
                                        const closeRequestModal = document.getElementById('closeRequestModal');
                                        const cancelRequest = document.getElementById('cancelRequest');

                                        openRequestModal.addEventListener('click', function() {
                                            requestModal.classList.remove('hidden');
                                            document.body.classList.add('overflow-hidden');  // <--- Prevent background scroll
                                        });

                                        function closeModal() {
                                            requestModal.classList.add('hidden');
                                            document.body.classList.remove('overflow-hidden');  // <--- Restore background scroll
                                            // Reset all product quantities when closing the modal
                                            resetProductQuantities();
                                        }

                                        closeRequestModal.addEventListener('click', closeModal);
                                        cancelRequest.addEventListener('click', closeModal);

                                        // Add CSS to ensure quantity inputs have sufficient width
                                        const style = document.createElement('style');
                                        style.textContent = `
                                            /* Increase width of quantity input fields to fit double digits */
                                            .product-card .quantity-input,
                                            #selected-items-list .quantity-input,
                                            .quantity-display,
                                            span.w-10 {
                                                min-width: 3rem !important; /* Ensure minimum width */
                                                width: 3rem !important; /* Fixed width */
                                                text-align: center;
                                                padding-left: 0.5rem !important;
                                                padding-right: 0.5rem !important;
                                            }

                                            /* Fix for the quantity input in the "Select Items to Request" modal */
                                            .quantity-wrapper {
                                                display: flex;
                                                align-items: center;
                                                min-width: 5rem; /* Ensure there's enough space */
                                            }

                                            .quantity-wrapper input[type="number"],
                                            .quantity-wrapper input {
                                                min-width: 2.5rem !important;
                                                width: 2.5rem !important;
                                                text-align: center;
                                                padding-left: 0.25rem !important;
                                                padding-right: 0.25rem !important;
                                                -moz-appearance: textfield; /* Remove spinner in Firefox */
                                            }

                                            /* Remove spinner arrows from number inputs in all browsers */
                                            input[type=number]::-webkit-inner-spin-button,
                                            input[type=number]::-webkit-outer-spin-button {
                                                -webkit-appearance: none;
                                                margin: 0;
                                            }

                                            /* Make Add to Request button match the counter height */
                                            .add-to-cart {
                                                padding-top: 0.25rem !important;
                                                padding-bottom: 0.25rem !important;
                                                height: 100% !important;
                                                display: flex !important;
                                                align-items: center !important;
                                                justify-content: center !important;
                                            }
                                        `;
                                        document.head.appendChild(style);

                                        // Product selection functionality (new UI)
                                        const productCards = document.querySelectorAll('.product-card');
                                        const selectedItemsContainer = document.getElementById('selected-items-container');
                                        const selectedItemsList = document.getElementById('selected-items-list');
                                        const requestItemsContainer = document.getElementById('request-items-container');
                                        const itemCountDisplay = document.getElementById('itemCount');
                                        const viewCartBtn = document.getElementById('viewCartBtn');
                                        const productsGrid = document.getElementById('products-grid');
                                        const itemSearch = document.getElementById('item-search');

                                        let selectedItems = [];
                                        let itemIndex = 0;

                                        // Track original available quantities
                                        const originalAvailableQuantities = {};
                                        productCards.forEach(card => {
                                            const supplyId = card.getAttribute('data-supply-id');
                                            const availableQuantity = parseInt(card.getAttribute('data-available'), 10);
                                            originalAvailableQuantities[supplyId] = availableQuantity;
                                        });

                                        // Apply classes to existing number inputs
                                        const modalQuantityInputs = document.querySelectorAll('#requestModal .quantity-input');
                                        modalQuantityInputs.forEach(input => {
                                            const parent = input.parentElement;
                                            if (!parent.classList.contains('quantity-wrapper')) {
                                                parent.classList.add('quantity-wrapper');
                                            }
                                        });

                                        // Update this section in your existing JavaScript code:
                                        // Replace the "Create Add All to Request button" section with this responsive version:

                                        // Create "Add All to Request" button to add all selected quantities at once
                                        const itemsHeaderDiv = document.querySelector('.flex.flex-col.sm\\:flex-row.justify-between.items-start.sm\\:items-center.gap-3.mb-4');
                                        if (itemsHeaderDiv) {
                                            const addAllToRequestBtn = document.createElement('button');
                                            addAllToRequestBtn.type = 'button';
                                            // Updated responsive classes
                                            addAllToRequestBtn.className =
                                                'px-2 sm:px-3 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-900 flex items-center shadow-sm mr-2 whitespace-nowrap text-sm';
                                            // Updated responsive HTML with mobile/desktop text variations
                                            addAllToRequestBtn.innerHTML = `
                                                    <svg class="h-4 w-4 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    <span class="hidden sm:inline">Add All to Request</span>
                                                    <span class="sm:hidden">Add All</span>
                                                `;

                                            // Find the container that has the viewCartBtn and insert our button before it
                                            const buttonsContainer = itemsHeaderDiv.querySelector('.flex.items-center');
                                            const viewCartBtn = document.getElementById('viewCartBtn');

                                            // Insert before the viewCartBtn
                                            buttonsContainer.insertBefore(addAllToRequestBtn, viewCartBtn);

                                            // Add event listener to the button (keep your existing functionality)
                                            addAllToRequestBtn.addEventListener('click', function() {
                                                let anyItemsAdded = false;

                                                // Loop through all product cards and add any with quantity > 0
                                                productCards.forEach(card => {
                                                    const quantityInput = card.querySelector('.quantity-input');
                                                    const quantity = parseInt(quantityInput.value, 10);

                                                    if (quantity > 0) {
                                                        const supplyId = card.getAttribute('data-supply-id');
                                                        const supplyName = card.getAttribute('data-name');
                                                        const maxAvailable = originalAvailableQuantities[supplyId];

                                                        addItemToSelection(supplyId, supplyName, quantity, maxAvailable);
                                                        quantityInput.value = 0; // Reset input after adding
                                                        anyItemsAdded = true;
                                                    }
                                                });

                                                if (!anyItemsAdded) {
                                                    // Alert user if no items were selected
                                                    alert('No items selected. Please set quantities for items you wish to request.');
                                                }
                                            });
                                        }

                                        // Search functionality
                                        if (itemSearch) {
                                            itemSearch.addEventListener('input', function(e) {
                                                const searchTerm = e.target.value.toLowerCase();

                                                productCards.forEach(card => {
                                                    const productName = card.getAttribute('data-name').toLowerCase();
                                                    if (productName.includes(searchTerm)) {
                                                        card.classList.remove('hidden');
                                                    } else {
                                                        card.classList.add('hidden');
                                                    }
                                                });
                                            });
                                        }

                                        // View cart button
                                        if (viewCartBtn) {
                                            viewCartBtn.addEventListener('click', function() {
                                                if (selectedItems.length > 0) {
                                                    // Toggle visibility of containers
                                                    if (selectedItemsContainer.classList.contains('hidden')) {
                                                        // Show selected items, hide products grid
                                                        selectedItemsContainer.classList.remove('hidden');
                                                        productsGrid.classList.add('hidden');

                                                        // Change button text to "Back to Items"
                                                        viewCartBtn.innerHTML = `<svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                    </svg>Back to Items`;
                                                    } else {
                                                        // Show products grid, hide selected items
                                                        selectedItemsContainer.classList.add('hidden');
                                                        productsGrid.classList.remove('hidden');

                                                        // Change button text to "View Selected"
                                                        viewCartBtn.innerHTML = `<svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>View Selected (<span id="itemCount">${selectedItems.length}</span>)`;
                                                    }

                                                    // Apply styling to quantity spans in the selected items list
                                                    setTimeout(() => {
                                                        const quantitySpans = document.querySelectorAll(
                                                            '#selected-items-list .w-10');
                                                        quantitySpans.forEach(span => {
                                                            span.classList.add('quantity-display');
                                                        });
                                                    }, 100);
                                                }
                                            });
                                        }

                                        // Quantity buttons
                                        productCards.forEach(card => {
                                            const minusBtn = card.querySelector('.quantity-btn.minus');
                                            const plusBtn = card.querySelector('.quantity-btn.plus');
                                            const quantityInput = card.querySelector('.quantity-input');
                                            const addToCartBtn = card.querySelector('.add-to-cart');
                                            const supplyId = card.getAttribute('data-supply-id');
                                            const supplyName = card.getAttribute('data-name');

                                            // Update available quantity span reference
                                            const availableSpan = card.querySelector(
                                                '.inline-flex.items-center.px-2\\.5.py-0\\.5.rounded-full');

                                            if (minusBtn && plusBtn && quantityInput) {
                                                minusBtn.addEventListener('click', function() {
                                                    let currentValue = parseInt(quantityInput.value, 10);
                                                    if (currentValue > 0) {
                                                        quantityInput.value = currentValue - 1;
                                                    }
                                                });

                                                plusBtn.addEventListener('click', function() {
                                                    let currentValue = parseInt(quantityInput.value, 10);
                                                    const currentAvailable = parseInt(card.getAttribute('data-available'), 10);

                                                    if (currentValue < currentAvailable) {
                                                        quantityInput.value = currentValue + 1;
                                                    }
                                                });

                                                quantityInput.addEventListener('change', function() {
                                                    let currentValue = parseInt(quantityInput.value, 10);
                                                    const currentAvailable = parseInt(card.getAttribute('data-available'), 10);

                                                    if (isNaN(currentValue) || currentValue < 0) {
                                                        quantityInput.value = 0;
                                                    } else if (currentValue > currentAvailable) {
                                                        quantityInput.value = currentAvailable;
                                                    }
                                                });

                                                addToCartBtn.addEventListener('click', function() {
                                                    const quantity = parseInt(quantityInput.value, 10);
                                                    if (quantity > 0) {
                                                        addItemToSelection(supplyId, supplyName, quantity,
                                                            originalAvailableQuantities[supplyId]);
                                                        quantityInput.value = 0;
                                                    }
                                                });
                                            }
                                        });

                                        function addItemToSelection(supplyId, supplyName, quantity, maxOriginalQuantity) {
                                            const card = document.querySelector(`.product-card[data-supply-id="${supplyId}"]`);
                                            const currentAvailable = parseInt(card.getAttribute('data-available'), 10);
                                            const description = card.getAttribute('data-description') || '';

                                            // Don't allow adding more than what's available
                                            if (quantity > currentAvailable) {
                                                quantity = currentAvailable;
                                            }

                                            if (quantity <= 0) {
                                                return; // Nothing to add
                                            }

                                            // Check if item already exists in selection
                                            const existingItemIndex = selectedItems.findIndex(item => item.supplyId === supplyId);

                                            if (existingItemIndex >= 0) {
                                                // Update existing item
                                                const newQuantity = selectedItems[existingItemIndex].quantity + quantity;

                                                // Ensure we don't exceed original max quantity
                                                if (newQuantity <= maxOriginalQuantity) {
                                                    // Update the item quantity
                                                    selectedItems[existingItemIndex].quantity = newQuantity;

                                                    // Update available quantity on the card
                                                    updateAvailableQuantity(supplyId, -quantity);
                                                } else {
                                                    // Show notification that max quantity reached
                                                    alert(`Maximum available quantity (${maxOriginalQuantity}) reached for ${supplyName}`);

                                                    // Set to max available
                                                    const additionalQty = maxOriginalQuantity - selectedItems[existingItemIndex].quantity;
                                                    if (additionalQty > 0) {
                                                        selectedItems[existingItemIndex].quantity = maxOriginalQuantity;
                                                        updateAvailableQuantity(supplyId, -additionalQty);
                                                    }
                                                }
                                            } else {
                                                // Add new item
                                                selectedItems.push({
                                                    supplyId: supplyId,
                                                    name: supplyName,
                                                    description: description,
                                                    quantity: quantity,
                                                    maxAvailable: maxOriginalQuantity,
                                                    index: itemIndex++
                                                });

                                                // Update available quantity on the card
                                                updateAvailableQuantity(supplyId, -quantity);
                                            }

                                            updateSelectedItemsList();
                                            updateFormInputs();
                                        }

                                        function updateAvailableQuantity(supplyId, change) {
                                            const card = document.querySelector(`.product-card[data-supply-id="${supplyId}"]`);
                                            if (!card) return;

                                            const availableSpan = card.querySelector(
                                            '.inline-flex.items-center.px-2\\.5.py-0\\.5.rounded-full');
                                            const currentAvailable = parseInt(card.getAttribute('data-available'), 10);
                                            const newAvailable = currentAvailable + change;

                                            // Update data attribute
                                            card.setAttribute('data-available', newAvailable);

                                            // Update display text
                                            if (availableSpan) {
                                                availableSpan.textContent = `${newAvailable} available`;

                                                // Update color based on availability
                                                availableSpan.className =
                                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium';

                                                if (newAvailable > 10) {
                                                    availableSpan.classList.add('bg-green-100', 'text-green-800', 'dark:bg-green-900',
                                                        'dark:text-green-300');
                                                } else if (newAvailable > 0) {
                                                    availableSpan.classList.add('bg-yellow-100', 'text-yellow-800', 'dark:bg-yellow-900',
                                                        'dark:text-yellow-300');
                                                } else {
                                                    availableSpan.classList.add('bg-red-100', 'text-red-800', 'dark:bg-red-900',
                                                        'dark:text-red-300');
                                                }
                                            }

                                            // Update max value of quantity input
                                            const quantityInput = card.querySelector('.quantity-input');
                                            if (quantityInput) {
                                                quantityInput.max = newAvailable;
                                            }

                                            // Enable/disable buttons based on availability
                                            const minusBtn = card.querySelector('.quantity-btn.minus');
                                            const plusBtn = card.querySelector('.quantity-btn.plus');
                                            const addToCartBtn = card.querySelector('.add-to-cart');

                                            if (newAvailable <= 0) {
                                                if (minusBtn) minusBtn.disabled = true;
                                                if (plusBtn) plusBtn.disabled = true;
                                                if (addToCartBtn) addToCartBtn.disabled = true;
                                                if (quantityInput) quantityInput.disabled = true;
                                            } else {
                                                if (minusBtn) minusBtn.disabled = false;
                                                if (plusBtn) plusBtn.disabled = false;
                                                if (addToCartBtn) addToCartBtn.disabled = false;
                                                if (quantityInput) quantityInput.disabled = false;
                                            }
                                        }

                                        function removeItemFromSelection(index) {
                                            // Find the item to remove
                                            const itemToRemove = selectedItems.find(item => item.index === index);

                                            if (itemToRemove) {
                                                // Restore the available quantity
                                                updateAvailableQuantity(itemToRemove.supplyId, itemToRemove.quantity);

                                                // Remove the item from the selection
                                                selectedItems = selectedItems.filter(item => item.index !== index);
                                            }

                                            updateSelectedItemsList();
                                            updateFormInputs();
                                        }

                                        function updateSelectedItemsList() {
                                            // Update counter
                                            if (itemCountDisplay) {
                                                itemCountDisplay.textContent = selectedItems.length;
                                            }

                                            if (!selectedItemsList) return;

                                            // Clear current list
                                            selectedItemsList.innerHTML = '';

                                            if (selectedItems.length === 0) {
                                                selectedItemsList.innerHTML = `
                                                        <tr>
                                                            <td colspan="3" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                                                No items selected. Add items from the product list.
                                                            </td>
                                                        </tr>
                                                    `;
                                                // Hide the selected items view if visible
                                                if (selectedItemsContainer && !selectedItemsContainer.classList.contains('hidden')) {
                                                    // Make sure we show products grid and hide selected items
                                                    selectedItemsContainer.classList.add('hidden');
                                                    productsGrid.classList.remove('hidden');

                                                    // Update the button text
                                                    if (viewCartBtn) {
                                                        viewCartBtn.innerHTML = `<svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>View Selected (<span id="itemCount">0</span>)`;
                                                    }
                                                }
                                            } else {
                                                // Add each item to the list
                                                selectedItems.forEach(item => {
                                                    const row = document.createElement('tr');
                                                    row.innerHTML = `
                                                        <td class="px-4 py-3">
                                                            <div class="flex items-center">
                                                                <div class="flex-shrink-0 h-10 w-10 bg-gray-200 dark:bg-gray-700 rounded-md flex items-center justify-center mr-3">
                                                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                                    </svg>
                                                                </div>
                                                                <div>
                                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">${item.name}</p>
                                                                    ${item.description ? `<p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2">${item.description}</p>` : ''}
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            <div class="flex items-center">
                                                                <button type="button" class="edit-quantity-btn minus px-2 py-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300" data-index="${item.index}" data-action="decrease">
                                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                                    </svg>
                                                                </button>
                                                                <span class="w-10 text-center text-gray-900 dark:text-white quantity-display">${item.quantity}</span>
                                                                <button type="button" class="edit-quantity-btn plus px-2 py-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300" data-index="${item.index}" data-action="increase">
                                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            <button type="button" class="remove-item-btn text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-500" data-index="${item.index}">
                                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </td>
                                                    `;
                                                    selectedItemsList.appendChild(row);
                                                });

                                                // Add event listeners to the new buttons
                                                document.querySelectorAll('.edit-quantity-btn').forEach(btn => {
                                                    btn.addEventListener('click', function() {
                                                        const index = parseInt(this.getAttribute('data-index'), 10);
                                                        const action = this.getAttribute('data-action');
                                                        editItemQuantity(index, action);
                                                    });
                                                });

                                                document.querySelectorAll('.remove-item-btn').forEach(btn => {
                                                    btn.addEventListener('click', function() {
                                                        const index = parseInt(this.getAttribute('data-index'), 10);
                                                        removeItemFromSelection(index);
                                                    });
                                                });
                                            }
                                        }

                                        function editItemQuantity(index, action) {
                                            const itemIndex = selectedItems.findIndex(item => item.index === index);
                                            if (itemIndex >= 0) {
                                                const card = document.querySelector(
                                                    `.product-card[data-supply-id="${selectedItems[itemIndex].supplyId}"]`);
                                                const currentAvailable = parseInt(card.getAttribute('data-available'), 10);
                                                const supplyId = selectedItems[itemIndex].supplyId;

                                                if (action === 'increase') {
                                                    // Check if we have any available quantity left to add
                                                    if (currentAvailable > 0) {
                                                        selectedItems[itemIndex].quantity++;
                                                        // Update the available quantity display on the card
                                                        updateAvailableQuantity(supplyId, -1);
                                                    }
                                                } else if (action === 'decrease' && selectedItems[itemIndex].quantity > 1) {
                                                    selectedItems[itemIndex].quantity--;
                                                    // Update the available quantity display on the card
                                                    updateAvailableQuantity(supplyId, 1);
                                                }

                                                updateSelectedItemsList();
                                                updateFormInputs();
                                            }
                                        }

                                        function updateFormInputs() {
                                            if (!requestItemsContainer) return;

                                            // Clear current inputs
                                            requestItemsContainer.innerHTML = '';

                                            // Add inputs for form submission
                                            selectedItems.forEach((item, i) => {
                                                const supplyIdInput = document.createElement('input');
                                                supplyIdInput.type = 'hidden';
                                                supplyIdInput.name = `supplies[${i}][supply_id]`;
                                                supplyIdInput.value = item.supplyId;

                                                const quantityInput = document.createElement('input');
                                                quantityInput.type = 'hidden';
                                                quantityInput.name = `supplies[${i}][quantity]`;
                                                quantityInput.value = item.quantity;

                                                requestItemsContainer.appendChild(supplyIdInput);
                                                requestItemsContainer.appendChild(quantityInput);
                                            });
                                        }

                                        // Function to reset product quantities to original values
                                        function resetProductQuantities() {
                                            // Reset selected items array
                                            selectedItems = [];

                                            // Reset all product cards to original quantities
                                            productCards.forEach(card => {
                                                const supplyId = card.getAttribute('data-supply-id');
                                                const originalQuantity = originalAvailableQuantities[supplyId];

                                                // Reset quantity input
                                                const quantityInput = card.querySelector('.quantity-input');
                                                if (quantityInput) {
                                                    quantityInput.value = 0;
                                                    quantityInput.max = originalQuantity;
                                                    quantityInput.disabled = originalQuantity <= 0;
                                                }

                                                // Reset available quantity display
                                                card.setAttribute('data-available', originalQuantity);
                                                const availableSpan = card.querySelector(
                                                    '.inline-flex.items-center.px-2\\.5.py-0\\.5.rounded-full');
                                                if (availableSpan) {
                                                    availableSpan.textContent = `${originalQuantity} available`;

                                                    // Update color based on original availability
                                                    availableSpan.className =
                                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium';

                                                    if (originalQuantity > 10) {
                                                        availableSpan.classList.add('bg-green-100', 'text-green-800',
                                                            'dark:bg-green-900', 'dark:text-green-300');
                                                    } else if (originalQuantity > 0) {
                                                        availableSpan.classList.add('bg-yellow-100', 'text-yellow-800',
                                                            'dark:bg-yellow-900', 'dark:text-yellow-300');
                                                    } else {
                                                        availableSpan.classList.add('bg-red-100', 'text-red-800', 'dark:bg-red-900',
                                                            'dark:text-red-300');
                                                    }
                                                }

                                                // Enable/disable buttons based on original availability
                                                const minusBtn = card.querySelector('.quantity-btn.minus');
                                                const plusBtn = card.querySelector('.quantity-btn.plus');
                                                const addToCartBtn = card.querySelector('.add-to-cart');

                                                const shouldDisable = originalQuantity <= 0;
                                                if (minusBtn) minusBtn.disabled = shouldDisable;
                                                if (plusBtn) plusBtn.disabled = shouldDisable;
                                                if (addToCartBtn) addToCartBtn.disabled = shouldDisable;
                                            });

                                            // Update UI
                                            updateSelectedItemsList();
                                            updateFormInputs();

                                            // Reset view back to products grid if needed
                                            if (selectedItemsContainer && !selectedItemsContainer.classList.contains('hidden')) {
                                                selectedItemsContainer.classList.add('hidden');
                                                productsGrid.classList.remove('hidden');

                                                // Update the button text
                                                if (viewCartBtn) {
                                                    viewCartBtn.innerHTML = `<svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>View Selected (<span id="itemCount">0</span>)`;
                                                }
                                            }
                                        }

                                        // Search and Filter Functionality
                                        const searchInput = document.getElementById('request-search-input');
                                        const statusFilters = document.querySelectorAll('.status-filter');
                                        const requestRows = document.querySelectorAll('.request-row');
                                        const noRequestsRow = document.getElementById('no-requests-row');

                                        if (statusFilters.length > 0) {
                                            // Handle status filter clicks
                                            statusFilters.forEach(filter => {
                                                filter.addEventListener('click', function() {
                                                    // Remove active class from all filters
                                                    statusFilters.forEach(f => {
                                                        f.classList.remove('bg-blue-100', 'dark:bg-blue-900',
                                                            'text-blue-800', 'dark:text-blue-300', 'active-filter');
                                                        // Add default text colors back
                                                        f.classList.add('text-gray-700', 'dark:text-gray-300');
                                                    });

                                                    // Add active class to clicked filter
                                                    this.classList.add('bg-blue-100', 'dark:bg-blue-900', 'text-blue-800',
                                                        'dark:text-blue-300', 'active-filter');
                                                    // Remove default text colors from active filter
                                                    this.classList.remove('text-gray-700', 'dark:text-gray-300');

                                                    // Apply filters
                                                    applyFilters();
                                                });
                                            });
                                        }

                                        if (searchInput) {
                                            // Handle search input
                                            searchInput.addEventListener('input', function() {
                                                applyFilters();
                                            });
                                        }

                                        // Function to apply both search and status filters
                                        function applyFilters() {
                                            if (!searchInput || !requestRows.length) return;

                                            const searchValue = searchInput.value.toLowerCase().trim();
                                            const activeFilter = document.querySelector('.status-filter.active-filter');
                                            const statusFilter = activeFilter ? activeFilter.getAttribute('data-status') : 'all';

                                            let visibleCount = 0;

                                            requestRows.forEach(row => {
                                                const rowStatus = row.getAttribute('data-status');
                                                const rowText = row.textContent.toLowerCase();
                                                const statusMatch = statusFilter === 'all' || rowStatus === statusFilter;
                                                const searchMatch = searchValue === '' || rowText.includes(searchValue);

                                                if (statusMatch && searchMatch) {
                                                    row.classList.remove('hidden');
                                                    visibleCount++;
                                                } else {
                                                    row.classList.add('hidden');
                                                }
                                            });

                                            // Show/hide "no requests" message
                                            if (visibleCount === 0 && noRequestsRow) {
                                                noRequestsRow.classList.remove('hidden');
                                            } else if (noRequestsRow) {
                                                noRequestsRow.classList.add('hidden');
                                            }
                                        }

                                        // ========================
                                        // E-SIGNATURE CONFIRMATION MODAL WITH STOCK VALIDATION
                                        // ========================

                                        // Get the form for requisition submission
                                        const risForm = document.querySelector('form[action="{{ route('ris.store') }}"]');

                                        if (risForm) {
                                            // Override the form submission
                                            risForm.addEventListener('submit', function(event) {
                                                // Prevent default form submission
                                                event.preventDefault();

                                                // Check if any items are selected
                                                if (selectedItems.length === 0) {
                                                    Swal.fire({
                                                        title: 'No Items Selected',
                                                        text: 'Please select at least one item before submitting your request.',
                                                        icon: 'warning',
                                                        confirmButtonColor: '#3085d6'
                                                    });
                                                    return;
                                                }

                                                // Get purpose text for confirmation
                                                const purposeText = document.querySelector('textarea[name="purpose"]').value.trim();

                                                // User's signature path for preview if available
                                                const userSignaturePath =
                                                    "{{ Auth::user()->signature_path ? Storage::url(Auth::user()->signature_path) : '' }}";

                                                // Signature preview HTML - will show if e-signature is selected
                                                const signaturePreviewHtml = userSignaturePath ?
                                                    `<div class="mt-3 border rounded p-2 text-center hidden" id="signature-preview-container">
                                                        <p class="text-sm mb-1">Your signature will appear as:</p>
                                                        <img src="${userSignaturePath}" alt="Your signature" class="max-h-16 mx-auto">
                                                    </div>` : '';

                                                // Show confirmation dialog with signature options
                                                Swal.fire({
                                                    title: 'Confirm Requisition',
                                                    html: `
                                                        <div class="text-left mb-4">
                                                            <p class="mb-3">You are about to submit a requisition with <strong>${selectedItems.length}</strong> item(s).</p>
                                                            <p class="mb-4"><strong>Purpose:</strong> ${purposeText || 'Not specified'}</p>

                                                            <div class="mb-4">
                                                                <label class="block text-sm font-bold mb-2">
                                                                    How would you like to sign this request?
                                                                </label>
                                                                <div class="flex items-center mb-2">
                                                                    <input type="radio" id="swal-esign" name="signature_type" value="esign" class="mr-2" ${!userSignaturePath ? 'disabled' : ''}>
                                                                    <label for="swal-esign" class="text-sm">Use E-Signature</label>
                                                                </div>
                                                                <div class="flex items-center">
                                                                    <input type="radio" id="swal-sgd" name="signature_type" value="sgd" class="mr-2" checked>
                                                                    <label for="swal-sgd" class="text-sm">Mark as SGD (Sign physically later)</label>
                                                                </div>
                                                                ${!userSignaturePath ?
                                                                    '<p class="text-xs text-red-500 mt-1">You need to upload a signature in your profile to use E-Signature.</p>' : ''}
                                                            </div>

                                                            ${signaturePreviewHtml}

                                                            <div id="esign-terms" class="hidden bg-gray-100 p-3 rounded text-xs mt-3">
                                                                <p class="font-bold mb-1">E-Signature Terms and Conditions:</p>
                                                                <ul class="list-disc pl-4 space-y-1">
                                                                    <li>I authorize the use of my electronic signature for this requisition.</li>
                                                                    <li>I understand this e-signature has the same legal validity as my handwritten signature.</li>
                                                                    <li>I confirm all details provided in this requisition are accurate and complete.</li>
                                                                </ul>
                                                                <div class="mt-2">
                                                                    <input type="checkbox" id="agree-terms" class="mr-1">
                                                                    <label for="agree-terms" class="text-xs">I agree to the above terms</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    `,
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#3085d6',
                                                    cancelButtonColor: '#d33',
                                                    confirmButtonText: 'Submit Request',
                                                    cancelButtonText: 'Cancel',
                                                    focusConfirm: false,
                                                    didRender: () => {
                                                        // Disable the confirm button initially if e-signature is selected (terms not agreed)
                                                        const confirmButton = Swal.getConfirmButton();
                                                        const agreeTerms = document.getElementById('agree-terms');
                                                        const esignRadio = document.getElementById('swal-esign');
                                                        const sgdRadio = document.getElementById('swal-sgd');
                                                        const termsDiv = document.getElementById('esign-terms');
                                                        const signaturePreview = document.getElementById(
                                                            'signature-preview-container');

                                                        // Function to toggle the confirm button state based on selections
                                                        const updateConfirmButtonState = () => {
                                                            if (esignRadio.checked && !agreeTerms.checked) {
                                                                confirmButton.disabled = true;
                                                                confirmButton.classList.add('opacity-50',
                                                                    'cursor-not-allowed');
                                                            } else {
                                                                confirmButton.disabled = false;
                                                                confirmButton.classList.remove('opacity-50',
                                                                    'cursor-not-allowed');
                                                            }
                                                        };

                                                        // Add event listeners
                                                        esignRadio.addEventListener('change', function() {
                                                            if (this.checked) {
                                                                termsDiv.classList.remove('hidden');
                                                                if (signaturePreview) signaturePreview.classList
                                                                    .remove('hidden');
                                                                updateConfirmButtonState();
                                                            }
                                                        });

                                                        sgdRadio.addEventListener('change', function() {
                                                            if (this.checked) {
                                                                termsDiv.classList.add('hidden');
                                                                if (signaturePreview) signaturePreview.classList
                                                                    .add('hidden');
                                                                updateConfirmButtonState();
                                                            }
                                                        });

                                                        if (agreeTerms) {
                                                            agreeTerms.addEventListener('change', updateConfirmButtonState);
                                                        }

                                                        // Initialize state
                                                        updateConfirmButtonState();
                                                    },
                                                    preConfirm: () => {
                                                        const signatureType = document.querySelector(
                                                            'input[name="signature_type"]:checked').value;

                                                        // If e-signature selected, check if terms are agreed to
                                                        if (signatureType === 'esign') {
                                                            const termsAgreed = document.getElementById('agree-terms')
                                                                .checked;
                                                            if (!termsAgreed) {
                                                                Swal.showValidationMessage(
                                                                    'You must agree to the terms to use e-signature');
                                                                return false;
                                                            }
                                                        }

                                                        return signatureType;
                                                    }
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        // Add signature type as hidden field
                                                        let signatureInput = risForm.querySelector(
                                                            'input[name="signature_type"]');

                                                        if (!signatureInput) {
                                                            signatureInput = document.createElement('input');
                                                            signatureInput.type = 'hidden';
                                                            signatureInput.name = 'signature_type';
                                                            risForm.appendChild(signatureInput);
                                                        }

                                                        signatureInput.value = result.value;

                                                        // Show loading indicator
                                                        Swal.fire({
                                                            title: 'Submitting...',
                                                            html: 'Your requisition is being processed',
                                                            allowOutsideClick: false,
                                                            didOpen: () => {
                                                                Swal.showLoading();
                                                            }
                                                        });

                                                        // Submit the form using AJAX to handle stock validation
                                                        const formData = new FormData(risForm);

                                                        fetch(risForm.action, {
                                                                method: 'POST',
                                                                body: formData,
                                                                headers: {
                                                                    'X-Requested-With': 'XMLHttpRequest',
                                                                    'X-CSRF-TOKEN': document.querySelector(
                                                                        'meta[name="csrf-token"]').getAttribute(
                                                                        'content')
                                                                }
                                                            })
                                                            .then(response => response.json())
                                                            .then(data => {
                                                                if (data.success) {
                                                                    // Success - show success message and reload page
                                                                    Swal.fire({
                                                                        title: 'Success!',
                                                                        text: data.message,
                                                                        icon: 'success',
                                                                        confirmButtonColor: '#10B981'
                                                                    }).then(() => {
                                                                        // Reload the page to refresh the stock data
                                                                        window.location.reload();
                                                                    });
                                                                } else if (data.type === 'stock_validation_error') {
                                                                    // Stock validation error - show detailed error message
                                                                    let errorHtml = `
                                                                            <div class="text-left">
                                                                                <p class="mb-3 text-red-600 font-semibold">${data.message}</p>
                                                                                <div class="bg-red-50 border border-red-200 rounded p-3 mb-3">
                                                                                    <h4 class="font-semibold text-red-800 mb-2">Issues found:</h4>
                                                                                    <ul class="space-y-2">
                                                                        `;

                                                                    data.errors.forEach(error => {
                                                                                                        errorHtml += `
                                                                        <li class="text-sm">
                                                                            <strong>${error.supply_name || 'Unknown Item'}:</strong><br>
                                                                            ${error.message}
                                                                        </li>
                                                                    `;
                                                                    });

                                                                    errorHtml += `
                                                                                </ul>
                                                                            </div>
                                                                            <p class="text-sm text-gray-600">
                                                                                <strong>Recommendation:</strong> Please refresh the page or adjust your quantities and try again.
                                                                            </p>
                                                                        </div>
                                                                    `;

                                                                    Swal.fire({
                                                                        title: 'Stock Availability Changed',
                                                                        html: errorHtml,
                                                                        icon: 'warning',
                                                                        showCancelButton: true,
                                                                        confirmButtonText: 'Refresh Page',
                                                                        cancelButtonText: 'Adjust Manually',
                                                                        confirmButtonColor: '#3085d6',
                                                                        cancelButtonColor: '#6B7280'
                                                                    }).then((result) => {
                                                                        if (result.isConfirmed) {
                                                                            // User chose to refresh the page
                                                                            window.location.reload();
                                                                        } else {
                                                                            // User chose to adjust manually
                                                                            // Update the UI with current availability
                                                                            if (data.current_availability) {
                                                                                Object.keys(data
                                                                                        .current_availability)
                                                                                    .forEach(supplyId => {
                                                                                        const newAvailable =
                                                                                            data
                                                                                            .current_availability[
                                                                                                supplyId];

                                                                                        // Find the card and update its data attribute
                                                                                        const card = document
                                                                                            .querySelector(
                                                                                                `.product-card[data-supply-id="${supplyId}"]`
                                                                                                );
                                                                                        if (card) {
                                                                                            // Calculate difference and update
                                                                                            const
                                                                                                currentDisplayed =
                                                                                                parseInt(card
                                                                                                    .getAttribute(
                                                                                                        'data-available'
                                                                                                        ), 10);
                                                                                            const difference =
                                                                                                newAvailable -
                                                                                                currentDisplayed;

                                                                                            updateAvailableQuantity
                                                                                                (supplyId,
                                                                                                    difference);
                                                                                            originalAvailableQuantities
                                                                                                [supplyId] =
                                                                                                newAvailable;
                                                                                        }
                                                                                    });
                                                                            }

                                                                            // Show a toast notification
                                                                            Swal.fire({
                                                                                toast: true,
                                                                                position: 'top-end',
                                                                                icon: 'info',
                                                                                title: 'Stock quantities updated',
                                                                                showConfirmButton: false,
                                                                                timer: 3000
                                                                            });
                                                                        }
                                                                    });
                                                                } else {
                                                                    // Other error
                                                                    Swal.fire({
                                                                        title: 'Error!',
                                                                        text: data.message ||
                                                                            'An error occurred while submitting your request.',
                                                                        icon: 'error',
                                                                        confirmButtonColor: '#EF4444'
                                                                    });
                                                                }
                                                            })
                                                            .catch(error => {
                                                                console.error('Error:', error);
                                                                Swal.fire({
                                                                    title: 'Error!',
                                                                    text: 'A network error occurred. Please try again.',
                                                                    icon: 'error',
                                                                    confirmButtonColor: '#EF4444'
                                                                });
                                                            });
                                                    }
                                                });
                                            });
                                        }
                                    });

                                    // Functions for search input (preserved from original)
                                    function toggleClearRequestButton() {
                                        const input = document.getElementById('request-search-input');
                                        const clearBtn = document.getElementById('clearRequestButton');
                                        if (input && clearBtn) {
                                            clearBtn.style.display = input.value.trim().length > 0 ? 'flex' : 'none';
                                        }
                                    }

                                    function clearRequestSearch() {
                                        const input = document.getElementById('request-search-input');
                                        if (input) {
                                            input.value = '';
                                            document.getElementById('clearRequestButton').style.display = 'none';
                                            // Trigger input event to update the filters
                                            const event = new Event('input', {
                                                bubbles: true
                                            });
                                            input.dispatchEvent(event);
                                        }
                                    }
                                </script>

                            </div>

                            <!-- Received Supplies Section (initially hidden) -->
                            <div id="received-supplies" class="content-section bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 hidden">
                                <!-- Header -->
                                <div class="mb-6">
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Supplies Received</h2>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">View and manage supplies that have been issued to you. Track all requisitions you've received across different departments.</p>
                                </div>

                                <!-- Modern Table Container -->
                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <!-- Minimal Table Header -->
                                            <thead class="bg-gray-50 dark:bg-gray-700">
                                                <tr>
                                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        RIS No
                                                    </th>
                                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        Date
                                                    </th>
                                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        Requested By
                                                    </th>
                                                    <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                        Actions
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                @forelse($receivedRequisitions as $requisition)
                                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                                        <!-- RIS Number -->
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                                {{ $requisition->ris_no }}
                                                            </div>
                                                        </td>

                                                        <!-- Date -->
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex flex-col">
                                                                <span class="text-sm text-gray-900 dark:text-gray-300">
                                                                    {{ $requisition->ris_date->format('M d, Y') }}
                                                                </span>
                                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                                    {{ $requisition->created_at->format('h:i A') }}
                                                                </span>
                                                            </div>
                                                        </td>

                                                        <!-- Requested By -->
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900 dark:text-gray-300">
                                                                {{ optional($requisition->requester)->name ?? 'N/A' }}
                                                            </div>
                                                        </td>

                                                        <!-- Actions -->
                                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                                            @php
                                                                $canReceive = $requisition->received_by == Auth::id() &&
                                                                            !$requisition->received_at &&
                                                                            $requisition->status === 'posted' &&
                                                                            $requisition->issued_at;
                                                            @endphp

                                                            <div class="flex items-center justify-center gap-2">
                                                                <!-- View Button -->
                                                                <a href="{{ route('ris.show', $requisition) }}"
                                                                    class="inline-flex items-center justify-center w-8 h-8 text-[#ce201f] dark:text-[#ce201f] hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-200"
                                                                    data-tooltip-target="tooltip-view-{{ $loop->index }}"
                                                                    data-tooltip-placement="top">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                                    </svg>
                                                                </a>
                                                                <div id="tooltip-view-{{ $loop->index }}" role="tooltip"
                                                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                                    View Details
                                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                                </div>

                                                                <!-- Receive Button -->
                                                                @if ($canReceive)
                                                                    <form action="{{ route('ris.receive', $requisition) }}" method="POST" class="inline" id="receive-form-{{ $requisition->ris_id }}">
                                                                        @csrf
                                                                        <button type="button"
                                                                            class="inline-flex items-center justify-center w-8 h-8 text-white bg-[#ce201f] hover:bg-[#a01b1a] rounded-lg transition-all duration-200"
                                                                            data-tooltip-target="tooltip-receive-{{ $loop->index }}"
                                                                            data-tooltip-placement="top"
                                                                            onclick="confirmReceive({{ $requisition->ris_id }})">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                            </svg>
                                                                        </button>
                                                                    </form>
                                                                    <div id="tooltip-receive-{{ $loop->index }}" role="tooltip"
                                                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                                        Receive Supplies
                                                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                                                    </div>
                                                                @elseif($requisition->received_at)
                                                                    <div class="flex items-center space-x-2">
                                                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                                                        <span class="text-xs text-gray-600 dark:text-gray-400">
                                                                            Received {{ $requisition->received_at->format('M d, Y') }}
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="px-6 py-12 text-center">
                                                            <!-- Modern Empty State -->
                                                            <div class="flex flex-col items-center justify-center">
                                                                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                                                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8 4-8-4V5l8 4 8-4m0 5l-8 4-8-4"></path>
                                                                    </svg>
                                                                </div>
                                                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No supplies received</h3>
                                                                <p class="text-sm text-gray-500 dark:text-gray-400">You haven't received any supplies yet</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Pagination -->
                                @if ($receivedRequisitions->hasPages())
                                    <div class="mt-6">
                                        {{ $receivedRequisitions->links() }}
                                    </div>
                                @endif
                            </div>

                            <!-- Properties Section (initially hidden) -->
                            <div id="properties"
                                class="content-section bg-white dark:bg-gray-700 shadow rounded-lg p-6 hidden">
                                <h2 class="text-xl font-bold mb-4 dark:text-white">My Properties</h2>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @forelse($properties as $property)
                                        <a href="{{ route('property.view', $property->id) }}" class="block group">
                                            <div
                                                class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-600 overflow-hidden transform transition-all duration-300 group-hover:shadow-lg group-hover:-translate-y-1">
                                                <div class="h-48 bg-gray-200 dark:bg-gray-700 relative">
                                                    @if ($property->images->isNotEmpty())
                                                        <img src="{{ asset('storage/' . $property->images->first()->file_path) }}"
                                                            alt="Property Image" class="w-full h-full object-cover" />
                                                    @else
                                                        <div
                                                            class="w-full h-full flex items-center justify-center bg-gray-300 dark:bg-gray-700">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-12 w-12 text-gray-400" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <div class="absolute top-2 right-2">
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-600 text-white">
                                                            {{ $property->property_number }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="p-4">
                                                    <h3
                                                        class="text-lg font-medium text-gray-900 dark:text-white truncate">
                                                        {{ $property->item_name }}</h3>
                                                    <p
                                                        class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                                                        {{ \Illuminate\Support\Str::limit($property->item_description ?? 'No description available', 60) }}
                                                    </p>
                                                    <div class="flex items-center justify-between mt-3">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $property->acquisition_date ? $property->acquisition_date->format('M Y') : 'Unknown date' }}
                                                        </span>
                                                        <span
                                                            class="text-blue-600 dark:text-blue-400 text-sm font-medium group-hover:underline">View
                                                            Details</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="col-span-full">
                                            <p class="text-gray-700 dark:text-gray-300">No properties assigned to you.
                                            </p>
                                        </div>
                                    @endforelse
                                </div>

                                <!-- Pagination -->
                                @if ($properties->hasPages())
                                    <div class="mt-4">
                                        {{ $properties->links() }}
                                    </div>
                                @endif
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- JavaScript for Tab Navigation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('#profile-nav a.profile-nav-link');

            // Get the last active tab from session storage or default to the first tab
            const lastActiveTab = sessionStorage.getItem('activeTab') || navLinks[0].getAttribute('data-target');

            // If no link is active by default, activate the saved one or the first one
            if (navLinks.length && !document.querySelector('#profile-nav a.profile-nav-link.active')) {
                // Find the link that matches the last active tab
                let activeLink = document.querySelector(`#profile-nav a[data-target="${lastActiveTab}"]`);
                if (!activeLink) {
                    activeLink = navLinks[0]; // Fallback to first tab if saved tab doesn't exist
                }

                activeLink.classList.add('active', 'text-blue-600', '!border-blue-600', 'bg-blue-50', 'font-medium',
                    'dark:text-blue-400', 'dark:bg-blue-900/50', 'dark:!border-blue-400');
                activeLink.classList.remove('text-gray-700', 'dark:text-gray-300', 'border-transparent');

                // Show the associated content section
                const targetSection = document.getElementById(lastActiveTab);
                if (targetSection) {
                    // Hide all sections first
                    document.querySelectorAll('.content-section').forEach(section => {
                        section.classList.add('hidden');
                    });
                    // Show the target section
                    targetSection.classList.remove('hidden');
                }
            }

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Remove active classes from all nav links
                    navLinks.forEach(nav => {
                        nav.classList.remove('active', 'text-blue-600', '!border-blue-600',
                            'bg-blue-50', 'font-medium', 'dark:text-blue-400',
                            'dark:bg-blue-900/50', 'dark:!border-blue-400');
                        nav.classList.add('text-gray-700', 'dark:text-gray-300',
                            'border-transparent');
                    });

                    // Add active classes to the clicked link
                    this.classList.add('active', 'text-blue-600', '!border-blue-600', 'bg-blue-50',
                        'font-medium', 'dark:text-blue-400', 'dark:bg-blue-900/50',
                        'dark:!border-blue-400');
                    this.classList.remove('text-gray-700', 'dark:text-gray-300',
                        'border-transparent');

                    // Hide all content sections
                    document.querySelectorAll('.content-section').forEach(section => {
                        section.classList.add('hidden');
                    });

                    // Show the targeted section
                    const targetId = this.getAttribute('data-target');
                    const targetSection = document.getElementById(targetId);
                    if (targetSection) {
                        targetSection.classList.remove('hidden');
                    }

                    // Save the active tab to session storage
                    sessionStorage.setItem('activeTab', targetId);
                });
            });
        });
    </script>

    <script>
        function confirmReceive(requisitionId) {
            // User's signature path for preview if available
            const userSignaturePath =
                "{{ Auth::user()->signature_path ? Storage::url(Auth::user()->signature_path) : '' }}";

            // Signature preview HTML - will show if e-signature is selected
            const signaturePreviewHtml = userSignaturePath ?
                `<div class="mt-3 border rounded p-2 text-center hidden" id="signature-preview-container">
                    <p class="text-sm mb-1">Your signature will appear as:</p>
                    <img src="${userSignaturePath}" alt="Your signature" class="max-h-16 mx-auto">
                </div>` : '';

            Swal.fire({
                title: 'Confirm Receipt',
                html: `
                    <div class="text-left mb-4">
                        <p class="mb-3">Are you sure you want to receive these supplies? This will confirm receipt with your signature.</p>

                        <div class="mb-4">
                            <label class="block text-sm font-bold mb-2">
                                How would you like to sign this receipt?
                            </label>
                            <div class="flex items-center mb-2">
                                <input type="radio" id="swal-esign" name="signature_type" value="esign" class="mr-2" ${!userSignaturePath ? 'disabled' : ''}>
                                <label for="swal-esign" class="text-sm">Use E-Signature</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="swal-sgd" name="signature_type" value="sgd" class="mr-2" checked>
                                <label for="swal-sgd" class="text-sm">Mark as SGD (Sign physically later)</label>
                            </div>
                            ${!userSignaturePath ?
                                '<p class="text-xs text-red-500 mt-1">You need to upload a signature in your profile to use E-Signature.</p>' : ''}
                        </div>

                        ${signaturePreviewHtml}

                        <div id="esign-terms" class="hidden bg-gray-100 p-3 rounded text-xs mt-3">
                            <p class="font-bold mb-1">E-Signature Terms and Conditions:</p>
                            <ul class="list-disc pl-4 space-y-1">
                                <li>I authorize the use of my electronic signature to confirm receipt.</li>
                                <li>I understand this e-signature has the same legal validity as my handwritten signature.</li>
                                <li>I confirm I have received all the items as specified in this requisition.</li>
                            </ul>
                            <div class="mt-2">
                                <input type="checkbox" id="agree-terms" class="mr-1">
                                <label for="agree-terms" class="text-xs">I agree to the above terms</label>
                            </div>
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10B981', // Green color (Tailwind's green-500)
                cancelButtonColor: '#6B7280', // Gray color (Tailwind's gray-500)
                confirmButtonText: 'Yes, receive supplies',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusConfirm: false,
                width: '28rem',
                padding: '1rem',
                customClass: {
                    confirmButton: 'px-4 py-2 text-sm font-medium rounded-md',
                    cancelButton: 'px-4 py-2 text-sm font-medium rounded-md'
                },
                didRender: () => {
                    // Disable the confirm button initially if e-signature is selected (terms not agreed)
                    const confirmButton = Swal.getConfirmButton();
                    const agreeTerms = document.getElementById('agree-terms');
                    const esignRadio = document.getElementById('swal-esign');
                    const sgdRadio = document.getElementById('swal-sgd');
                    const termsDiv = document.getElementById('esign-terms');
                    const signaturePreview = document.getElementById('signature-preview-container');

                    // Function to toggle the confirm button state based on selections
                    const updateConfirmButtonState = () => {
                        if (esignRadio.checked && !agreeTerms.checked) {
                            confirmButton.disabled = true;
                            confirmButton.classList.add('opacity-50', 'cursor-not-allowed');
                        } else {
                            confirmButton.disabled = false;
                            confirmButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    };

                    // Add event listeners
                    esignRadio.addEventListener('change', function() {
                        if (this.checked) {
                            termsDiv.classList.remove('hidden');
                            if (signaturePreview) signaturePreview.classList.remove('hidden');
                            updateConfirmButtonState();
                        }
                    });

                    sgdRadio.addEventListener('change', function() {
                        if (this.checked) {
                            termsDiv.classList.add('hidden');
                            if (signaturePreview) signaturePreview.classList.add('hidden');
                            updateConfirmButtonState();
                        }
                    });

                    if (agreeTerms) {
                        agreeTerms.addEventListener('change', updateConfirmButtonState);
                    }

                    // Initialize state
                    updateConfirmButtonState();
                },
                preConfirm: () => {
                    const signatureType = document.querySelector('input[name="signature_type"]:checked').value;

                    // If e-signature selected, check if terms are agreed to
                    if (signatureType === 'esign') {
                        const termsAgreed = document.getElementById('agree-terms').checked;
                        if (!termsAgreed) {
                            Swal.showValidationMessage('You must agree to the terms to use e-signature');
                            return false;
                        }
                    }

                    return signatureType;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Get the form
                    const form = document.getElementById('receive-form-' + requisitionId);

                    // Add signature type as hidden field
                    let signatureInput = document.createElement('input');
                    signatureInput.type = 'hidden';
                    signatureInput.name = 'signature_type';
                    signatureInput.value = result.value;
                    form.appendChild(signatureInput);

                    // Submit the form
                    form.submit();

                    // Show loading state while processing
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Confirming receipt of supplies',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });
                }
            });
        }
    </script>

</x-app-layout>
