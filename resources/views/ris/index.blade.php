<!-- resources/views/ris/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Requisition and Issue Slips') }}
            </h2>
            <div class="flex items-center space-x-3">
                {{-- <span class="text-sm text-gray-600 dark:text-gray-400">
                    Manage supply requisitionssss
                </span> --}}

                @if (auth()->user()->hasRole('admin'))
                    <!-- Manual Entry Button -->
                    <button type="button" id="openManualEntryModal"
                        class="inline-flex items-center px-4 py-2 bg-[#ce201f] hover:bg-[#a01b1a] text-white
                            font-medium text-sm rounded-lg transition-colors shadow-sm focus:ring-4 focus:ring-[#ce201f]/30
                            dark:bg-[#ce201f] dark:hover:bg-[#a01b1a]">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add Manual Entry
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Card -->
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                <!-- Filter & Controls Section -->
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <!-- Stats Summary - Now Clickable -->
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('ris.index') }}"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ !request('status') ? 'bg-[#ce201f] text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                <span class="mr-1">Total:</span>
                                <span class="font-semibold">{{ $totalCount ?? $risSlips->total() }}</span>
                            </a>

                            <a href="{{ route('ris.index', ['status' => 'draft']) }}"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request('status') === 'draft' ? 'bg-[#ce201f] text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                <span class="w-3 h-3 mr-2 rounded-full bg-gray-400 dark:bg-gray-500"></span>
                                <span>Pending: </span>
                                <span class="font-semibold ml-1">{{ $pendingCount ?? 0 }}</span>
                            </a>

                            <a href="{{ route('ris.index', ['status' => 'approved']) }}"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request('status') === 'approved' ? 'bg-[#ce201f] text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                <span class="w-3 h-3 mr-2 rounded-full bg-[#6366f1] dark:bg-[#818cf8]"></span>
                                <span>Approved: </span>
                                <span class="font-semibold ml-1">{{ $approvedCount ?? 0 }}</span>
                            </a>

                            <a href="{{ route('ris.index', ['status' => 'pending-receipt']) }}"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request('status') === 'pending-receipt' ? 'bg-[#ce201f] text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                <span class="w-3 h-3 mr-2 rounded-full bg-yellow-500"></span>
                                <span>Pending Receipt: </span>
                                <span class="font-semibold ml-1">{{ $pendingReceiptCount ?? 0 }}</span>
                            </a>

                            <a href="{{ route('ris.index', ['status' => 'completed']) }}"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request('status') === 'completed' ? 'bg-[#ce201f] text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                <span class="w-3 h-3 mr-2 rounded-full bg-[#10b981] dark:bg-[#34d399]"></span>
                                <span>Completed: </span>
                                <span class="font-semibold ml-1">{{ $completedCount ?? 0 }}</span>
                            </a>

                            <a href="{{ route('ris.index', ['status' => 'declined']) }}"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request('status') === 'declined' ? 'bg-[#ce201f] text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                <span class="w-3 h-3 mr-2 rounded-full bg-red-500"></span>
                                <span>Declined: </span>
                                <span class="font-semibold ml-1">{{ $declinedCount ?? 0 }}</span>
                            </a>
                        </div>

                        <!-- Search Form -->
                        <form method="GET" action="{{ route('ris.index') }}"
                            class="w-full max-w-sm flex items-center space-x-2">
                            <!-- Preserve status filter when searching -->
                            @if (request('status'))
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            @endif

                            <div class="relative flex-grow">
                                <input type="text" name="search" id="search-input"
                                    value="{{ request()->get('search') }}" oninput="toggleClearButton()"
                                    placeholder="Search RIS number, requestor..."
                                    class="px-4 py-2 w-full border text-sm font-medium border-gray-300 rounded-lg
                                        focus:ring-1 focus:ring-[#ce201f] focus:border-[#ce201f]
                                        dark:bg-gray-800 dark:border-gray-700 dark:text-white
                                        dark:focus:ring-[#ce201f] dark:focus:border-[#ce201f] transition-all duration-200" />

                                <!-- The 'X' Button (hidden by default) -->
                                <button type="button" id="clearButton" onclick="clearSearch()" style="display: none;"
                                    class="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-[#ce201f] focus:outline-none transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x">
                                        <line x1="18" x2="6" y1="6" y2="18" />
                                        <line x1="6" x2="18" y1="6" y2="18" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Separate Search Button -->
                            <button type="submit"
                                class="px-3 py-2 text-sm text-white bg-[#ce201f] rounded-lg
                                    hover:bg-[#a01b1a] focus:ring-1 focus:outline-none
                                    focus:ring-[#ce201f]/30 dark:bg-[#ce201f] dark:hover:bg-[#a01b1a]
                                    dark:focus:ring-[#ce201f]/30 flex items-center transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="size-5">
                                    <path fill-rule="evenodd"
                                        d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>


                <!-- Manual RIS Entry Modal -->
                <div id="manualEntryModal"
                    class="fixed inset-0 z-50 overflow-y-auto -webkit-overflow-scrolling-touch bg-black bg-opacity-50
                    flex items-start sm:items-center justify-center hidden p-1 sm:p-4">

                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-xl
                            w-full max-w-6xl mx-3 mt-8 sm:mt-0 sm:max-h-[90vh] flex flex-col pb-8 sm:pb-0">

                        <!-- Fixed Header -->
                        <div class="flex items-center justify-between p-4 border-b dark:border-gray-700 shrink-0">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Manual RIS Entry
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Create historical requisition record manually
                                </p>
                            </div>
                            <button id="closeManualEntryModal"
                                class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 p-1">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Error Display Section -->
                        @if ($errors->any())
                            <div class="p-4 bg-red-50 dark:bg-red-900/20 border-b border-red-200 dark:border-red-900">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-red-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-red-800 dark:text-red-300">There were errors with your submission</h4>

                                        @if ($errors->has('duplicate_items'))
                                            <div class="mt-3 p-3 bg-red-100 dark:bg-red-900/30 rounded-md">
                                                <h5 class="text-sm font-semibold text-red-800 dark:text-red-300 mb-2 flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                    Duplicate Item Found
                                                </h5>
                                                <p class="text-sm text-red-700 dark:text-red-400">{{ $errors->first('duplicate_items') }}</p>
                                            </div>
                                        @endif

                                        @if ($errors->has('stock_validation'))
                                            <div class="mt-3">
                                                <h5 class="text-sm font-medium text-red-800 dark:text-red-300 mb-1">Stock Validation Issues:</h5>
                                                @foreach ($errors->get('stock_validation')[0] as $stockError)
                                                    <div class="text-xs bg-red-100 dark:bg-red-900/30 p-2 rounded mb-1">
                                                        <strong>{{ $stockError['supply_name'] ?? 'Unknown Item' }}:</strong>
                                                        {{ $stockError['message'] }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        @php
                                            $otherErrors = collect($errors->all())->filter(function($error) use ($errors) {
                                                return !$errors->has('duplicate_items') &&
                                                    !$errors->has('stock_validation') &&
                                                    !str_contains($error, 'duplicate items') &&
                                                    !str_contains($error, 'stock validation');
                                            });
                                        @endphp

                                        @if ($otherErrors->count() > 0)
                                            <ul class="mt-2 text-sm text-red-700 dark:text-red-400 list-disc list-inside">
                                                @foreach ($otherErrors as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($errors->any())
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const modal = document.getElementById('manualEntryModal');
                                    if (modal && modal.classList.contains('hidden')) {
                                        modal.classList.remove('hidden');
                                        document.body.style.overflow = 'hidden';
                                    }
                                });
                            </script>
                        @endif

                        <form action="{{ route('ris.store-manual') }}" method="POST"
                            class="flex flex-col flex-1 overflow-hidden">
                            @csrf
                            <input type="hidden" name="is_manual_entry" value="1">

                            <!-- Scrollable Content Area -->
                            <div class="flex-1 overflow-y-auto">
                                <div class="p-6">

                                    <!-- Date Selection (Important for Historical Data) -->
                                    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-900 rounded-lg">
                                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-3">
                                        📅 Historical Date Information
                                    </h4>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        {{-- ► RIS Date --}}
                                        <div>
                                        <label for="ris_date"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            RIS Date <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="date"
                                            id="ris_date"
                                            name="ris_date"
                                            value="{{ old('ris_date') }}"
                                            max="{{ now()->format('Y-m-d') }}"
                                            min="{{ now()->subYears(5)->format('Y-m-d') }}"
                                            required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                        >
                                        @error('ris_date')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                        </div>

                                        {{-- ► RIS No. --}}
                                        <div>
                                        <label for="reference_no"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            RIS No.
                                        </label>
                                        <input
                                            type="text"
                                            id="reference_no"
                                            name="reference_no"
                                            value="{{ old('reference_no', '') }}"
                                            placeholder="RIS YYYY-MM-XXX"
                                            required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                        >
                                        @error('reference_no')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                        </div>
                                    </div>
                                    </div>

                                    <!-- Basic RIS Information -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                                        <div class="md:col-span-2 lg:col-span-1">
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Entity Name <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="entity_name" value="CHEDRO 12" required
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600
                                                dark:bg-gray-700 dark:text-white rounded-md
                                                focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Division <span class="text-red-500">*</span>
                                            </label>
                                            <select name="division" required
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600
                                                dark:bg-gray-700 dark:text-white rounded-md
                                                focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Select Division</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}">{{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Fund Cluster
                                            </label>
                                            <select name="fund_cluster"
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600
                                                dark:bg-gray-700 dark:text-white rounded-md
                                                focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Select Fund Cluster</option>
                                                <option value="101">101</option>
                                                <option value="151">151</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Office
                                            </label>
                                            <input type="text" name="office"
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600
                                                dark:bg-gray-700 dark:text-white rounded-md
                                                focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Responsibility Center Code
                                            </label>
                                            <input type="text" name="responsibility_center_code"
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600
                                                dark:bg-gray-700 dark:text-white rounded-md
                                                focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Requested By <span class="text-red-500">*</span>
                                            </label>

                                            <!-- Custom Searchable Dropdown -->
                                            <div class="relative">
                                                <!-- Hidden input for form submission -->
                                                <input type="hidden" name="requested_by" id="requested_by_value" required>

                                                <!-- Dropdown trigger -->
                                                <div id="userDropdownTrigger"
                                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600
                                                            dark:bg-gray-700 dark:text-white rounded-md
                                                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                                            cursor-pointer flex items-center justify-between min-h-[2.5rem]">
                                                    <span id="selectedUserText" class="text-gray-500 dark:text-gray-400">Select User</span>
                                                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" id="dropdownIcon"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </div>

                                                <!-- Dropdown content -->
                                                <div id="userDropdownContent"
                                                    class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600
                                                            rounded-md shadow-lg hidden max-h-80 overflow-hidden">

                                                    <!-- Search input inside dropdown -->
                                                    <div class="p-3 border-b border-gray-200 dark:border-gray-600">
                                                        <div class="relative">
                                                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                            </svg>
                                                            <input type="text" id="userSearchInput"
                                                                class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 dark:border-gray-600
                                                                        rounded-md bg-white dark:bg-gray-800 dark:text-white
                                                                        focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                                placeholder="Search users by name, department, or designation..."
                                                                autocomplete="off">
                                                        </div>
                                                    </div>

                                                    <!-- Users list -->
                                                    <div class="max-h-60 overflow-y-auto" id="usersList">
                                                        @php
                                                            // Group users by department
                                                            $usersByDepartment = collect($users)->groupBy(function ($user) {
                                                                return $user->department ? $user->department->name : 'No Department';
                                                            })->sortKeys();
                                                        @endphp

                                                        @foreach ($usersByDepartment as $departmentName => $departmentUsers)
                                                            <div class="user-department-group" data-department="{{ $departmentName }}">
                                                                <div class="px-3 py-2 text-xs font-bold text-blue-600 dark:text-blue-400 bg-gray-50 dark:bg-gray-700 border-b border-gray-100 dark:border-gray-600">
                                                                    {{ $departmentName }}
                                                                </div>
                                                                @foreach ($departmentUsers->sortBy('name') as $user)
                                                                    <div class="user-option px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-50 dark:border-gray-700"
                                                                        data-value="{{ $user->id }}"
                                                                        data-search-text="{{ strtolower($user->name . ' ' . $user->email . ' ' . $departmentName . ' ' . ($user->designation->name ?? '')) }}">
                                                                        <div class="font-medium text-gray-900 dark:text-white">
                                                                            {{ $user->name }}
                                                                            @if ($user->designation)
                                                                                <span class="text-sm text-gray-600 dark:text-gray-400">- {{ $user->designation->name }}</span>
                                                                            @endif
                                                                        </div>
                                                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <style>
                                            /* Enhanced styling for the custom dropdown */
                                            .user-option.hidden {
                                                display: none !important;
                                            }

                                            .user-department-group.hidden {
                                                display: none !important;
                                            }

                                            .user-option:hover {
                                                background-color: #f9fafb !important;
                                            }

                                            .dark .user-option:hover {
                                                background-color: #374151 !important;
                                            }

                                            /* Smooth transitions */
                                            #userDropdownContent {
                                                transition: all 0.2s ease-in-out;
                                            }

                                            #dropdownIcon {
                                                transition: transform 0.2s ease-in-out;
                                            }

                                            #dropdownIcon.rotated {
                                                transform: rotate(180deg);
                                            }
                                        </style>

                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const dropdownTrigger = document.getElementById('userDropdownTrigger');
                                                const dropdownContent = document.getElementById('userDropdownContent');
                                                const searchInput = document.getElementById('userSearchInput');
                                                const selectedUserText = document.getElementById('selectedUserText');
                                                const hiddenInput = document.getElementById('requested_by_value');
                                                const dropdownIcon = document.getElementById('dropdownIcon');
                                                const userOptions = document.querySelectorAll('.user-option');
                                                const departmentGroups = document.querySelectorAll('.user-department-group');

                                                // Toggle dropdown
                                                dropdownTrigger.addEventListener('click', function() {
                                                    const isOpen = !dropdownContent.classList.contains('hidden');

                                                    if (isOpen) {
                                                        closeDropdown();
                                                    } else {
                                                        openDropdown();
                                                    }
                                                });

                                                function openDropdown() {
                                                    dropdownContent.classList.remove('hidden');
                                                    dropdownIcon.classList.add('rotated');
                                                    searchInput.focus();
                                                    searchInput.value = '';
                                                    filterUsers(); // Reset filter
                                                }

                                                function closeDropdown() {
                                                    dropdownContent.classList.add('hidden');
                                                    dropdownIcon.classList.remove('rotated');
                                                }

                                                // Close dropdown when clicking outside
                                                document.addEventListener('click', function(event) {
                                                    if (!dropdownTrigger.contains(event.target) && !dropdownContent.contains(event.target)) {
                                                        closeDropdown();
                                                    }
                                                });

                                                // Handle user selection
                                                userOptions.forEach(option => {
                                                    option.addEventListener('click', function() {
                                                        const userId = this.getAttribute('data-value');
                                                        const userName = this.querySelector('.font-medium').textContent.trim();

                                                        hiddenInput.value = userId;
                                                        selectedUserText.textContent = userName;
                                                        selectedUserText.classList.remove('text-gray-500', 'dark:text-gray-400');
                                                        selectedUserText.classList.add('text-gray-900', 'dark:text-white');

                                                        closeDropdown();
                                                    });
                                                });

                                                // Search functionality
                                                searchInput.addEventListener('input', filterUsers);
                                                searchInput.addEventListener('keyup', filterUsers);

                                                function filterUsers() {
                                                    const searchTerm = searchInput.value.toLowerCase();

                                                    if (searchTerm === '') {
                                                        // Show all options and groups
                                                        userOptions.forEach(option => {
                                                            option.classList.remove('hidden');
                                                        });
                                                        departmentGroups.forEach(group => {
                                                            group.classList.remove('hidden');
                                                        });
                                                        return;
                                                    }

                                                    // Track which groups have visible options
                                                    const visibleGroups = new Set();

                                                    // Filter options
                                                    userOptions.forEach(option => {
                                                        const searchText = option.getAttribute('data-search-text') || '';

                                                        if (searchText.includes(searchTerm)) {
                                                            option.classList.remove('hidden');
                                                            // Mark this group as having visible options
                                                            const group = option.closest('.user-department-group');
                                                            if (group) {
                                                                visibleGroups.add(group);
                                                            }
                                                        } else {
                                                            option.classList.add('hidden');
                                                        }
                                                    });

                                                    // Show/hide groups based on whether they have visible options
                                                    departmentGroups.forEach(group => {
                                                        if (visibleGroups.has(group)) {
                                                            group.classList.remove('hidden');
                                                        } else {
                                                            group.classList.add('hidden');
                                                        }
                                                    });
                                                }

                                                // Prevent form submission when Enter is pressed in search
                                                searchInput.addEventListener('keydown', function(e) {
                                                    if (e.key === 'Enter') {
                                                        e.preventDefault();
                                                    }
                                                });
                                            });
                                        </script>

                                    </div>

                                    <!-- Purpose -->
                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Purpose <span class="text-red-500">*</span>
                                        </label>
                                        <textarea name="purpose" rows="3" required
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600
                                            dark:bg-gray-700 dark:text-white rounded-md
                                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                            placeholder="Enter the purpose of this requisition..."></textarea>
                                    </div>

                                    <!-- Supply Items Section -->
                                    <div class="mb-6">
                                        <div class="flex items-center justify-between mb-4">
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    Supply Items
                                                </h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Add items that were requested (only available supplies will be
                                                    shown)
                                                </p>
                                            </div>
                                            <button type="button" id="addManualItemBtn"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700
                                                text-white text-sm font-medium rounded-lg transition-all duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 6v12m6-6H6" />
                                                </svg>
                                                Add Item
                                            </button>
                                        </div>

                                        <!-- Items Table -->
                                        <div class="supply-items-table-container rounded-xl border border-gray-200 dark:border-gray-700" style="overflow: visible !important;">
                                            <div style="overflow-x: auto; overflow-y: visible !important;">
                                                <table class="w-full">
                                                    <thead>
                                                        <tr class="bg-gray-50 dark:bg-gray-900/50">
                                                            <th
                                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                                Supply Item
                                                            </th>
                                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                                                style="width: 120px;">
                                                                Available Qty
                                                            </th>
                                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                                                style="width: 120px;">
                                                                Requested Qty
                                                            </th>
                                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                                                style="width: 120px;">
                                                                Issued Qty
                                                            </th>
                                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                                                style="width: 200px;">
                                                                Remarks
                                                            </th>
                                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                                                style="width: 60px;">

                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="manualItemsTable"
                                                        class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                                                        <!-- Dynamic rows will be added here -->
                                                    </tbody>
                                                </table>
                                            </div>

                                            <!-- Empty State -->
                                            <div id="manualEmptyState"
                                                class="p-12 text-center bg-white dark:bg-gray-800">
                                                <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                                </svg>
                                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No items added
                                                    yet</p>
                                                <p class="text-xs text-gray-400 dark:text-gray-500">Click "Add Item" to
                                                    start</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status Selection for Historical Data -->
                                    <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-900 rounded-lg">
                                        <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-300 mb-3">
                                            📋 Historical Status Information
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    Final Status <span class="text-red-500">*</span>
                                                </label>
                                                <select name="final_status" required id="finalStatusSelect"
                                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600
                                                    dark:bg-gray-700 dark:text-white rounded-md
                                                    focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                    <option value="">Select Status</option>
                                                    <option value="completed">Completed (Fully Processed)</option>
                                                    <option value="posted">Issued (Pending Receipt)</option>
                                                    <option value="declined">Declined</option>
                                                </select>
                                            </div>
                                            <div id="declineReasonDiv" class="hidden">
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    Decline Reason
                                                </label>
                                                <input type="text" name="decline_reason"
                                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600
                                                    dark:bg-gray-700 dark:text-white rounded-md
                                                    focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                        </div>

                                        <!-- Info message for completed status -->
                                        <div id="completedInfoMessage" class="hidden mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-900 rounded-md">
                                            <div class="flex items-start">
                                                <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                                </svg>
                                                <div>
                                                    <p class="text-sm text-blue-800 dark:text-blue-300 font-medium">Completed Status</p>
                                                    <p class="text-xs text-blue-700 dark:text-blue-400 mt-1">
                                                        • <strong>Approved by:</strong> Current CAO from database<br>
                                                        • <strong>Issued by:</strong> Current user (you)<br>
                                                        • <strong>Received by:</strong> Requester (automatically set)
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fixed Footer -->
                            <div
                                class="px-6 py-4 border-t dark:border-gray-700 flex justify-end space-x-3 shrink-0 bg-gray-50 dark:bg-gray-800">
                                <button type="button" id="cancelManualEntry"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium
                                    text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700
                                    transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" id="submitManualEntry"
                                    class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium
                                    text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2
                                    focus:ring-blue-500 transition-colors">
                                    Create Manual RIS
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Template for manual item row with searchable dropdown -->
                <template id="manualItemRowTemplate">
                    <tr
                        class="manual-item-row hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors duration-150">
                        <td class="px-4 py-3">
                            <!-- Fixed positioning searchable dropdown like IAR module -->
                            <div class="supply-select-wrapper relative w-full">
                                <!-- Hidden select for form submission -->
                                <select name="items[INDEX][supply_id]" required class="supply-select hidden">
                                    <option value="">Select Supply</option>
                                </select>

                                <!-- Display button -->
                                <button type="button"
                                    class="supply-dropdown-trigger w-full px-3 py-2 text-left border border-gray-200 dark:border-gray-700
                                        rounded-md text-sm bg-white dark:bg-gray-700 dark:text-white
                                        focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                        flex items-center justify-between"
                                    onclick="toggleSupplyDropdown(this)">
                                    <span class="selected-supply-text text-gray-500 dark:text-gray-400">Select Supply</span>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <!-- Fixed positioned dropdown menu -->
                                <div class="supply-dropdown-menu hidden bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600
                                            rounded-lg shadow-lg overflow-hidden">
                                    <!-- Search input -->
                                    <div class="p-2 border-b border-gray-200 dark:border-gray-600">
                                        <div class="relative">
                                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                            <input type="text"
                                                class="supply-search-input w-full pl-9 pr-3 py-2 text-sm border border-gray-200 dark:border-gray-600
                                                        rounded-md bg-white dark:bg-gray-800 dark:text-white
                                                        focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Search supplies..."
                                                onkeyup="filterSupplyOptions(this)">
                                        </div>
                                    </div>

                                    <!-- Options list -->
                                    <div class="supply-options-container max-h-[200px] overflow-y-auto">
                                        @foreach ($availableSupplies as $stock)
                                            <div class="supply-option px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer
                                                        border-b border-gray-100 dark:border-gray-700 last:border-0"
                                                data-supply-id="{{ $stock->supply_id }}"
                                                data-supply-name="{{ $stock->supply->item_name ?? 'Unknown Item' }}"
                                                data-supply-stockno="{{ $stock->supply->stock_no ?? 'N/A' }}"
                                                data-supply-description="{{ $stock->supply->description ?? '' }}"
                                                data-available="{{ $stock->quantity_on_hand }}"
                                                data-unit="{{ $stock->supply->unit_of_measurement ?? 'pcs' }}"
                                                onclick="selectSupplyOption(this)">
                                                <div class="font-medium text-sm text-gray-900 dark:text-white">
                                                    {{ $stock->supply->item_name ?? 'Unknown Item' }} ({{ $stock->supply->stock_no ?? 'N/A' }})
                                                </div>
                                                @if($stock->supply->description ?? '')
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                        {{ Str::limit($stock->supply->description, 60) }}
                                                    </div>
                                                @endif
                                                <div class="text-xs text-green-600 dark:text-green-400 mt-0.5">
                                                    Available: {{ $stock->quantity_on_hand }} {{ $stock->supply->unit_of_measurement ?? 'pcs' }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- No results message -->
                                    <div class="supply-no-results hidden p-3 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No supplies found matching your search
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="available-qty font-medium text-green-600 dark:text-green-400">0</span>
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" name="items[INDEX][quantity_requested]" min="1" required
                                class="requested-qty w-full px-3 py-2 border border-gray-200 dark:border-gray-700
                                rounded-md text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" name="items[INDEX][quantity_issued]" min="0"
                                class="issued-qty w-full px-3 py-2 border border-gray-200 dark:border-gray-700
                                rounded-md text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </td>
                        <td class="px-4 py-3">
                            <input type="text" name="items[INDEX][remarks]"
                                class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700
                                rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button type="button"
                                class="remove-manual-item-btn p-1.5 text-red-500 hover:text-red-700
                                hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                </template>

                <style>
                    @keyframes modal-slide-up {
                        from {
                            opacity: 0;
                            transform: translateY(20px);
                        }

                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }

                    .animate-modal-slide-up {
                        animation: modal-slide-up 0.3s ease-out;
                    }

                    /* Enhanced form styling */
                    .supply-select:focus {
                        border-color: #3b82f6;
                        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
                    }

                    .field-error {
                        animation: shake 0.3s ease-in-out;
                    }

                    @keyframes shake {
                        0%, 100% {
                            transform: translateX(0);
                        }
                        25% {
                            transform: translateX(-4px);
                        }
                        75% {
                            transform: translateX(4px);
                        }
                    }

                    /* Improved loading states */
                    .loading-overlay {
                        position: absolute;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background: rgba(255, 255, 255, 0.8);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        z-index: 10;
                    }

                    /* Enhanced table styling */
                    .manual-item-row:hover {
                        background-color: rgba(59, 130, 246, 0.05);
                    }

                    /* Status-specific styling */
                    .status-completed {
                        color: #10b981;
                    }

                    .status-posted {
                        color: #f59e0b;
                    }

                    .status-declined {
                        color: #ef4444;
                    }

                    /* FIXED: More specific targeting to avoid breaking other layouts */
                    .supply-items-table-container .overflow-hidden {
                        overflow: visible !important;
                    }

                    .supply-items-table-container .overflow-x-auto {
                        overflow-x: auto;
                        overflow-y: visible !important;
                    }

                    /* Supply dropdown positioning - matches IAR module approach */
                    .supply-dropdown-menu {
                        position: fixed !important;
                        z-index: 999999 !important; /* Increased z-index for maximum visibility */
                        max-width: 400px;
                        min-width: 300px;
                        max-height: 300px;
                        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                    }

                    /* Ensure modal doesn't interfere with dropdown */
                    #manualEntryModal {
                        z-index: 50;
                    }

                    /* Supply option styling */
                    .supply-option {
                        transition: all 0.15s ease;
                        cursor: pointer;
                    }

                    .supply-option:hover {
                        transform: translateX(2px);
                    }

                    .supply-option.disabled {
                        opacity: 0.5;
                        cursor: not-allowed;
                        background-color: #fafafa;
                    }

                    .supply-option.disabled:hover {
                        background-color: #fafafa;
                        transform: none;
                    }

                    .supply-option.selected {
                        background-color: #dbeafe;
                        color: #1d4ed8;
                        font-weight: 500;
                    }

                    /* Dark mode supply option styles */
                    .dark .supply-option.disabled {
                        background-color: #1f2937;
                    }

                    .dark .supply-option.disabled:hover {
                        background-color: #1f2937;
                    }

                    .dark .supply-option.selected {
                        background-color: #1e3a8a;
                        color: #93c5fd;
                    }

                    /* Custom scrollbar for supply options */
                    .supply-options-container::-webkit-scrollbar {
                        width: 6px;
                    }

                    .supply-options-container::-webkit-scrollbar-track {
                        background: #f1f1f1;
                        border-radius: 3px;
                    }

                    .supply-options-container::-webkit-scrollbar-thumb {
                        background: #c1c1c1;
                        border-radius: 3px;
                    }

                    .supply-options-container::-webkit-scrollbar-thumb:hover {
                        background: #a8a8a8;
                    }

                    /* Dark mode scrollbar */
                    .dark .supply-options-container::-webkit-scrollbar-track {
                        background: #374151;
                    }

                    .dark .supply-options-container::-webkit-scrollbar-thumb {
                        background: #6b7280;
                    }

                    .dark .supply-options-container::-webkit-scrollbar-thumb:hover {
                        background: #9ca3af;
                    }

                    /* Search input focus styles */
                    .supply-search-input:focus {
                        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
                    }
                </style>

                <!-- Enhanced JavaScript with searchable dropdown functionality (CLEAN PRODUCTION VERSION) -->
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const modal = document.getElementById('manualEntryModal');
                        const openBtn = document.getElementById('openManualEntryModal');
                        const closeBtn = document.getElementById('closeManualEntryModal');
                        const cancelBtn = document.getElementById('cancelManualEntry');
                        const addItemBtn = document.getElementById('addManualItemBtn');
                        const itemsTable = document.getElementById('manualItemsTable');
                        const emptyState = document.getElementById('manualEmptyState');
                        const template = document.getElementById('manualItemRowTemplate');
                        const risDateInput = document.querySelector('input[name="ris_date"]');
                        const risNoInput = document.querySelector('input[name="reference_no"]');
                        const submitBtn = document.getElementById('submitManualEntry');

                        let itemIndex = 0;
                        let availableSupplies = [];
                        let selectedSupplyIds = [];
                        let isInitialized = false;

                        // Supply dropdown functions (keeping existing functionality)
                        window.toggleSupplyDropdown = function(trigger) {
                            const dropdown = trigger.parentNode.querySelector('.supply-dropdown-menu');
                            const allDropdowns = document.querySelectorAll('.supply-dropdown-menu');

                            allDropdowns.forEach(d => {
                                if (d !== dropdown) {
                                    d.classList.add('hidden');
                                }
                            });

                            if (dropdown.classList.contains('hidden')) {
                                const rect = trigger.getBoundingClientRect();
                                dropdown.style.position = 'fixed';
                                dropdown.style.top = (rect.bottom + window.scrollY + 4) + 'px';
                                dropdown.style.left = rect.left + 'px';
                                dropdown.style.width = rect.width + 'px';
                                dropdown.style.minWidth = '300px';
                                dropdown.style.maxWidth = '400px';

                                dropdown.classList.remove('hidden');

                                const searchInput = dropdown.querySelector('.supply-search-input');
                                setTimeout(() => searchInput.focus(), 100);
                            } else {
                                dropdown.classList.add('hidden');
                            }
                        }

                        window.filterSupplyOptions = function(searchInput) {
                            const searchTerm = searchInput.value.toLowerCase();
                            const dropdown = searchInput.closest('.supply-dropdown-menu');
                            const options = dropdown.querySelectorAll('.supply-option');
                            const noResults = dropdown.querySelector('.supply-no-results');
                            let visibleCount = 0;

                            options.forEach(option => {
                                const name = option.dataset.supplyName.toLowerCase();
                                const stockNo = option.dataset.supplyStockno.toLowerCase();
                                const description = (option.dataset.supplyDescription || '').toLowerCase();

                                if (name.includes(searchTerm) || stockNo.includes(searchTerm) || description.includes(searchTerm)) {
                                    option.style.display = 'block';
                                    visibleCount++;
                                } else {
                                    option.style.display = 'none';
                                }
                            });

                            if (visibleCount === 0) {
                                noResults.classList.remove('hidden');
                            } else {
                                noResults.classList.add('hidden');
                            }
                        }

                        window.selectSupplyOption = function(option) {
                            const wrapper = option.closest('.supply-select-wrapper');
                            const hiddenSelect = wrapper.querySelector('.supply-select');
                            const trigger = wrapper.querySelector('.supply-dropdown-trigger');
                            const selectedText = trigger.querySelector('.selected-supply-text');
                            const dropdown = wrapper.querySelector('.supply-dropdown-menu');

                            const supplyId = option.dataset.supplyId;
                            const allSelects = document.querySelectorAll('.supply-select');
                            let isDuplicate = false;

                            allSelects.forEach(select => {
                                if (select !== hiddenSelect && select.value === supplyId) {
                                    isDuplicate = true;
                                }
                            });

                            if (isDuplicate) {
                                showAlert('This item has already been added to this requisition. Each item can only appear once per RIS.', 'error');
                                return;
                            }

                            hiddenSelect.innerHTML = `
                                <option value="">Select Supply</option>
                                <option value="${supplyId}" selected
                                        data-available="${option.dataset.available}"
                                        data-stock-no="${option.dataset.supplyStockno}"
                                        data-unit="${option.dataset.unit}">
                                    ${option.dataset.supplyName} (${option.dataset.supplyStockno})
                                </option>
                            `;
                            hiddenSelect.value = supplyId;

                            selectedText.textContent = `${option.dataset.supplyName} (${option.dataset.supplyStockno})`;
                            selectedText.className = 'selected-supply-text text-gray-900 dark:text-white';

                            dropdown.classList.add('hidden');

                            const searchInput = dropdown.querySelector('.supply-search-input');
                            searchInput.value = '';
                            filterSupplyOptions(searchInput);

                            const changeEvent = new Event('change', { bubbles: true });
                            hiddenSelect.dispatchEvent(changeEvent);

                            updateSelectedSupplies();
                        }

                        // Helper functions
                        // Replace your showLoadingState function with this:
                        function showLoadingState() {
                            if (addItemBtn) {
                                addItemBtn.disabled = true;
                                addItemBtn.innerHTML = `
                                    <span class="inline-block animate-spin mr-2">⏳</span>
                                    Loading...
                                `;
                            }
                        }

                        function hideLoadingState() {
                            if (addItemBtn) {
                                addItemBtn.disabled = false;
                                addItemBtn.innerHTML = `
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                                    </svg>
                                    Add Item
                                `;
                            }
                        }

                        function showAlert(message, type = 'error') {
                            const alertColors = {
                                'info': 'bg-blue-500',
                                'success': 'bg-green-500',
                                'warning': 'bg-orange-500',
                                'error': 'bg-red-500'
                            };

                            const alert = document.createElement('div');
                            alert.className = `fixed top-4 right-4 z-[70] ${alertColors[type]} text-white px-4 py-3 rounded-lg shadow-lg max-w-md`;
                            alert.innerHTML = `
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 flex-shrink-0 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm">${message}</p>
                                    </div>
                                    <button onclick="this.parentElement.parentElement.remove()" class="ml-2 hover:bg-black/10 rounded p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            `;

                            document.body.appendChild(alert);
                            setTimeout(() => alert.remove(), 5000);
                        }

                        // Generate RIS Number function
                        function generateRisNumber(selectedDate = null) {
                            const dateToUse = selectedDate || risDateInput?.value;
                            if (!dateToUse || !risNoInput) return;

                            const originalValue = risNoInput.value;
                            const originalPlaceholder = risNoInput.placeholder;

                            risNoInput.value = '';
                            risNoInput.placeholder = 'Generating RIS number...';
                            risNoInput.disabled = true;
                            risNoInput.classList.add('animate-pulse', 'bg-blue-50', 'dark:bg-blue-900/20');

                            fetch(`{{ route('ris.next') }}?ris_date=` + encodeURIComponent(dateToUse), {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                credentials: 'same-origin'
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                risNoInput.value = data.defaultRis;
                                risNoInput.classList.remove('animate-pulse', 'bg-blue-50', 'dark:bg-blue-900/20');
                                risNoInput.classList.add('bg-green-50', 'dark:bg-green-900/20', 'border-green-300', 'dark:border-green-700');
                                setTimeout(() => {
                                    risNoInput.classList.remove('bg-green-50', 'dark:bg-green-900/20', 'border-green-300', 'dark:border-green-700');
                                }, 800);
                            })
                            .catch(error => {
                                risNoInput.value = originalValue;
                                risNoInput.classList.remove('animate-pulse', 'bg-blue-50', 'dark:bg-blue-900/20');
                                risNoInput.classList.add('bg-red-50', 'dark:bg-red-900/20', 'border-red-300', 'dark:border-red-700');
                                setTimeout(() => {
                                    risNoInput.classList.remove('bg-red-50', 'dark:bg-red-900/20', 'border-red-300', 'dark:border-red-700');
                                }, 2000);
                                showAlert(`Failed to generate RIS number: ${error.message}`, 'error');
                            })
                            .finally(() => {
                                risNoInput.disabled = false;
                                risNoInput.placeholder = originalPlaceholder;
                            });
                        }

                        // Reset modal state
                        function resetModalState() {
                            itemsTable.innerHTML = '';
                            itemIndex = 0;
                            selectedSupplyIds = [];
                            const existingWarning = document.querySelector('.supplies-warning');
                            if (existingWarning) {
                                existingWarning.remove();
                            }
                            updateEmptyState();
                        }

                        // Update selected supplies tracking
                        function updateSelectedSupplies() {
                            selectedSupplyIds = [];
                            const allSelects = itemsTable.querySelectorAll('.supply-select');
                            allSelects.forEach(select => {
                                if (select.value) {
                                    selectedSupplyIds.push(select.value);
                                }
                            });

                            const allDropdowns = itemsTable.querySelectorAll('.supply-dropdown-menu');
                            allDropdowns.forEach(dropdown => {
                                const options = dropdown.querySelectorAll('.supply-option');
                                options.forEach(option => {
                                    const supplyId = option.dataset.supplyId;
                                    const isSelected = selectedSupplyIds.includes(supplyId);

                                    if (isSelected) {
                                        option.style.display = 'none';
                                    } else {
                                        option.style.display = 'block';
                                    }
                                });
                            });
                        }

                        // Load available supplies
                        function loadAvailableSupplies() {
                            try {
                                showLoadingState();
                                let suppliesData = @json($availableSupplies ?? []);

                                if (typeof suppliesData === 'object' && !Array.isArray(suppliesData)) {
                                    const keys = Object.keys(suppliesData);
                                    if (keys.length > 0) {
                                        suppliesData = Object.values(suppliesData);
                                    } else {
                                        suppliesData = [];
                                    }
                                }

                                if (!Array.isArray(suppliesData)) {
                                    suppliesData = [];
                                }

                                availableSupplies = [];

                                if (suppliesData.length > 0) {
                                    suppliesData.forEach((stock) => {
                                        const processedSupply = {
                                            supply_id: stock.supply_id,
                                            stock_no: stock.supply?.stock_no || stock.stock_no || 'N/A',
                                            item_name: stock.supply?.item_name || stock.item_name || 'Unknown Item',
                                            description: stock.supply?.description || stock.description || '',
                                            unit_of_measurement: stock.supply?.unit_of_measurement || stock.unit_of_measurement || 'pcs',
                                            available_quantity: stock.actual_available || stock.quantity_on_hand || 0,
                                            fund_cluster: stock.fund_cluster || ''
                                        };
                                        availableSupplies.push(processedSupply);
                                    });
                                }

                                hideLoadingState();

                                if (availableSupplies.length === 0) {
                                    showNoSuppliesMessage();
                                } else {
                                    const existingWarning = document.querySelector('.supplies-warning');
                                    if (existingWarning) {
                                        existingWarning.remove();
                                    }
                                }

                            } catch (error) {
                                hideLoadingState();
                                availableSupplies = [];
                                showNoSuppliesMessage();
                                showAlert(`Failed to load supplies: ${error.message}`, 'error');
                            }
                        }

                        function showNoSuppliesMessage() {
                            const itemsSection = document.querySelector('.mb-6:has(#manualItemsTable)');
                            if (!itemsSection) return;

                            const existingWarning = itemsSection.querySelector('.supplies-warning');
                            if (existingWarning) {
                                existingWarning.remove();
                            }

                            const alertDiv = document.createElement('div');
                            alertDiv.className = 'supplies-warning mb-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-900 rounded-lg';
                            alertDiv.innerHTML = `
                                <div class="flex">
                                    <svg class="w-5 h-5 text-yellow-500 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-300">No Supplies Available</h4>
                                        <p class="text-xs text-yellow-700 dark:text-yellow-400 mt-1">
                                            For historical entries, you can still create the RIS. Individual items will be processed during creation.
                                        </p>
                                    </div>
                                </div>
                            `;

                            itemsSection.insertBefore(alertDiv, itemsSection.firstChild);
                        }

                        // Modal controls
                        openBtn?.addEventListener('click', () => {
                            resetModalState();
                            modal.classList.remove('hidden');
                            document.body.style.overflow = 'hidden';

                            if (risDateInput && !risDateInput.value) {
                                const today = new Date().toISOString().split('T')[0];
                                risDateInput.value = today;
                                setTimeout(() => {
                                    generateRisNumber(today);
                                }, 100);
                            } else if (risDateInput && risDateInput.value) {
                                setTimeout(() => {
                                    generateRisNumber(risDateInput.value);
                                }, 100);
                            }

                            loadAvailableSupplies();
                            isInitialized = true;
                        });

                        function closeModal() {
                            modal.classList.add('hidden');
                            document.body.style.overflow = '';
                            document.querySelector('#manualEntryModal form')?.reset();
                            resetModalState();
                            isInitialized = false;
                        }

                        closeBtn?.addEventListener('click', closeModal);
                        cancelBtn?.addEventListener('click', closeModal);

                        // RIS Date change handler
                        risDateInput?.addEventListener('change', function() {
                            const selectedDate = this.value;
                            if (selectedDate) {
                                generateRisNumber(selectedDate);
                                const allSelects = itemsTable.querySelectorAll('.supply-select');
                                allSelects.forEach((select, index) => {
                                    if (select.value) {
                                        fetchAvailabilityForDate(select.value, index, selectedDate);
                                    }
                                });
                            }
                        });

                        function fetchAvailabilityForDate(supplyId, rowIndex, date) {
                            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                            fetch("{{ route('ris.validate-manual-stock') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": csrfToken
                                },
                                body: JSON.stringify({
                                    ris_date: date,
                                    items: [{ supply_id: supplyId }]
                                })
                            })
                            .then(res => res.json())
                            .then(json => {
                                const available = json.current_availability[supplyId] || 0;
                                const rows = Array.from(itemsTable.querySelectorAll('tr.manual-item-row'));
                                const row = rows[rowIndex];

                                if (row) {
                                    const span = row.querySelector('.available-qty');
                                    const req = row.querySelector('.requested-qty');
                                    const iss = row.querySelector('.issued-qty');

                                    span.textContent = available;
                                    span.className = `available-qty font-medium ${
                                        available > 0
                                        ? 'text-green-600 dark:text-green-400'
                                        : 'text-red-600 dark:text-red-400'
                                    }`;

                                    req.max = available;
                                    iss.max = available;
                                    req.disabled = (available === 0);
                                    iss.disabled = (available === 0);

                                    if (+req.value > available) req.value = available;
                                    if (+iss.value > available) iss.value = available;
                                }
                            })
                            .catch(() => {
                                // Silent error handling - functionality continues without logging
                            });
                        }

                        // Add item functionality
                        addItemBtn?.addEventListener('click', addManualItem);

                        function addManualItem() {
                            if (availableSupplies.length === 0) {
                                showAlert('No supplies available in the system.', 'warning');
                                return;
                            }

                            if (selectedSupplyIds.length >= availableSupplies.length) {
                                showAlert('All available supplies have been added.', 'warning');
                                return;
                            }

                            const templateContent = template.content.cloneNode(true);
                            const row = templateContent.querySelector('tr');

                            row.innerHTML = row.innerHTML.replace(/INDEX/g, itemIndex);

                            const availableQtySpan = row.querySelector('.available-qty');
                            const requestedQtyInput = row.querySelector('.requested-qty');
                            const issuedQtyInput = row.querySelector('.issued-qty');
                            const removeBtn = row.querySelector('.remove-manual-item-btn');

                            const supplyOptions = row.querySelectorAll('.supply-option');
                            supplyOptions.forEach(option => {
                                if (selectedSupplyIds.includes(option.dataset.supplyId)) {
                                    option.style.display = 'none';
                                }
                            });

                            if (requestedQtyInput) {
                                requestedQtyInput.addEventListener('input', function() {
                                    const available = parseInt(availableQtySpan?.textContent) || 0;
                                    const requested = parseInt(this.value) || 0;

                                    if (requested > available) {
                                        this.value = available;
                                        showAlert(`Maximum available: ${available}`, 'warning');
                                    }

                                    if (issuedQtyInput && this.value) {
                                        issuedQtyInput.value = this.value;
                                    }
                                });
                            }

                            if (issuedQtyInput) {
                                issuedQtyInput.addEventListener('input', function() {
                                    const requested = parseInt(requestedQtyInput?.value) || 0;
                                    const issued = parseInt(this.value) || 0;

                                    if (issued > requested) {
                                        this.value = requested;
                                        showAlert(`Cannot exceed requested: ${requested}`, 'warning');
                                    }
                                });
                            }

                            removeBtn?.addEventListener('click', function() {
                                if (itemsTable.children.length === 1) {
                                    showAlert('At least one item is required.', 'warning');
                                    return;
                                }

                                row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                                row.style.opacity = '0';
                                row.style.transform = 'translateX(-10px)';

                                setTimeout(() => {
                                    row.remove();
                                    updateSelectedSupplies();
                                    updateEmptyState();
                                }, 300);
                            });

                            itemsTable.appendChild(row);
                            itemIndex++;
                            updateEmptyState();
                        }

                        // Close dropdowns when clicking outside
                        document.addEventListener('click', function(event) {
                            if (!event.target.closest('.supply-select-wrapper')) {
                                document.querySelectorAll('.supply-dropdown-menu').forEach(dropdown => {
                                    dropdown.classList.add('hidden');
                                });
                            }
                        });

                        // Keyboard support for dropdowns
                        document.addEventListener('keydown', function(e) {
                            if (e.key === 'Escape') {
                                const openDropdowns = document.querySelectorAll('.supply-dropdown-menu:not(.hidden)');
                                if (openDropdowns.length > 0) {
                                    openDropdowns.forEach(dropdown => dropdown.classList.add('hidden'));
                                    e.preventDefault();
                                }
                            }
                        });

                        // Handle supply selection change
                        document.addEventListener('change', function(e) {
                            if (e.target.classList.contains('supply-select')) {
                                const selectedOption = e.target.options[e.target.selectedIndex];
                                const row = e.target.closest('tr');

                                if (selectedOption.value && row) {
                                    const available = parseInt(selectedOption.getAttribute('data-available')) || 0;
                                    const availableQtySpan = row.querySelector('.available-qty');
                                    const requestedQtyInput = row.querySelector('.requested-qty');
                                    const issuedQtyInput = row.querySelector('.issued-qty');

                                    if (availableQtySpan) {
                                        availableQtySpan.textContent = available;
                                        availableQtySpan.className = `available-qty font-medium ${available > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'}`;
                                    }

                                    if (requestedQtyInput) {
                                        requestedQtyInput.max = available;
                                        requestedQtyInput.disabled = available === 0;
                                    }

                                    if (issuedQtyInput) {
                                        issuedQtyInput.max = available;
                                        issuedQtyInput.disabled = available === 0;
                                    }

                                    if (requestedQtyInput) requestedQtyInput.value = '';
                                    if (issuedQtyInput) issuedQtyInput.value = '';

                                    const currentDate = risDateInput?.value;
                                    if (currentDate) {
                                        const rowIndex = Array.from(itemsTable.querySelectorAll('tr.manual-item-row')).indexOf(row);
                                        fetchAvailabilityForDate(selectedOption.value, rowIndex, currentDate);
                                    }
                                } else if (row) {
                                    const availableQtySpan = row.querySelector('.available-qty');
                                    const requestedQtyInput = row.querySelector('.requested-qty');
                                    const issuedQtyInput = row.querySelector('.issued-qty');

                                    if (availableQtySpan) {
                                        availableQtySpan.textContent = '0';
                                        availableQtySpan.className = 'available-qty font-medium text-gray-400';
                                    }

                                    if (requestedQtyInput) {
                                        requestedQtyInput.max = '';
                                        requestedQtyInput.disabled = false;
                                        requestedQtyInput.value = '';
                                    }

                                    if (issuedQtyInput) {
                                        issuedQtyInput.max = '';
                                        issuedQtyInput.disabled = false;
                                        issuedQtyInput.value = '';
                                    }
                                }
                            }
                        });

                        function updateEmptyState() {
                            const rowCount = itemsTable.children.length;
                            if (rowCount === 0) {
                                emptyState?.classList.remove('hidden');
                            } else {
                                emptyState?.classList.add('hidden');
                            }
                        }

                        // Status change handler
                        const finalStatusSelect = document.getElementById('finalStatusSelect');
                        const declineReasonDiv = document.getElementById('declineReasonDiv');
                        const completedInfoMessage = document.getElementById('completedInfoMessage');

                        if (finalStatusSelect) {
                            finalStatusSelect.addEventListener('change', function() {
                                const selectedStatus = this.value;

                                if (selectedStatus === 'declined') {
                                    if (declineReasonDiv) declineReasonDiv.classList.remove('hidden');
                                    if (completedInfoMessage) completedInfoMessage.classList.add('hidden');
                                } else {
                                    if (declineReasonDiv) declineReasonDiv.classList.add('hidden');

                                    if (selectedStatus === 'completed') {
                                        if (completedInfoMessage) completedInfoMessage.classList.remove('hidden');
                                    } else {
                                        if (completedInfoMessage) completedInfoMessage.classList.add('hidden');
                                    }
                                }
                            });
                        }

                        // Form submission
                        document.querySelector('#manualEntryModal form')?.addEventListener('submit', function(e) {
                            e.preventDefault();

                            const requiredFields = ['ris_date', 'entity_name', 'division', 'requested_by', 'purpose'];
                            let isValid = true;

                            requiredFields.forEach(fieldName => {
                                const field = document.querySelector(`[name="${fieldName}"]`);
                                if (field && !field.value.trim()) {
                                    isValid = false;
                                    field.classList.add('border-red-500');
                                } else if (field) {
                                    field.classList.remove('border-red-500');
                                }
                            });

                            if (!isValid) {
                                showAlert('Please fill in all required fields.', 'error');
                                return;
                            }

                            if (itemsTable.children.length === 0) {
                                showAlert('Please add at least one item.', 'error');
                                return;
                            }

                            const allSelects = itemsTable.querySelectorAll('.supply-select');
                            const suppliesInForm = [];
                            let hasDuplicates = false;

                            allSelects.forEach(select => {
                                if (select.value) {
                                    if (suppliesInForm.includes(select.value)) {
                                        hasDuplicates = true;
                                    } else {
                                        suppliesInForm.push(select.value);
                                    }
                                }
                            });

                            if (hasDuplicates) {
                                showAlert('Duplicate items found. Each item can only appear once in the requisition.', 'error');
                                return;
                            }

                            if (submitBtn) {
                                submitBtn.disabled = true;
                                submitBtn.innerHTML = '⏳ Processing...';
                            }

                            this.submit();
                        });

                        updateEmptyState();

                        // Handle validation errors modal reopening
                        @if ($errors->any() && old('is_manual_entry'))
                            resetModalState();
                            loadAvailableSupplies();

                            @if (old('ris_date'))
                                const savedDate = "{{ old('ris_date') }}";
                                if (risDateInput) {
                                    risDateInput.value = savedDate;
                                    setTimeout(() => {
                                        generateRisNumber(savedDate);
                                    }, 500);
                                }
                            @endif

                            isInitialized = true;
                        @endif
                    });
                </script>

                <!-- Keep existing compatibility scripts -->
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const risDateInput = document.querySelector('input[name="ris_date"]');
                        const tableBody = document.getElementById('manualItemsTable');
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                        function fetchAvailability(supplyId, rowIndex) {
                            if (!risDateInput?.value) return;

                            fetch("{{ route('ris.validate-manual-stock') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": csrfToken
                                },
                                body: JSON.stringify({
                                    ris_date: risDateInput.value,
                                    items: [{ supply_id: supplyId }]
                                })
                            })
                            .then(res => res.json())
                            .then(json => {
                                const available = json.current_availability[supplyId] || 0;
                                const row = Array.from(tableBody.querySelectorAll('tr.manual-item-row'))[rowIndex];

                                if (row) {
                                    const span = row.querySelector('.available-qty');
                                    const req = row.querySelector('.requested-qty');
                                    const iss = row.querySelector('.issued-qty');

                                    span.textContent = available;
                                    span.className = `available-qty font-medium ${
                                        available > 0
                                        ? 'text-green-600 dark:text-green-400'
                                        : 'text-red-600   dark:text-red-400'
                                    }`;

                                    req.max = available;
                                    iss.max = available;
                                    req.disabled = (available === 0);
                                    iss.disabled = (available === 0);
                                    if (+req.value > available) req.value = available;
                                    if (+iss.value > available) iss.value = available;
                                }
                            })
                            .catch(console.error);
                        }

                        risDateInput?.addEventListener('change', () => {
                            Array.from(tableBody.querySelectorAll('tr.manual-item-row'))
                            .forEach((row, idx) => {
                                const sel = row.querySelector('.supply-select');
                                if (sel.value) {
                                    fetchAvailability(sel.value, idx);
                                } else {
                                    const span = row.querySelector('.available-qty');
                                    if (span) {
                                        span.textContent = '0';
                                        span.className = 'available-qty font-medium text-gray-400 dark:text-gray-500';
                                    }
                                }
                            });
                        });

                        tableBody?.addEventListener('change', e => {
                            if (!e.target.classList.contains('supply-select')) return;
                            const rows = Array.from(tableBody.querySelectorAll('tr.manual-item-row'));
                            const idx = rows.indexOf(e.target.closest('tr'));
                            const supId = e.target.value;
                            if (supId) {
                                fetchAvailability(supId, idx);
                            } else {
                                const span = rows[idx]?.querySelector('.available-qty');
                                if (span) {
                                    span.textContent = '0';
                                    span.className = 'available-qty font-medium text-gray-400 dark:text-gray-500';
                                }
                            }
                        });
                    });
                </script>

                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const dateField = document.getElementById('ris_date');
                        const risNoField = document.getElementById('reference_no');
                        if (!dateField || !risNoField) return;

                        dateField.addEventListener('change', () => {
                        fetch(`{{ route('ris.next') }}?ris_date=` + encodeURIComponent(dateField.value), {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' },
                            credentials: 'same-origin'
                        })
                        .then(res => res.ok ? res.json() : Promise.reject(res.statusText))
                        .then(json => {
                            risNoField.value = json.defaultRis;
                        })
                        .catch(err => console.error('RIS lookup failed:', err));
                        });
                    });
                </script>

                <!-- Division-Office Auto-fill Script (PRODUCTION VERSION - NO CONSOLE LOGS) -->
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // For regular RIS form (if you have one)
                        const regularDivisionSelect = document.querySelector('select[name="division"]:not(#manualEntryModal select)');
                        const regularOfficeInput = document.querySelector('input[name="office"]:not(#manualEntryModal input)');

                        if (regularDivisionSelect && regularOfficeInput) {
                            regularDivisionSelect.addEventListener('change', function() {
                                updateOfficeField(this.value, regularOfficeInput);
                            });
                        }

                        // For manual entry modal
                        const manualDivisionSelect = document.querySelector('#manualEntryModal select[name="division"]');
                        const manualOfficeInput = document.querySelector('#manualEntryModal input[name="office"]');

                        if (manualDivisionSelect && manualOfficeInput) {
                            manualDivisionSelect.addEventListener('change', function() {
                                updateOfficeField(this.value, manualOfficeInput);
                            });
                        }

                        function updateOfficeField(departmentId, officeInput) {
                            if (!departmentId || !officeInput) {
                                if (officeInput) {
                                    officeInput.value = '';
                                }
                                return;
                            }

                            // Show loading state
                            const originalValue = officeInput.value;
                            const originalPlaceholder = officeInput.placeholder;
                            officeInput.value = '';
                            officeInput.placeholder = 'Loading office...';
                            officeInput.disabled = true;
                            officeInput.classList.add('animate-pulse', 'bg-blue-50', 'dark:bg-blue-900/20');

                            // Fetch department details
                            const baseUrl = window.location.origin;
                            const fetchUrl = `${baseUrl}/ris/department-details?department_id=${departmentId}`;

                            fetch(fetchUrl, {
                                method: 'GET',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                },
                                credentials: 'same-origin'
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                officeInput.value = data.office || '';
                                officeInput.classList.remove('animate-pulse', 'bg-blue-50', 'dark:bg-blue-900/20');
                                officeInput.classList.add('bg-green-50', 'dark:bg-green-900/20', 'border-green-300', 'dark:border-green-700');
                                setTimeout(() => {
                                    officeInput.classList.remove('bg-green-50', 'dark:bg-green-900/20', 'border-green-300', 'dark:border-green-700');
                                }, 800);
                            })
                            .catch(error => {
                                officeInput.value = originalValue;
                                officeInput.classList.remove('animate-pulse', 'bg-blue-50', 'dark:bg-blue-900/20');
                                officeInput.classList.add('bg-red-50', 'dark:bg-red-900/20', 'border-red-300', 'dark:border-red-700');
                                setTimeout(() => {
                                    officeInput.classList.remove('bg-red-50', 'dark:bg-red-900/20', 'border-red-300', 'dark:border-red-700');
                                }, 2000);

                                // Optional: Show user-friendly error message
                                if (typeof showAlert === 'function') {
                                    showAlert('Failed to load office information. Please enter manually.', 'warning');
                                }
                            })
                            .finally(() => {
                                officeInput.disabled = false;
                                officeInput.placeholder = originalPlaceholder;
                            });
                        }
                    });
                </script>

                <div class="p-5">
                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div id="alert-success"
                            class="flex items-center p-4 mb-5 text-[#10b981] rounded-lg bg-[#10b981]/10 dark:bg-gray-800 dark:text-[#34d399]"
                            role="alert">
                            <svg class="flex-shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                            </svg>
                            <span class="sr-only">Success</span>
                            <div class="ml-3 text-sm font-medium">{{ session('success') }}</div>
                            <button type="button"
                                class="ml-auto -mx-1.5 -my-1.5 bg-[#10b981]/10 text-[#10b981] rounded-lg focus:ring-2 focus:ring-[#10b981]/30 p-1.5 hover:bg-[#10b981]/20 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-[#34d399] dark:hover:bg-gray-700 transition-all duration-200"
                                data-dismiss-target="#alert-success" aria-label="Close">
                                <span class="sr-only">Close</span>
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                            </button>
                        </div>
                    @endif

                    <!-- RIS Table - Removed vertical scroll, keeping horizontal scroll for mobile -->
                    <div class="overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left border-collapse">
                                <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">RIS No</th>
                                        <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">Date</th>
                                        <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">Division</th>
                                        <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">Requested By</th>
                                        <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">Status</th>
                                        <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($risSlips as $ris)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                                {{ $ris->ris_no }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-col">
                                                    <span class="text-gray-900 dark:text-white">{{ $ris->ris_date->format('M d, Y') }}</span>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $ris->created_at->format('h:i A') }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-gray-900 dark:text-white">
                                                {{ $ris->department->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-900 dark:text-white">
                                                {{ $ris->requester->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4">
                                                @if ($ris->status === 'draft')
                                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                                                        <span class="relative flex h-2 w-2 mr-1">
                                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-gray-400 opacity-75"></span>
                                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-gray-500"></span>
                                                        </span>
                                                        Pending
                                                    </span>
                                                @elseif($ris->status === 'approved')
                                                    <span class="bg-[#6366f1]/10 text-[#6366f1] text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-[#6366f1]/20 dark:text-[#818cf8]">
                                                        <svg class="w-3 h-3 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                                                        </svg>
                                                        Approved
                                                    </span>
                                                @elseif($ris->status === 'posted' && !$ris->received_at)
                                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-yellow-900/20 dark:text-yellow-300">
                                                        <svg class="w-3 h-3 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z" />
                                                        </svg>
                                                        Issued - Pending Receipt
                                                    </span>
                                                @elseif($ris->status === 'posted' && $ris->received_at)
                                                    <span class="bg-[#10b981]/10 text-[#10b981] text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-[#10b981]/20 dark:text-[#34d399]">
                                                        <svg class="w-3 h-3 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="m18.774 8.245-.892-.893a1.5 1.5 0 0 1-.437-1.052V5.036a2.484 2.484 0 0 0-2.48-2.48H13.7a1.5 1.5 0 0 1-1.052-.438l-.893-.892a2.484 2.484 0 0 0-3.51 0l-.893.892a1.5 1.5 0 0 1-1.052.437H5.036a2.484 2.484 0 0 0-2.48 2.481V6.3a1.5 1.5 0 0 1-.438 1.052l-.892.893a2.484 2.484 0 0 0 0 3.51l.892.893a1.5 1.5 0 0 1 .437 1.052v1.264a2.484 2.484 0 0 0 2.481 2.481H6.3a1.5 1.5 0 0 1 1.052.437l.893.892a2.484 2.484 0 0 0 3.51 0l.893-.892a1.5 1.5 0 0 1 1.052-.437h1.264a2.484 2.484 0 0 0 2.481-2.48V13.7a1.5 1.5 0 0 1 .437-1.052l.892-.893a2.484 2.484 0 0 0 0-3.51Z" />
                                                            <path d="M8 13a1 1 0 0 1-.707-.293l-2-2a1 1 0 1 1 1.414-1.414L8 10.586l4.293-4.293a1 1 0 0 1 1.414 1.414l-5 5A1 1 0 0 1 8 13Z" />
                                                        </svg>
                                                        Completed
                                                    </span>
                                                @elseif($ris->status === 'declined')
                                                    <span class="bg-red-100 text-red-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-red-900/20 dark:text-red-300">
                                                        <svg class="w-3 h-3 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 1 1 1.414 1.414L11.414 10l2.293 2.293Z" />
                                                        </svg>
                                                        Declined
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <a href="{{ route('ris.show', $ris) }}"
                                                    class="p-2 text-[#10b981] hover:bg-[#10b981]/10 rounded-lg inline-flex items-center justify-center
                                                focus:outline-none focus:ring-2 focus:ring-[#10b981]/30 dark:text-[#34d399]
                                                dark:hover:bg-[#10b981]/20 transition-all duration-200"
                                                    data-tooltip-target="tooltip-view-{{ $ris->ris_id }}"
                                                    data-tooltip-placement="left">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="w-5 h-5">
                                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                </a>
                                                <div id="tooltip-view-{{ $ris->ris_id }}" role="tooltip"
                                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                    View Details
                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-8 text-center">
                                                <div class="flex flex-col items-center justify-center py-8">
                                                    <svg class="w-12 h-12 text-gray-300 mb-4" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="1.5"
                                                            d="M4 4v15a1 1 0 0 0 1 1h15M8 16l2.5-5.5 3 3L17.3 7 20 9.7" />
                                                    </svg>
                                                    <p class="text-lg font-medium text-gray-400 dark:text-gray-500">
                                                        No requisitions found</p>
                                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">
                                                        @if (request('status') || request('search'))
                                                            Try adjusting your filters or search term.
                                                        @else
                                                            There are no requisition slips in the system yet.
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

                    <br>

                    <div class="mt-2 sm:mt-0">
                        {{ $risSlips->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

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

    <!-- JavaScript for Alert Dismissal and Search -->
    <script>
        // Alert auto-dismissal after 5 seconds
        document.addEventListener('DOMContentLoaded', () => {
            const alert = document.getElementById('alert-success');
            if (alert) {
                setTimeout(() => {
                    alert.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }, 5000);
            }
        });

        // Functions for search input
        function toggleClearButton() {
            const input = document.getElementById('search-input');
            const clearBtn = document.getElementById('clearButton');
            if (input && clearBtn) {
                clearBtn.style.display = input.value.trim().length > 0 ? 'flex' : 'none';
            }
        }

        function clearSearch() {
            const input = document.getElementById('search-input');
            if (input) {
                input.value = '';
                document.getElementById('clearButton').style.display = 'none';

                // Preserve status filter when clearing search
                const currentUrl = new URL(window.location.href);
                const status = currentUrl.searchParams.get('status');

                if (status) {
                    window.location.href = window.location.pathname + '?status=' + status;
                } else {
                    window.location.href = window.location.pathname;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            toggleClearButton();
        });
    </script>

    <!-- Script 1: Handle Final Status Change -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle final status dropdown change
            const finalStatusSelect = document.getElementById('finalStatusSelect');
            const declineReasonDiv = document.getElementById('declineReasonDiv');

            if (finalStatusSelect) {
                finalStatusSelect.addEventListener('change', function() {
                    if (this.value === 'declined') {
                        declineReasonDiv.classList.remove('hidden');
                        // Make decline reason required
                        const declineReasonInput = document.querySelector('input[name="decline_reason"]');
                        if (declineReasonInput) {
                            declineReasonInput.required = true;
                        }
                    } else {
                        declineReasonDiv.classList.add('hidden');
                        // Remove required attribute
                        const declineReasonInput = document.querySelector('input[name="decline_reason"]');
                        if (declineReasonInput) {
                            declineReasonInput.required = false;
                            declineReasonInput.value = '';
                        }
                    }
                });
            }
        });
    </script>

    <!-- Script 2: Reopen Modal on Validation Errors -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if there are errors and if we're coming from manual entry form
            @if ($errors->any() && old('is_manual_entry'))
                console.log('Reopening modal due to validation errors...');

                // Reopen the manual entry modal
                const modal = document.getElementById('manualEntryModal');
                if (modal) {
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';

                    // Re-populate the form with old values
                    @if (old('ris_date'))
                        const risDateInput = document.querySelector('input[name="ris_date"]');
                        if (risDateInput) {
                            risDateInput.value = "{{ old('ris_date') }}";
                        }
                    @endif

                    @if (old('reference_no'))
                        const referenceNoInput = document.querySelector('input[name="reference_no"]');
                        if (referenceNoInput) {
                            referenceNoInput.value = "{{ old('reference_no') }}";
                        }
                    @endif

                    @if (old('entity_name'))
                        const entityNameInput = document.querySelector('input[name="entity_name"]');
                        if (entityNameInput) {
                            entityNameInput.value = "{{ old('entity_name') }}";
                        }
                    @endif

                    @if (old('division'))
                        const divisionSelect = document.querySelector('select[name="division"]');
                        if (divisionSelect) {
                            divisionSelect.value = "{{ old('division') }}";
                        }
                    @endif

                    @if (old('office'))
                        const officeInput = document.querySelector('input[name="office"]');
                        if (officeInput) {
                            officeInput.value = "{{ old('office') }}";
                        }
                    @endif

                    @if (old('fund_cluster'))
                        const fundClusterSelect = document.querySelector('select[name="fund_cluster"]');
                        if (fundClusterSelect) {
                            fundClusterSelect.value = "{{ old('fund_cluster') }}";
                        }
                    @endif

                    @if (old('responsibility_center_code'))
                        const responsibilityCodeInput = document.querySelector('input[name="responsibility_center_code"]');
                        if (responsibilityCodeInput) {
                            responsibilityCodeInput.value = "{{ old('responsibility_center_code') }}";
                        }
                    @endif

                    @if (old('requested_by'))
                        const requestedBySelect = document.querySelector('select[name="requested_by"]');
                        if (requestedBySelect) {
                            requestedBySelect.value = "{{ old('requested_by') }}";
                        }
                    @endif

                    @if (old('purpose'))
                        const purposeTextarea = document.querySelector('textarea[name="purpose"]');
                        if (purposeTextarea) {
                            purposeTextarea.value = "{{ old('purpose') }}";
                        }
                    @endif

                    @if (old('final_status'))
                        const finalStatusSelect = document.querySelector('select[name="final_status"]');
                        if (finalStatusSelect) {
                            finalStatusSelect.value = "{{ old('final_status') }}";

                            // Show decline reason field if status was declined
                            if ("{{ old('final_status') }}" === 'declined') {
                                const declineReasonDiv = document.getElementById('declineReasonDiv');
                                if (declineReasonDiv) {
                                    declineReasonDiv.classList.remove('hidden');
                                }

                                @if (old('decline_reason'))
                                    const declineReasonInput = document.querySelector('input[name="decline_reason"]');
                                    if (declineReasonInput) {
                                        declineReasonInput.value = "{{ old('decline_reason') }}";
                                    }
                                @endif
                            }
                        }
                    @endif

                    // Trigger change event on RIS date to regenerate RIS number
                    const risDateInput = document.querySelector('input[name="ris_date"]');
                    if (risDateInput && risDateInput.value) {
                        risDateInput.dispatchEvent(new Event('change'));
                    }

                    // Load available supplies
                    if (typeof loadAvailableSupplies === 'function') {
                        loadAvailableSupplies();
                    }

                    // Show error message
                    setTimeout(() => {
                        const errorAlert = document.createElement('div');
                        errorAlert.className = 'fixed top-4 right-4 z-[70] bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg max-w-md animate-modal-slide-up';
                        errorAlert.innerHTML = `
                            <div class="flex items-start">
                                <svg class="w-5 h-5 flex-shrink-0 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium">There were errors with your submission</p>
                                    <p class="text-xs mt-1 opacity-90">Please check the form for details.</p>
                                </div>
                                <button onclick="this.parentElement.parentElement.remove()" class="ml-2 hover:bg-black/10 rounded p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        `;
                        document.body.appendChild(errorAlert);
                        setTimeout(() => errorAlert.remove(), 5000);
                    }, 500);
                }
            @endif
        });
    </script>

    <!-- Script 3: Enhanced Form Validation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add real-time validation to required fields
            const requiredFields = {
                'ris_date': 'RIS Date',
                'entity_name': 'Entity Name',
                'division': 'Division',
                'requested_by': 'Requested By',
                'purpose': 'Purpose',
                'final_status': 'Final Status'
            };

            Object.keys(requiredFields).forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    field.addEventListener('blur', function() {
                        if (!this.value.trim()) {
                            this.classList.add('border-red-500');

                            // Remove any existing error message
                            const existingError = this.parentElement.querySelector('.field-error-message');
                            if (existingError) {
                                existingError.remove();
                            }

                            // Add error message
                            const errorMsg = document.createElement('p');
                            errorMsg.className = 'field-error-message text-xs text-red-500 mt-1';
                            errorMsg.textContent = `${requiredFields[fieldName]} is required`;
                            this.parentElement.appendChild(errorMsg);
                        } else {
                            this.classList.remove('border-red-500');
                            const existingError = this.parentElement.querySelector('.field-error-message');
                            if (existingError) {
                                existingError.remove();
                            }
                        }
                    });
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
        const risDateInput = document.querySelector('input[name="ris_date"]');
        const tableBody    = document.getElementById('manualItemsTable');
        const csrfToken    = document.querySelector('meta[name="csrf-token"]').content;

        // fetch availability as of ris_date for one supply row
        function fetchAvailability(supplyId, rowIndex) {
            fetch("{{ route('ris.validate-manual-stock') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({
                ris_date: risDateInput.value,
                items: [{ supply_id: supplyId }]
            })
            })
            .then(res => res.json())
            .then(json => {
            const available = json.current_availability[supplyId] || 0;
            const row       = Array.from(tableBody.querySelectorAll('tr.manual-item-row'))[rowIndex];
            const span      = row.querySelector('.available-qty');
            const req       = row.querySelector('.requested-qty');
            const iss       = row.querySelector('.issued-qty');

            // update display
            span.textContent = available;
            span.className = `available-qty font-medium ${
                available > 0
                ? 'text-green-600 dark:text-green-400'
                : 'text-red-600   dark:text-red-400'
            }`;

            // enforce limits
            req.max       = available;
            iss.max       = available;
            req.disabled  = (available === 0);
            iss.disabled  = (available === 0);
            if (+req.value > available) req.value = available;
            if (+iss.value > available) iss.value = available;
            })
            .catch(console.error);
        }

        // when RIS date changes, refresh all rows
        risDateInput.addEventListener('change', () => {
            Array.from(tableBody.querySelectorAll('tr.manual-item-row'))
            .forEach((row, idx) => {
                const sel = row.querySelector('.supply-select');
                if (sel.value) {
                fetchAvailability(sel.value, idx);
                } else {
                // reset if no supply chosen
                const span = row.querySelector('.available-qty');
                span.textContent = '0';
                span.className = 'available-qty font-medium text-gray-400 dark:text-gray-500';
                }
            });
        });

        // when you pick a supply in a row, fetch just that row’s availability
        tableBody.addEventListener('change', e => {
            if (!e.target.classList.contains('supply-select')) return;
            const rows = Array.from(tableBody.querySelectorAll('tr.manual-item-row'));
            const idx  = rows.indexOf(e.target.closest('tr'));
            const supId = e.target.value;
            if (supId) {
            fetchAvailability(supId, idx);
            } else {
            const span = rows[idx].querySelector('.available-qty');
            span.textContent = '0';
            span.className = 'available-qty font-medium text-gray-400 dark:text-gray-500';
            }
        });
        });
    </script>

</x-app-layout>
