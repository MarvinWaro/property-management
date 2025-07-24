<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Supplies') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">


            <!-- Supplies Section - Now in its own card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Button and Search Bar Container -->
                    <div class="flex items-center justify-between mb-4 mt-2 space-x-2 w-full">
                        <!-- Search Bar Container -->
                        <form method="GET" action="{{ route('supplies.index') }}"
                            class="w-full max-w-sm flex items-center space-x-2">
                            <div class="relative flex-grow">
                                <input type="text" name="search" id="search-input"
                                    value="{{ request()->get('search') }}" oninput="toggleClearButton()"
                                    placeholder="Search..."
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
                        @if(auth()->user()->hasRole('admin'))
                            <button data-modal-target="createSupplyModal" data-modal-toggle="createSupplyModal"
                                type="button"
                                class="py-2 px-3 text-white bg-[#ce201f] hover:bg-[#a01b1a] hover:shadow-lg rounded-lg text-sm font-medium focus:ring-4 focus:outline-none focus:ring-[#ce201f]/30 dark:focus:ring-[#ce201f]/30 transition-all duration-200 transform hover:scale-105 ml-2 inline-flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="hidden sm:inline-block">Create New Item</span>
                            </button>
                        @endif
                    </div>

                    <!-- Flash Messages -->
                    @if (session()->has('success'))
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

                    @if (session()->has('deleted'))
                        <div id="flashMessage"
                            class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                            role="alert">
                            <span class="font-medium">Success!</span> {{ session('deleted') }}
                        </div>
                        <script>
                            setTimeout(() => {
                                document.getElementById('flashMessage').style.display = 'none';
                            }, 3000);
                        </script>
                    @endif

                    @if (session()->has('error'))
                        <div id="flashMessage"
                            class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                            role="alert">
                            <span class="font-medium">Error!</span> {{ session('error') }}
                        </div>
                        <script>
                            setTimeout(() => {
                                document.getElementById('flashMessage').style.display = 'none';
                            }, 3000);
                        </script>
                    @endif

                    <!-- Table Description Caption -->
                    <div class="p-4 mb-4 text-sm text-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-gray-300">
                        <h3 class="text-lg font-semibold mb-1 text-gray-900 dark:text-white">CHED Supplies</h3>
                        <p>
                            This section provides a comprehensive overview of CHED supplies,
                            detailing current stock levels, item specifications, and
                            inventory management information to support efficient
                            procurement and resource allocation.
                        </p>
                    </div>

                    <!-- Supply Table - Removed vertical scroll, keeping horizontal scroll for mobile -->
                    <div class="overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left border-collapse">
                                <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                    <tr>
                                        {{-- <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">ID</th> --}}
                                        <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">Stock No</th>
                                        <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">Item Details</th>
                                        <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">Category</th>
                                        <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">Unit</th>
                                        <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">Reorder Point</th>
                                        @if(auth()->user()->hasRole('admin'))
                                            <th scope="col" class="px-6 py-3 text-center font-bold text-gray-800 dark:text-gray-200">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($supplies as $supply)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                            <!-- Supply ID -->
                                            {{-- <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ $supply->supply_id }}
                                            </td> --}}

                                            <!-- Stock No -->
                                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ $supply->stock_no }}
                                            </td>

                                            <!-- Item Details (Name + Description) -->
                                            <td class="px-6 py-4">
                                                <div class="text-gray-900 font-medium dark:text-white">{{ $supply->item_name }}</div>
                                                @if($supply->description)
                                                    <div class="text-xs italic text-blue-600 dark:text-blue-400">
                                                        {{ $supply->description }}
                                                    </div>
                                                @endif
                                            </td>

                                            <td class="px-6 py-4 dark:text-white">
                                                {{ $supply->category->name ?? 'Uncategorized' }}
                                            </td>

                                            <!-- Unit -->
                                            <td class="px-6 py-4 dark:text-white">
                                                {{ $supply->unit_of_measurement }}
                                            </td>
                                            <!-- Reorder Point -->
                                            <td class="px-6 py-4 dark:text-white">
                                                {{ $supply->reorder_point }}
                                            </td>
                                            <!-- Actions -->
                                            @if(auth()->user()->hasRole('admin'))
                                                <td class="px-6 py-4 text-center dark:text-white">
                                                    <div class="flex items-center justify-center space-x-2">
                                                        <!-- Edit Button - Now with Yellow styling -->
                                                        <!-- Replace the Edit Button section in your blade file with this -->
                                                        <button type="button" data-modal-target="editSupplyModal"
                                                            data-modal-toggle="editSupplyModal"
                                                            class="edit-supply-btn p-2 text-yellow-600 rounded-lg hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-yellow-300 dark:text-yellow-400 dark:hover:bg-gray-700 transition-all duration-200"
                                                            data-supply-id="{{ $supply->supply_id }}"
                                                            data-stock-no="{{ $supply->stock_no ?? '' }}"
                                                            data-item-name="{{ $supply->item_name ?? '' }}"
                                                            data-description="{{ $supply->description ?? '' }}"
                                                            data-unit="{{ $supply->unit_of_measurement ?? '' }}"
                                                            data-category-id="{{ $supply->category_id ?? '' }}"
                                                            data-reorder-point="{{ $supply->reorder_point ?? 0 }}"
                                                            data-acquisition-cost="{{ $supply->acquisition_cost ? number_format($supply->acquisition_cost, 2) : '0.00' }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path
                                                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                                <path
                                                                    d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z" />
                                                            </svg>
                                                        </button>

                                                        <!-- Delete Button -->
                                                        <button type="button"
                                                            data-modal-target="deleteSupplyModal{{ $supply->supply_id }}"
                                                            data-modal-toggle="deleteSupplyModal{{ $supply->supply_id }}"
                                                            class="p-2 text-red-600 rounded-lg hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-300 dark:text-red-400 dark:hover:bg-gray-700 transition-all duration-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M3 6h18" />
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                                <line x1="14" x2="14" y1="11" y2="17" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-8 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M18 6h-4V2a1 1 0 00-1-1H7a1 1 0 00-1 1v4H2a1 1 0 00-1 1v11a1 1 0 001 1h16a1 1 0 001-1V7a1 1 0 00-1-1z">
                                                        </path>
                                                    </svg>
                                                    <p class="text-lg font-medium text-gray-400 dark:text-gray-500">
                                                        No Supply found</p>
                                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">
                                                        Get started by adding a new Item/Supply</p>
                                                    @if(auth()->user()->hasRole('admin'))
                                                        <button type="button" data-modal-target="createSupplyModal"
                                                            data-modal-toggle="createSupplyModal"
                                                            class="mt-4 inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white font-medium text-sm rounded-lg transition-colors shadow-sm focus:ring-4 focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700">
                                                            <svg class="w-4 h-4 mr-2" fill="currentColor"
                                                                viewBox="0 0 20 20"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                            Add Items/Supply
                                                        </button>
                                                    @endif
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
                        {{ $supplies->links() }}
                    </div>

                    @if(auth()->user()->hasRole('admin'))
                        <!-- CREATE SUPPLY MODAL -->
                        <div id="createSupplyModal" tabindex="-1" aria-hidden="true"
                            class="hidden fixed top-0 right-0 left-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-full max-h-full flex justify-center items-center bg-gray-900 bg-opacity-50">

                            <div class="relative w-full max-w-4xl max-h-full">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-800 overflow-hidden">
                                    <!-- Modal header -->
                                    <div
                                        class="flex items-center justify-between p-5 border-b dark:border-gray-700 bg-gradient-to-r from-blue-600 to-blue-800">
                                        <h3 class="text-2xl font-bold text-white flex items-center">
                                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm9 4a1 1 0 10-2 0v6a1 1 0 102 0V7zm-3 2a1 1 0 10-2 0v4a1 1 0 102 0V9zm-3 3a1 1 0 10-2 0v1a1 1 0 102 0v-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Create New Supply
                                        </h3>
                                        <button type="button"
                                            class="text-white bg-blue-700 hover:bg-blue-800 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                                    dark:hover:bg-gray-600 transition-all duration-200"
                                            data-modal-hide="createSupplyModal">
                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>

                                    <!-- Modal body -> Form -->
                                    <form action="{{ route('supplies.store') }}" method="POST"
                                        class="p-6 bg-gray-50 dark:bg-gray-800">
                                        @csrf

                                        <!-- Validation Errors Alert - FIXED -->
                                        @if ($errors->any())
                                            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                                                role="alert">
                                                <div class="font-medium mb-2">Oops! There were some problems with your input:</div>
                                                <ul class="list-disc list-inside space-y-1">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Fill in the information
                                            below to
                                            create a new supply item in the inventory.</p>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <!-- Left Column -->
                                            <div class="space-y-5">
                                                <!-- Basic Information Section -->
                                                <div class="p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                                                    <h4
                                                        class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor"
                                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd"
                                                                d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        Basic Information
                                                    </h4>

                                                    <!-- Stock No -->
                                                    <div class="mb-4">
                                                        <label for="stock_no"
                                                            class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Stock No <span class="text-red-500">*</span>
                                                        </label>
                                                        <div class="relative">
                                                            <div
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                    fill="currentColor" viewBox="0 0 20 20"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd"
                                                                        d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <input type="text" name="stock_no" id="stock_no"
                                                                placeholder="Enter Stock No" value="{{ old('stock_no') }}"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                                dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                                        </div>
                                                        @error('stock_no')
                                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                                {{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Item Name -->
                                                    <div class="mb-4">
                                                        <label for="item_name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Item Name <span class="text-red-500">*</span>
                                                        </label>
                                                        <div class="relative">
                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <input type="text"
                                                                name="item_name"
                                                                id="item_name"
                                                                placeholder="Enter Item Name"
                                                                value="{{ old('item_name') }}"
                                                                style="text-transform: uppercase;"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                        focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                                        </div>
                                                        @error('item_name')
                                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Description -->
                                                    <div class="mb-4">
                                                        <label for="description"
                                                            class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Description
                                                        </label>
                                                        <div class="relative">
                                                            <div
                                                                class="absolute top-3 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                    fill="currentColor" viewBox="0 0 20 20"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd"
                                                                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <textarea name="description" id="description" placeholder="Enter Description" rows="3"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                                dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ old('description') }}</textarea>
                                                        </div>
                                                    </div>
                                                    @error('description')
                                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                            {{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Unit of Measurement Section -->
                                                <div class="p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                                                    <h4
                                                        class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor"
                                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd"
                                                                d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        Measurement & Classification
                                                    </h4>

                                                    <!-- Unit of Measurement -->
                                                    <div class="mb-4">
                                                        <label for="unit_of_measurement"
                                                            class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Unit of Measurement <span class="text-red-500">*</span>
                                                        </label>
                                                        <div class="relative">
                                                            <div
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                    fill="currentColor" viewBox="0 0 20 20"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd"
                                                                        d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <select name="unit_of_measurement" id="unit_of_measurement"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                    focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                                    dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                                                    dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                                <option value="" disabled selected>Select unit
                                                                </option>
                                                                <option value="PIECE" {{ old('unit_of_measurement') == 'PIECE' ? 'selected' : '' }}>PIECE (PCS)</option>
                                                                <option value="BOX" {{ old('unit_of_measurement') == 'BOX' ? 'selected' : '' }}>BOX</option>
                                                                <option value="REAM" {{ old('unit_of_measurement') == 'REAM' ? 'selected' : '' }}>REAM</option>
                                                                <option value="GALLON" {{ old('unit_of_measurement') == 'GALLON' ? 'selected' : '' }}>GALLON</option>
                                                                <option value="LITRE" {{ old('unit_of_measurement') == 'LITRE' ? 'selected' : '' }}>LITRE (L)</option>
                                                                <option value="PACK" {{ old('unit_of_measurement') == 'PACK' ? 'selected' : '' }}>PACK</option>
                                                                <option value="PAIR" {{ old('unit_of_measurement') == 'PAIR' ? 'selected' : '' }}>PAIR</option>
                                                                <option value="CAN" {{ old('unit_of_measurement') == 'CAN' ? 'selected' : '' }}>CAN</option>
                                                                <option value="SET" {{ old('unit_of_measurement') == 'SET' ? 'selected' : '' }}>SET</option>
                                                                <option value="ROLL" {{ old('unit_of_measurement') == 'ROLL' ? 'selected' : '' }}>ROLL</option>
                                                                <option value="BOTTLE" {{ old('unit_of_measurement') == 'BOTTLE' ? 'selected' : '' }}>BOTTLE</option>
                                                                <option value="PAD" {{ old('unit_of_measurement') == 'PAD' ? 'selected' : '' }}>PAD</option>
                                                                <option value="POUCH" {{ old('unit_of_measurement') == 'POUCH' ? 'selected' : '' }}>POUCH</option>
                                                                <option value="SHEET" {{ old('unit_of_measurement') == 'SHEET' ? 'selected' : '' }}>SHEET</option>
                                                            </select>
                                                        </div>
                                                        @error('unit_of_measurement')
                                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                                {{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Category (Optional) -->
                                                    <div class="mb-4">
                                                        <label for="category_id"
                                                            class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Category <span class="text-gray-500 text-xs">(Optional)</span>
                                                        </label>
                                                        <div class="relative">
                                                            <div
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                    fill="currentColor" viewBox="0 0 20 20"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <select name="category_id" id="category_id"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                    focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                    dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                                    dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                                <option value="" selected>Select Category</option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                                        {{ $category->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @error('category_id')
                                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                                {{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Right Column -->
                                            <div class="space-y-5">
                                                <!-- Inventory Management Section -->
                                                <div class="p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                                                    <h4
                                                        class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor"
                                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z">
                                                            </path>
                                                            <path fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        Inventory Management
                                                    </h4>

                                                    <!-- Reorder Point -->
                                                    <div class="mb-4">
                                                        <label for="reorder_point"
                                                            class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Reorder Point <span class="text-red-500">*</span>
                                                        </label>
                                                        <div class="relative">
                                                            <div
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                    fill="currentColor" viewBox="0 0 20 20"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd"
                                                                        d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <input type="number" name="reorder_point" id="reorder_point"
                                                                value="{{ old('reorder_point', '0') }}" min="0" required
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                                dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                        </div>
                                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Minimum
                                                            quantity before reordering is required</p>
                                                        @error('reorder_point')
                                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                                {{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Acquisition Cost -->
                                                    <div class="mb-4">
                                                        <label for="acquisition_cost"
                                                            class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Acquisition Cost
                                                        </label>
                                                        <div class="relative">
                                                            <div
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                    fill="currentColor" viewBox="0 0 20 20"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z">
                                                                    </path>
                                                                    <path fill-rule="evenodd"
                                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <input type="text" id="acquisition_cost"
                                                                name="acquisition_cost"
                                                                value="{{ old('acquisition_cost', '0.00') }}"
                                                                placeholder="0.00"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                        focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                        </div>
                                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Cost per
                                                            unit in your local currency</p>
                                                        @error('acquisition_cost')
                                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                                {{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Notes & Tips -->
                                                <div
                                                    class="p-4 bg-blue-50 dark:bg-gray-700 rounded-lg border border-blue-200 dark:border-blue-900">
                                                    <h4
                                                        class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-2 flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd"
                                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        Important Information
                                                    </h4>
                                                    <ul
                                                        class="text-xs text-blue-700 dark:text-blue-300 space-y-1 ml-6 list-disc">
                                                        <li>All fields marked with <span class="text-red-500">*</span> are
                                                            required</li>
                                                        <li>Category field is optional</li>
                                                        <li>Stock numbers should be unique to avoid confusion</li>
                                                        <li>Set appropriate reorder points to avoid stockouts</li>
                                                        <li>Acquisition costs help track budget and inventory value</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Footer -->
                                        <div
                                            class="flex items-center justify-end pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                                            <button type="button" data-modal-hide="createSupplyModal"
                                                class="py-2.5 px-5 mr-3 text-sm font-medium text-gray-900 focus:outline-none
                                                        bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700
                                                        focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700
                                                        dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600
                                                        dark:hover:text-white dark:hover:bg-gray-700 transition-all duration-200">
                                                Cancel
                                            </button>
                                            <button type="submit"
                                                class="text-white bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900
                                                        focus:ring-4 focus:outline-none focus:ring-blue-300
                                                        font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center
                                                        dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-all duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Save Supply
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(auth()->user()->hasRole('admin'))
                        <!-- Edit Supply Modal - WITHOUT SUPPLIER/DEPARTMENT FIELDS -->
                        <div id="editSupplyModal" tabindex="-1" aria-hidden="true"
                            class="hidden fixed top-0 right-0 left-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-full max-h-full flex justify-center items-center bg-gray-900 bg-opacity-50">
                            <div class="relative w-full max-w-4xl max-h-full">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-800 overflow-hidden">
                                    <!-- Modal header -->
                                    <div
                                        class="flex items-center justify-between p-5 border-b dark:border-gray-700 bg-gradient-to-r from-blue-600 to-blue-800">
                                        <h3 class="text-2xl font-bold text-white flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit Supply
                                        </h3>
                                        <button type="button"
                                            class="text-white bg-blue-700 hover:bg-blue-800 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                                        dark:hover:bg-gray-600 transition-all duration-200"
                                            data-modal-hide="editSupplyModal">
                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>

                                    <!-- Modal body -> Form -->
                                    <form id="editSupplyForm" method="POST" class="p-6 bg-gray-50 dark:bg-gray-800">
                                        @csrf
                                        @method('PUT')

                                        <!-- Validation Errors Alert -->
                                        @if ($errors->any() && session('show_edit_modal'))
                                            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                                                role="alert">
                                                <div class="font-medium mb-2">Oops! There were some problems with your input:</div>
                                                <ul class="list-disc list-inside space-y-1">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <input type="hidden" id="edit_supply_id" name="supply_id">
                                        <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Update the information
                                            below to modify this supply item.</p>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <!-- Left Column -->
                                            <div class="space-y-5">
                                                <!-- Basic Information Section -->
                                                <div class="p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                                                    <h4
                                                        class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor"
                                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd"
                                                                d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        Basic Information
                                                    </h4>

                                                    <!-- Stock No -->
                                                    <div class="mb-4">
                                                        <label for="edit_stock_no"
                                                            class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Stock No <span class="text-red-500">*</span>
                                                        </label>
                                                        <div class="relative">
                                                            <div
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                    fill="currentColor" viewBox="0 0 20 20"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd"
                                                                        d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <input type="text" name="stock_no" id="edit_stock_no"
                                                                placeholder="Enter Stock No"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                                dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                                required />
                                                        </div>
                                                        @error('stock_no')
                                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                                {{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Item Name -->
                                                    <div class="mb-4">
                                                        <label for="edit_item_name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Item Name <span class="text-red-500">*</span>
                                                        </label>
                                                        <div class="relative">
                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <input type="text"
                                                                name="item_name"
                                                                id="edit_item_name"
                                                                placeholder="Enter Item Name"
                                                                style="text-transform: uppercase;"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                        focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                                required />
                                                        </div>
                                                        @error('item_name')
                                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Description -->
                                                    <div class="mb-4">
                                                        <label for="edit_description"
                                                            class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Description
                                                        </label>
                                                        <div class="relative">
                                                            <div
                                                                class="absolute top-3 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                    fill="currentColor" viewBox="0 0 20 20"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd"
                                                                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <textarea name="description" id="edit_description" placeholder="Enter Description" rows="3"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                                        </div>
                                                        @error('description')
                                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                                {{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Unit of Measurement Section -->
                                                <div class="p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                                                    <h4
                                                        class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor"
                                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd"
                                                                d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        Measurement & Classification
                                                    </h4>

                                                    <!-- Unit of Measurement -->
                                                    <div class="mb-4">
                                                        <label for="edit_unit_of_measurement"
                                                            class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Unit of Measurement <span class="text-red-500">*</span>
                                                        </label>
                                                        <div class="relative">
                                                            <div
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                    fill="currentColor" viewBox="0 0 20 20"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd"
                                                                        d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <select name="unit_of_measurement"
                                                                id="edit_unit_of_measurement" required
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                    focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                    dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                                    dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                                <option value="" disabled>Select unit</option>
                                                                <option value="PIECE">PIECE (PCS)</option>
                                                                <option value="BOX">BOX</option>
                                                                <option value="REAM">REAM</option>
                                                                <option value="GALLON">GALLON</option>
                                                                <option value="LITRE">LITRE (L)</option>
                                                                <option value="PACK">PACK</option>
                                                                <option value="PAIR">PAIR</option>
                                                                <option value="CAN">CAN</option>
                                                                <option value="SET">SET</option>
                                                                <option value="ROLL">ROLL</option>
                                                                <option value="BOTTLE">BOTTLE</option>
                                                                <option value="PAD">PAD</option>
                                                                <option value="POUCH">POUCH</option>
                                                                <option value="SHEET">SHEET</option>
                                                            </select>
                                                        </div>
                                                        @error('unit_of_measurement')
                                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                                {{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Category (Optional) -->
                                                    <div class="mb-4">
                                                        <label for="edit_category_id"
                                                            class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Category <span class="text-gray-500 text-xs">(Optional)</span>
                                                        </label>
                                                        <div class="relative">
                                                            <div
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                    fill="currentColor" viewBox="0 0 20 20"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <select name="category_id" id="edit_category_id"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                    focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                    dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                                    dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                                <option value="">Select Category</option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}">
                                                                        {{ $category->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @error('category_id')
                                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                                {{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Right Column -->
                                            <div class="space-y-5">
                                                <!-- Inventory Management Section -->
                                                <div class="p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                                                    <h4
                                                        class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor"
                                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z">
                                                            </path>
                                                            <path fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        Inventory Management
                                                    </h4>

                                                    <!-- Reorder Point -->
                                                    <div class="mb-4">
                                                        <label for="edit_reorder_point"
                                                            class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Reorder Point <span class="text-red-500">*</span>
                                                        </label>
                                                        <div class="relative">
                                                            <div
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                    fill="currentColor" viewBox="0 0 20 20"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd"
                                                                        d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <input type="number" name="reorder_point"
                                                                id="edit_reorder_point" min="0" required
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                    focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                                    dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                        </div>
                                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Minimum
                                                            quantity before reordering is required</p>
                                                        @error('reorder_point')
                                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                                {{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Acquisition Cost -->
                                                    <div class="mb-4">
                                                        <label for="edit_acquisition_cost"
                                                            class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Acquisition Cost
                                                        </label>
                                                        <div class="relative">
                                                            <div
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                    fill="currentColor" viewBox="0 0 20 20"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z">
                                                                    </path>
                                                                    <path fill-rule="evenodd"
                                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <input type="text" id="edit_acquisition_cost"
                                                                name="acquisition_cost" placeholder="0.00"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                    focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                                    dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                        </div>
                                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Cost per
                                                            unit in your local currency</p>
                                                    </div>
                                                    @error('acquisition_cost')
                                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                            {{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Update Information -->
                                                <div
                                                    class="p-4 bg-yellow-50 dark:bg-gray-700 rounded-lg border border-yellow-200 dark:border-yellow-900">
                                                    <h4
                                                        class="text-sm font-medium text-yellow-800 dark:text-yellow-300 mb-2 flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd"
                                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        Update Information
                                                    </h4>
                                                    <ul
                                                        class="text-xs text-yellow-700 dark:text-yellow-300 space-y-1 ml-6 list-disc">
                                                        <li>All fields marked with <span class="text-red-500">*</span> are
                                                            required</li>
                                                        <li>Category field is optional</li>
                                                        <li>Changes will be applied immediately upon saving</li>
                                                        <li>Updating a supply item will not affect any related inventory
                                                            transactions</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Footer -->
                                        <div
                                            class="flex items-center justify-end pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                                            <button type="button" data-modal-hide="editSupplyModal"
                                                class="py-2.5 px-5 mr-3 text-sm font-medium text-gray-900 focus:outline-none
                                                    bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700
                                                    focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700
                                                    dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600
                                                    dark:hover:text-white dark:hover:bg-gray-700 transition-all duration-200">
                                                Cancel
                                            </button>
                                            <button type="submit"
                                                class="text-white bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900
                                                    focus:ring-4 focus:outline-none focus:ring-blue-300
                                                    font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center
                                                    dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-all duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                </svg>
                                                Update Supply
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Delete Supply Modal -->
                    @if(auth()->user()->hasRole('admin'))
                        @foreach ($supplies as $supply)
                            <div id="deleteSupplyModal{{ $supply->supply_id }}" tabindex="-1" aria-hidden="true"
                                class="hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full flex bg-gray-900 bg-opacity-50">
                                <div class="relative p-4 w-full max-w-md max-h-full">
                                    <!-- Modal content -->
                                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                        <!-- Modal header -->
                                        <div
                                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 bg-gradient-to-r from-red-500 to-red-700">
                                            <h3 class="text-lg font-semibold text-white flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="mr-2">
                                                    <path d="M3 6h18"></path>
                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17">
                                                    </line>
                                                    <line x1="14" y1="11" x2="14" y2="17">
                                                    </line>
                                                </svg>
                                                Delete Supply
                                            </h3>
                                            <button type="button"
                                                class="text-white bg-transparent hover:bg-red-800 hover:text-white rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                                data-modal-hide="deleteSupplyModal{{ $supply->supply_id }}">
                                                <svg class="w-3 h-3" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 14 14">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                </svg>
                                                <span class="sr-only">Close modal</span>
                                            </button>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="p-6">
                                            <div class="mb-5 text-center">
                                                <div
                                                    class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-600"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                </div>
                                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mt-3">
                                                    Confirm Deletion</h3>
                                                <div class="mt-2 text-gray-600 dark:text-gray-400">
                                                    <p>Are you sure you want to delete this supply item:</p>
                                                    <p class="font-semibold text-gray-800 dark:text-white mt-1">
                                                        "{{ $supply->item_name }}" ({{ $supply->stock_no }})</p>
                                                </div>
                                                <p class="mt-3 text-sm text-red-500">This action cannot be undone.</p>
                                            </div>

                                            <form action="{{ route('supplies.destroy', $supply->supply_id) }}"
                                                method="POST" class="mt-6">
                                                @csrf
                                                @method('DELETE')
                                                <div class="flex items-center justify-center space-x-4">
                                                    <button data-modal-hide="deleteSupplyModal{{ $supply->supply_id }}"
                                                        type="button"
                                                        class="py-2.5 px-5 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                                        Cancel
                                                    </button>
                                                    <button type="submit"
                                                        class="py-2.5 px-5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 inline-flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="mr-2">
                                                            <path d="M3 6h18"></path>
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                        </svg>
                                                        Delete Permanently
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>
        </div>
    </div>
f
    <!-- JavaScript for Search Input -->
    <script>
        function toggleClearButton() {
            const input = document.getElementById('search-input');
            const clearBtn = document.getElementById('clearButton');
            clearBtn.style.display = input.value.trim().length > 0 ? 'flex' : 'none';
        }

        function clearSearch() {
            const input = document.getElementById('search-input');
            input.value = '';
            document.getElementById('clearButton').style.display = 'none';
            // Optional: Trigger form submission to refresh results with empty search
            // input.form.submit();
        }

        document.addEventListener('DOMContentLoaded', () => {
            toggleClearButton();
        });
    </script>

    {{-- Comma on Acquisition Cost --}}
    <script>
        const acqCostInput = document.getElementById('acquisition_cost');

        if (acqCostInput) {
            acqCostInput.addEventListener('input', function() {
                // 1) Remove all non-digit characters
                let digits = this.value.replace(/\D/g, '');
                if (digits === '') {
                    digits = '0';
                }

                // 2) Interpret as cents: parse integer, then divide by 100
                let intValue = parseInt(digits, 10);
                let amount = intValue / 100;

                // 3) Format with commas + always 2 decimals
                this.value = amount.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            });
        }
    </script>

    <!-- JavaScript for Supply Modals - UPDATED WITH REORDER POINT FIX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Format acquisition cost inputs
            formatAcquisitionCost('acquisition_cost');
            formatAcquisitionCost('edit_acquisition_cost');

            // Setup uppercase conversion for item name inputs
            setupUppercaseInputs();

            // Setup modal toggling
            setupModalToggles();

            // Setup reorder point inputs (NEW)
            setupReorderPointInputs();

            // Check for validation errors and show modals if needed
            @if (session('show_create_modal'))
                document.getElementById('createSupplyModal').classList.remove('hidden');
            @endif

            @if (session('show_edit_modal'))
                // Find the edit button with the matching supply ID and trigger click
                const supplyId = {{ session('show_edit_modal') }};
                const editButtons = document.querySelectorAll('.edit-supply-btn');
                editButtons.forEach(button => {
                    if (button.getAttribute('data-supply-id') == supplyId) {
                        button.click();
                    }
                });
            @endif
        });

        /**
         * Setup reorder point inputs to auto-select on focus (NEW FUNCTION)
         */
        function setupReorderPointInputs() {
            // Handle both create and edit modal reorder point inputs
            const reorderInputs = ['reorder_point', 'edit_reorder_point'];

            reorderInputs.forEach(inputId => {
                const input = document.getElementById(inputId);
                if (input) {
                    // Select all text when input gains focus
                    input.addEventListener('focus', function() {
                        this.select();
                    });

                    // Alternative: Clear if value is "0" when user starts typing a number
                    input.addEventListener('keydown', function(e) {
                        // If the current value is "0" and user presses a number key, clear it first
                        if (this.value === '0' && /^[0-9]$/.test(e.key)) {
                            this.value = '';
                        }
                    });

                    // Prevent negative values
                    input.addEventListener('input', function() {
                        if (this.value < 0) {
                            this.value = 0;
                        }
                    });
                }
            });
        }

        /**
         * Setup uppercase conversion for item name inputs
         */
        function setupUppercaseInputs() {
            // Create modal item name input
            const createItemNameInput = document.getElementById('item_name');
            if (createItemNameInput) {
                createItemNameInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            }

            // Edit modal item name input
            const editItemNameInput = document.getElementById('edit_item_name');
            if (editItemNameInput) {
                editItemNameInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            }
        }

        function formatAcquisitionCost(inputId) {
            const input = document.getElementById(inputId);
            if (input) {
                input.addEventListener('input', function() {
                    // Remove all non-digit characters
                    let digits = this.value.replace(/\D/g, '');
                    if (digits === '') {
                        digits = '0';
                    }

                    // Interpret as cents: parse integer, then divide by 100
                    let intValue = parseInt(digits, 10);
                    let amount = intValue / 100;

                    // Format with commas + always 2 decimals
                    this.value = amount.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                });
            }
        }

        function setupModalToggles() {
            // Handle edit button clicks - FIXED WITH BETTER NULL HANDLING
            const editButtons = document.querySelectorAll('.edit-supply-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    try {
                        // Get the data attributes with null checking
                        const id = this.getAttribute('data-supply-id') || '';
                        const stockNo = this.getAttribute('data-stock-no') || '';
                        const itemName = this.getAttribute('data-item-name') || '';
                        const description = this.getAttribute('data-description') || '';
                        const unit = this.getAttribute('data-unit') || '';
                        const categoryId = this.getAttribute('data-category-id') || '';
                        const reorderPoint = this.getAttribute('data-reorder-point') || '0';
                        const acquisitionCost = this.getAttribute('data-acquisition-cost') || '0.00';

                        console.log('Edit button clicked. Data:', {
                            id, stockNo, itemName, description, unit, categoryId, reorderPoint, acquisitionCost
                        });

                        // Populate form fields with null checking
                        const supplyIdInput = document.getElementById('edit_supply_id');
                        if (supplyIdInput) supplyIdInput.value = id;

                        const stockNoInput = document.getElementById('edit_stock_no');
                        if (stockNoInput) stockNoInput.value = stockNo;

                        const itemNameInput = document.getElementById('edit_item_name');
                        if (itemNameInput) {
                            // Safely convert to uppercase only if itemName exists
                            itemNameInput.value = itemName ? itemName.toUpperCase() : '';
                        }

                        const descriptionInput = document.getElementById('edit_description');
                        if (descriptionInput) descriptionInput.value = description;

                        // Set the select dropdowns with null checking
                        const unitDropdown = document.getElementById('edit_unit_of_measurement');
                        if (unitDropdown && unit) {
                            // Reset all options first
                            Array.from(unitDropdown.options).forEach(option => {
                                option.selected = false;
                            });
                            // Then select the matching option
                            unitDropdown.value = unit;
                            // If value didn't set, try to find and select manually
                            if (unitDropdown.value !== unit) {
                                Array.from(unitDropdown.options).forEach(option => {
                                    if (option.value === unit) {
                                        option.selected = true;
                                    }
                                });
                            }
                        }

                        const categoryDropdown = document.getElementById('edit_category_id');
                        if (categoryDropdown) {
                            // Reset all options first
                            Array.from(categoryDropdown.options).forEach(option => {
                                option.selected = false;
                            });
                            // Set the value, handling empty/null categoryId
                            categoryDropdown.value = categoryId || '';
                        }

                        const reorderPointInput = document.getElementById('edit_reorder_point');
                        if (reorderPointInput) reorderPointInput.value = reorderPoint;

                        const acquisitionCostInput = document.getElementById('edit_acquisition_cost');
                        if (acquisitionCostInput) acquisitionCostInput.value = acquisitionCost;

                        // Set the form action URL
                        const form = document.getElementById('editSupplyForm');
                        if (form) {
                            form.action = `/supplies/${id}`;
                        }

                        // Open the modal programmatically
                        const modal = document.getElementById('editSupplyModal');
                        if (modal) {
                            modal.classList.remove('hidden');
                        }
                    } catch (error) {
                        console.error('Error populating edit form:', error);
                        alert('An error occurred while loading the supply data. Please try again.');
                    }
                });
            });

            // Add event listeners for all modal toggle buttons
            const toggleButtons = document.querySelectorAll('[data-modal-toggle]');
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const modalId = this.getAttribute('data-modal-toggle');
                    const modal = document.getElementById(modalId);

                    if (modal) {
                        modal.classList.remove('hidden');
                    }
                });
            });

            // Add event listeners for all modal close buttons
            const closeButtons = document.querySelectorAll('[data-modal-hide]');
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const modalId = this.getAttribute('data-modal-hide');
                    const modal = document.getElementById(modalId);

                    if (modal) {
                        modal.classList.add('hidden');
                    }
                });
            });

            // Add click event listener for clicking outside modals to close them
            document.addEventListener('click', function(event) {
                // Check if we clicked directly on modal background
                const modals = document.querySelectorAll('.fixed.flex.justify-center.items-center');
                modals.forEach(modal => {
                    if (event.target === modal) {
                        modal.classList.add('hidden');
                    }
                });
            });
        }
    </script>

</x-app-layout>
