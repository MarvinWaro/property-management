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
                                    <th scope="col" class="px-6 py-3">Date</th>
                                    <th scope="col" class="px-6 py-3">Type</th>
                                    <th scope="col" class="px-6 py-3">Stock No</th>
                                    <th scope="col" class="px-6 py-3">Item Name</th>
                                    <th scope="col" class="px-6 py-3 text-right">Qty</th>
                                    <th scope="col" class="px-6 py-3 text-right">Balance</th>
                                    <th scope="col" class="px-6 py-3">Ref No</th>
                                    <th scope="col" class="px-6 py-3">Department</th>
                                    <th scope="col" class="px-6 py-3">User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($txns as $txn)
                                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700
                                              {{ $txn->transaction_type === 'receipt' ? 'bg-green-50 dark:bg-green-900/20' : '' }}
                                              {{ $txn->transaction_type === 'issue' ? 'bg-red-50 dark:bg-red-900/20' : '' }}
                                              {{ $txn->transaction_type === 'adjustment' ? 'bg-yellow-50 dark:bg-yellow-900/20' : '' }}">
                                        <td class="px-6 py-4">{{ $txn->transaction_date->format('M d, Y') }}</td>
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
                                    </tr>
                                @empty
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td colspan="9" class="px-6 py-12 text-center">
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

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $txns->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
