<!-- stock-cards/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Stock Card: {{ $supply->item_name }}
            </h2>
            <a href="{{ route('stock-cards.export-pdf', $supply->supply_id) }}?fund_cluster={{ $fundCluster }}"
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
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Supply Details Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow mb-6">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-500 to-blue-700">
                    <div class="flex flex-wrap justify-between items-center">
                        <div class="text-white">
                            <h3 class="text-xl font-bold">{{ $supply->item_name }}</h3>
                            <p class="text-blue-100">Stock No: {{ $supply->stock_no }}</p>
                        </div>
                        <div class="mt-2 md:mt-0">
                            <form method="GET" action="{{ route('stock-cards.show', $supply->supply_id) }}" class="flex space-x-2">
                                <select name="fund_cluster" onchange="this.form.submit()"
                                    class="px-4 py-2 rounded-lg text-sm bg-blue-600 text-white border border-blue-400">
                                    @foreach($fundClusters as $fc)
                                        <option value="{{ $fc }}" {{ $fundCluster == $fc ? 'selected' : '' }}>
                                            Fund Cluster: {{ $fc }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Unit</p>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $supply->unit_of_measurement }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Category</p>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $supply->category->name ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Current Balance</p>
                            <p class="font-medium text-gray-800 dark:text-white">{{ number_format($currentStock) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Card Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Stock Card</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Entity Name: COMMISSION ON HIGHER EDUCATION REGIONAL OFFICE XII &nbsp;&nbsp;|&nbsp;&nbsp;
                        Fund Cluster: {{ $fundCluster }}
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Date</th>
                                <th scope="col" class="px-6 py-3">Reference</th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    <div>Receipt</div>
                                    <div class="text-xs font-normal">Qty.</div>
                                </th>
                                <th scope="col" colspan="2" class="px-6 py-3 text-center">
                                    <div>Issue</div>
                                    <div class="grid grid-cols-2 text-xs font-normal">
                                        <div>Qty.</div>
                                        <div>Office</div>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    <div>Balance</div>
                                    <div class="text-xs font-normal">Qty.</div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-right">Days to Consume</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stockCardEntries as $entry)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($entry['date'])->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($entry['reference'] === 'Beginning Balance')
                                            <span class="font-medium text-gray-800 dark:text-white">
                                                {{ $entry['reference'] }}
                                            </span>
                                        @else
                                            {{ $entry['reference'] }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($entry['receipt_qty'])
                                            <span class="font-medium text-green-600 dark:text-green-400">
                                                {{ number_format($entry['receipt_qty']) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($entry['issue_qty'])
                                            <span class="font-medium text-red-600 dark:text-red-400">
                                                {{ number_format($entry['issue_qty']) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $entry['issue_office'] }}
                                    </td>
                                    <td class="px-6 py-4 text-center font-medium text-gray-800 dark:text-white">
                                        {{ number_format($entry['balance_qty']) }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        {{ $entry['days_to_consume'] ?? 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No transactions found for this supply.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
