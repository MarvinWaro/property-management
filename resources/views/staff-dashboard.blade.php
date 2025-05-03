<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Welcome Back!') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-100">
                <div class="container mx-auto py-8">
                    <div class="grid grid-cols-4 sm:grid-cols-12 gap-6 px-4">


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
                        <div class="col-span-4 sm:col-span-3">
                            <!-- Dynamic Profile Card -->
                            <div class="bg-white shadow rounded-lg p-6 mb-6">
                                <div class="flex flex-col items-center">
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
                                        class="w-32 h-32 object-cover object-center bg-gray-300 rounded-full mb-4 shrink-0">
                                    <h1 class="text-xl font-bold">{{ Auth::user()->name }}</h1>
                                    <p class="text-gray-700">{{ optional(Auth::user()->designation)->name }}</p>
                                    <p class="text-gray-700">{{ optional(Auth::user()->department)->name }}</p>
                                </div>
                            </div>

                            <!-- Navigation Menu -->
                            <div class="bg-white shadow rounded-lg overflow-hidden">
                                <h3 class="text-md font-semibold px-6 py-3 border-b border-gray-200">Profile Navigation
                                </h3>
                                <nav id="profile-nav" class="flex flex-col">
                                    <a href="#"
                                        class="profile-nav-link px-6 py-3 text-gray-700 hover:bg-gray-50 border-l-4 border-transparent transition duration-300 ease-in-out"
                                        data-target="requests">
                                        Requests
                                    </a>
                                    <a href="#"
                                        class="profile-nav-link px-6 py-3 text-gray-700 hover:bg-gray-50 border-l-4 border-transparent transition duration-300 ease-in-out"
                                        data-target="received-supplies">
                                        Received Supplies
                                    </a>
                                    <a href="#"
                                        class="profile-nav-link px-6 py-3 text-gray-700 hover:bg-gray-50 border-l-4 border-transparent transition duration-300 ease-in-out"
                                        data-target="properties">
                                        Properties
                                    </a>
                                </nav>
                            </div>
                        </div>

                        <!-- Main content area -->
                        <div class="details col-span-4 sm:col-span-9">
                            <!-- Requests Section (initially hidden) -->
                            {{-- @if (session('success'))
                                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                                    role="alert">
                                    <span class="block sm:inline">{{ session('success') }}</span>
                                </div>
                            @endif --}}

                            <!-- Enhanced My Requests Section -->
                            <div id="requests" class="content-section bg-white shadow rounded-lg p-6 hidden">
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
                                <div
                                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 space-y-4 sm:space-y-0">
                                    <h2 class="text-xl font-bold">My Requests</h2>

                                    <button id="openRequestModal"
                                        class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br
                                            focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800
                                            font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Request Supply
                                    </button>
                                </div>

                                <!-- Filter & Search Section -->
                                <div
                                    class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-4 border border-gray-200 dark:border-gray-600">
                                    <div class="flex flex-wrap gap-4 items-center justify-between">
                                        <!-- Status Filters -->
                                        <div class="flex flex-wrap gap-2">
                                            <button type="button"
                                                class="status-filter px-3 py-2 text-sm font-medium rounded-lg bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 active-filter"
                                                data-status="all">
                                                All
                                            </button>
                                            <button type="button"
                                                class="status-filter px-3 py-2 text-sm font-medium rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-900 hover:text-yellow-800 dark:hover:text-yellow-300"
                                                data-status="draft">
                                                <span
                                                    class="w-2 h-2 mr-1 inline-block rounded-full bg-yellow-400"></span>
                                                Pending
                                            </button>
                                            <button type="button"
                                                class="status-filter px-3 py-2 text-sm font-medium rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-800 dark:hover:text-blue-300"
                                                data-status="approved">
                                                <span class="w-2 h-2 mr-1 inline-block rounded-full bg-blue-400"></span>
                                                Approved
                                            </button>
                                            <button type="button"
                                                class="status-filter px-3 py-2 text-sm font-medium rounded-lg hover:bg-green-100 dark:hover:bg-green-900 hover:text-green-800 dark:hover:text-green-300"
                                                data-status="posted">
                                                <span
                                                    class="w-2 h-2 mr-1 inline-block rounded-full bg-green-400"></span>
                                                Issued
                                            </button>
                                        </div>

                                        <!-- Search Form -->
                                        <div class="relative w-full max-w-sm">
                                            <div class="relative flex-grow">
                                                <input type="text" id="request-search-input"
                                                    oninput="toggleClearRequestButton()"
                                                    placeholder="Search by RIS number..."
                                                    class="px-4 py-2 w-full border text-sm font-medium border-gray-300 rounded-lg
                                                        focus:ring-1 focus:ring-blue-500 focus:border-blue-500
                                                        dark:bg-gray-800 dark:border-gray-700 dark:text-white
                                                        dark:focus:ring-blue-500 dark:focus:border-blue-500" />

                                                <!-- The 'X' Button (hidden by default) -->
                                                <button type="button" id="clearRequestButton"
                                                    onclick="clearRequestSearch()" style="display: none;"
                                                    class="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-red-500 focus:outline-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="lucide lucide-x">
                                                        <line x1="18" x2="6" y1="6"
                                                            y2="18" />
                                                        <line x1="6" x2="18" y1="6"
                                                            y2="18" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Table Description Caption -->
                                <div
                                    class="p-4 mb-4 text-sm text-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-gray-300">
                                    <h3 class="text-lg font-semibold mb-1 text-gray-900 dark:text-white">Supply
                                        Requisition Management</h3>
                                    <p>
                                        Track and manage your supply requisition requests. You can filter by status,
                                        search for specific requisitions,
                                        and view detailed information about each request.
                                    </p>
                                </div>

                                <!-- Requests Table -->
                                <div class="overflow-hidden shadow-md sm:rounded-lg border border-gray-200 dark:border-gray-700 mb-4">
                                    <div class="overflow-x-auto">
                                        <div class="overflow-y-auto max-h-[500px]">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead class="text-xs text-white uppercase bg-gradient-to-r from-blue-600 to-blue-800 dark:from-blue-700 dark:to-blue-900 sticky top-0 z-10">
                                                    <tr>
                                                        <th class="px-6 py-3 text-left tracking-wider">RIS NO</th>
                                                        <th class="px-6 py-3 text-left tracking-wider">DATE</th>
                                                        <th class="px-6 py-3 text-left tracking-wider">STATUS</th>
                                                        <th class="px-6 py-3 text-center tracking-wider">ACTIONS</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700" id="requests-table-body">
                                                    @php
                                                        // Sort requests by created_at in descending order
                                                        $myRequests = $myRequests->sortByDesc('created_at');
                                                    @endphp
                                                    @forelse($myRequests as $request)
                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 request-row" data-status="{{ $request->status }}">
                                                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">
                                                                {{ $request->ris_no }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <div class="flex flex-col">
                                                                    <span>{{ $request->ris_date->format('M d, Y') }}</span>
                                                                    <span class="text-xs text-gray-500">{{ $request->created_at->format('h:i A') }}</span>
                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                @if ($request->status === 'draft')
                                                                    <span class="px-2.5 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 font-medium inline-flex items-center">
                                                                        <span class="relative flex h-2 w-2 mr-1">
                                                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-500 opacity-75"></span>
                                                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-yellow-500"></span>
                                                                        </span>
                                                                        Pending
                                                                    </span>
                                                                @elseif($request->status === 'approved')
                                                                    <span class="px-2.5 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 font-medium inline-flex items-center">
                                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                        </svg>
                                                                        Approved
                                                                    </span>
                                                                @elseif($request->status === 'posted')
                                                                    <span class="px-2.5 py-0.5 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 font-medium inline-flex items-center">
                                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                                        </svg>
                                                                        Issued
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                                <a href="{{ route('ris.show', $request->ris_id) }}"
                                                                class="inline-flex items-center justify-center w-8 h-8 text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg"
                                                                data-tooltip-target="tooltip-view-request-{{ $loop->index }}"
                                                                data-tooltip-placement="top">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                                    </svg>
                                                                </a>
                                                                <div id="tooltip-view-request-{{ $loop->index }}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                                    View Details
                                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr id="no-requests-row">
                                                            <td colspan="4" class="px-6 py-8 text-center">
                                                                <!-- Empty state content -->
                                                                <div class="flex flex-col items-center justify-center py-8">
                                                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                    </svg>
                                                                    <p class="text-lg font-medium text-gray-500 dark:text-gray-400">No requisitions found</p>
                                                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Get started by clicking the "Request Supply" button</p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- RIS Request Modal with Only Items Section Scrollable -->
                                <div id="requestModal" class="fixed inset-0 z-50 overflow-hidden bg-black bg-opacity-50 flex items-center justify-center hidden">
                                    <div class="bg-white rounded-lg shadow-xl w-full max-w-5xl h-[90vh] flex flex-col">
                                        <div class="flex items-center justify-between p-4 border-b shrink-0">
                                            <h3 class="text-xl font-semibold text-gray-900">
                                                Create Requisition and Issue Slip
                                            </h3>
                                            <button id="closeRequestModal" class="text-gray-400 hover:text-gray-500">
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>

                                        <form action="{{ route('ris.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                                            @csrf
                                            <!-- Fixed Upper Section -->
                                            <div class="p-6 pb-0 shrink-0">
                                                <!-- Header Information -->
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Entity Name</label>
                                                        <input type="text" name="entity_name" value="CHEDRO 12"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                            required>
                                                    </div>

                                                    <!-- Hidden Fund Cluster field that admin will fill later -->
                                                    <input type="hidden" name="fund_cluster" value="">

                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Division</label>
                                                        <select name="division"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                            required>
                                                            @foreach ($departments as $department)
                                                                <option value="{{ $department->id }}" {{ Auth::user()->department_id == $department->id ? 'selected' : '' }}>
                                                                    {{ $department->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Office</label>
                                                        <input type="text" name="office"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                    </div>

                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Responsibility Center Code</label>
                                                        <input type="text" name="responsibility_center_code"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                    </div>

                                                    <div class="md:col-span-2">
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                                                        <textarea name="purpose" rows="1"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                            required></textarea>
                                                    </div>
                                                </div>

                                                <!-- Items Header (Also Fixed) -->
                                                <div class="flex justify-between items-center mb-4">
                                                    <h4 class="text-lg font-semibold text-gray-900">Select Items to Request</h4>

                                                    <div class="flex items-center">
                                                        <div class="relative mr-2">
                                                            <input type="text" id="item-search" placeholder="Search items..."
                                                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                                </svg>
                                                            </div>
                                                        </div>

                                                        <button type="button" id="viewCartBtn" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center shadow-sm">
                                                            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                            </svg>
                                                            View Selected (<span id="itemCount">0</span>)
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Scrollable Items Section -->
                                            <div class="px-6 overflow-y-auto flex-1">
                                                <!-- Supply Items -->
                                                <div class="mb-6">
                                                    <!-- Product Grid View -->
                                                    <!-- Product Grid View with Fund Cluster Information -->
                                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6" id="products-grid">
                                                        @foreach ($stocks as $stock)
                                                            <div class="product-card border rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200"
                                                                data-supply-id="{{ $stock->supply_id }}"
                                                                data-name="{{ $stock->supply->item_name }}"
                                                                data-available="{{ $stock->quantity_on_hand }}"
                                                                data-fund-cluster="{{ $stock->fund_cluster }}">
                                                                <div class="p-4 flex flex-col h-full">
                                                                    <div class="flex-shrink-0 h-40 bg-gray-200 rounded-md mb-3 flex items-center justify-center">
                                                                        <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                                        </svg>
                                                                    </div>
                                                                    <div class="flex-1">
                                                                        <h5 class="font-medium text-gray-900 mb-1 line-clamp-2">{{ $stock->supply->item_name }}</h5>
                                                                        <p class="text-sm text-gray-500 mb-2">Stock No: {{ $stock->supply->stock_no ?? 'N/A' }}</p>
                                                                        <div class="flex items-center justify-between mb-2">
                                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $stock->quantity_on_hand > 10 ? 'bg-green-100 text-green-800' : ($stock->quantity_on_hand > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                                                {{ $stock->quantity_on_hand }} available
                                                                            </span>
                                                                        </div>
                                                                        <div class="text-xs text-gray-600">
                                                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-50 text-blue-700">
                                                                                Fund: {{ $stock->fund_cluster ?: 'Unspecified' }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mt-3 pt-3 border-t flex items-center">
                                                                        <div class="flex items-center border rounded-md">
                                                                            <button type="button" class="quantity-btn minus px-2 py-1 text-gray-500 hover:text-gray-700 disabled:opacity-50" data-action="decrease" {{ $stock->quantity_on_hand <= 0 ? 'disabled' : '' }}>
                                                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                                                </svg>
                                                                            </button>
                                                                            <input type="number" class="quantity-input w-12 text-center border-none focus:ring-0" value="0" min="0" max="{{ $stock->quantity_on_hand }}" {{ $stock->quantity_on_hand <= 0 ? 'disabled' : '' }}>
                                                                            <button type="button" class="quantity-btn plus px-2 py-1 text-gray-500 hover:text-gray-700 disabled:opacity-50" data-action="increase" {{ $stock->quantity_on_hand <= 0 ? 'disabled' : '' }}>
                                                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"></path>
                                                                                </svg>
                                                                            </button>
                                                                        </div>
                                                                        <button type="button" class="add-to-cart ml-2 px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 flex-grow text-center" {{ $stock->quantity_on_hand <= 0 ? 'disabled' : '' }}>
                                                                            Add to Request
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    <!-- Selected Items View (Initially Hidden) -->
                                                    <div id="selected-items-container" class="hidden">
                                                        <div class="bg-gray-50 border rounded-lg p-4 mb-4">
                                                            <h5 class="font-medium text-gray-900 mb-3">Selected Items</h5>
                                                            <div class="overflow-x-auto">
                                                                <table class="min-w-full divide-y divide-gray-200">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                                Item</th>
                                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                                Quantity</th>
                                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                                Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="selected-items-list" class="bg-white divide-y divide-gray-200">
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

                                            <div class="px-6 py-3 border-t flex justify-end shrink-0">
                                                <button type="button" id="cancelRequest"
                                                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 mr-2">
                                                    Cancel
                                                </button>
                                                <button type="submit"
                                                    class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    Submit Request
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        // Modal Controls
                                        const requestModal = document.getElementById('requestModal');
                                        const openRequestModal = document.getElementById('openRequestModal');
                                        const closeRequestModal = document.getElementById('closeRequestModal');
                                        const cancelRequest = document.getElementById('cancelRequest');

                                        openRequestModal.addEventListener('click', function() {
                                            requestModal.classList.remove('hidden');
                                        });

                                        function closeModal() {
                                            requestModal.classList.add('hidden');
                                        }

                                        closeRequestModal.addEventListener('click', closeModal);
                                        cancelRequest.addEventListener('click', closeModal);

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
                                                    productsGrid.classList.toggle('hidden');
                                                    selectedItemsContainer.classList.toggle('hidden');

                                                    // Change button text based on current view
                                                    if (selectedItemsContainer.classList.contains('hidden')) {
                                                        viewCartBtn.innerHTML = `<svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                        </svg>View Selected (<span id="itemCount">${selectedItems.length}</span>)`;
                                                    } else {
                                                        viewCartBtn.innerHTML = `<svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                        </svg>Back to Items`;
                                                    }
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
                                            const maxQuantity = parseInt(card.getAttribute('data-available'), 10);

                                            if (minusBtn && plusBtn && quantityInput) {
                                                minusBtn.addEventListener('click', function() {
                                                    let currentValue = parseInt(quantityInput.value, 10);
                                                    if (currentValue > 0) {
                                                        quantityInput.value = currentValue - 1;
                                                    }
                                                });

                                                plusBtn.addEventListener('click', function() {
                                                    let currentValue = parseInt(quantityInput.value, 10);
                                                    if (currentValue < maxQuantity) {
                                                        quantityInput.value = currentValue + 1;
                                                    }
                                                });

                                                quantityInput.addEventListener('change', function() {
                                                    let currentValue = parseInt(quantityInput.value, 10);
                                                    if (isNaN(currentValue) || currentValue < 0) {
                                                        quantityInput.value = 0;
                                                    } else if (currentValue > maxQuantity) {
                                                        quantityInput.value = maxQuantity;
                                                    }
                                                });

                                                addToCartBtn.addEventListener('click', function() {
                                                    const quantity = parseInt(quantityInput.value, 10);
                                                    if (quantity > 0) {
                                                        addItemToSelection(supplyId, supplyName, quantity, maxQuantity);
                                                        quantityInput.value = 0;
                                                    }
                                                });
                                            }
                                        });

                                        function addItemToSelection(supplyId, supplyName, quantity, maxAvailable) {
                                            // Check if item already exists in selection
                                            const existingItemIndex = selectedItems.findIndex(item => item.supplyId === supplyId);

                                            if (existingItemIndex >= 0) {
                                                // Update existing item
                                                const newQuantity = selectedItems[existingItemIndex].quantity + quantity;
                                                if (newQuantity <= maxAvailable) {
                                                    selectedItems[existingItemIndex].quantity = newQuantity;
                                                } else {
                                                    // Show notification that max quantity reached
                                                    alert(`Maximum available quantity (${maxAvailable}) reached for ${supplyName}`);
                                                    selectedItems[existingItemIndex].quantity = maxAvailable;
                                                }
                                            } else {
                                                // Add new item
                                                selectedItems.push({
                                                    supplyId: supplyId,
                                                    name: supplyName,
                                                    quantity: quantity,
                                                    maxAvailable: maxAvailable,
                                                    index: itemIndex++
                                                });
                                            }

                                            updateSelectedItemsList();
                                            updateFormInputs();
                                        }

                                        function removeItemFromSelection(index) {
                                            selectedItems = selectedItems.filter(item => item.index !== index);
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
                                                        <td colspan="3" class="px-4 py-4 text-center text-gray-500">
                                                            No items selected. Add items from the product list.
                                                        </td>
                                                    </tr>
                                                `;
                                                // Hide the selected items view if visible
                                                if (selectedItemsContainer && !selectedItemsContainer.classList.contains('hidden')) {
                                                    viewCartBtn.click();
                                                }
                                            } else {
                                                // Add each item to the list
                                                selectedItems.forEach(item => {
                                                    const row = document.createElement('tr');
                                                    row.innerHTML = `
                                                        <td class="px-4 py-3">
                                                            <div class="flex items-center">
                                                                <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-md flex items-center justify-center mr-3">
                                                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                                    </svg>
                                                                </div>
                                                                <div>
                                                                    <p class="text-sm font-medium text-gray-900">${item.name}</p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            <div class="flex items-center">
                                                                <button type="button" class="edit-quantity-btn minus px-2 py-1 text-gray-500 hover:text-gray-700" data-index="${item.index}" data-action="decrease">
                                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                                    </svg>
                                                                </button>
                                                                <span class="w-10 text-center">${item.quantity}</span>
                                                                <button type="button" class="edit-quantity-btn plus px-2 py-1 text-gray-500 hover:text-gray-700" data-index="${item.index}" data-action="increase">
                                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            <button type="button" class="remove-item-btn text-red-500 hover:text-red-700" data-index="${item.index}">
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
                                                if (action === 'increase' && selectedItems[itemIndex].quantity < selectedItems[itemIndex].maxAvailable) {
                                                    selectedItems[itemIndex].quantity++;
                                                } else if (action === 'decrease' && selectedItems[itemIndex].quantity > 1) {
                                                    selectedItems[itemIndex].quantity--;
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

                                        // Add/Remove Item Functionality (original functionality)
                                        const addItemBtn = document.getElementById('addItem');
                                        const itemsContainer = document.getElementById('itemsContainer');

                                        if (addItemBtn && itemsContainer) {
                                            addItemBtn.addEventListener('click', function() {
                                                const itemRows = document.querySelectorAll('.item-row');
                                                const newIndex = itemRows.length;

                                                const newRow = document.createElement('tr');
                                                newRow.className = 'item-row';
                                                newRow.innerHTML = `
                                                        <td class="px-4 py-2">
                                                            <select name="supplies[${newIndex}][supply_id]" class="w-full px-2 py-1 border border-gray-300 rounded" required>
                                                                <option value="">Select an item</option>
                                                                @foreach ($stocks as $stock)
                                                                    <option value="{{ $stock->supply_id }}">
                                                                        {{ $stock->supply->item_name }} ({{ $stock->quantity_on_hand }} available)
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td class="px-4 py-2">
                                                            <input type="number" name="supplies[${newIndex}][quantity]" min="1" value="1"
                                                                class="w-full px-2 py-1 border border-gray-300 rounded" required>
                                                        </td>
                                                        <td class="px-4 py-2">
                                                            <button type="button" class="text-red-500 hover:text-red-700 remove-item">
                                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                </svg>
                                                            </button>
                                                        </td>
                                                    `;

                                                itemsContainer.appendChild(newRow);

                                                // Enable the first row's remove button if we now have more than one row
                                                if (newIndex === 1) {
                                                    document.querySelector('.remove-item').removeAttribute('disabled');
                                                }

                                                // Add event listener to the new remove button
                                                newRow.querySelector('.remove-item').addEventListener('click', function() {
                                                    removeItem(this);
                                                });
                                            });

                                            // Function to remove an item row
                                            function removeItem(button) {
                                                const row = button.closest('.item-row');
                                                row.remove();

                                                // If only one row left, disable its remove button
                                                const itemRows = document.querySelectorAll('.item-row');
                                                if (itemRows.length === 1) {
                                                    itemRows[0].querySelector('.remove-item').setAttribute('disabled', 'disabled');
                                                }

                                                // Reindex the remaining rows
                                                itemRows.forEach((row, index) => {
                                                    const selectInput = row.querySelector('select');
                                                    const quantityInput = row.querySelector('input[type="number"]');

                                                    selectInput.name = `supplies[${index}][supply_id]`;
                                                    quantityInput.name = `supplies[${index}][quantity]`;
                                                });
                                            }

                                            // Add event listener to the first row's remove button
                                            const firstRemoveButton = document.querySelector('.remove-item');
                                            if (firstRemoveButton) {
                                                firstRemoveButton.addEventListener('click', function() {
                                                    removeItem(this);
                                                });
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
                                                    });

                                                    // Add active class to clicked filter
                                                    this.classList.add('bg-blue-100', 'dark:bg-blue-900', 'text-blue-800',
                                                        'dark:text-blue-300', 'active-filter');

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
                            <div id="received-supplies" class="content-section bg-white shadow rounded-lg p-6 hidden">
                                <h2 class="text-xl font-bold mb-4">Supplies Received</h2>

                                <!-- Table Description Caption -->
                                <div class="p-4 mb-4 text-sm text-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-gray-300">
                                    <h3 class="text-lg font-semibold mb-1 text-gray-900 dark:text-white">Received Supplies Management</h3>
                                    <p>
                                        View and manage supplies that have been issued to you. Track all requisitions you've received across different departments.
                                    </p>
                                </div>

                                <!-- Received Supplies Table -->
                                <div class="overflow-hidden shadow-md sm:rounded-lg border border-gray-200 dark:border-gray-700 mb-4">
                                    <div class="overflow-x-auto">
                                        <div class="overflow-y-auto max-h-[500px]">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead class="text-xs text-white uppercase bg-gradient-to-r from-blue-600 to-blue-800 dark:from-blue-700 dark:to-blue-900 sticky top-0 z-10">
                                                    <tr>
                                                        <th class="px-6 py-3 text-left tracking-wider">RIS NO</th>
                                                        <th class="px-6 py-3 text-left tracking-wider">DATE</th>
                                                        <th class="px-6 py-3 text-left tracking-wider">REQUESTED BY</th>
                                                        <th class="px-6 py-3 text-center tracking-wider">ACTIONS</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                                    @php
                                                        // Get an array of distinct RIS numbers the user received supplies for
                                                        $risNumbers = DB::table('supply_transactions')
                                                            ->join('user_transactions', 'supply_transactions.transaction_id', '=', 'user_transactions.transaction_id')
                                                            ->where('user_transactions.user_id', Auth::id())
                                                            ->where('user_transactions.role', 'receiver')
                                                            ->select('reference_no')
                                                            ->distinct()
                                                            ->pluck('reference_no')
                                                            ->toArray();

                                                        // Get detailed information for each RIS
                                                        $receivedRequisitions = [];
                                                        foreach ($risNumbers as $risNo) {
                                                            $risSlip = \App\Models\RisSlip::where('ris_no', $risNo)->first();

                                                            if ($risSlip) {
                                                                $receivedRequisitions[] = [
                                                                    'reference_no' => $risNo,
                                                                    'transaction_date' => $risSlip->ris_date,
                                                                    'purpose' => $risSlip->purpose ?? 'N/A',
                                                                    'requester_name' => optional($risSlip->requester)->name ?? 'N/A',
                                                                    'ris_id' => $risSlip->ris_id,
                                                                    'created_at' => $risSlip->created_at
                                                                ];
                                                            } else {
                                                                // Fallback to transaction info if RIS not found
                                                                $transaction = \App\Models\SupplyTransaction::where('reference_no', $risNo)
                                                                    ->first();

                                                                if ($transaction) {
                                                                    $requester = $transaction->requesters()->first();

                                                                    $receivedRequisitions[] = [
                                                                        'reference_no' => $risNo,
                                                                        'transaction_date' => $transaction->transaction_date,
                                                                        'purpose' => 'N/A',
                                                                        'requester_name' => optional($requester)->name ?? 'N/A',
                                                                        'ris_id' => null,
                                                                        'created_at' => $transaction->created_at
                                                                    ];
                                                                }
                                                            }
                                                        }

                                                        // Sort by created_at in descending order (newest first)
                                                        usort($receivedRequisitions, function($a, $b) {
                                                            return $b['created_at']->timestamp - $a['created_at']->timestamp;
                                                        });
                                                    @endphp

                                                    @forelse($receivedRequisitions as $requisition)
                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">
                                                                {{ $requisition['reference_no'] }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <div class="flex flex-col">
                                                                    <span>{{ $requisition['transaction_date']->format('M d, Y') }}</span>
                                                                    <span class="text-xs text-gray-500">{{ $requisition['created_at']->format('h:i A') }}</span>
                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                {{ $requisition['requester_name'] }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                                @php
                                                                    // Get the RIS slip to check its status
                                                                    $risSlip = \App\Models\RisSlip::where('ris_no', $requisition['reference_no'])->first();
                                                                    $canReceive = $risSlip &&
                                                                                $risSlip->received_by == Auth::id() &&
                                                                                !$risSlip->received_at &&
                                                                                $risSlip->status === 'posted' &&
                                                                                $risSlip->issued_at;
                                                                @endphp

                                                                <div class="flex items-center justify-center gap-2">
                                                                    <a href="{{ $requisition['ris_id'] ? route('ris.show', $requisition['ris_id']) : '#' }}"
                                                                    class="inline-flex items-center justify-center w-8 h-8 text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg"
                                                                    data-tooltip-target="tooltip-view-{{ $loop->index }}"
                                                                    data-tooltip-placement="top">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                                        </svg>
                                                                    </a>
                                                                    <div id="tooltip-view-{{ $loop->index }}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                                        View Details
                                                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                                                    </div>

                                                                    @if($canReceive)
                                                                        <form action="{{ route('ris.receive', $risSlip) }}" method="POST" class="inline">
                                                                            @csrf
                                                                            <button type="submit"
                                                                                    onclick="return confirm('Are you sure you want to receive these supplies? This will confirm receipt with your e-signature.')"
                                                                                    class="inline-flex items-center justify-center w-8 h-8 text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 rounded-lg"
                                                                                    data-tooltip-target="tooltip-receive-{{ $loop->index }}"
                                                                                    data-tooltip-placement="top">
                                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                                </svg>
                                                                            </button>
                                                                        </form>
                                                                        <div id="tooltip-receive-{{ $loop->index }}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                                            Receive Supplies
                                                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                                                        </div>
                                                                    @elseif($risSlip && $risSlip->received_at)
                                                                        <span class="text-xs text-green-600 dark:text-green-400">
                                                                            Received {{ $risSlip->received_at->format('M d, Y') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="px-6 py-8 text-center">
                                                                <!-- Empty state content -->
                                                                <div class="flex flex-col items-center justify-center py-8">
                                                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8 4-8-4V5l8 4 8-4m0 5l-8 4-8-4"></path>
                                                                    </svg>
                                                                    <p class="text-lg font-medium text-gray-500 dark:text-gray-400">No received supplies found</p>
                                                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">You haven't received any supplies yet</p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Properties Section (initially hidden) -->
                            <div id="properties" class="content-section bg-white shadow rounded-lg p-6 hidden">
                                <h2 class="text-xl font-bold mb-4">My Properties</h2>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @forelse(Auth::user()->properties as $property)
                                        <a href="{{ route('property.view', $property->id) }}" class="block group">
                                            <div
                                                class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden transform transition-all duration-300 group-hover:shadow-lg group-hover:-translate-y-1">
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
                                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white truncate">
                                                        {{ $property->item_name }}</h3>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
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
                                        <p class="text-gray-700">No properties assigned to you.</p>
                                    @endforelse
                                </div>
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

                activeLink.classList.add('active', 'text-blue-600', 'border-blue-600', 'bg-blue-50', 'font-medium');
                activeLink.classList.remove('text-gray-700');

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
                        nav.classList.remove('active', 'text-blue-600', 'border-blue-600',
                            'bg-blue-50', 'font-medium');
                        nav.classList.add('text-gray-700');
                    });

                    // Add active classes to the clicked link
                    this.classList.add('active', 'text-blue-600', 'border-blue-600', 'bg-blue-50',
                        'font-medium');
                    this.classList.remove('text-gray-700');

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

</x-app-layout>
