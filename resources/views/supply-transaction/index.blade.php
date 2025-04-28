<!-- resources/views/supply-transaction/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Supply Transactions') }}
            </h2>
            <span class="text-sm text-gray-600 dark:text-gray-400">
                Showing all inventory movements
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                <!-- Filter Panel -->
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Transaction Filters</h3>

                    <form method="GET" action="{{ route('supply-transactions.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- From Date -->
                            <div>
                                <label for="from" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">From Date</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                        </svg>
                                    </div>
                                    <input type="date" id="from" name="from" value="{{ request('from') }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Select date">
                                </div>
                            </div>

                            <!-- To Date -->
                            <div>
                                <label for="to" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">To Date</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                        </svg>
                                    </div>
                                    <input type="date" id="to" name="to" value="{{ request('to') }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Select date">
                                </div>
                            </div>

                            <!-- Transaction Type -->
                            <div>
                                <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Transaction Type</label>
                                <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="">All Types</option>
                                    <option value="receipt" @selected(request('type')=='receipt')>Receipt (IN)</option>
                                    <option value="issue" @selected(request('type')=='issue')>Issue (OUT)</option>
                                    <option value="adjustment" @selected(request('type')=='adjustment')>Adjustment</option>
                                </select>
                            </div>

                            <!-- Buttons -->
                            <div class="flex items-end gap-2">
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Apply Filters
                                </button>

                                @if(request('from') || request('to') || request('type'))
                                    <a href="{{ route('supply-transactions.index') }}"
                                        class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                        Clear Filters
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <div class="p-5">
                    <!-- Transaction Legend -->
                    <div class="flex flex-wrap gap-4 mb-4 items-center">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Transaction Types:</span>

                        <span class="flex items-center">
                            <span class="inline-block w-3 h-3 me-1 rounded-full bg-green-500 dark:bg-green-400"></span>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Receipt (IN)</span>
                        </span>

                        <span class="flex items-center">
                            <span class="inline-block w-3 h-3 me-1 rounded-full bg-red-500 dark:bg-red-400"></span>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Issue (OUT)</span>
                        </span>

                        <span class="flex items-center">
                            <span class="inline-block w-3 h-3 me-1 rounded-full bg-yellow-500 dark:bg-yellow-400"></span>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Adjustment</span>
                        </span>

                        <span class="ms-auto inline-flex items-center justify-center text-xs font-semibold px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                            {{ $txns->total() }} Transactions
                        </span>
                    </div>

                    <!-- Transactions Table -->
                    <div class="relative overflow-x-auto sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-white uppercase bg-blue-600 dark:bg-blue-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3">ID</th>
                                    <th scope="col" class="px-6 py-3">Date & Time</th>
                                    <th scope="col" class="px-6 py-3">Type</th>
                                    <th scope="col" class="px-6 py-3">Stock No</th>
                                    <th scope="col" class="px-6 py-3">Item Name</th>
                                    <th scope="col" class="px-6 py-3 text-right">Qty</th>
                                    <th scope="col" class="px-6 py-3 text-right">Balance</th>
                                    <th scope="col" class="px-6 py-3">Ref No</th>
                                    <th scope="col" class="px-6 py-3">Department</th>
                                    <th scope="col" class="px-6 py-3">User/Staffs</th>
                                    <th scope="col" class="px-6 py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($txns as $txn)
                                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700
                                            {{ $txn->transaction_type === 'receipt' ? 'bg-green-50 dark:bg-green-900/20' : '' }}
                                            {{ $txn->transaction_type === 'issue' ? 'bg-red-50 dark:bg-red-900/20' : '' }}
                                            {{ $txn->transaction_type === 'adjustment' ? 'bg-yellow-50 dark:bg-yellow-900/20' : '' }}">
                                        <td class="px-6 py-4 font-medium">{{ $txn->transaction_id }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span>{{ $txn->transaction_date->format('M d, Y') }}</span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $txn->created_at->format('h:i A') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($txn->transaction_type === 'receipt')
                                                <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">
                                                    Receipt
                                                </span>
                                            @elseif($txn->transaction_type === 'issue')
                                                <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">
                                                    Issue
                                                </span>
                                            @else
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300">
                                                    Adjustment
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 font-mono">{{ $txn->supply->stock_no }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $txn->supply->item_name }}</td>
                                        <td class="px-6 py-4 text-right font-medium">
                                            @if($txn->transaction_type === 'receipt')
                                                <span class="font-semibold text-green-600 dark:text-green-400">+{{ number_format($txn->quantity) }}</span>
                                            @elseif($txn->transaction_type === 'issue')
                                                <span class="font-semibold text-red-600 dark:text-red-400">-{{ number_format($txn->quantity) }}</span>
                                            @else
                                                <span class="font-semibold text-yellow-600 dark:text-yellow-400">{{ number_format($txn->quantity) }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right font-medium text-gray-900 dark:text-white">{{ number_format($txn->balance_quantity) }}</td>
                                        <td class="px-6 py-4">{{ $txn->reference_no }}</td>
                                        <td class="px-6 py-4">{{ $txn->department->name }}</td>
                                        <td class="px-6 py-4">{{ $txn->user->name }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <button type="button"
                                                class="view-transaction-btn p-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-300 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800 transition-all duration-200"
                                                data-transaction-id="{{ $txn->transaction_id }}"
                                                data-transaction-date="{{ $txn->transaction_date->format('M d, Y') }}"
                                                data-transaction-time="{{ $txn->created_at->format('h:i A') }}"
                                                data-transaction-type="{{ $txn->transaction_type }}"
                                                data-supply-name="{{ $txn->supply->item_name }}"
                                                data-stock-no="{{ $txn->supply->stock_no }}"
                                                data-quantity="{{ $txn->quantity }}"
                                                data-unit-cost="{{ number_format($txn->unit_cost, 2) }}"
                                                data-total-cost="{{ number_format($txn->total_cost, 2) }}"
                                                data-balance="{{ $txn->balance_quantity }}"
                                                data-reference="{{ $txn->reference_no }}"
                                                data-department="{{ $txn->department->name }}"
                                                data-user="{{ $txn->user->name }}"
                                                data-remarks="{{ $txn->remarks }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-eye">
                                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td colspan="11" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                                </svg>
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">No transactions found</h3>
                                                <p class="text-gray-500 dark:text-gray-400 mt-2">Try adjusting your filters to find what you're looking for.</p>
                                                @if(request('from') || request('to') || request('type'))
                                                    <a href="{{ route('supply-transactions.index') }}" class="mt-4 inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                        Clear Filters
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Transaction Details Modal -->
                    <div id="viewTransactionModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-full max-h-full flex justify-center items-center bg-gray-900 bg-opacity-50">
                        <div class="relative w-full max-w-2xl max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-800 overflow-hidden">
                                <!-- Modal header -->
                                <div id="modal-header" class="flex items-center justify-between p-5 border-b dark:border-gray-700 bg-gradient-to-r from-blue-600 to-blue-800">
                                    <h3 class="text-2xl font-bold text-white flex items-center">
                                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span id="modal-title">Transaction Details</span>
                                    </h3>
                                    <button type="button" data-modal-hide="viewTransactionModal"
                                        class="text-white bg-blue-700 hover:bg-blue-800 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                        dark:hover:bg-gray-600 transition-all duration-200">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>

                                <!-- Modal body -->
                                <div class="p-6 bg-gray-50 dark:bg-gray-800">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Left Column -->
                                        <div class="space-y-5">
                                            <!-- Transaction Information Section -->
                                            <div class="p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                                                <h4 class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Transaction Information
                                                </h4>

                                                <div class="grid grid-cols-1 gap-3">
                                                    <div class="flex flex-col">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">Transaction Date & Time</span>
                                                        <div class="font-medium text-gray-900 dark:text-white">
                                                            <span id="tx-date"></span>
                                                            <span class="text-sm ml-2 text-gray-600 dark:text-gray-400" id="tx-time"></span>
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">Type</span>
                                                        <div id="tx-type-container"></div>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">Reference No.</span>
                                                        <span id="tx-reference" class="font-medium text-gray-900 dark:text-white"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Supply Information Section -->
                                            <div class="p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                                                <h4 class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Supply Information
                                                </h4>

                                                <div class="grid grid-cols-1 gap-3">
                                                    <div class="flex flex-col">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">Item Name</span>
                                                        <span id="tx-item-name" class="font-medium text-gray-900 dark:text-white"></span>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">Stock No.</span>
                                                        <span id="tx-stock-no" class="font-mono font-medium text-gray-900 dark:text-white"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right Column -->
                                        <div class="space-y-5">
                                            <!-- Quantity & Cost Information -->
                                            <div class="p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                                                <h4 class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Quantity & Cost
                                                </h4>

                                                <div class="grid grid-cols-1 gap-3">
                                                    <div class="flex flex-col">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">Quantity</span>
                                                        <span id="tx-quantity" class="font-medium text-gray-900 dark:text-white"></span>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">Unit Cost</span>
                                                        <span id="tx-unit-cost" class="font-medium text-gray-900 dark:text-white"></span>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">Total Cost</span>
                                                        <span id="tx-total-cost" class="font-medium text-gray-900 dark:text-white"></span>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">Balance After Transaction</span>
                                                        <span id="tx-balance" class="font-medium text-gray-900 dark:text-white"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Personnel Section -->
                                            <div class="p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                                                <h4 class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Personnel Information
                                                </h4>

                                                <div class="grid grid-cols-1 gap-3">
                                                    <div class="flex flex-col">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">Department</span>
                                                        <span id="tx-department" class="font-medium text-gray-900 dark:text-white"></span>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">User/Staff</span>
                                                        <span id="tx-user" class="font-medium text-gray-900 dark:text-white"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Remarks Section -->
                                    <div class="mt-6 p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                                        <h4 class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Remarks
                                        </h4>

                                        <div id="tx-remarks-container" class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                                            <p id="tx-remarks" class="text-gray-600 dark:text-gray-400"></p>
                                        </div>
                                    </div>

                                    <!-- Modal Footer -->
                                    <div class="flex items-center justify-end pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                                        <button type="button" data-modal-hide="viewTransactionModal"
                                            class="text-white bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900
                                                focus:ring-4 focus:outline-none focus:ring-blue-300
                                                font-medium rounded-lg text-sm px-5 py-2.5 text-center
                                                dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-all duration-200">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- JavaScript for modal functionality -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Get all view buttons
                            const viewButtons = document.querySelectorAll('.view-transaction-btn');
                            const modal = document.getElementById('viewTransactionModal');
                            const modalHeader = document.getElementById('modal-header');

                            // Close modal functionality
                            const closeButtons = document.querySelectorAll('[data-modal-hide="viewTransactionModal"]');
                            closeButtons.forEach(button => {
                                button.addEventListener('click', () => {
                                    modal.classList.add('hidden');
                                });
                            });

                            // Add click event to all view buttons
                            viewButtons.forEach(button => {
                                button.addEventListener('click', function() {
                                    // Get transaction data from data attributes
                                    const data = this.dataset;

                                    // Update modal title based on transaction type
                                    const typeTitle = data.transactionType.charAt(0).toUpperCase() + data.transactionType.slice(1);
                                    document.getElementById('modal-title').textContent = typeTitle + ' Transaction Details';

                                    // Change header color based on transaction type
                                    modalHeader.className = 'flex items-center justify-between p-5 border-b dark:border-gray-700 bg-gradient-to-r';
                                    if (data.transactionType === 'receipt') {
                                        modalHeader.classList.add('from-green-600', 'to-green-800');
                                    } else if (data.transactionType === 'issue') {
                                        modalHeader.classList.add('from-red-600', 'to-red-800');
                                    } else {
                                        modalHeader.classList.add('from-yellow-600', 'to-yellow-800');
                                    }

                                    // Populate transaction type badge
                                    const typeContainer = document.getElementById('tx-type-container');
                                    let typeBadge = '';
                                    if (data.transactionType === 'receipt') {
                                        typeBadge = '<span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">Receipt</span>';
                                    } else if (data.transactionType === 'issue') {
                                        typeBadge = '<span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">Issue</span>';
                                    } else {
                                        typeBadge = '<span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300">Adjustment</span>';
                                    }
                                    typeContainer.innerHTML = typeBadge;

                                    // Format quantity display
                                    let qtyDisplay = data.quantity;
                                    if (data.transactionType === 'receipt') {
                                        qtyDisplay = '<span class="font-semibold text-green-600 dark:text-green-400">+' + data.quantity + '</span>';
                                    } else if (data.transactionType === 'issue') {
                                        qtyDisplay = '<span class="font-semibold text-red-600 dark:text-red-400">-' + data.quantity + '</span>';
                                    } else {
                                        qtyDisplay = '<span class="font-semibold text-yellow-600 dark:text-yellow-400">' + data.quantity + '</span>';
                                    }

                                    // Set other transaction details
                                    document.getElementById('tx-date').textContent = data.transactionDate;
                                    document.getElementById('tx-time').textContent = data.transactionTime;
                                    document.getElementById('tx-reference').textContent = data.reference;
                                    document.getElementById('tx-item-name').textContent = data.supplyName;
                                    document.getElementById('tx-stock-no').textContent = data.stockNo;
                                    document.getElementById('tx-quantity').innerHTML = qtyDisplay;
                                    document.getElementById('tx-unit-cost').textContent = '₱ ' + data.unitCost;
                                    document.getElementById('tx-total-cost').textContent = '₱ ' + data.totalCost;
                                    document.getElementById('tx-balance').textContent = data.balance;
                                    document.getElementById('tx-department').textContent = data.department;
                                    document.getElementById('tx-user').textContent = data.user;

                                    // Set remarks or display "No remarks" message
                                    if (data.remarks && data.remarks.trim() !== '') {
                                        document.getElementById('tx-remarks').textContent = data.remarks;
                                    } else {
                                        document.getElementById('tx-remarks').innerHTML = '<span class="italic text-gray-400 dark:text-gray-500">No remarks provided</span>';
                                    }

                                    // Show the modal
                                    modal.classList.remove('hidden');
                                });
                            });

                            // Close modal when clicking outside
                            modal.addEventListener('click', function(event) {
                                if (event.target === modal) {
                                    modal.classList.add('hidden');
                                }
                            });
                        });
                    </script>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $txns->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
