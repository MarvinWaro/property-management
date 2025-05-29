<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detailed RSMI Report by Item - {{ $startDate->format('F Y') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('rsmi.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Back to RSMI
                </a>
                <a href="{{ route('rsmi.export-pdf') }}?month={{ $month }}&fund_cluster={{ $fundCluster }}&format=detailed"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                    <span class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span>Export as PDF</span>
                    </span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach($reportData as $supplyData)
                <!-- Supply Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow mb-6">
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-500 to-indigo-700">
                        <div class="flex justify-between items-center text-white">
                            <div>
                                <h3 class="text-lg font-bold">{{ $supplyData['item_name'] }}</h3>
                                <p class="text-indigo-100">Stock No: {{ $supplyData['stock_no'] }} | Category: {{ $supplyData['category'] }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-indigo-100">Total Quantity Issued</p>
                                <p class="text-2xl font-bold">{{ number_format($supplyData['total_quantity']) }} {{ $supplyData['unit'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5">
                        <!-- Summary Stats -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Average Unit Cost</p>
                                <p class="font-medium text-gray-800 dark:text-white">₱{{ number_format($supplyData['average_unit_cost'], 2) }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Total Cost</p>
                                <p class="font-medium text-gray-800 dark:text-white">₱{{ number_format($supplyData['total_cost'], 2) }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Transactions</p>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $supplyData['transactions']->count() }}</p>
                            </div>
                        </div>

                        <!-- Transaction Details -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-4 py-2">RIS No.</th>
                                        <th scope="col" class="px-4 py-2">Department</th>
                                        <th scope="col" class="px-4 py-2">Date</th>
                                        <th scope="col" class="px-4 py-2 text-right">Quantity</th>
                                        <th scope="col" class="px-4 py-2 text-right">Unit Cost</th>
                                        <th scope="col" class="px-4 py-2 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supplyData['transactions'] as $txn)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <td class="px-4 py-2">{{ $txn['ris_no'] }}</td>
                                            <td class="px-4 py-2">{{ $txn['department'] }}</td>
                                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($txn['date'])->format('M d, Y') }}</td>
                                            <td class="px-4 py-2 text-right">{{ number_format($txn['quantity']) }}</td>
                                            <td class="px-4 py-2 text-right">₱{{ number_format($txn['unit_cost'], 2) }}</td>
                                            <td class="px-4 py-2 text-right font-medium">₱{{ number_format($txn['total'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
