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

                        <!-- Replace the header section with navigation buttons -->
                        <div class="flex space-x-2">
                            <!-- Stock Cards button - using teal/cyan instead of blue -->
                            <a href="{{ route('stock-cards.index') }}" class="py-2 px-3 text-white bg-teal-600 hover:bg-teal-700 rounded-lg text-sm font-medium focus:ring-4 focus:outline-none focus:ring-teal-300 dark:focus:ring-teal-800 transition-all duration-200 inline-flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Stock Cards</span>
                            </a>

                            <!-- Supply Ledger Cards button - keeping purple for consistency with individual card access -->
                            <a href="{{ route('supply-ledger-cards.index') }}" class="py-2 px-3 text-white bg-purple-600 hover:bg-purple-700 rounded-lg text-sm font-medium focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 transition-all duration-200 inline-flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Ledger Cards</span>
                            </a>

                            <!-- Add Stock button - keeping blue as it's the primary action -->
                            <button data-modal-target="createStockModal" data-modal-toggle="createStockModal" type="button"
                                class="py-2 px-3 text-white bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 rounded-lg text-sm font-medium focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 transition-all duration-200 inline-flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="hidden sm:inline-block">Add Stock</span>
                            </button>
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
                                                <!-- ID -->
                                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
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
                                                        @elseif($stock->status == 'reserved')  bg-blue-100  text-blue-800  dark:bg-blue-900  dark:text-blue-300
                                                        @elseif($stock->status == 'expired')   bg-red-100   text-red-800   dark:bg-red-900   dark:text-red-300
                                                        @else                                 bg-gray-100  text-gray-800 dark:bg-gray-900 dark:text-gray-300 @endif">
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
                                                <!-- Updates for the Action buttons in the table -->
                                                <td class="px-6 py-4 text-center">
                                                    <div class="flex items-center justify-center space-x-2">
                                                        <!-- + Add Stock -->
                                                        <button type="button" data-modal-target="createStockModal"
                                                            data-modal-toggle="createStockModal"
                                                            class="add-stock-btn inline-flex items-center justify-center w-8 h-8 bg-green-100 text-green-600 rounded-full hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-300 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800 transition-all duration-200"
                                                            data-supply-id="{{ $stock->supply_id }}"
                                                            data-unit-cost="{{ number_format($stock->unit_cost, 2) }}"
                                                            data-fund-cluster="{{ $stock->fund_cluster }}"
                                                            title="Add stock to {{ $stock->supply->item_name }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                <path
                                                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" />
                                                            </svg>
                                                        </button>

                                                        <!-- Edit Stock (Adjustment/Re‑value) -->
                                                        <button type="button"
                                                            class="edit-stock-btn p-2 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200
                                                                focus:outline-none focus:ring-2 focus:ring-yellow-300 dark:bg-yellow-900
                                                                dark:text-yellow-300 dark:hover:bg-yellow-800 transition-all duration-200"
                                                            data-stock-id="{{ $stock->stock_id }}"
                                                            data-supply-id="{{ $stock->supply_id }}"
                                                            data-supply-name="{{ $stock->supply->item_name }}"
                                                            data-quantity="{{ $stock->quantity_on_hand }}"
                                                            data-unit-cost="{{ number_format($stock->unit_cost, 2) }}"
                                                            data-status="{{ $stock->status }}"
                                                            data-expiry-date="{{ optional($stock->expiry_date)->format('Y-m-d') }}"
                                                            data-fund-cluster="{{ $stock->fund_cluster }}"
                                                            data-days-to-consume="{{ $stock->days_to_consume }}"
                                                            data-remarks="{{ $stock->remarks }}"
                                                            data-modal-target="editStockModal"
                                                            data-modal-toggle="editStockModal" title="Re‑value stock">
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

                                                        <!-- Stock Card Button - using teal to match header button -->
                                                        <a href="{{ route('stock-cards.show', $stock->supply_id) }}"
                                                            class="p-2 bg-teal-100 text-teal-600 rounded-lg hover:bg-teal-200 focus:outline-none focus:ring-2 focus:ring-teal-300 dark:bg-teal-900 dark:text-teal-300 dark:hover:bg-teal-800 transition-all duration-200"
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

                                                        <!-- Supply Ledger Card Button - keeping purple -->
                                                        <a href="{{ route('supply-ledger-cards.show', $stock->supply_id) }}"
                                                            class="p-2 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 focus:outline-none focus:ring-2 focus:ring-purple-300 dark:bg-purple-900 dark:text-purple-300 dark:hover:bg-purple-800 transition-all duration-200"
                                                            title="View Supply Ledger Card">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                                <path d="M14 2v6h6"></path>
                                                                <path d="M16 13H8"></path>
                                                                <path d="M16 17H8"></path>
                                                                <path d="M10 9H8"></path>
                                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                            </svg>
                                                        </a>

                                                        <!-- Delete Stock -->
                                                        <button type="button"
                                                            class="delete-stock-btn p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-300 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800 transition-all duration-200"
                                                            data-stock-id="{{ $stock->stock_id }}"
                                                            title="Delete stock">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M3 6h18" />
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                                <line x1="10" x2="10" y1="11"
                                                                    y2="17" />
                                                                <line x1="14" x2="14" y1="11"
                                                                    y2="17" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="px-6 py-8 text-center">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <!-- empty‑state SVG + copy -->
                                                        <svg class="w-12 h-12 text-gray-400 mb-4" ...>…</svg>
                                                        <p
                                                            class="text-lg font-medium text-gray-500 dark:text-gray-400">
                                                            No stock entries found</p>
                                                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Get
                                                            started by adding new stock items</p>
                                                        <button type="button" data-modal-target="createStockModal"
                                                            data-modal-toggle="createStockModal"
                                                            class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg transition-colors shadow-sm focus:ring-4 focus:ring-blue-300">
                                                            <svg class="w-4 h-4 mr-2" ...>…</svg> Add Stock
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>

                                </table>

                                <script>
                                    document.addEventListener('DOMContentLoaded', () => {

                                        /* ---------- Flowbite helpers ---------- */
                                        const modals = {
                                            editStockModal: document.getElementById('editStockModal')
                                        };

                                        const show = id => {
                                            if (!modals[id]) return;
                                            if (window.Flowbite?.Modal) {
                                                new window.Flowbite.Modal(modals[id], {
                                                    backdrop: 'static',
                                                    placement: 'center',
                                                    backdropClasses: 'bg-gray-900 bg-opacity-50 fixed inset-0 z-40'
                                                }).show();
                                            } else {
                                                modals[id].classList.remove('hidden');
                                            }
                                        };
                                        const hide = id => {
                                            if (!modals[id]) return;
                                            if (window.Flowbite?.Modal) {
                                                new window.Flowbite.Modal(modals[id]).hide();
                                            } else {
                                                modals[id].classList.add('hidden');
                                            }
                                        };
                                        document.querySelectorAll('[data-modal-hide]').forEach(btn =>
                                            btn.addEventListener('click', () => hide(btn.getAttribute('data-modal-hide')))
                                        );

                                        /* ---------- Edit‑stock (re‑value) ---------- */
                                        document.querySelectorAll('.edit-stock-btn').forEach(btn => {
                                            btn.addEventListener('click', () => {

                                                /* pull dataset */
                                                const d = btn.dataset;

                                                /* hidden fields required by controller */
                                                document.getElementById('edit_supply_id').value  = d.supplyId;

                                                /* visible fields */
                                                document.getElementById('edit_supply_name').value = d.supplyName;
                                                document.getElementById('edit_unit_cost').value  = d.unitCost;
                                                document.getElementById('edit_status').value     = d.status;
                                                document.getElementById('edit_expiry_date').value= d.expiryDate;
                                                document.getElementById('edit_fund_cluster').value = d.fundCluster;
                                                document.getElementById('edit_days_to_consume').value = d.daysToConsume;
                                                document.getElementById('edit_remarks').value    = d.remarks;

                                                show('editStockModal');
                                            });
                                        });

                                        /* ---------- money formatting helper ---------- */
                                        const formatMoney = el => {
                                            let digits = (el.value || '').replace(/\D/g, '') || '0';
                                            el.value = (parseInt(digits, 10) / 100).toLocaleString('en-US', {
                                                minimumFractionDigits: 2, maximumFractionDigits: 2
                                            });
                                        };
                                        const uc = document.getElementById('edit_unit_cost');
                                        if (uc) uc.addEventListener('input', () => formatMoney(uc));
                                    });
                                    </script>


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
                        class="hidden fixed inset-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-full max-h-full flex justify-center items-center bg-gray-900 bg-opacity-50">

                        <div class="relative w-full max-w-3xl max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-800 overflow-hidden">
                                <!-- Modal header -->
                                <div class="flex items-center justify-between p-5 border-b dark:border-gray-700 bg-gradient-to-r from-blue-600 to-blue-800">
                                    <h3 class="text-2xl font-bold text-white flex items-center">
                                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
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
                                <form action="{{ route('stocks.store') }}" method="POST" class="p-6 bg-gray-50 dark:bg-gray-800">
                                    @csrf

                                    <!-- Validation Errors Alert -->
                                    @if ($errors->any() && session('show_create_modal'))
                                        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                                            <div class="font-medium">Oops! There were some problems with your input:</div>
                                            <ul class="mt-1.5 ml-4 list-disc list-inside">
                                                @foreach ($errors->all() as $e)
                                                    <li>{{ $e }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Fill in the information below to add new stock to the inventory.</p>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Left Column -->
                                        <div class="space-y-5">
                                            <!-- Supply Selection Section -->
                                            <div class="p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                                                <h4 class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Supply Information
                                                </h4>

                                                <!-- Supply Selection -->
                                                <div class="mb-4">
                                                    <label for="supply_id" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        Supply Item <span class="text-red-500">*</span>
                                                    </label>
                                                    <div class="relative">
                                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                        <select name="supply_id" id="supply_id" required
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                            focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                            dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                                            dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                            <option value="">Select a supply</option>
                                                            @foreach ($supplies as $s)
                                                                <option value="{{ $s->supply_id }}" data-cost="{{ $s->acquisition_cost }}"
                                                                    {{ old('supply_id') == $s->supply_id ? 'selected' : '' }}>
                                                                    {{ $s->item_name }} ({{ $s->stock_no }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('supply_id')
                                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Quantity & Unit Cost -->
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label for="quantity_on_hand" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Quantity <span class="text-red-500">*</span>
                                                        </label>
                                                        <div class="relative">
                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path>
                                                                </svg>
                                                            </div>
                                                            <input type="number" name="quantity_on_hand" id="quantity_on_hand" min="1" required
                                                                value="{{ old('quantity_on_hand', 0) }}"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                                dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                                        </div>
                                                        @error('quantity_on_hand')
                                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div>
                                                        <label for="unit_cost" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Unit Cost <span class="text-red-500">*</span>
                                                        </label>
                                                        <div class="relative">
                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <input type="text" name="unit_cost" id="unit_cost" required
                                                                value="{{ old('unit_cost', '0.00') }}"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                                dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                                        </div>
                                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Cost per unit in your local currency</p>
                                                        @error('unit_cost')
                                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Status & Expiry Date Section -->
                                            <div class="p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                                                <h4 class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Status Information
                                                </h4>

                                                <!-- Status -->
                                                <div class="mb-4">
                                                    <label for="status" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        Status <span class="text-red-500">*</span>
                                                    </label>
                                                    <div class="relative">
                                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                        <select name="status" id="status" required
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                            focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                            dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                                            dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                                                            <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                                                            <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                                            <option value="depleted" {{ old('status') == 'depleted' ? 'selected' : '' }}>Depleted</option>
                                                        </select>
                                                    </div>
                                                    @error('status')
                                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Expiry Date -->
                                                <div class="mb-4">
                                                    <label for="expiry_date" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        Expiry Date
                                                    </label>
                                                    <div class="relative">
                                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                        <input type="date" name="expiry_date" id="expiry_date"
                                                            value="{{ old('expiry_date') }}"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                            focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                            dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                                    </div>
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Date when this item will expire (if applicable)</p>
                                                    @error('expiry_date')
                                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right Column -->
                                        <div class="space-y-5">
                                            <!-- Fund & Consumption Section -->
                                            <div class="p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                                                <h4 class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Funding & Consumption
                                                </h4>

                                                <!-- Fund Cluster -->
                                                <div class="mb-4">
                                                    <label for="fund_cluster" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        Fund Cluster <span class="text-red-500">*</span>
                                                    </label>
                                                    <div class="relative">
                                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                        <select name="fund_cluster" id="fund_cluster" required
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                            focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                            dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                                            dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                            <option value="101" {{ old('fund_cluster') == '101' ? 'selected' : '' }}>101</option>
                                                            <option value="151" {{ old('fund_cluster') == '151' ? 'selected' : '' }}>151</option>
                                                        </select>
                                                    </div>
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Budget source for this stock</p>
                                                    @error('fund_cluster')
                                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Days to Consume -->
                                                <div class="mb-4">
                                                    <label for="days_to_consume" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        Days to Consume
                                                    </label>
                                                    <div class="relative">
                                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                        <input type="number" name="days_to_consume" id="days_to_consume" min="0"
                                                            value="{{ old('days_to_consume') }}"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                            focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                            dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                                    </div>
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Estimated time until this stock is consumed</p>
                                                    @error('days_to_consume')
                                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Remarks Section -->
                                            <div class="p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                                                <h4 class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Additional Information
                                                </h4>

                                                <!-- Remarks -->
                                                <div class="mb-4">
                                                    <label for="remarks" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        Remarks
                                                    </label>
                                                    <div class="relative">
                                                        <div class="absolute top-3 left-0 flex items-center pl-3 pointer-events-none">
                                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                        <textarea name="remarks" id="remarks" rows="3"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                            focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                            dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                            placeholder="Enter any additional notes or comments">{{ old('remarks') }}</textarea>
                                                    </div>
                                                    @error('remarks')
                                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Notes & Tips -->
                                            <div class="p-4 bg-blue-50 dark:bg-gray-700 rounded-lg border border-blue-200 dark:border-blue-900">
                                                <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-2 flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Important Information
                                                </h4>
                                                <ul class="text-xs text-blue-700 dark:text-blue-300 space-y-1 ml-6 list-disc">
                                                    <li>All fields marked with <span class="text-red-500">*</span> are required</li>
                                                    <li>Select the correct supply item to maintain inventory accuracy</li>
                                                    {{-- <li>The unit cost should match the acquisition cost of the item</li> --}}
                                                    <li>Set appropriate expiry dates for perishable items</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Footer -->
                                    <div class="flex items-center justify-end pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
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
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            Save Stock
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Edit / Re‑value Stock Modal -->
                    <div id="editStockModal" tabindex="-1" aria-hidden="true"
                        class="hidden fixed inset-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-full max-h-full flex justify-center items-center bg-gray-900 bg-opacity-50"
                        data-modal-backdrop="static" data-modal-placement="center">

                        <div class="relative w-full max-w-3xl max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-800 overflow-hidden">
                                <!-- Modal header -->
                                <div class="flex items-center justify-between p-5 border-b dark:border-gray-700 bg-gradient-to-r from-yellow-600 to-yellow-800">
                                    <h3 class="text-2xl font-bold text-white flex items-center">
                                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                        </svg>
                                        Re‑value Stock
                                    </h3>
                                    <button type="button" data-modal-hide="editStockModal"
                                        class="text-white bg-yellow-700 hover:bg-yellow-800 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                        dark:hover:bg-gray-600 transition-all duration-200">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>

                                <!-- Modal body -> Form -->
                                <form id="editStockForm" method="POST" action="{{ route('stocks.update', ['stock' => 0]) }}">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="stock_id" id="edit_stock_id">
                                    <input type="hidden" name="supply_id" id="edit_supply_id">
                                    <input type="hidden" name="department_id" value="{{ auth()->user()->department_id }}">

                                    <div class="p-6 bg-gray-50 dark:bg-gray-800">
                                        <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Update stock information including cost, status, and other details.</p>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <!-- Left Column -->
                                            <div class="space-y-5">
                                                <!-- Supply Information Section -->
                                                <div class="p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                                                    <h4 class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Supply Information
                                                    </h4>

                                                    <!-- Supply (read‑only) -->
                                                    <div class="mb-4">
                                                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Supply Item
                                                        </label>
                                                        <div class="relative">
                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <input type="text" id="edit_supply_name" disabled
                                                                class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                block w-full pl-10 p-2.5
                                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                                dark:text-gray-300" />
                                                        </div>
                                                    </div>

                                                    <!-- New Unit Cost -->
                                                    <div class="mb-4">
                                                        <label for="edit_unit_cost" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            New Unit Cost <span class="text-red-500">*</span>
                                                        </label>
                                                        <div class="relative">
                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <input type="text" name="unit_cost" id="edit_unit_cost" required
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                focus:ring-yellow-500 focus:border-yellow-500 block w-full pl-10 p-2.5
                                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                                dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500" />
                                                        </div>
                                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">New cost per unit in your local currency</p>
                                                    </div>
                                                </div>

                                                <!-- Status Information Section -->
                                                <div class="p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                                                    <h4 class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Status Information
                                                    </h4>

                                                    <!-- Status -->
                                                    <div class="mb-4">
                                                        <label for="edit_status" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Status <span class="text-red-500">*</span>
                                                        </label>
                                                        <div class="relative">
                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <select name="status" id="edit_status" required
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                focus:ring-yellow-500 focus:border-yellow-500 block w-full pl-10 p-2.5
                                                                dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                                                dark:focus:ring-yellow-500 dark:focus:border-yellow-500">
                                                                <option value="available">Available</option>
                                                                <option value="reserved">Reserved</option>
                                                                <option value="expired">Expired</option>
                                                                <option value="depleted">Depleted</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <!-- Expiry Date -->
                                                    <div class="mb-4">
                                                        <label for="edit_expiry_date" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Expiry Date
                                                        </label>
                                                        <div class="relative">
                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <input type="date" name="expiry_date" id="edit_expiry_date"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                focus:ring-yellow-500 focus:border-yellow-500 block w-full pl-10 p-2.5
                                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                                dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500" />
                                                        </div>
                                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Date when this item will expire (if applicable)</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Right Column -->
                                            <div class="space-y-5">
                                                <!-- Fund & Consumption Section -->
                                                <div class="p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                                                    <h4 class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Funding & Consumption
                                                    </h4>

                                                    <!-- Fund Cluster -->
                                                    <div class="mb-4">
                                                        <label for="edit_fund_cluster" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Fund Cluster <span class="text-red-500">*</span>
                                                        </label>
                                                        <div class="relative">
                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <select name="fund_cluster" id="edit_fund_cluster" required
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                focus:ring-yellow-500 focus:border-yellow-500 block w-full pl-10 p-2.5
                                                                dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                                                dark:focus:ring-yellow-500 dark:focus:border-yellow-500">
                                                                <option value="101">101</option>
                                                                <option value="151">151</option>
                                                            </select>
                                                        </div>
                                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Budget source for this stock</p>
                                                    </div>

                                                    <!-- Days to Consume -->
                                                    <div class="mb-4">
                                                        <label for="edit_days_to_consume" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Days to Consume
                                                        </label>
                                                        <div class="relative">
                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <input type="number" name="days_to_consume" id="edit_days_to_consume" min="0"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                focus:ring-yellow-500 focus:border-yellow-500 block w-full pl-10 p-2.5
                                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                                dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500" />
                                                        </div>
                                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Estimated time until this stock is consumed</p>
                                                    </div>
                                                </div>

                                                <!-- Remarks Section -->
                                                <div class="p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                                                    <h4 class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Additional Information
                                                    </h4>

                                                    <!-- Remarks -->
                                                    <div class="mb-4">
                                                        <label for="edit_remarks" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Remarks
                                                        </label>
                                                        <div class="relative">
                                                            <div class="absolute top-3 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            <textarea name="remarks" id="edit_remarks" rows="3"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                focus:ring-yellow-500 focus:border-yellow-500 block w-full pl-10 p-2.5
                                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                                dark:text-white dark:focus:ring-yellow-500 dark:focus:border-yellow-500"
                                                                placeholder="Enter reason for re-valuation or other notes"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Notes & Tips -->
                                                <div class="p-4 bg-yellow-50 dark:bg-gray-700 rounded-lg border border-yellow-200 dark:border-yellow-900">
                                                    <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-300 mb-2 flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Important Information
                                                    </h4>
                                                    <ul class="text-xs text-yellow-700 dark:text-yellow-300 space-y-1 ml-6 list-disc">
                                                        <li>All fields marked with <span class="text-red-500">*</span> are required</li>
                                                        <li>Re-valuation creates a transaction record for auditing</li>
                                                        <li>The original supply item cannot be changed</li>
                                                        <li>Include the reason for re-valuation in remarks</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Footer -->
                                        <div class="flex items-center justify-end pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                                            <button type="button" data-modal-hide="editStockModal"
                                                class="py-2.5 px-5 mr-3 text-sm font-medium text-gray-900 focus:outline-none
                                                    bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-yellow-700
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
                                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                Save Re‑valuation
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>




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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Initialize Flowbite modals
        const modals = {
            createStockModal: document.getElementById('createStockModal'),
            editStockModal: document.getElementById('editStockModal')
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

        // + Add‑Stock button
        document.querySelectorAll('.add-stock-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const {
                    supplyId,
                    unitCost,
                    fundCluster
                } = btn.dataset;
                document.getElementById('supply_id').value = supplyId;
                document.getElementById('fund_cluster').value = fundCluster;
                document.getElementById('unit_cost').value =
                    parseFloat(unitCost).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                // reset other fields
                document.getElementById('quantity_on_hand').value = 0;
                document.getElementById('status').value = 'available';
                document.getElementById('expiry_date').value = '';
                document.getElementById('days_to_consume').value = '';
                document.getElementById('remarks').value = '';
                show('createStockModal');
                document.getElementById('quantity_on_hand').focus();
            });
        });

        // === Edit‑Stock button (updated) ===
        document.querySelectorAll('.edit-stock-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const d = btn.dataset;
                // Set the stock_id in the hidden field
                document.getElementById('edit_stock_id').value = d.stockId;
                document.getElementById('edit_supply_id').value = d.supplyId;
                document.getElementById('edit_supply_name').value = d.supplyName;
                document.getElementById('edit_unit_cost').value = d.unitCost;
                document.getElementById('edit_status').value = d.status;
                document.getElementById('edit_expiry_date').value = d.expiryDate;
                document.getElementById('edit_fund_cluster').value = d.fundCluster;
                document.getElementById('edit_days_to_consume').value = d.daysToConsume;
                document.getElementById('edit_remarks').value = d.remarks;

                // Update form action to point to the correct route
                document.getElementById('editStockForm').action = `/stocks/${d.stockId}`;

                // Show the modal
                show('editStockModal');
            });
        });

        // Delete‑Stock button
        document.querySelectorAll('.delete-stock-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                if (!confirm('Are you sure you want to delete this stock?')) return;
                const id = btn.dataset.stockId;
                fetch(`/supply-stocks/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector(
                            'meta[name="csrf-token"]').content
                    }
                }).then(() => location.reload());
            });
        });

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

</x-app-layout>
