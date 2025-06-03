<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Report of Supplies and Materials Issued - {{ $startDate->format('F Y') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('rsmi.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Back to RSMI
                </a>
                <a href="{{ route('rsmi.export-pdf') }}?month={{ $month }}&fund_cluster={{ $fundCluster }}&department_id={{ $departmentId }}"
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
            <!-- Report Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow mb-6">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-500 to-blue-700">
                    <div class="text-white text-center">
                        <h3 class="text-xl font-bold">REPORT OF SUPPLIES AND MATERIALS ISSUED</h3>
                        <p class="text-blue-100 mt-1">{{ $entityName }}</p>
                        <p class="text-blue-100">Fund Cluster: {{ $fundCluster }}</p>
                        <p class="text-blue-100">Date: {{ $startDate->format('F Y') }}</p>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Items Issued</p>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($summary['total_items']) }}</p>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Cost</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">₱{{ number_format($summary['total_cost'], 2) }}</p>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Unique Supplies</p>
                            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($summary['unique_supplies']) }}</p>
                        </div>
                        <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Departments Served</p>
                            <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($summary['departments_served']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-transparent border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-center font-bold">RIS No.</th>
                                <th scope="col" class="px-4 py-3 text-center font-bold">Responsibility<br>Center Code</th>
                                <th scope="col" class="px-4 py-3 text-center font-bold">Stock No.</th>
                                <th scope="col" class="px-4 py-3 font-bold">Item</th>
                                <th scope="col" class="px-4 py-3 text-center font-bold">Unit</th>
                                <th scope="col" class="px-4 py-3 text-center font-bold">Quantity<br>Issued</th>
                                <th scope="col" class="px-4 py-3 text-center font-bold">Unit Cost</th>
                                <th scope="col" class="px-4 py-3 text-center font-bold">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportData as $risData)
                                @foreach($risData['items'] as $index => $item)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-3 text-center">
                                            @if($index === 0)
                                                {{ $risData['ris_no'] }}
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($index === 0)
                                                {{ $risData['department'] }}
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">{{ $item['stock_no'] }}</td>
                                        <td class="px-4 py-3">{{ $item['item_name'] }}</td>
                                        <td class="px-4 py-3 text-center">{{ $item['unit'] }}</td>
                                        <td class="px-4 py-3 text-center font-medium">{{ number_format($item['quantity_issued']) }}</td>
                                        <td class="px-4 py-3 text-center">{{ number_format($item['unit_cost'], 2) }}</td>
                                        <td class="px-4 py-3 text-center font-medium">{{ number_format($item['total_cost'], 2) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-100 dark:bg-gray-700 font-bold">
                                <td colspan="7" class="px-4 py-3 text-right">GRAND TOTAL:</td>
                                <td class="px-4 py-3 text-center text-blue-600 dark:text-blue-400">
                                    ₱{{ number_format($summary['total_cost'], 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
