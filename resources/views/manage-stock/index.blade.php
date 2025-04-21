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
                                                <td class="px-6 py-4 text-center">
                                                    <div class="flex items-center justify-center space-x-2">
                                                        <!-- + Add Stock -->
                                                        <button type="button" data-modal-target="createStockModal"
                                                            data-modal-toggle="createStockModal"
                                                            class="add-stock-btn inline-flex items-center justify-center w-8 h-8 bg-green-100 text-green-600 rounded-full hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-300 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800 transition-all duration-200"
                                                            data-supply-id  ="{{ $stock->supply_id }}"
                                                            data-unit-cost  ="{{ number_format($stock->unit_cost, 2) }}"
                                                            data-fund-cluster="{{ $stock->fund_cluster }}"
                                                            title="Add stock to {{ $stock->supply->item_name }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                <path
                                                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" />
                                                            </svg>
                                                        </button>

                                                        <!-- Edit Stock (no data-modal- attributes) -->
                                                        <!-- Edit Stock (with data-modal attributes) -->
                                                        <button type="button"
                                                            class="edit-stock-btn p-2 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-yellow-300 dark:bg-yellow-900 dark:text-yellow-300 dark:hover:bg-yellow-800 transition-all duration-200"
                                                            data-stock-id      ="{{ $stock->stock_id }}"
                                                            data-supply-id     ="{{ $stock->supply_id }}"
                                                            data-supply-name   ="{{ $stock->supply->item_name }}"
                                                            data-quantity      ="{{ $stock->quantity_on_hand }}"
                                                            data-unit-cost     ="{{ number_format($stock->unit_cost, 2) }}"
                                                            data-status        ="{{ $stock->status }}"
                                                            data-expiry-date   ="{{ optional($stock->expiry_date)->format('Y-m-d') }}"
                                                            data-fund-cluster  ="{{ $stock->fund_cluster }}"
                                                            data-days-to-consume="{{ $stock->days_to_consume }}"
                                                            data-remarks       ="{{ $stock->remarks }}"
                                                            data-modal-target="editStockModal"
                                                            data-modal-toggle="editStockModal" title="Edit stock">
                                                            <!-- your edit icon SVG here -->
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

                                                        <!-- Delete Stock (no data-modal- attributes) -->
                                                        <button type="button"
                                                            class="delete-stock-btn p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-300 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800 transition-all duration-200"
                                                            data-stock-id="{{ $stock->stock_id }}"
                                                            title="Delete stock">
                                                            <!-- your delete icon SVG here -->
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
                        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 p-4">
                        <div class="relative w-full max-w-2xl max-h-full overflow-auto">
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl">
                                <div
                                    class="flex items-center justify-between p-5 bg-gradient-to-r from-blue-600 to-blue-800 border-b">
                                    <h3 class="text-2xl font-bold text-white">Add New Stock</h3>
                                    <button type="button" data-modal-hide="createStockModal"
                                        class="text-white">✕</button>
                                </div>

                                <form action="{{ route('stocks.store') }}" method="POST"
                                    class="p-6 bg-gray-50 dark:bg-gray-800">
                                    @csrf

                                    @if ($errors->any() && session('show_create_modal'))
                                        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                                            <ul class="list-disc list-inside">
                                                @foreach ($errors->all() as $e)
                                                    <li>{{ $e }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="grid gap-4">
                                        <!-- Supply Selection -->
                                        <div>
                                            <label for="supply_id"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Supply Item <span class="text-red-500">*</span>
                                            </label>
                                            <select name="supply_id" id="supply_id" required
                                                class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Select a supply</option>
                                                @foreach ($supplies as $s)
                                                    <option value="{{ $s->supply_id }}"
                                                        data-cost="{{ $s->acquisition_cost }}"
                                                        {{ old('supply_id') == $s->supply_id ? 'selected' : '' }}>
                                                        {{ $s->item_name }} ({{ $s->stock_no }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Quantity & Unit Cost -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="quantity_on_hand"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Quantity <span class="text-red-500">*</span>
                                                </label>
                                                <input type="number" name="quantity_on_hand" id="quantity_on_hand"
                                                    min="1" required value="{{ old('quantity_on_hand', 0) }}"
                                                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" />
                                            </div>
                                            <div>
                                                <label for="unit_cost"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Unit Cost <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="unit_cost" id="unit_cost" required
                                                    value="{{ old('unit_cost', '0.00') }}"
                                                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" />
                                            </div>
                                        </div>

                                        <!-- Status & Expiry Date -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="status"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Status <span class="text-red-500">*</span>
                                                </label>
                                                <select name="status" id="status" required
                                                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
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
                                            </div>
                                            <div>
                                                <label for="expiry_date"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Expiry Date
                                                </label>
                                                <input type="date" name="expiry_date" id="expiry_date"
                                                    value="{{ old('expiry_date') }}"
                                                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" />
                                            </div>
                                        </div>

                                        <!-- Fund Cluster & Days to Consume -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="fund_cluster"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Fund Cluster <span class="text-red-500">*</span>
                                                </label>
                                                <select name="fund_cluster" id="fund_cluster" required
                                                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                                    <option value="101"
                                                        {{ old('fund_cluster') == '101' ? 'selected' : '' }}>101
                                                    </option>
                                                    <option value="151"
                                                        {{ old('fund_cluster') == '151' ? 'selected' : '' }}>151
                                                    </option>
                                                </select>
                                            </div>
                                            <div>
                                                <label for="days_to_consume"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Days to Consume
                                                </label>
                                                <input type="number" name="days_to_consume" id="days_to_consume"
                                                    min="0" value="{{ old('days_to_consume') }}"
                                                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" />
                                            </div>
                                        </div>

                                        <!-- Remarks -->
                                        <div>
                                            <label for="remarks"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Remarks
                                            </label>
                                            <textarea name="remarks" id="remarks" rows="3"
                                                class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">{{ old('remarks') }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Modal Footer -->
                                    <div class="flex items-center justify-end mt-6 space-x-3 border-t pt-4">
                                        <button type="button" data-modal-hide="createStockModal"
                                            class="px-4 py-2 bg-white border rounded-lg hover:bg-gray-100">
                                            Cancel
                                        </button>
                                        <button type="submit"
                                            class="px-5 py-2 bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg">
                                            Save Stock
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Stock Modal -->
                    <div id="editStockModal" tabindex="-1" aria-hidden="true"
                        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 p-4"
                        data-modal-backdrop="static" data-modal-placement="center">
                        <div class="relative w-full max-w-2xl max-h-full overflow-auto">
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl">
                                <div
                                    class="flex items-center justify-between p-5 bg-gradient-to-r from-yellow-600 to-yellow-800 border-b">
                                    <h3 class="text-2xl font-bold text-white">Edit Stock</h3>
                                    <button type="button" data-modal-hide="editStockModal"
                                        class="text-white text-2xl leading-none">×</button>
                                </div>

                                <form id="editStockForm" method="POST" class="p-6 bg-gray-50 dark:bg-gray-800">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="stock_id" id="edit_stock_id">

                                    @if ($errors->any() && session('show_edit_modal'))
                                        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                                            <ul class="list-disc list-inside">
                                                @foreach ($errors->all() as $e)
                                                    <li>{{ $e }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="grid gap-4">
                                        <!-- Supply Item (read‑only) -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Supply
                                                Item</label>
                                            <input type="text" id="edit_supply_name" disabled
                                                class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-100 dark:bg-gray-700" />
                                            <input type="hidden" name="supply_id" id="edit_supply_id" />
                                        </div>

                                        <!-- Unit Cost -->
                                        <div>
                                            <label for="edit_unit_cost"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Unit Cost <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="unit_cost" id="edit_unit_cost" required
                                                class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-yellow-500 focus:border-yellow-500" />
                                        </div>

                                        <!-- Status & Expiry Date -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="edit_status"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Status <span class="text-red-500">*</span>
                                                </label>
                                                <select name="status" id="edit_status" required
                                                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-yellow-500 focus:border-yellow-500">
                                                    <option value="available">Available</option>
                                                    <option value="reserved">Reserved</option>
                                                    <option value="expired">Expired</option>
                                                    <option value="depleted">Depleted</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label for="edit_expiry_date"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Expiry Date
                                                </label>
                                                <input type="date" name="expiry_date" id="edit_expiry_date"
                                                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-yellow-500 focus:border-yellow-500" />
                                            </div>
                                        </div>

                                        <!-- Fund Cluster & Days to Consume -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="edit_fund_cluster"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Fund Cluster <span class="text-red-500">*</span>
                                                </label>
                                                <select name="fund_cluster" id="edit_fund_cluster" required
                                                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-yellow-500 focus:border-yellow-500">
                                                    <option value="101">101</option>
                                                    <option value="151">151</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label for="edit_days_to_consume"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Days to Consume
                                                </label>
                                                <input type="number" name="days_to_consume"
                                                    id="edit_days_to_consume" min="0"
                                                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-yellow-500 focus:border-yellow-500" />
                                            </div>
                                        </div>

                                        <!-- Remarks -->
                                        <div>
                                            <label for="edit_remarks"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Remarks
                                            </label>
                                            <textarea name="remarks" id="edit_remarks" rows="3"
                                                class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-yellow-500 focus:border-yellow-500"></textarea>
                                        </div>
                                    </div>

                                    <!-- Modal Footer -->
                                    <div class="flex items-center justify-end mt-6 space-x-3 border-t pt-4">
                                        <button type="button" data-modal-hide="editStockModal"
                                            class="px-4 py-2 bg-white border rounded-lg hover:bg-gray-100">
                                            Cancel
                                        </button>
                                        <button type="submit"
                                            class="px-5 py-2 bg-gradient-to-r from-yellow-500 to-yellow-700 text-white rounded-lg">
                                            Save Changes
                                        </button>
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

            // === Edit‑Stock button (fixed) ===
            document.querySelectorAll('.edit-stock-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const d = btn.dataset;
                    // populate the modal fields
                    document.getElementById('edit_stock_id').value = d.stockId;
                    document.getElementById('edit_supply_id').value = d.supplyId;
                    document.getElementById('edit_supply_name').value = d.supplyName;
                    document.getElementById('edit_unit_cost').value = d.unitCost;
                    document.getElementById('edit_status').value = d.status;
                    document.getElementById('edit_expiry_date').value = d.expiryDate;
                    document.getElementById('edit_fund_cluster').value = d.fundCluster;
                    document.getElementById('edit_days_to_consume').value = d.daysToConsume;
                    document.getElementById('edit_remarks').value = d.remarks;

                    // point the form at the correct route: PUT /supply-stocks/{id}
                    document.getElementById('editStockForm').action = `/supply-stocks/${d.stockId}`;

                    // show the modal
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
