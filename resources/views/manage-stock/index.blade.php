<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Supply Stocks') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="section-container p-6">
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

                        <!-- Replace the header section with navigation buttons -->
                        <div class="flex space-x-2">
                            <!-- Stock Cards button - using green for secondary action -->
                            <a href="{{ route('stock-cards.index') }}"
                            class="py-2 px-3 text-white bg-[#10b981] hover:bg-[#059669] rounded-lg text-sm font-medium focus:ring-4 focus:outline-none focus:ring-[#10b981]/30 dark:focus:ring-[#10b981]/30 transition-all duration-200 inline-flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="hidden sm:inline-block">Stock Cards</span>
                            </a>

                            <!-- Supply Ledger Cards button - using gray for neutral action -->
                            <a href="{{ route('supply-ledger-cards.index') }}"
                            class="py-2 px-3 text-white bg-gray-600 hover:bg-gray-700 rounded-lg text-sm font-medium focus:ring-4 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 dark:bg-gray-600 dark:hover:bg-gray-700 transition-all duration-200 inline-flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="hidden sm:inline-block">Ledger Cards</span>
                            </a>

                            {{-- only admins should see "Add Stock" --}}
                            @if(auth()->user()->hasRole('admin'))
                                <!-- Add Stock button - primary red action -->
                                <button data-modal-target="createStockModal" data-modal-toggle="createStockModal" type="button"
                                    class="py-2 px-3 text-white bg-[#ce201f] hover:bg-[#a01b1a] hover:shadow-lg rounded-lg text-sm font-medium focus:ring-4 focus:outline-none focus:ring-[#ce201f]/30 dark:focus:ring-[#ce201f]/30 transition-all duration-200 transform hover:scale-105 inline-flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span class="hidden sm:inline-block">Add Stock</span>
                                </button>
                            @endif
                        </div>
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
                        <h3 class="text-lg font-semibold mb-1 text-gray-900 dark:text-white">CHED Supply Stocks</h3>
                        <p>
                            This section provides a comprehensive overview of CHED supply stocks,
                            detailing current stock levels, unit costs, and inventory valuation
                            to support efficient inventory management and financial reporting.
                        </p>
                    </div>

                    <!-- Supply Stock Table - Removed vertical scroll, keeping horizontal scroll for mobile -->
                    <div class="overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left border-collapse">
                                <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">STOCK NO</th>
                                        <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">Supply Item</th>
                                        <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">Supplier</th>
                                        <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">Department</th>
                                        <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">Quantity</th>
                                        <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">Status</th>
                                        <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">Expiry Date</th>
                                        @if(auth()->user()->hasRole('admin'))
                                            <th scope="col" class="px-6 py-3 text-center font-bold text-gray-800 dark:text-gray-200">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stocks as $stock)
                                        <tr class="{{ $stock->status_background }} border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                            <!-- ID -->
                                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                                {{-- {{ $stock->stock_id }} --}}
                                                {{ $stock->supply->stock_no }}
                                            </td>
                                            <!-- Supply Item -->
                                            <td class="px-6 py-4 dark:text-white">
                                                <div class="font-medium">{{ $stock->supply->item_name }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{-- {{ $stock->supply->stock_no }} --}}
                                                </div>
                                                @if($stock->supply->description)
                                                    <div class="text-xs text-blue-600 dark:text-blue-400 italic mt-1">
                                                        {{ $stock->supply->description }}
                                                    </div>
                                                @endif
                                            </td>
                                            <!-- Supplier -->
                                            <td class="px-6 py-4 dark:text-white">
                                                @if($stock->supplier)
                                                    <div class="text-sm">{{ $stock->supplier->name }}</div>
                                                @else
                                                    <span class="text-gray-400 dark:text-gray-500 text-sm">-</span>
                                                @endif
                                            </td>
                                            <!-- Department -->
                                            <td class="px-6 py-4 dark:text-white">
                                                @if($stock->department)
                                                    <div class="text-sm">{{ $stock->department->name }}</div>
                                                @else
                                                    <span class="text-gray-400 dark:text-gray-500 text-sm">-</span>
                                                @endif
                                            </td>
                                            <!-- Quantity -->
                                            <td class="px-6 py-4 dark:text-white">
                                                {{ number_format($stock->quantity_on_hand) }} {{ $stock->supply->unit_of_measurement }}
                                            </td>
                                            <!-- Status -->
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $stock->status_badge_color }}">
                                                    {{ $stock->status_display }}
                                                </span>
                                                @if($stock->dynamic_status === 'low_stock')
                                                    <div class="text-xs text-[#f59e0b] dark:text-[#fbbf24] mt-1 flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Need to reorder
                                                    </div>
                                                @elseif($stock->dynamic_status === 'depleted')
                                                    <div class="text-xs text-[#ce201f] dark:text-[#ce201f] mt-1 flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Out of stock
                                                    </div>
                                                @endif
                                            </td>
                                            <!-- Expiry Date -->
                                            <td class="px-6 py-4 dark:text-white">
                                                @if ($stock->expiry_date)
                                                    <span class="@if (\Carbon\Carbon::parse($stock->expiry_date)->isPast()) text-[#ce201f] dark:text-[#ce201f] @endif">
                                                        {{ \Carbon\Carbon::parse($stock->expiry_date)->format('M d, Y') }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-500 dark:text-gray-400">N/A</span>
                                                @endif
                                            </td>
                                            <!-- Actions -->
                                            @if(auth()->user()->hasRole('admin'))
                                                <td class="px-6 py-4 text-center">
                                                    <div class="flex items-center justify-center space-x-2">
                                                        <!-- + Add Stock -->
                                                        <button type="button" data-modal-target="createStockModal"
                                                            data-modal-toggle="createStockModal"
                                                            class="add-stock-btn p-2 text-[#ce201f] hover:bg-[#ce201f]/10 rounded-lg
                                                            focus:outline-none focus:ring-2 focus:ring-[#ce201f]/30 dark:text-[#ce201f]
                                                            dark:hover:bg-[#ce201f]/20 transition-all duration-200"
                                                            data-supply-id="{{ $stock->supply_id }}"
                                                            data-unit-cost="{{ number_format($stock->unit_cost, 2) }}"
                                                            data-fund-cluster="{{ $stock->fund_cluster }}"
                                                            title="Add stock to {{ $stock->supply->item_name }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                                            </svg>
                                                        </button>
                                                        <!-- Edit Stock -->
                                                        <button type="button" data-modal-target="editStockModal" data-modal-toggle="editStockModal"
                                                            class="edit-stock-btn p-2 text-[#f59e0b] hover:bg-[#f59e0b]/10 rounded-lg
                                                            focus:outline-none focus:ring-2 focus:ring-[#f59e0b]/30 dark:text-[#fbbf24]
                                                            dark:hover:bg-[#f59e0b]/20 transition-all duration-200"
                                                            data-stock-id="{{ $stock->stock_id }}"
                                                            title="Re‑value stock">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                                <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z" />
                                                            </svg>
                                                        </button>
                                                        <!-- Stock Card Button -->
                                                        <a href="{{ route('stock-cards.show', $stock->supply_id) }}"
                                                            class="p-2 text-[#10b981] hover:bg-[#10b981]/10 rounded-lg
                                                            focus:outline-none focus:ring-2 focus:ring-[#10b981]/30 dark:text-[#34d399]
                                                            dark:hover:bg-[#10b981]/20 transition-all duration-200"
                                                            title="View Stock Card">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                                <path d="M14 2v6h6"></path>
                                                                <path d="M16 13H8"></path>
                                                                <path d="M16 17H8"></path>
                                                                <path d="M10 9H8"></path>
                                                            </svg>
                                                        </a>
                                                        <!-- Supply Ledger Card Button -->
                                                        <a href="{{ route('supply-ledger-cards.show', $stock->supply_id) }}"
                                                            class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg
                                                            focus:outline-none focus:ring-2 focus:ring-gray-300 dark:text-gray-400
                                                            dark:hover:bg-gray-700 transition-all duration-200"
                                                            title="View Supply Ledger Card">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                                <path d="M14 2v6h6"></path>
                                                                <path d="M16 13H8"></path>
                                                                <path d="M16 17H8"></path>
                                                                <path d="M10 9H8"></path>
                                                            </svg>
                                                        </a>
                                                        <!-- Delete Stock -->
                                                        <button type="button"
                                                            class="delete-stock-btn p-2 text-[#ce201f] hover:bg-[#ce201f]/10 rounded-lg
                                                            focus:outline-none focus:ring-2 focus:ring-[#ce201f]/30 dark:text-[#ce201f]
                                                            dark:hover:bg-[#ce201f]/20 transition-all duration-200"
                                                            title="Delete stock">
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
                                            <td colspan="8" class="px-6 py-8 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                    </svg>
                                                    <p class="text-lg font-medium text-gray-400 dark:text-gray-500">
                                                        No stock entries found</p>
                                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Get
                                                        started by adding new stock items</p>
                                                    @if(auth()->user()->hasRole('admin'))
                                                        <button type="button" data-modal-target="createStockModal"
                                                            data-modal-toggle="createStockModal"
                                                            class="mt-4 inline-flex items-center px-4 py-2 bg-[#ce201f] hover:bg-[#a01b1a] text-white
                                                            font-medium text-sm rounded-lg transition-colors shadow-sm focus:ring-4 focus:ring-[#ce201f]/30
                                                            dark:bg-[#ce201f] dark:hover:bg-[#a01b1a]">
                                                            <svg class="w-4 h-4 mr-2" fill="currentColor"
                                                                viewBox="0 0 20 20"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                            Add Stock
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
                        {{ $stocks->links() }}
                    </div>

                    @if(auth()->user()->hasRole('admin'))
                        <!-- Create Stock Modal (Multiple Items) - Modern Minimalist Design -->
                        <div id="createStockModal" tabindex="-1" aria-hidden="true"
                            class="hidden fixed inset-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-full max-h-full flex justify-center items-center bg-gray-900/60 backdrop-blur-sm">

                            <div class="relative w-full max-w-7xl max-h-[90vh] animate-modal-slide-up">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-2xl shadow-2xl dark:bg-gray-800 overflow-hidden">
                                    <!-- Modal header - Minimalist -->
                                    <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-700">
                                        <div>
                                            <h3 class="text-2xl font-semibold text-gray-900 dark:text-white">
                                                Add New Stock
                                            </h3>
                                            {{-- <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                Create new stock entries with a single IAR reference
                                            </p> --}}
                                        </div>
                                        <button type="button"
                                            class="text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg p-2
                                            hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                                            data-modal-hide="createStockModal">
                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>

                                    <!-- Modal body -->
                                    <form action="{{ route('stocks.store') }}" method="POST" class="p-6">
                                        @csrf
                                        <input type="hidden" name="submission_token" value="{{ uniqid() . time() }}">

                                        <!-- Enhanced Validation Errors Alert with Better UX -->
                                        @if ($errors->any())
                                            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-900 rounded-lg p-4">
                                                <div class="flex">
                                                    <div class="flex-shrink-0">
                                                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <div class="ml-3 flex-1">
                                                        <h3 class="text-sm font-medium text-red-800 dark:text-red-300">
                                                            Please fix the following errors before submitting:
                                                        </h3>
                                                        <div class="mt-2 text-xs text-red-700 dark:text-red-400">
                                                            <ul class="list-disc list-inside space-y-1">
                                                                @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>

                                                        <!-- Error Summary for Multiple Items -->
                                                        @if($errors->has('items.*'))
                                                        <div class="mt-3 p-3 bg-red-100 dark:bg-red-800/30 rounded-md">
                                                            <h4 class="text-xs font-medium text-red-800 dark:text-red-300 mb-2">Item-specific errors:</h4>
                                                            @foreach($errors->get('items.*') as $field => $messages)
                                                                @php
                                                                    // Extract item index from field name (e.g., "items.0.supply_id" -> "Item 1")
                                                                    preg_match('/items\.(\d+)\.(.+)/', $field, $matches);
                                                                    $itemIndex = isset($matches[1]) ? $matches[1] + 1 : 'Unknown';
                                                                    $fieldName = isset($matches[2]) ? ucwords(str_replace('_', ' ', $matches[2])) : 'Field';
                                                                @endphp
                                                                @foreach($messages as $message)
                                                                <div class="text-xs text-red-700 dark:text-red-400">
                                                                    <strong>Row {{ $itemIndex }}</strong> - {{ $fieldName }}: {{ $message }}
                                                                </div>
                                                                @endforeach
                                                            @endforeach
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="ml-4">
                                                        <button type="button" onclick="this.parentElement.parentElement.parentElement.style.display='none'"
                                                            class="text-red-400 hover:text-red-600 focus:outline-none">
                                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- IAR Information Card -->
                                        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-6 mb-6">
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                                                <div>
                                                    <label for="receipt_date" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                                        RECEIPT DATE
                                                    </label>
                                                    <div class="flex items-center space-x-3">
                                                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                                        <!-- calendar icon -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-days-icon lucide-calendar-days text-gray-700"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>
                                                        </div>

                                                        <input
                                                        type="date"
                                                        name="receipt_date"
                                                        id="receipt_date"
                                                        value="{{ old('receipt_date', now()->format('Y-m-d')) }}"
                                                        max="{{ now()->format('Y-m-d') }}"
                                                        class="flex-1 px-4 py-2 bg-white dark:bg-gray-800 border
                                                                @error('receipt_date') border-red-500 @else border-gray-200 @enderror
                                                                rounded-lg text-sm text-gray-900 dark:text-white
                                                                focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                                                        >
                                                    </div>
                                                    @error('receipt_date')
                                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label for="reference_no" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                                        IAR REFERENCE
                                                    </label>
                                                    <div class="flex items-center space-x-3">
                                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ticket-check-icon lucide-ticket-check text-gray-700"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="m9 12 2 2 4-4"/></svg>
                                                        </div>
                                                        <input
                                                        type="text"
                                                        name="reference_no"
                                                        id="reference_no"
                                                        value="{{ old('reference_no', $defaultIar) }}"
                                                        placeholder="IAR YYYY-MM-XXX"
                                                        class="flex-1 px-4 py-2 bg-white dark:bg-gray-800 border
                                                                @error('reference_no') border-red-500 @else border-gray-200 @enderror
                                                                rounded-lg text-sm text-gray-900 dark:text-white
                                                                focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                                                        >
                                                    </div>
                                                    @error('reference_no')
                                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                                    @enderror
                                                </div>


                                                <div>
                                                    <label for="general_supplier_id" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
                                                        SUPPLIER (OPTIONAL)
                                                    </label>
                                                    <select name="general_supplier_id" id="general_supplier_id"
                                                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                                        rounded-lg text-sm text-gray-900 dark:text-white
                                                        focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                                                        <option value="">No Supplier</option>
                                                        @foreach($suppliers as $supplier)
                                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div>
                                                    <label for="general_department_id" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
                                                        DEPARTMENT (OPTIONAL)
                                                    </label>
                                                    <select name="general_department_id" id="general_department_id"
                                                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                                        rounded-lg text-sm text-gray-900 dark:text-white
                                                        focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                                                        <option value="">No Department</option>
                                                        @foreach($departments as $department)
                                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mt-4">
                                                <label for="general_remarks" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
                                                    GENERAL REMARKS
                                                </label>
                                                <input type="text" name="general_remarks" id="general_remarks"
                                                    placeholder="e.g., PO #12345, DR #67890"
                                                    class="w-full px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                                    rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400
                                                    focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                                            </div>
                                        </div>

                                        <!-- Supply Items Section -->
                                        <div class="mb-6">
                                            <div class="flex items-center justify-between mb-4">
                                                <div>
                                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                        Supply Items
                                                    </h4>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        Add all items received in this delivery
                                                    </p>
                                                </div>
                                                <button type="button" id="addItemBtn"
                                                    class="inline-flex items-center px-4 py-2.5 bg-gray-900 hover:bg-gray-800 dark:bg-white dark:hover:bg-gray-100
                                                    text-white dark:text-gray-900 text-sm font-medium rounded-lg transition-all duration-200
                                                    shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                                                    </svg>
                                                    Add Item
                                                </button>
                                            </div>

                                            <!-- Supply Items Table - Modern Design -->
                                            <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                                                <div class="overflow-x-auto">
                                                    <table class="w-full">
                                                        <thead>
                                                            <tr class="bg-gray-50 dark:bg-gray-900/50">
                                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                                    Supply Item
                                                                </th>
                                                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider" style="width: 120px;">
                                                                    Quantity
                                                                </th>
                                                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider" style="width: 150px;">
                                                                    Unit Cost
                                                                </th>
                                                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider" style="width: 150px;">
                                                                    Total Cost
                                                                </th>
                                                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider" style="width: 120px;">
                                                                    Fund
                                                                </th>
                                                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider" style="width: 120px;">
                                                                    Status
                                                                </th>
                                                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider" style="width: 130px;">
                                                                    Expiry
                                                                </th>
                                                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider" style="width: 60px;">

                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="supplyItemsTable" class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                                                            <!-- Dynamic rows will be added here -->
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <!-- Empty State -->
                                                <div id="emptyState" class="p-12 text-center bg-white dark:bg-gray-800">
                                                    <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                                    </svg>
                                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No items added yet</p>
                                                    <p class="text-xs text-gray-400 dark:text-gray-500">Click "Add Item" to start</p>
                                                </div>

                                                <!-- Grand Total Footer -->
                                                <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-4 border-t border-gray-200 dark:border-gray-700 hidden" id="grandTotalRow">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center">
                                                            <span class="text-sm font-medium text-gray-900 dark:text-white">Grand Total</span>
                                                            <span class="ml-2 text-xs text-gray-500 dark:text-gray-400" id="totalItemsCount">(0 items)</span>
                                                        </div>
                                                        <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                                            ₱<span id="grandTotal">0.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Info Alert -->
                                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-900 rounded-lg p-4 mb-6">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">
                                                        Important Notes
                                                    </h3>
                                                    <div class="mt-2 text-xs text-blue-700 dark:text-blue-400 space-y-1">
                                                        <p>• All items will share the same IAR reference number</p>
                                                        <p>• Enter costs in peso format (e.g., 100.00)</p>
                                                        <p>• Set expiry dates for perishable items</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Footer -->
                                        <div class="flex items-center justify-end space-x-3">
                                            <button type="button" data-modal-hide="createStockModal"
                                                class="px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300
                                                bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600
                                                rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700
                                                focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600
                                                transition-all duration-200">
                                                Cancel
                                            </button>
                                            <button type="submit" id="submitStockBtn"
                                                class="px-6 py-2.5 text-sm font-medium text-white
                                                bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600
                                                rounded-lg shadow-sm hover:shadow-md
                                                focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                                                transition-all duration-200 transform hover:-translate-y-0.5
                                                disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Save Stock Receipt
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Add these styles -->

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

                            @keyframes spin {
                                from { transform: rotate(0deg); }
                                to { transform: rotate(360deg); }
                            }

                            .animate-spin {
                                animation: spin 1s linear infinite;
                            }

                            /* Custom scrollbar */
                            .modal-scrollbar::-webkit-scrollbar {
                                width: 6px;
                            }

                            .modal-scrollbar::-webkit-scrollbar-track {
                                background: #f1f1f1;
                                border-radius: 3px;
                            }

                            .modal-scrollbar::-webkit-scrollbar-thumb {
                                background: #888;
                                border-radius: 3px;
                            }

                            .modal-scrollbar::-webkit-scrollbar-thumb:hover {
                                background: #555;
                            }

                            /* FIXED: Allow dropdowns to escape the modal boundaries */
                            .supply-select-wrapper {
                                position: relative;
                                z-index: 1;
                            }

                            .supply-dropdown-menu {
                                position: fixed !important;
                                z-index: 99999 !important; /* Increased z-index */
                                max-width: 400px;
                                min-width: 300px;
                                /* Remove conflicting positioning */
                            }

                            /* FIXED: Allow table container to let dropdowns overflow */
                            .overflow-hidden {
                                overflow: visible !important;
                            }

                            /* FIXED: Specific override for the table container */
                            .overflow-x-auto {
                                overflow-x: auto;
                                overflow-y: visible !important;
                            }

                            /* Custom dropdown styles */
                            .supply-options-container::-webkit-scrollbar {
                                width: 6px;
                            }

                            .supply-options-container::-webkit-scrollbar-track {
                                background: #f1f1f1;
                                border-radius: 3px;
                            }

                            .supply-options-container::-webkit-scrollbar-thumb {
                                background: #888;
                                border-radius: 3px;
                            }

                            .supply-options-container::-webkit-scrollbar-thumb:hover {
                                background: #555;
                            }

                            .supply-option {
                                transition: all 0.2s ease;
                            }

                            .supply-option:hover {
                                transform: translateX(2px);
                            }
                        </style>

                        <script>
                            // Enhanced Multiple Item Stock Form Handler
                            document.addEventListener('DOMContentLoaded', function() {
                                let itemIndex = 0;

                                // Add item button
                                const addItemBtn = document.getElementById('addItemBtn');
                                const itemsTable = document.getElementById('supplyItemsTable');
                                const template = document.getElementById('supplyItemRowTemplate');
                                const emptyState = document.getElementById('emptyState');
                                const grandTotalRow = document.getElementById('grandTotalRow');

                                // Function to format number as currency
                                function formatCurrency(amount) {
                                    return parseFloat(amount).toLocaleString('en-US', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                }

                                // Function to parse currency input
                                function parseCurrency(value) {
                                    // Remove all non-numeric characters except decimal point
                                    return parseFloat(value.replace(/[^0-9.-]+/g, '')) || 0;
                                }

                                // Custom dropdown functions
                                window.toggleSupplyDropdown = function(trigger) {
                                    const dropdown = trigger.nextElementSibling;
                                    const allDropdowns = document.querySelectorAll('.supply-dropdown-menu');

                                    // Close all other dropdowns
                                    allDropdowns.forEach(d => {
                                        if (d !== dropdown) {
                                            d.classList.add('hidden');
                                        }
                                    });

                                    // Toggle current dropdown
                                    dropdown.classList.toggle('hidden');

                                    // Focus on search input if opening
                                    if (!dropdown.classList.contains('hidden')) {
                                        const searchInput = dropdown.querySelector('.supply-search-input');
                                        setTimeout(() => searchInput.focus(), 100);
                                    }
                                }

                                window.filterSupplyOptions = function(searchInput) {
                                    const searchTerm = searchInput.value.toLowerCase();
                                    const options = searchInput.closest('.supply-dropdown-menu').querySelectorAll('.supply-option');
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
                                }

                                window.selectSupplyOption = function(option) {
                                    const wrapper = option.closest('.supply-select-wrapper');
                                    const hiddenInput = wrapper.querySelector('.supply-id-input');
                                    const trigger = wrapper.querySelector('.supply-dropdown-trigger');
                                    const selectedText = trigger.querySelector('.selected-supply-text');
                                    const dropdown = wrapper.querySelector('.supply-dropdown-menu');

                                    // Set values
                                    hiddenInput.value = option.dataset.supplyId;
                                    selectedText.textContent = `${option.dataset.supplyName} (${option.dataset.supplyStockno})`;
                                    selectedText.classList.remove('text-gray-500');

                                    // Close dropdown
                                    dropdown.classList.add('hidden');

                                    // Clear search
                                    const searchInput = dropdown.querySelector('.supply-search-input');
                                    searchInput.value = '';
                                    filterSupplyOptions(searchInput);

                                    // Trigger validation for duplicate check
                                    validateDuplicateSupplyCustom(hiddenInput);
                                }

                                // Update the validateDuplicateSupply function
                                function validateDuplicateSupplyCustom(input) {
                                    const selectedValue = input.value;
                                    if (!selectedValue) return;

                                    const allInputs = document.querySelectorAll('.supply-id-input');
                                    let duplicateCount = 0;

                                    allInputs.forEach(function(i) {
                                        if (i.value === selectedValue) {
                                            duplicateCount++;
                                        }
                                    });

                                    if (duplicateCount > 1) {
                                        showAlert('This supply item has already been selected.');
                                        // Clear the selection
                                        input.value = '';
                                        const wrapper = input.closest('.supply-select-wrapper');
                                        const selectedText = wrapper.querySelector('.selected-supply-text');
                                        selectedText.textContent = 'Select Supply Item';
                                        selectedText.classList.add('text-gray-500');
                                    }
                                }

                                // Function to add new item row
                                function addItemRow() {
                                    const templateContent = template.content.cloneNode(true);
                                    const row = templateContent.querySelector('tr');

                                    // Replace INDEX with actual index
                                    row.innerHTML = row.innerHTML.replace(/INDEX/g, itemIndex);

                                    // Add event listeners
                                    const quantityInput = row.querySelector('.quantity-input');
                                    const unitCostInput = row.querySelector('.unit-cost-input');
                                    const removeBtn = row.querySelector('.remove-item-btn');

                                    // Initialize unit cost with 0.00
                                    unitCostInput.value = '0.00';

                                    // Format unit cost on focus/blur
                                    unitCostInput.addEventListener('focus', function() {
                                        if (this.value === '0.00') {
                                            this.value = '';
                                        }
                                    });

                                    unitCostInput.addEventListener('blur', function() {
                                        const value = parseCurrency(this.value);
                                        this.value = formatCurrency(value);
                                        calculateRowTotal(row);
                                    });

                                    unitCostInput.addEventListener('input', function() {
                                        // Allow typing but validate on blur
                                        calculateRowTotal(row);
                                    });

                                    quantityInput.addEventListener('input', function() {
                                        calculateRowTotal(row);
                                    });

                                    quantityInput.addEventListener('blur', function() {
                                        if (!this.value || this.value < 1) {
                                            this.value = '1';
                                        }
                                        calculateRowTotal(row);
                                    });

                                    removeBtn.addEventListener('click', function() {
                                        removeItemRow(row);
                                    });

                                    // Hide empty state and show grand total row
                                    emptyState.classList.add('hidden');
                                    grandTotalRow.classList.remove('hidden');

                                    itemsTable.appendChild(row);
                                    itemIndex++;

                                    // Update submit button state and item count
                                    updateSubmitButton();
                                    updateItemCount();

                                    // Focus on dropdown trigger
                                    setTimeout(() => {
                                        const dropdownTrigger = row.querySelector('.supply-dropdown-trigger');
                                        if (dropdownTrigger) {
                                            dropdownTrigger.click();
                                        }
                                    }, 100);
                                }

                                // Calculate row total
                                function calculateRowTotal(row) {
                                    const quantity = parseInt(row.querySelector('.quantity-input').value) || 0;
                                    const unitCost = parseCurrency(row.querySelector('.unit-cost-input').value);
                                    const total = quantity * unitCost;

                                    row.querySelector('.total-cost').textContent = '₱' + formatCurrency(total);

                                    calculateGrandTotal();
                                }

                                // Calculate grand total
                                function calculateGrandTotal() {
                                    let grandTotal = 0;
                                    document.querySelectorAll('.total-cost').forEach(function(el) {
                                        const value = parseCurrency(el.textContent);
                                        grandTotal += value;
                                    });

                                    document.getElementById('grandTotal').textContent = formatCurrency(grandTotal);
                                }

                                // Update item count
                                function updateItemCount() {
                                    const count = document.querySelectorAll('.supply-item-row').length;
                                    const itemsText = count === 1 ? '1 item' : `${count} items`;
                                    document.getElementById('totalItemsCount').textContent = `(${itemsText})`;

                                    // Show/hide empty state
                                    if (count === 0) {
                                        emptyState.classList.remove('hidden');
                                        grandTotalRow.classList.add('hidden');
                                    }
                                }

                                // Remove item row
                                function removeItemRow(row) {
                                    if (document.querySelectorAll('.supply-item-row').length > 1) {
                                        // Add fade out animation
                                        row.style.opacity = '0';
                                        row.style.transform = 'translateX(-10px)';

                                        setTimeout(() => {
                                            row.remove();
                                            calculateGrandTotal();
                                            updateSubmitButton();
                                            updateItemCount();
                                        }, 200);
                                    } else {
                                        // Show a nicer alert
                                        showAlert('At least one item is required.');
                                    }
                                }

                                // Show custom alert
                                function showAlert(message) {
                                    // Create alert element
                                    const alert = document.createElement('div');
                                    alert.className = 'fixed top-4 right-4 z-[60] bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center space-x-2 animate-modal-slide-up';
                                    alert.innerHTML = `
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <span>${message}</span>
                                    `;

                                    document.body.appendChild(alert);

                                    // Remove after 3 seconds
                                    setTimeout(() => {
                                        alert.style.opacity = '0';
                                        setTimeout(() => alert.remove(), 300);
                                    }, 3000);
                                }

                                // Update submit button state
                                function updateSubmitButton() {
                                    const submitBtn = document.getElementById('submitStockBtn');
                                    const rowCount = document.querySelectorAll('.supply-item-row').length;

                                    if (rowCount === 0) {
                                        submitBtn.disabled = true;
                                        submitBtn.classList.add('disabled:opacity-50', 'disabled:cursor-not-allowed', 'disabled:transform-none');
                                    } else {
                                        submitBtn.disabled = false;
                                        submitBtn.classList.remove('disabled:opacity-50', 'disabled:cursor-not-allowed', 'disabled:transform-none');
                                    }
                                }

                                // Add item button click
                                if (addItemBtn) {
                                    addItemBtn.addEventListener('click', addItemRow);
                                }

                                // Handle form submission
                                const stockForm = document.querySelector('#createStockModal form');
                                if (stockForm) {
                                    stockForm.addEventListener('submit', function(e) {
                                        // Validate at least one item
                                        const rowCount = document.querySelectorAll('.supply-item-row').length;
                                        if (rowCount === 0) {
                                            e.preventDefault();
                                            showAlert('Please add at least one supply item.');
                                            return false;
                                        }

                                        // Validate all rows have required data
                                        let isValid = true;
                                        let emptyFields = [];

                                        document.querySelectorAll('.supply-item-row').forEach(function(row, index) {
                                            const supplyId = row.querySelector('input[name*="supply_id"]').value;
                                            const quantity = row.querySelector('input[name*="quantity"]').value;
                                            const unitCost = parseCurrency(row.querySelector('input[name*="unit_cost"]').value);

                                            if (!supplyId) {
                                                isValid = false;
                                                emptyFields.push(`Row ${index + 1}: Supply item not selected`);
                                            }
                                            if (!quantity || quantity < 1) {
                                                isValid = false;
                                                emptyFields.push(`Row ${index + 1}: Invalid quantity`);
                                            }
                                            if (unitCost <= 0) {
                                                isValid = false;
                                                emptyFields.push(`Row ${index + 1}: Unit cost must be greater than 0`);
                                            }
                                        });

                                        if (!isValid) {
                                            e.preventDefault();
                                            showAlert(emptyFields[0]); // Show first error
                                            return false;
                                        }

                                        // Show loading state
                                        const submitBtn = document.getElementById('submitStockBtn');
                                        const originalHTML = submitBtn.innerHTML;
                                        submitBtn.disabled = true;
                                        submitBtn.innerHTML = `
                                            <span class="flex items-center">
                                                <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Processing IAR...
                                            </span>
                                        `;
                                    });
                                }

                                // Handle + Add Stock button for single items (keep existing functionality)
                                document.querySelectorAll('.add-stock-btn').forEach(btn => {
                                    btn.addEventListener('click', () => {
                                        // Reset the form for multiple items
                                        itemsTable.innerHTML = '';
                                        itemIndex = 0;

                                        // Add a single row with pre-filled data
                                        addItemRow();

                                        // Pre-fill the first row with the selected supply
                                        setTimeout(() => {
                                            const firstRow = itemsTable.querySelector('.supply-item-row');
                                            if (firstRow) {
                                                const hiddenInput = firstRow.querySelector('.supply-id-input');
                                                const selectedText = firstRow.querySelector('.selected-supply-text');
                                                const unitCostInput = firstRow.querySelector('.unit-cost-input');
                                                const fundClusterSelect = firstRow.querySelector('select[name*="fund_cluster"]');

                                                // Set the supply ID
                                                hiddenInput.value = btn.dataset.supplyId;

                                                // Find and set the supply name
                                                const supplyOption = firstRow.querySelector(`.supply-option[data-supply-id="${btn.dataset.supplyId}"]`);
                                                if (supplyOption) {
                                                    selectedText.textContent = `${supplyOption.dataset.supplyName} (${supplyOption.dataset.supplyStockno})`;
                                                    selectedText.classList.remove('text-gray-500');
                                                }

                                                unitCostInput.value = formatCurrency(parseCurrency(btn.dataset.unitCost));
                                                fundClusterSelect.value = btn.dataset.fundCluster;

                                                // Calculate total
                                                calculateRowTotal(firstRow);

                                                // Focus on quantity
                                                firstRow.querySelector('.quantity-input').focus();
                                            }
                                        }, 100);
                                    });
                                });

                                // Close dropdowns when clicking outside
                                document.addEventListener('click', function(event) {
                                    if (!event.target.closest('.supply-select-wrapper')) {
                                        document.querySelectorAll('.supply-dropdown-menu').forEach(dropdown => {
                                            dropdown.classList.add('hidden');
                                        });
                                    }
                                });

                                // Keyboard shortcuts
                                document.addEventListener('keydown', function(e) {
                                    // Ctrl/Cmd + Enter to submit
                                    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                                        const submitBtn = document.getElementById('submitStockBtn');
                                        if (submitBtn && !submitBtn.disabled) {
                                            submitBtn.click();
                                        }
                                    }

                                    // Ctrl/Cmd + Shift + A to add new item
                                    if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'A') {
                                        e.preventDefault();
                                        const addBtn = document.getElementById('addItemBtn');
                                        if (addBtn) {
                                            addBtn.click();
                                        }
                                    }
                                });
                            });
                        </script>


                        <!-- Template for supply item row (hidden) -->
                        <template id="supplyItemRowTemplate">
                            <tr class="supply-item-row hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors duration-150">
                                <td class="px-4 py-3">
                                    <!-- Custom searchable dropdown -->
                                    <div class="supply-select-wrapper relative">
                                        <input type="hidden" name="items[INDEX][supply_id]" class="supply-id-input" required>
                                        <div class="supply-dropdown-trigger w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                            rounded-lg text-sm text-gray-900 dark:text-white cursor-pointer
                                            focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                                            onclick="toggleSupplyDropdown(this)">
                                            <span class="selected-supply-text text-gray-500">Select Supply Item</span>
                                            <svg class="w-4 h-4 absolute right-3 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>

                                        <!-- Custom dropdown menu -->
                                        <div class="supply-dropdown-menu hidden absolute z-50 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg">
                                            <div class="p-2 border-b border-gray-200 dark:border-gray-700">
                                                <input type="text" class="supply-search-input w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                                    placeholder="Search supplies..." onkeyup="filterSupplyOptions(this)">
                                            </div>
                                            <div class="supply-options-container max-h-[200px] overflow-y-auto">
                                                @foreach ($supplies as $s)
                                                    <div class="supply-option px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-700 last:border-0"
                                                        data-supply-id="{{ $s->supply_id }}"
                                                        data-supply-name="{{ $s->item_name }}"
                                                        data-supply-stockno="{{ $s->stock_no }}"
                                                        data-supply-description="{{ $s->description }}"
                                                        data-unit="{{ $s->unit_of_measurement }}"
                                                        onclick="selectSupplyOption(this)">
                                                        <div class="font-medium text-sm text-gray-900 dark:text-white">{{ $s->item_name }} ({{ $s->stock_no }})</div>
                                                        @if($s->description)
                                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ Str::limit($s->description, 60) }}</div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="items[INDEX][quantity]" min="1" required
                                        placeholder="0"
                                        class="quantity-input w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                        rounded-lg text-sm text-center text-gray-900 dark:text-white
                                        focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                                </td>
                                <td class="px-4 py-3">
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400 text-sm">₱</span>
                                        <input type="text" name="items[INDEX][unit_cost]" required
                                            placeholder="0.00"
                                            class="unit-cost-input w-full pl-8 pr-3 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                            rounded-lg text-sm text-right text-gray-900 dark:text-white
                                            focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="total-cost font-medium text-gray-900 dark:text-white">₱0.00</span>
                                </td>
                                <td class="px-4 py-3">
                                    <select name="items[INDEX][fund_cluster]" required
                                        class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                        rounded-lg text-sm text-gray-900 dark:text-white
                                        focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                                        <option value="101">101</option>
                                        <option value="151">151</option>
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <select name="items[INDEX][status]" required
                                        class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                        rounded-lg text-sm text-gray-900 dark:text-white
                                        focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                                        <option value="available">Available</option>
                                        <option value="reserved">Reserved</option>
                                        <option value="expired">Expired</option>
                                        <option value="depleted">Depleted</option>
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="date" name="items[INDEX][expiry_date]"
                                        class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                        rounded-lg text-sm text-gray-900 dark:text-white
                                        focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" class="remove-item-btn p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400
                                        hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </template>

                        <!-- Add this script for validation error handling -->
                        @if ($errors->any() && session('show_create_modal') && old('items'))
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Wait for modal to be shown
                                    setTimeout(function() {
                                        const oldItems = @json(old('items', []));
                                        const generalRemarks = @json(old('general_remarks', ''));
                                        const generalSupplierId = @json(old('general_supplier_id', ''));
                                        const generalDepartmentId = @json(old('general_department_id', ''));

                                        // Set general fields
                                        document.getElementById('general_remarks').value = generalRemarks;
                                        document.getElementById('general_supplier_id').value = generalSupplierId;
                                        document.getElementById('general_department_id').value = generalDepartmentId;

                                        // Clear any existing rows
                                        document.getElementById('supplyItemsTable').innerHTML = '';

                                        // Re-add rows with old data
                                        oldItems.forEach(function(item, index) {
                                            // Trigger add item button
                                            document.getElementById('addItemBtn').click();

                                            // Fill in the data
                                            setTimeout(function() {
                                                const rows = document.querySelectorAll('.supply-item-row');
                                                const row = rows[index];

                                                if (row) {
                                                    row.querySelector('select[name*="supply_id"]').value = item.supply_id || '';
                                                    row.querySelector('input[name*="quantity"]').value = item.quantity || '1';

                                                    // Format unit cost
                                                    const unitCostInput = row.querySelector('input[name*="unit_cost"]');
                                                    const unitCost = item.unit_cost || '0.00';
                                                    unitCostInput.value = parseFloat(unitCost.replace(/,/g, '')).toLocaleString('en-US', {
                                                        minimumFractionDigits: 2,
                                                        maximumFractionDigits: 2
                                                    });

                                                    row.querySelector('select[name*="fund_cluster"]').value = item.fund_cluster || '101';
                                                    row.querySelector('select[name*="status"]').value = item.status || 'available';
                                                    row.querySelector('input[name*="expiry_date"]').value = item.expiry_date || '';

                                                    // Trigger calculation
                                                    const event = new Event('input', { bubbles: true });
                                                    row.querySelector('.quantity-input').dispatchEvent(event);
                                                }
                                            }, 100 * (index + 1));
                                        });
                                    }, 500);
                                });
                            </script>
                        @endif

                        @if ($errors->any() || session('show_create_modal'))
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                document
                                    .getElementById('createStockModal')
                                    .classList.remove('hidden');
                                });
                            </script>
                        @endif

                    @endif

                    <!-- Add this Confirmation Modal AFTER your existing modals -->
                    @if(auth()->user()->hasRole('admin'))
                        <!-- Stock Save Confirmation Modal -->
                        <div id="confirmStockModal" tabindex="-1" aria-hidden="true"
                            class="hidden fixed inset-0 z-[60] w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-full max-h-full flex justify-center items-center bg-gray-900/70 backdrop-blur-sm">

                            <div class="relative w-full max-w-md max-h-full animate-modal-slide-up">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-2xl shadow-2xl dark:bg-gray-800 overflow-hidden border border-gray-200 dark:border-gray-700">
                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-3">
                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    Confirm Stock Save
                                                </h3>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Please review before saving
                                                </p>
                                            </div>
                                        </div>
                                        <!-- Close button -->
                                        <button type="button" id="confirmModalCloseBtn"
                                            class="text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg p-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>

                                    <!-- Modal body -->
                                    <div class="p-6">
                                        <!-- Summary for Create Stock -->
                                        <div id="createStockSummary" class="hidden">
                                            <div class="mb-4">
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Stock Receipt Summary</h4>

                                                <!-- IAR Info -->
                                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 mb-4">
                                                    <div class="grid grid-cols-2 gap-3 text-xs">
                                                        <div>
                                                            <span class="text-gray-500 dark:text-gray-400">IAR Reference:</span>
                                                            <div class="font-medium text-gray-900 dark:text-white" id="confirm_iar_ref">-</div>
                                                        </div>
                                                        <div>
                                                            <span class="text-gray-500 dark:text-gray-400">Receipt Date:</span>
                                                            <div class="font-medium text-gray-900 dark:text-white" id="confirm_receipt_date">-</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Items Summary -->
                                                <div class="border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                                                    <div class="bg-gray-50 dark:bg-gray-700 px-3 py-2 border-b border-gray-200 dark:border-gray-600">
                                                        <div class="text-xs font-medium text-gray-700 dark:text-gray-300">Items to be added</div>
                                                    </div>
                                                    <div id="confirm_items_list" class="max-h-32 overflow-y-auto">
                                                        <!-- Items will be inserted here -->
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-700 px-3 py-2 border-t border-gray-200 dark:border-gray-600">
                                                        <div class="flex justify-between text-sm font-medium text-gray-900 dark:text-white">
                                                            <span>Total:</span>
                                                            <span id="confirm_total_amount">₱0.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Summary for Edit Stock -->
                                        <div id="editStockSummary" class="hidden">
                                            <div class="mb-4">
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Re-valuation Summary</h4>

                                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                                    <div class="space-y-2 text-xs">
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-500 dark:text-gray-400">Supply Item:</span>
                                                            <span class="font-medium text-gray-900 dark:text-white" id="confirm_edit_supply">-</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-500 dark:text-gray-400">New Unit Cost:</span>
                                                            <span class="font-medium text-gray-900 dark:text-white" id="confirm_edit_cost">-</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-500 dark:text-gray-400">Current Quantity:</span>
                                                            <span class="font-medium text-gray-900 dark:text-white" id="confirm_edit_quantity">-</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Confirmation Message -->
                                        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-900 rounded-lg p-3 mb-4">
                                            <div class="flex">
                                                <svg class="w-4 h-4 text-amber-400 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                <div>
                                                    <p class="text-xs text-amber-700 dark:text-amber-300 font-medium">
                                                        Please review all information carefully before confirming.
                                                    </p>
                                                    <p class="text-xs text-amber-600 dark:text-amber-400 mt-1">
                                                        This action will create permanent transaction records.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
                                            Are you sure all information is correct?
                                        </p>
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="flex items-center justify-end space-x-3 p-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                                        <button type="button" id="cancelConfirmBtn"
                                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300
                                            bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600
                                            rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600
                                            focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600
                                            transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Cancel
                                        </button>
                                        <button type="button" id="confirmSaveBtn"
                                            class="px-4 py-2 text-sm font-medium text-white
                                            bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600
                                            rounded-lg shadow-sm hover:shadow-md
                                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                                            transition-all duration-200 transform hover:-translate-y-0.5
                                            disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Yes, Save Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add CSS for modal animation if not already present -->
                        <style>
                            .animate-modal-slide-up {
                                animation: modalSlideUp 0.3s ease-out;
                            }

                            @keyframes modalSlideUp {
                                from {
                                    opacity: 0;
                                    transform: translateY(20px) scale(0.95);
                                }
                                to {
                                    opacity: 1;
                                    transform: translateY(0) scale(1);
                                }
                            }

                            /* Prevent modal backdrop clicks during submission */
                            .modal-submitting {
                                pointer-events: none !important;
                            }

                            .modal-submitting .modal-content {
                                pointer-events: auto;
                            }
                        </style>

                        <!-- Additional JavaScript for the close button -->
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const confirmModalCloseBtn = document.getElementById('confirmModalCloseBtn');
                                if (confirmModalCloseBtn) {
                                    confirmModalCloseBtn.addEventListener('click', function() {
                                        const confirmStockModal = document.getElementById('confirmStockModal');
                                        if (confirmStockModal) {
                                            confirmStockModal.classList.add('hidden');
                                            // Reset confirmation variables
                                            setTimeout(() => {
                                                window.pendingForm = null;
                                                window.isConfirmed = false;
                                                window.isSubmitting = false;
                                            }, 100);
                                        }
                                    });
                                }
                            });
                        </script>
                    @endif

                    {{-- For date and reference number auto-fill --}}
                    <script>
                        document.addEventListener('DOMContentLoaded', function(){
                        const dateField = document.getElementById('receipt_date');
                        const refField  = document.getElementById('reference_no');
                        if (!dateField || !refField) return;

                        dateField.addEventListener('change', function(){
                            const d = this.value;
                            if (!d) return;

                            fetch(
                            "{{ route('stocks.next-iar') }}?receipt_date="
                            + encodeURIComponent(d),
                            {
                                credentials: 'same-origin',                   // ← send Laravel session cookie
                                headers: { 'X-Requested-With': 'XMLHttpRequest' }
                            }
                            )
                            .then(r => {
                            if (!r.ok) throw new Error(r.statusText);
                            return r.json();
                            })
                            .then(json => {
                            refField.value = json.defaultIar;
                            })
                            .catch(err => console.error('IAR lookup failed:', err));
                        });
                        });
                    </script>

                    @if(auth()->user()->hasRole('admin'))
                        <!-- Edit / Re‑value Stock Modal - Wider Design -->
                        <div id="editStockModal" tabindex="-1" aria-hidden="true"
                            class="hidden fixed inset-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-full max-h-full flex justify-center items-center bg-gray-900/60 backdrop-blur-sm">

                            <div class="relative w-full max-w-7xl max-h-[90vh] animate-modal-slide-up">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-2xl shadow-2xl dark:bg-gray-800 overflow-hidden">
                                    <!-- Modal header - Modern Clean Style -->
                                    <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-700">
                                        <div>
                                            <h3 class="text-2xl font-semibold text-gray-900 dark:text-white">
                                                Re-value Stock
                                            </h3>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                Update stock information including cost, status, and other details
                                            </p>
                                        </div>
                                        <button type="button"
                                            class="text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg p-2
                                            hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                                            data-modal-hide="editStockModal">
                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>

                                    <!-- Modal body with matching padding -->
                                    <form id="editStockForm" method="POST" action="{{ route('stocks.update', ['stock' => 0]) }}" class="p-6">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="submission_token" value="{{ uniqid() . time() }}">
                                        <input type="hidden" name="stock_id" id="edit_stock_id">
                                        <input type="hidden" name="supply_id" id="edit_supply_id">

                                        <!-- Updated IAR Information Card - Now with matching spacing -->
                                        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-6 mb-6">
                                            <div class="mb-4">
                                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                                    IAR Information
                                                </h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Receipt and reference details
                                                </p>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

                                                <!-- Receipt Date - NOW EDITABLE -->
                                                <div>
                                                    <label for="edit_receipt_date" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                                        RECEIPT DATE
                                                    </label>
                                                    <div class="flex items-center space-x-3">
                                                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-700">
                                                                <path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/>
                                                            </svg>
                                                        </div>
                                                        <input
                                                            type="date"
                                                            name="receipt_date"
                                                            id="edit_receipt_date"
                                                            max="{{ now()->format('Y-m-d') }}"
                                                            class="flex-1 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                                                    rounded-lg text-sm text-gray-900 dark:text-white
                                                                    focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                                                    </div>
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Date when stock was received</p>
                                                </div>

                                                <!-- IAR Reference - NOW EDITABLE -->
                                                <div>
                                                    <label for="edit_reference_no" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                                        IAR REFERENCE
                                                    </label>
                                                    <div class="flex items-center space-x-3">
                                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-700">
                                                                <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="m9 12 2 2 4-4"/>
                                                            </svg>
                                                        </div>
                                                        <input
                                                            type="text"
                                                            name="reference_no"
                                                            id="edit_reference_no"
                                                            placeholder="IAR YYYY-MM-XXX"
                                                            class="flex-1 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                                                    rounded-lg text-sm text-gray-900 dark:text-white
                                                                    focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                                                    </div>
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">IAR reference number</p>
                                                </div>

                                                <!-- Supplier -->
                                                <div>
                                                    <label for="edit_supplier_id" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
                                                        SUPPLIER (OPTIONAL)
                                                    </label>
                                                    <select name="supplier_id" id="edit_supplier_id"
                                                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                                        rounded-lg text-sm text-gray-900 dark:text-white
                                                        focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                                                        <option value="">No Supplier</option>
                                                        @foreach($suppliers as $supplier)
                                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Department -->
                                                <div>
                                                    <label for="edit_department_id" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
                                                        DEPARTMENT (OPTIONAL)
                                                    </label>
                                                    <select name="department_id" id="edit_department_id"
                                                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                                        rounded-lg text-sm text-gray-900 dark:text-white
                                                        focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                                                        <option value="">No Department</option>
                                                        @foreach($departments as $department)
                                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Supply Information Section with matching spacing -->
                                        <div class="mb-6">
                                            <div class="mb-4">
                                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    Supply Information
                                                </h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Current item being re-valued
                                                </p>
                                            </div>

                                            <!-- Supply Item Table with matching layout -->
                                            <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 mb-6">
                                                <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4">
                                                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                                        <!-- Supply Item (Read-only) -->
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
                                                                SUPPLY ITEM
                                                            </label>
                                                            <div class="relative">
                                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                </div>
                                                                <input type="text" id="edit_supply_name" disabled
                                                                    class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                    block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600
                                                                    dark:placeholder-gray-400 dark:text-gray-300" />
                                                            </div>
                                                        </div>

                                                        <!-- Current Quantity (Read-only) -->
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
                                                                CURRENT QUANTITY
                                                            </label>
                                                            <div class="relative">
                                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                </div>
                                                                <input type="text" id="edit_current_quantity" disabled
                                                                    class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                    block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600
                                                                    dark:placeholder-gray-400 dark:text-gray-300" />
                                                            </div>
                                                        </div>

                                                        <!-- New Unit Cost -->
                                                        <div>
                                                            <label for="edit_unit_cost" class="block text-xs font-medium text-orange-500 dark:text-yellow-400 mb-2">
                                                                NEW UNIT COST / MOVING AVERAGE COST
                                                            </label>
                                                            <div class="relative">
                                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                    <span class="text-gray-500 dark:text-gray-400 text-sm">₱</span>
                                                                </div>
                                                                <input type="text" name="unit_cost" id="edit_unit_cost" required
                                                                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 text-sm rounded-lg
                                                                    focus:ring-blue-500 focus:border-blue-500 block w-full pl-8 p-2.5
                                                                    dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Additional Details Grid with matching layout -->
                                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                                <!-- Status -->
                                                <div>
                                                    <label for="edit_status" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
                                                        STATUS <span class="text-red-500">*</span>
                                                    </label>
                                                    <select name="status" id="edit_status" required
                                                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                                        rounded-lg text-sm text-gray-900 dark:text-white
                                                        focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                                                        <option value="available">Available</option>
                                                        <option value="reserved">Reserved</option>
                                                        <option value="expired">Expired</option>
                                                        <option value="depleted">Depleted</option>
                                                    </select>
                                                </div>

                                                <!-- Fund Cluster -->
                                                <div>
                                                    <label for="edit_fund_cluster" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
                                                        FUND CLUSTER <span class="text-red-500">*</span>
                                                    </label>
                                                    <select name="fund_cluster" id="edit_fund_cluster" required
                                                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                                        rounded-lg text-sm text-gray-900 dark:text-white
                                                        focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
                                                        <option value="101">101</option>
                                                        <option value="151">151</option>
                                                    </select>
                                                </div>

                                                <!-- Expiry Date -->
                                                <div>
                                                    <label for="edit_expiry_date" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
                                                        EXPIRY DATE
                                                    </label>
                                                    <input type="date" name="expiry_date" id="edit_expiry_date"
                                                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                                        rounded-lg text-sm text-gray-900 dark:text-white
                                                        focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200" />
                                                </div>

                                                <!-- Days to Consume -->
                                                <div>
                                                    <label for="edit_days_to_consume" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
                                                        DAYS TO CONSUME
                                                    </label>
                                                    <input type="number" name="days_to_consume" id="edit_days_to_consume" min="0"
                                                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                                        rounded-lg text-sm text-gray-900 dark:text-white
                                                        focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200" />
                                                </div>

                                                <!-- Remarks - spans 2 columns on xl screens -->
                                                <div class="xl:col-span-2">
                                                    <label for="edit_remarks" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
                                                        REMARKS
                                                    </label>
                                                    <textarea name="remarks" id="edit_remarks" rows="2"
                                                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                                                        rounded-lg text-sm text-gray-900 dark:text-white
                                                        focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                                                        placeholder="Enter reason for re-valuation or other notes"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Important Notes with matching spacing -->
                                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-900 rounded-lg p-4 mb-6">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">
                                                        Important Notes
                                                    </h3>
                                                    <div class="mt-2 text-xs text-blue-700 dark:text-blue-400 space-y-1">
                                                        <p>• All fields marked with <span class="text-red-500">*</span> are required</p>
                                                        <p>• Re-valuation creates a transaction record for auditing</p>
                                                        <p>• The original supply item cannot be changed</p>
                                                        <p>• Include the reason for re-valuation in remarks</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Footer with matching spacing -->
                                        <div class="flex items-center justify-end space-x-3">
                                            <button type="button" data-modal-hide="editStockModal"
                                                class="px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300
                                                bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600
                                                rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700
                                                focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600
                                                transition-all duration-200">
                                                Cancel
                                            </button>
                                            <button type="submit" id="editSubmitBtn"
                                                class="px-6 py-2.5 text-sm font-medium text-white
                                                bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600
                                                rounded-lg shadow-sm hover:shadow-md
                                                focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                                                transition-all duration-200 transform hover:-translate-y-0.5
                                                disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Save Re-valuation
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

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

    @if(auth()->user()->hasRole('admin'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Confirmation Modal Variables
                let pendingForm = null;
                let isConfirmed = false;
                let isSubmitting = false; // Add this to prevent multiple submissions

                // Initialize Flowbite modals
                const modals = {
                    createStockModal: document.getElementById('createStockModal'),
                    editStockModal: document.getElementById('editStockModal'),
                    confirmStockModal: document.getElementById('confirmStockModal')
                };

                // Manual modal control functions
                const show = id => {
                    if (modals[id]) {
                        const options = {
                            backdrop: 'static',
                            placement: 'center',
                            backdropClasses: 'bg-gray-900 bg-opacity-50 fixed inset-0 z-40'
                        };

                        // Use Flowbite Modal if available, otherwise use classList
                        if (window.Flowbite && window.Flowbite.Modal) {
                            const modal = new window.Flowbite.Modal(modals[id], options);
                            modal.show();
                        } else {
                            modals[id].classList.remove('hidden');
                        }
                    }
                };

                const hide = id => {
                    if (modals[id]) {
                        // Use Flowbite Modal if available, otherwise use classList
                        if (window.Flowbite && window.Flowbite.Modal) {
                            const modal = new window.Flowbite.Modal(modals[id]);
                            modal.hide();
                        } else {
                            modals[id].classList.add('hidden');
                        }
                    }
                };

                // Close modals
                document.querySelectorAll('[data-modal-hide]').forEach(btn =>
                    btn.addEventListener('click', () => hide(btn.getAttribute('data-modal-hide')))
                );

                // === CONFIRMATION MODAL FUNCTIONS ===
                function showConfirmModal() {
                    if (modals.confirmStockModal) {
                        modals.confirmStockModal.classList.remove('hidden');
                    }
                }

                function hideConfirmModal() {
                    if (modals.confirmStockModal) {
                        modals.confirmStockModal.classList.add('hidden');
                    }
                    // Don't reset pendingForm here immediately
                    // pendingForm = null;
                    // isConfirmed = false;
                }

                function resetConfirmModal() {
                    // Reset form button state if pendingForm exists
                    if (pendingForm) {
                        // Remove the confirmed flag
                        delete pendingForm.dataset.confirmed;

                        // Reset submit button to original state
                        const submitBtn = pendingForm.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.disabled = false;

                            // Determine if it's create or edit form and restore original button text
                            const isCreateForm = pendingForm.closest('#createStockModal');
                            if (isCreateForm) {
                                submitBtn.innerHTML = `
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Save Stock Receipt
                                    </span>
                                `;
                            } else {
                                submitBtn.innerHTML = `
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Save Re-valuation
                                    </span>
                                `;
                            }

                            // Remove any added classes
                            submitBtn.className = submitBtn.className.replace(/\s*opacity-50\s*/, '').replace(/\s*cursor-not-allowed\s*/, '');
                        }

                        // Re-enable modal close buttons
                        const modal = pendingForm.closest('[id$="Modal"]');
                        if (modal) {
                            const closeButtons = modal.querySelectorAll('[data-modal-hide]');
                            closeButtons.forEach(btn => {
                                btn.disabled = false;
                                btn.style.opacity = '';
                                btn.style.pointerEvents = '';
                            });
                        }
                    }

                    // Reset modal variables
                    pendingForm = null;
                    isConfirmed = false;
                    isSubmitting = false;

                    // Reset confirmation modal button
                    const confirmSaveBtn = document.getElementById('confirmSaveBtn');
                    if (confirmSaveBtn) {
                        confirmSaveBtn.disabled = false;
                        confirmSaveBtn.innerHTML = `
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Yes, Save Now
                        `;
                    }
                }

                // Make resetConfirmModal available globally
                window.resetConfirmModal = resetConfirmModal;

                // Format currency for display
                function formatCurrency(amount) {
                    return parseFloat(amount).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }

                // Parse currency input
                function parseCurrency(value) {
                    return parseFloat(value.replace(/[^0-9.-]+/g, '')) || 0;
                }

                // Populate confirmation modal for Create Stock
                function populateCreateStockConfirmation(form) {
                    const createSummary = document.getElementById('createStockSummary');
                    const editSummary = document.getElementById('editStockSummary');

                    if (createSummary) createSummary.classList.remove('hidden');
                    if (editSummary) editSummary.classList.add('hidden');

                    const iarRef = form.querySelector('#reference_no')?.value || 'Not specified';
                    const receiptDate = form.querySelector('#receipt_date')?.value || 'Not specified';

                    const iarRefEl = document.getElementById('confirm_iar_ref');
                    const receiptDateEl = document.getElementById('confirm_receipt_date');

                    if (iarRefEl) iarRefEl.textContent = iarRef;
                    if (receiptDateEl) receiptDateEl.textContent = receiptDate;

                    const itemRows = form.querySelectorAll('.supply-item-row');
                    const itemsList = document.getElementById('confirm_items_list');
                    let totalAmount = 0;

                    if (itemsList) {
                        itemsList.innerHTML = '';

                        itemRows.forEach((row) => {
                            const supplyName = row.querySelector('.selected-supply-text')?.textContent || 'Unknown Item';
                            const quantity = row.querySelector('.quantity-input')?.value || '0';
                            const unitCost = parseCurrency(row.querySelector('.unit-cost-input')?.value || '0');
                            const itemTotal = parseInt(quantity) * unitCost;
                            totalAmount += itemTotal;

                            if (supplyName === 'Select Supply Item') return;

                            const itemDiv = document.createElement('div');
                            itemDiv.className = 'px-3 py-2 border-b border-gray-100 dark:border-gray-600 last:border-b-0';
                            itemDiv.innerHTML = `
                                <div class="flex justify-between items-start">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs font-medium text-gray-900 dark:text-white truncate">${supplyName}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">${quantity} × ₱${formatCurrency(unitCost)}</div>
                                    </div>
                                    <div class="text-xs font-medium text-gray-900 dark:text-white ml-2">
                                        ₱${formatCurrency(itemTotal)}
                                    </div>
                                </div>
                            `;
                            itemsList.appendChild(itemDiv);
                        });
                    }

                    const totalAmountEl = document.getElementById('confirm_total_amount');
                    if (totalAmountEl) totalAmountEl.textContent = `₱${formatCurrency(totalAmount)}`;
                }

                // Populate confirmation modal for Edit Stock
                function populateEditStockConfirmation(form) {
                    const createSummary = document.getElementById('createStockSummary');
                    const editSummary = document.getElementById('editStockSummary');

                    if (editSummary) editSummary.classList.remove('hidden');
                    if (createSummary) createSummary.classList.add('hidden');

                    const supplyName = form.querySelector('#edit_supply_name')?.value || 'Not specified';
                    const unitCost = form.querySelector('#edit_unit_cost')?.value || '0';
                    const quantity = form.querySelector('#edit_current_quantity')?.value || '0';

                    const supplyEl = document.getElementById('confirm_edit_supply');
                    const costEl = document.getElementById('confirm_edit_cost');
                    const quantityEl = document.getElementById('confirm_edit_quantity');

                    if (supplyEl) supplyEl.textContent = supplyName;
                    if (costEl) costEl.textContent = `₱${unitCost}`;
                    if (quantityEl) quantityEl.textContent = quantity;
                }

                // === FIXED Edit‑Stock button with AJAX ===
                document.querySelectorAll('.edit-stock-btn').forEach(btn => {
                    btn.addEventListener('click', async () => {
                        const stockId = btn.dataset.stockId;

                        if (!stockId) {
                            alert('Stock ID not found');
                            return;
                        }

                        // Declare variables outside try-catch for proper scope
                        const originalContent = btn.innerHTML;
                        const originalDisabled = btn.disabled;

                        try {
                            // Show loading state on button
                            btn.disabled = true;
                            btn.innerHTML = `
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            `;

                            // Fetch stock data via AJAX
                            const response = await fetch(`/stocks/${stockId}/iar-data`, {
                                method: 'GET',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                }
                            });

                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }

                            const result = await response.json();

                            if (result.success) {
                                const data = result.data;

                                console.log('Loaded stock data:', data); // Debug log

                                // Populate the modal fields
                                document.getElementById('edit_stock_id').value = data.stock_id || '';
                                document.getElementById('edit_supply_id').value = data.supply_id || '';

                                // Supply information (read-only)
                                document.getElementById('edit_supply_name').value = data.supply_name || '';

                                // Current quantity (read-only)
                                const currentQuantityField = document.getElementById('edit_current_quantity');
                                if (currentQuantityField) {
                                    currentQuantityField.value = data.current_quantity || '0';
                                }

                                // Editable fields
                                document.getElementById('edit_unit_cost').value = data.unit_cost || '';
                                document.getElementById('edit_status').value = data.status || '';
                                document.getElementById('edit_expiry_date').value = data.expiry_date || '';
                                document.getElementById('edit_fund_cluster').value = data.fund_cluster || '';
                                document.getElementById('edit_days_to_consume').value = data.days_to_consume || '';
                                document.getElementById('edit_remarks').value = data.remarks || '';
                                document.getElementById('edit_supplier_id').value = data.supplier_id || '';
                                document.getElementById('edit_department_id').value = data.department_id || '';

                                // IAR reference and date (now editable)
                                const receiptDateField = document.getElementById('edit_receipt_date');
                                if (receiptDateField) {
                                    receiptDateField.value = data.receipt_date || '';
                                }

                                const referenceNoField = document.getElementById('edit_reference_no');
                                if (referenceNoField) {
                                    referenceNoField.value = data.reference_no || '';
                                }

                                // Update form action
                                const form = document.getElementById('editStockForm');
                                if (form) {
                                    form.action = `/stocks/${data.stock_id}`;
                                }

                                // Restore button state before showing modal
                                btn.disabled = originalDisabled;
                                btn.innerHTML = originalContent;

                                // Show the modal
                                show('editStockModal');

                            } else {
                                alert('Error loading stock data: ' + (result.message || 'Unknown error'));
                            }

                        } catch (error) {
                            console.error('Error fetching stock data:', error);
                            alert('Error loading stock data. Please try again.');
                        } finally {
                            // Always restore button state
                            btn.disabled = originalDisabled;
                            btn.innerHTML = originalContent;
                        }
                    });
                });

                // Delete‑Stock button
                document.querySelectorAll('.delete-stock-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        if (!confirm('Are you sure you want to delete this stock?')) return;
                        const id = btn.dataset.stockId;
                        fetch(`/stocks/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            }
                        }).then(() => location.reload());
                    });
                });

                // === FORM SUBMISSION INTERCEPTORS ===

                // Intercept Create Stock Form Submission
                const createStockForm = document.querySelector('#createStockModal form');
                if (createStockForm) {
                    createStockForm.addEventListener('submit', function(e) {
                        if (isConfirmed && !isSubmitting) {
                            isSubmitting = true;
                            return true; // Let it proceed
                        }

                        e.preventDefault();

                        // Validate form first
                        const itemRows = this.querySelectorAll('.supply-item-row');
                        if (itemRows.length === 0) {
                            alert('Please add at least one supply item.');
                            return false;
                        }

                        let isValid = true;
                        itemRows.forEach((row) => {
                            const supplyId = row.querySelector('input[name*="supply_id"]')?.value;
                            const quantity = row.querySelector('input[name*="quantity"]')?.value;
                            const unitCost = parseCurrency(row.querySelector('input[name*="unit_cost"]')?.value || '0');

                            if (!supplyId || !quantity || quantity < 1 || unitCost <= 0) {
                                isValid = false;
                            }
                        });

                        if (!isValid) {
                            alert('Please complete all required fields for each item.');
                            return false;
                        }

                        pendingForm = this;
                        populateCreateStockConfirmation(this);
                        showConfirmModal();
                    });
                }

                // Intercept Edit Stock Form Submission
                const editStockForm = document.querySelector('#editStockModal form');
                if (editStockForm) {
                    editStockForm.addEventListener('submit', function(e) {
                        if (isConfirmed && !isSubmitting) {
                            isSubmitting = true;
                            return true; // Let it proceed
                        }

                        e.preventDefault();

                        const unitCost = this.querySelector('#edit_unit_cost')?.value;
                        if (!unitCost || parseCurrency(unitCost) <= 0) {
                            alert('Please enter a valid unit cost.');
                            return false;
                        }

                        pendingForm = this;
                        populateEditStockConfirmation(this);
                        showConfirmModal();
                    });
                }

                // Confirmation Modal Event Listeners
                const cancelConfirmBtn = document.getElementById('cancelConfirmBtn');
                const confirmSaveBtn = document.getElementById('confirmSaveBtn');

                if (cancelConfirmBtn) {
                    cancelConfirmBtn.addEventListener('click', function() {
                        // First reset everything, then hide modal
                        resetConfirmModal();
                        hideConfirmModal();
                    });
                }

                if (confirmSaveBtn) {
                    confirmSaveBtn.addEventListener('click', function() {
                        // Check if we have a valid form and not already submitting
                        if (!pendingForm) {
                            console.error('No pending form found');
                            return;
                        }

                        if (isSubmitting) {
                            console.log('Already submitting, please wait...');
                            return;
                        }

                        // Disable the button immediately to prevent double clicks
                        this.disabled = true;
                        this.innerHTML = `
                            <svg class="animate-spin w-4 h-4 mr-2 inline" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        `;

                        // Set confirmation flags
                        isConfirmed = true;
                        pendingForm.dataset.confirmed = 'true';

                        // Hide the confirmation modal
                        hideConfirmModal();

                        // Submit the form with a small delay to ensure modal is hidden
                        setTimeout(() => {
                            try {
                                if (pendingForm && typeof pendingForm.submit === 'function') {
                                    pendingForm.submit();
                                } else {
                                    console.error('Form submit method not available');
                                    // Reset states if submission fails
                                    resetConfirmModal();
                                    this.disabled = false;
                                    this.innerHTML = `
                                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Yes, Save Now
                                    `;
                                }
                            } catch (error) {
                                console.error('Error submitting form:', error);
                                resetConfirmModal();
                                this.disabled = false;
                                this.innerHTML = `
                                    <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Yes, Save Now
                                `;
                            }
                        }, 150);
                    });
                }

                // Close confirmation modal when clicking outside
                if (modals.confirmStockModal) {
                    modals.confirmStockModal.addEventListener('click', function(e) {
                        if (e.target === modals.confirmStockModal) {
                            resetConfirmModal();
                            hideConfirmModal();
                        }
                    });
                }

                // format money fields
                const formatMoney = el => {
                    let digits = (el.value || '').replace(/\D/g, '') || '0';
                    let amt = parseInt(digits, 10) / 100;
                    el.value = amt.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                };
                ['unit_cost', 'edit_unit_cost'].forEach(id => {
                    const input = document.getElementById(id);
                    if (input) input.addEventListener('input', () => formatMoney(input));
                });

                // search clear
                const inp = document.getElementById('search-input'),
                    clr = document.getElementById('clearButton');
                const toggleClr = () => clr && (clr.style.display = inp.value.trim() ? 'flex' : 'none');
                if (inp && clr) {
                    inp.addEventListener('input', toggleClr);
                    toggleClr();
                    window.clearSearch = () => {
                        inp.value = '';
                        toggleClr();
                        inp.form.submit();
                    }
                }

                // Handle ESC key to close confirmation modal
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && modals.confirmStockModal && !modals.confirmStockModal.classList.contains('hidden')) {
                        resetConfirmModal();
                        hideConfirmModal();
                    }
                });

                // re‑open on validation errors
                @if ($errors->any() && session('show_create_modal'))
                    show('createStockModal');
                @endif
                @if ($errors->any() && session('show_edit_modal'))
                    // find the button for this edit and trigger it
                    const editId = {{ session('show_edit_modal') }};
                    document.querySelectorAll('.edit-stock-btn').forEach(b => {
                        if (b.dataset.stockId == editId) b.click();
                    });
                @endif
            });
        </script>
    @endif

    <!-- Simplified Double submission prevention -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Prevent double submission for stock forms
            function preventDoubleSubmission() {
                const stockForms = [
                    document.querySelector('#createStockModal form'),
                    document.querySelector('#editStockModal form')
                ];

                stockForms.forEach(form => {
                    if (!form) return;

                    form.addEventListener('submit', function(e) {
                        // Only apply double submission prevention to confirmed forms
                        if (form.dataset.confirmed === 'true') {
                            const submitBtn = form.querySelector('button[type="submit"]');
                            if (submitBtn && !submitBtn.disabled) {
                                const originalHTML = submitBtn.innerHTML;
                                const originalClasses = submitBtn.className;

                                submitBtn.disabled = true;
                                submitBtn.className = originalClasses + ' opacity-50 cursor-not-allowed';

                                const isCreateForm = form.closest('#createStockModal');
                                const loadingText = isCreateForm ? 'Saving Stock...' : 'Updating Stock...';

                                submitBtn.innerHTML = `
                                    <svg class="animate-spin w-4 h-4 mr-2 inline" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    ${loadingText}
                                `;

                                // Disable modal close buttons during submission
                                const modal = form.closest('[id$="Modal"]');
                                if (modal) {
                                    const closeButtons = modal.querySelectorAll('[data-modal-hide]');
                                    closeButtons.forEach(btn => {
                                        btn.disabled = true;
                                        btn.style.opacity = '0.5';
                                        btn.style.pointerEvents = 'none';
                                    });
                                }
                            }
                        }
                    });
                });
            }

            // Initialize the prevention
            preventDoubleSubmission();
        });

        // Add CSS for spinning animation
        if (!document.getElementById('double-submit-styles')) {
            const style = document.createElement('style');
            style.id = 'double-submit-styles';
            style.textContent = `
                .animate-spin {
                    animation: spin 1s linear infinite;
                }

                @keyframes spin {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }

                button:disabled {
                    pointer-events: none !important;
                }

                .cursor-not-allowed {
                    cursor: not-allowed !important;
                }
            `;
            document.head.appendChild(style);
        }
    </script>

</x-app-layout>
