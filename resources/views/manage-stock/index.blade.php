<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Supply Stocks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="section-container p-5">
                    <!-- Button and Search Bar Container -->
                    <div class="flex items-center justify-between mb-4 mt-2 space-x-2 w-full">
                        <!-- Search Bar Container -->
                        <form method="GET" action="{{ route('stocks.index') }}"
                            class="w-full max-w-sm flex items-center space-x-2">
                            <div class="relative flex-grow">
                                <input type="text" name="search" id="search-input"
                                    value="{{ request()->get('search') }}" oninput="toggleClearButton()"
                                    placeholder="Search..."
                                    class="px-4 py-2 w-full border text-sm font-medium border-gray-300 rounded-lg
                                              focus:ring-1 focus:ring-blue-500 focus:border-blue-500
                                              dark:bg-gray-800 dark:border-gray-700 dark:text-white
                                              dark:focus:ring-blue-500 dark:focus:border-blue-500" />

                                <!-- The 'X' Button (hidden by default) -->
                                <button type="button" id="clearButton" onclick="clearSearch()" style="display: none;"
                                    class="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-red-500 focus:outline-none">
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
                                class="px-3 py-2 text-sm text-white bg-blue-700 rounded-lg
                                           hover:bg-blue-800 focus:ring-1 focus:outline-none
                                           focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700
                                           dark:focus:ring-blue-800 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="size-5">
                                    <path fill-rule="evenodd"
                                        d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </form>

                        <!-- Create Stock Button -->
                        <button data-modal-target="createStockModal" data-modal-toggle="createStockModal" type="button"
                            class="py-2 px-3 text-white bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 rounded-lg text-sm font-medium focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 transition-all duration-200 ml-2 inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="hidden sm:inline-block">Add Stock</span>
                        </button>
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
                    <div
                        class="p-4 mb-4 text-sm text-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-gray-300">
                        <h3 class="text-lg font-semibold mb-1 text-gray-900 dark:text-white">CHED Supply Stocks</h3>
                        <p>
                            This section provides a comprehensive overview of CHED supply stocks,
                            detailing current stock levels, unit costs, and inventory valuation
                            to support efficient inventory management and financial reporting.
                        </p>
                    </div>

                    <!-- Supply Stock Table -->
                    <div class="overflow-hidden shadow-md sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="overflow-x-auto">
                            <div class="overflow-y-auto max-h-[500px]">
                                <table class="w-full text-sm text-left">
                                    <thead
                                        class="text-xs text-white uppercase bg-gradient-to-r from-blue-600 to-blue-800 dark:from-blue-700 dark:to-blue-900 sticky top-0 z-10">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">ID</th>
                                            <th scope="col" class="px-6 py-3">Supply Item</th>
                                            <th scope="col" class="px-6 py-3">Quantity</th>
                                            <th scope="col" class="px-6 py-3">Unit Cost</th>
                                            <th scope="col" class="px-6 py-3">Total Value</th>
                                            <th scope="col" class="px-6 py-3">Status</th>
                                            <th scope="col" class="px-6 py-3">Expiry Date</th>
                                            <th scope="col" class="px-6 py-3 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($stocks as $stock)
                                            <tr
                                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <!-- Stock ID -->
                                                <td
                                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {{ $stock->stock_id }}
                                                </td>
                                                <!-- Supply Item -->
                                                <td class="px-6 py-4 dark:text-white">
                                                    <div class="font-medium">{{ $stock->supply->item_name }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $stock->supply->stock_no }}</div>
                                                </td>
                                                <!-- Quantity -->
                                                <td class="px-6 py-4 dark:text-white">
                                                    {{ number_format($stock->quantity_on_hand) }}
                                                    {{ $stock->supply->unit_of_measurement }}
                                                </td>
                                                <!-- Unit Cost -->
                                                <td class="px-6 py-4 dark:text-white">
                                                    ₱{{ number_format($stock->unit_cost, 2) }}
                                                </td>
                                                <!-- Total Value -->
                                                <td class="px-6 py-4 dark:text-white">
                                                    ₱{{ number_format($stock->total_cost, 2) }}
                                                </td>
                                                <!-- Status -->
                                                <td class="px-6 py-4">
                                                    <span
                                                        class="px-2 py-1 text-xs font-medium rounded-full
                                                        @if ($stock->status == 'available') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                                        @elseif($stock->status == 'reserved') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                                        @elseif($stock->status == 'expired') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                                        @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300 @endif">
                                                        {{ ucfirst($stock->status) }}
                                                    </span>
                                                </td>
                                                <!-- Expiry Date -->
                                                <td class="px-6 py-4 dark:text-white">
                                                    @if ($stock->expiry_date)
                                                        <span
                                                            class="@if (\Carbon\Carbon::parse($stock->expiry_date)->isPast()) text-red-600 dark:text-red-400 @endif">
                                                            {{ \Carbon\Carbon::parse($stock->expiry_date)->format('M d, Y') }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-500 dark:text-gray-400">N/A</span>
                                                    @endif
                                                </td>
                                                <!-- Actions -->
                                                <td class="px-6 py-4 text-center">
                                                    <div class="flex items-center justify-center space-x-2">
                                                        <!-- Edit Button -->
                                                        <!-- Edit Button -->
                                                        <!-- Edit Button -->
                                                        <button type="button"
                                                            data-modal-target="editStockModal"
                                                            data-modal-toggle="editStockModal"
                                                            class="edit-stock-btn p-2 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-yellow-300 dark:bg-yellow-900 dark:text-yellow-300 dark:hover:bg-yellow-800 transition-all duration-200"
                                                            data-stock-id="{{ $stock->stock_id }}"
                                                            data-supply-id="{{ $stock->supply_id }}"
                                                            data-supply-name="{{ $stock->supply->item_name }}"
                                                            data-quantity="{{ $stock->quantity_on_hand }}"
                                                            data-unit-cost="{{ number_format($stock->unit_cost, 2) }}"
                                                            data-status="{{ $stock->status }}"
                                                            data-expiry-date="{{ $stock->expiry_date ? $stock->expiry_date->format('Y-m-d') : '' }}"
                                                            data-fund-cluster="{{ $stock->fund_cluster }}"
                                                            data-days-to-consume="{{ $stock->days_to_consume }}"
                                                            data-remarks="{{ $stock->remarks }}">
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
                                                        <!-- Delete Button -->
                                                        <button type="button"
                                                            data-modal-target="deleteStockModal{{ $stock->stock_id }}"
                                                            data-modal-toggle="deleteStockModal{{ $stock->stock_id }}"
                                                            class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-300 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800 transition-all duration-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M3 6h18"/>
                                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                                            <line x1="10" x2="10" y1="11" y2="17"/>
                                                            <line x1="14" x2="14" y1="11" y2="17"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="px-6 py-8 text-center">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M18 6h-4V2a1 1 0 00-1-1H7a1 1 0 00-1 1v4H2a1 1 0 00-1 1v11a1 1 0 001 1h16a1 1 0 001-1V7a1 1 0 00-1-1z">
                                                            </path>
                                                        </svg>
                                                        <p
                                                            class="text-lg font-medium text-gray-500 dark:text-gray-400">
                                                            No Stock found</p>
                                                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">
                                                            Get started by adding a new stock item</p>
                                                        <button type="button" data-modal-target="createStockModal"
                                                            data-modal-toggle="createStockModal"
                                                            class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg transition-colors shadow-sm focus:ring-4 focus:ring-blue-300">
                                                            <svg class="w-4 h-4 mr-2" fill="currentColor"
                                                                viewBox="0 0 20 20"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                            Add Stock
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <nav class="flex items-center justify-between pt-4 mb-3" aria-label="Table navigation">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            @if ($stocks->count() > 0)
                                Showing {{ $stocks->firstItem() }} to {{ $stocks->lastItem() }} of
                                {{ $stocks->total() }} stocks
                            @endif
                        </div>
                        <div class="mt-2 sm:mt-0">
                            {{ $stocks->links() }}
                        </div>
                    </nav>

                    <!-- Create Stock Modal -->
                    <div id="createStockModal" tabindex="-1" aria-hidden="true"
                        class="hidden fixed top-0 right-0 left-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-full max-h-full flex justify-center items-center bg-gray-900 bg-opacity-50">

                        <div class="relative w-full max-w-2xl max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-800 overflow-hidden">
                                <!-- Modal header -->
                                <div
                                    class="flex items-center justify-between p-5 border-b dark:border-gray-700 bg-gradient-to-r from-blue-600 to-blue-800">
                                    <h3 class="text-2xl font-bold text-white flex items-center">
                                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Add New Stock
                                    </h3>
                                    <button type="button"
                                        class="text-white bg-blue-700 hover:bg-blue-800 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                        dark:hover:bg-gray-600 transition-all duration-200"
                                        data-modal-hide="createStockModal">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>

                                <!-- Modal body -> Form -->
                                <form action="{{ route('stocks.store') }}" method="POST"
                                    class="p-6 bg-gray-50 dark:bg-gray-800">
                                    @csrf

                                    <!-- Validation Errors Alert -->
                                    @if ($errors->any() && session('show_create_modal'))
                                        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                                            role="alert">
                                            <div class="font-medium">Oops! There were some problems with your input:
                                            </div>
                                            <ul class="mt-1.5 ml-4 list-disc list-inside">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                                        Add a new stock entry to the inventory. Fields marked with
                                        <span class="text-red-500">*</span> are required.
                                    </p>

                                    <div class="grid gap-4 mb-6">
                                        <!-- Supply Selection -->
                                        <div class="col-span-2">
                                            <label for="supply_id"
                                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Supply Item <span class="text-red-500">*</span>
                                            </label>
                                            <select name="supply_id" id="supply_id" required
                                                class="bg-gray-50 border @error('supply_id') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg
                                                    focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                                                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                    dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option value="">Select a supply</option>
                                                @foreach ($supplies as $supply)
                                                    <option value="{{ $supply->supply_id }}"
                                                        {{ old('supply_id') == $supply->supply_id ? 'selected' : '' }}>
                                                        {{ $supply->item_name }} ({{ $supply->stock_no }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('supply_id')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <!-- Quantity and Unit Cost row -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Quantity -->
                                            <div>
                                                <label for="quantity_on_hand"
                                                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Quantity <span class="text-red-500">*</span>
                                                </label>
                                                <input type="number" name="quantity_on_hand" id="quantity_on_hand"
                                                    value="{{ old('quantity_on_hand', 0) }}" min="0" required
                                                    class="bg-gray-50 border @error('quantity_on_hand') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg
                                                        focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                @error('quantity_on_hand')
                                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                        {{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Unit Cost -->
                                            <div>
                                                <label for="unit_cost"
                                                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Unit Cost <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="unit_cost" id="unit_cost"
                                                    value="{{ old('unit_cost', '0.00') }}" required
                                                    class="bg-gray-50 border @error('unit_cost') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg
                                                        focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                @error('unit_cost')
                                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                        {{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Status and Expiry Date row -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Status -->
                                            <div>
                                                <label for="status"
                                                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Status <span class="text-red-500">*</span>
                                                </label>
                                                <select name="status" id="status" required
                                                    class="bg-gray-50 border @error('status') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg
                                                        focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                    <option value="available"
                                                        {{ old('status') == 'available' ? 'selected' : '' }}>Available
                                                    </option>
                                                    <option value="reserved"
                                                        {{ old('status') == 'reserved' ? 'selected' : '' }}>Reserved
                                                    </option>
                                                    <option value="expired"
                                                        {{ old('status') == 'expired' ? 'selected' : '' }}>Expired
                                                    </option>
                                                    <option value="depleted"
                                                        {{ old('status') == 'depleted' ? 'selected' : '' }}>Depleted
                                                    </option>
                                                </select>
                                                @error('status')
                                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                        {{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Expiry Date -->
                                            <div>
                                                <label for="expiry_date"
                                                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Expiry Date
                                                </label>
                                                <input type="date" name="expiry_date" id="expiry_date"
                                                    value="{{ old('expiry_date') }}"
                                                    class="bg-gray-50 border @error('expiry_date') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg
                                                        focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                @error('expiry_date')
                                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                        {{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Fund Cluster and Days to Consume row -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Fund Cluster -->
                                            <div>
                                                <label for="fund_cluster"
                                                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Fund Cluster
                                                </label>
                                                <input type="text" name="fund_cluster" id="fund_cluster"
                                                    value="{{ old('fund_cluster') }}"
                                                    class="bg-gray-50 border @error('fund_cluster') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg
                                                        focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                @error('fund_cluster')
                                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                        {{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Days to Consume -->
                                            <div>
                                                <label for="days_to_consume"
                                                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Days to Consume
                                                </label>
                                                <input type="number" name="days_to_consume" id="days_to_consume"
                                                    value="{{ old('days_to_consume') }}" min="0"
                                                    class="bg-gray-50 border @error('days_to_consume') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg
                                                        focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                @error('days_to_consume')
                                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                        {{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Remarks -->
                                        <div class="col-span-2">
                                            <label for="remarks"
                                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Remarks
                                            </label>
                                            <textarea name="remarks" id="remarks" rows="3"
                                                class="bg-gray-50 border @error('remarks') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg
                                                    focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                                                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                    dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ old('remarks') }}</textarea>
                                            @error('remarks')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Modal Footer -->
                                    <div
                                        class="flex items-center justify-end pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                                        <button type="button" data-modal-hide="createStockModal"
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
                                            Save Stock
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Stock Modal -->
                    <div id="editStockModal" tabindex="-1" aria-hidden="true"
                        class="hidden fixed top-0 right-0 left-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-full max-h-full flex justify-center items-center bg-gray-900 bg-opacity-50">

                        <div class="relative w-full max-w-2xl max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-800 overflow-hidden">
                                <!-- Modal header -->
                                <div
                                    class="flex items-center justify-between p-5 border-b dark:border-gray-700 bg-gradient-to-r from-yellow-500 to-yellow-700">
                                    <h3 class="text-2xl font-bold text-white flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit Stock
                                    </h3>
                                    <button type="button"
                                        class="text-white bg-yellow-600 hover:bg-yellow-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                        dark:hover:bg-gray-600 transition-all duration-200"
                                        data-modal-hide="editStockModal">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>

                                <!-- Modal body -> Form -->
                                <form id="editStockForm" method="POST" class="p-6 bg-gray-50 dark:bg-gray-800">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" id="edit_stock_id" name="stock_id">

                                    <!-- Validation Errors Alert -->
                                    @if ($errors->any() && session('show_edit_modal'))
                                        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                                            role="alert">
                                            <div class="font-medium">Oops! There were some problems with your input:
                                            </div>
                                            <ul class="mt-1.5 ml-4 list-disc list-inside">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                                        Update the stock information below. Fields marked with
                                        <span class="text-red-500">*</span> are required.
                                    </p>

                                    <div class="grid gap-4 mb-6">
                                        <!-- Supply Selection (disabled in edit mode) -->
                                        <div class="col-span-2">
                                            <label for="edit_supply_id"
                                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Supply Item
                                            </label>
                                            <input type="text" id="edit_supply_name" disabled
                                                class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                    block w-full p-2.5 cursor-not-allowed
                                                    dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400">
                                            <input type="hidden" name="supply_id" id="edit_supply_id">
                                        </div>

                                        <!-- Quantity and Unit Cost row -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Quantity -->
                                            <div>
                                                <label for="edit_quantity_on_hand"
                                                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Quantity <span class="text-red-500">*</span>
                                                </label>
                                                <input type="number" name="quantity_on_hand"
                                                    id="edit_quantity_on_hand" min="0" required
                                                    class="bg-gray-50 border @error('quantity_on_hand') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg
                                                            focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                                                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                            dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                @error('quantity_on_hand')
                                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                        {{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Unit Cost -->
                                            <div>
                                                <label for="edit_unit_cost"
                                                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Unit Cost <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="unit_cost" id="edit_unit_cost" required
                                                    class="bg-gray-50 border @error('unit_cost') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg
                                                        focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                @error('unit_cost')
                                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                        {{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Status and Expiry Date row -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Status -->
                                            <div>
                                                <label for="edit_status"
                                                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Status <span class="text-red-500">*</span>
                                                </label>
                                                <select name="status" id="edit_status" required
                                                    class="bg-gray-50 border @error('status') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg
                                                        focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                    <option value="available">Available</option>
                                                    <option value="reserved">Reserved</option>
                                                    <option value="expired">Expired</option>
                                                    <option value="depleted">Depleted</option>
                                                </select>
                                                @error('status')
                                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                        {{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Expiry Date -->
                                            <div>
                                                <label for="edit_expiry_date"
                                                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Expiry Date
                                                </label>
                                                <input type="date" name="expiry_date" id="edit_expiry_date"
                                                    class="bg-gray-50 border @error('expiry_date') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg
                                                        focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                @error('expiry_date')
                                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                        {{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Fund Cluster and Days to Consume row -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Fund Cluster -->
                                            <div>
                                                <label for="edit_fund_cluster"
                                                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Fund Cluster
                                                </label>
                                                <input type="text" name="fund_cluster" id="edit_fund_cluster"
                                                    class="bg-gray-50 border @error('fund_cluster') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg
                                                            focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                                                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                            dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                @error('fund_cluster')
                                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                        {{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Days to Consume -->
                                            <div>
                                                <label for="edit_days_to_consume"
                                                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Days to Consume
                                                </label>
                                                <input type="number" name="days_to_consume"
                                                    id="edit_days_to_consume" min="0"
                                                    class="bg-gray-50 border @error('days_to_consume') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg
                                                        focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                @error('days_to_consume')
                                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">
                                                        {{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Remarks -->
                                        <div class="col-span-2">
                                            <label for="edit_remarks"
                                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Remarks
                                            </label>
                                            <textarea name="remarks" id="edit_remarks" rows="3"
                                                class="bg-gray-50 border @error('remarks') border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg
                                                    focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                                                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                    dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                            @error('remarks')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Modal Footer -->
                                    <div
                                        class="flex items-center justify-end pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                                        <button type="button" data-modal-hide="editStockModal"
                                            class="py-2.5 px-5 mr-3 text-sm font-medium text-gray-900 focus:outline-none
                                                bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700
                                                focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700
                                                dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600
                                                dark:hover:text-white dark:hover:bg-gray-700 transition-all duration-200">
                                            Cancel
                                        </button>
                                        <button type="submit"
                                            class="text-white bg-gradient-to-r from-yellow-500 to-yellow-700 hover:from-yellow-600 hover:to-yellow-800
                                                focus:ring-4 focus:outline-none focus:ring-yellow-300
                                                font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center
                                                dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800 transition-all duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                            </svg>
                                            Update Stock
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Stock Modals -->
                    @foreach($stocks as $stock)
                        <div id="deleteStockModal{{ $stock->stock_id }}" tabindex="-1" aria-hidden="true"
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
                                            Delete Stock
                                        </h3>
                                        <button type="button"
                                            class="text-white bg-transparent hover:bg-red-800 hover:text-white rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                            data-modal-hide="deleteStockModal{{ $stock->stock_id }}">
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
                                                <p>Are you sure you want to delete this stock item:</p>
                                                <p class="font-semibold text-gray-800 dark:text-white mt-1">
                                                    "{{ $stock->supply->item_name }}" ({{ $stock->quantity_on_hand }} {{ $stock->supply->unit_of_measurement }})</p>
                                            </div>
                                            <p class="mt-3 text-sm text-red-500">This action cannot be undone.</p>
                                        </div>

                                        <form action="{{ route('stocks.destroy', $stock->stock_id) }}"
                                            method="POST" class="mt-6">
                                            @csrf
                                            @method('DELETE')
                                            <div class="flex items-center justify-center space-x-4">
                                                <button data-modal-hide="deleteStockModal{{ $stock->stock_id }}"
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





                </div><!-- End .section-container -->
            </div>
        </div>
    </div>

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
            input.form.submit();
        }

        document.addEventListener('DOMContentLoaded', () => {
            toggleClearButton();

            // Format unit cost input
            const unitCostInput = document.getElementById('unit_cost');
            if (unitCostInput) {
                unitCostInput.addEventListener('input', function() {
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

            // Modal handling
            const modalToggles = document.querySelectorAll('[data-modal-toggle]');
            modalToggles.forEach(button => {
                button.addEventListener('click', function() {
                    const modalId = this.getAttribute('data-modal-toggle');
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.classList.remove('hidden');
                    }
                });
            });

            const modalHides = document.querySelectorAll('[data-modal-hide]');
            modalHides.forEach(button => {
                button.addEventListener('click', function() {
                    const modalId = this.getAttribute('data-modal-hide');
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.classList.add('hidden');
                    }
                });
            });

            // Check if we need to show create modal due to validation errors
            @if ($errors->any() && session('show_create_modal'))
                document.getElementById('createStockModal').classList.remove('hidden');
            @endif
        });
    </script>

    <!-- JavaScript for Supply Stock Management -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Format acquisition cost input in the create modal
            const unitCostInput = document.getElementById('unit_cost');
            if (unitCostInput) {
                unitCostInput.addEventListener('input', function() {
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

            // Handle edit button clicks
            const editButtons = document.querySelectorAll('.edit-stock-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Get data attributes
                    const stockId = this.getAttribute('data-stock-id');
                    const supplyId = this.getAttribute('data-supply-id');
                    const supplyName = this.getAttribute('data-supply-name');
                    const quantity = this.getAttribute('data-quantity');
                    const unitCost = this.getAttribute('data-unit-cost');
                    const status = this.getAttribute('data-status');
                    const expiryDate = this.getAttribute('data-expiry-date');
                    const fundCluster = this.getAttribute('data-fund-cluster');
                    const daysToConsume = this.getAttribute('data-days-to-consume');
                    const remarks = this.getAttribute('data-remarks');

                    // Set form values
                    document.getElementById('edit_stock_id').value = stockId;
                    document.getElementById('edit_supply_id').value = supplyId;
                    document.getElementById('edit_supply_name').value = supplyName;
                    document.getElementById('edit_quantity_on_hand').value = quantity;
                    document.getElementById('edit_unit_cost').value = unitCost;
                    document.getElementById('edit_status').value = status;
                    document.getElementById('edit_expiry_date').value = expiryDate;
                    document.getElementById('edit_fund_cluster').value = fundCluster;
                    document.getElementById('edit_days_to_consume').value = daysToConsume;
                    document.getElementById('edit_remarks').value = remarks;

                    // Set form action URL
                    const form = document.getElementById('editStockForm');
                    form.action = `/stocks/${stockId}`;

                    // Open modal
                    const modal = document.getElementById('editStockModal');
                    modal.classList.remove('hidden');
                });
            });

            // Format edit unit cost input
            const editUnitCostInput = document.getElementById('edit_unit_cost');
            if (editUnitCostInput) {
                editUnitCostInput.addEventListener('input', function() {
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

            // Search input functionality
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
                    input.form.submit();
                }
            }

            toggleClearButton();

            // Modal handling
            const modalToggles = document.querySelectorAll('[data-modal-toggle]');
            modalToggles.forEach(button => {
                button.addEventListener('click', function() {
                    const modalId = this.getAttribute('data-modal-toggle');
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.classList.remove('hidden');
                    }
                });
            });

            const modalHides = document.querySelectorAll('[data-modal-hide]');
            modalHides.forEach(button => {
                button.addEventListener('click', function() {
                    const modalId = this.getAttribute('data-modal-hide');
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.classList.add('hidden');
                    }
                });
            });

            // Check if we need to show modals due to validation errors
            @if ($errors->any() && session('show_create_modal'))
                document.getElementById('createStockModal').classList.remove('hidden');
            @endif

            @if ($errors->any() && session('show_edit_modal'))
                const editId = {{ session('show_edit_modal') }};
                const editButtons = document.querySelectorAll('.edit-stock-btn');
                editButtons.forEach(button => {
                    if (button.getAttribute('data-stock-id') == editId) {
                        button.click();
                    }
                });
            @endif

            // Make these functions globally available
            window.toggleClearButton = toggleClearButton;
            window.clearSearch = clearSearch;
        });
    </script>


</x-app-layout>
