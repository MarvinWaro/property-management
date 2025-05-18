<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Supply Ledger Card: {{ $supply->item_name }}
            </h2>
                <a href="{{ route('supply-ledger-cards.export-pdf', $supply->supply_id) }}?fund_cluster={{ $fundCluster }}&year={{ $selectedYear }}"
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
                        <div class="mt-2 md:mt-0 flex space-x-2">
                            <form method="GET" action="{{ route('supply-ledger-cards.show', $supply->supply_id) }}" class="flex space-x-2">
                                <input type="hidden" name="fund_cluster" value="{{ $fundCluster }}">
                                <select name="year" onchange="this.form.submit()"
                                    class="px-4 py-2 rounded-lg text-sm bg-blue-600 text-white border border-blue-400">
                                    @foreach($availableYears as $year)
                                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                            Year: {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                            <form method="GET" action="{{ route('supply-ledger-cards.show', $supply->supply_id) }}" class="flex space-x-2">
                                <input type="hidden" name="year" value="{{ $selectedYear }}">
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

            <!-- Supply Ledger Card Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Supply Ledger Card - {{ $selectedYear }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Entity Name: COMMISSION ON HIGHER EDUCATION REGIONAL OFFICE XII &nbsp;&nbsp;|&nbsp;&nbsp;
                        Fund Cluster: {{ $fundCluster }}
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead>
                            <!-- Main header row with column groups -->
                            <tr class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                <th scope="col" class="px-4 py-3 border-b border-r border-gray-200 dark:border-gray-600 text-center" rowspan="2">DATE</th>
                                <th scope="col" class="px-4 py-3 border-b border-r border-gray-200 dark:border-gray-600 text-center" rowspan="2">REFERENCE</th>
                                <th scope="col" colspan="3" class="px-4 py-3 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-green-50 dark:bg-green-900/20">
                                    RECEIPT
                                </th>
                                <th scope="col" colspan="3" class="px-4 py-3 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-red-50 dark:bg-red-900/20">
                                    ISSUE
                                </th>
                                <th scope="col" colspan="3" class="px-4 py-3 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-blue-50 dark:bg-blue-900/20">
                                    BALANCE
                                </th>
                                <th scope="col" class="px-4 py-3 border-b border-gray-200 dark:border-gray-600 text-center" rowspan="2">
                                    DAYS TO<br />CONSUME
                                </th>
                            </tr>

                            <!-- Subheader for column details -->
                            <tr class="text-xs text-gray-700 bg-gray-50 dark:bg-gray-700/70 dark:text-gray-400">
                                <!-- Receipt columns -->
                                <th scope="col" class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-green-50/50 dark:bg-green-900/10">QTY.</th>
                                <th scope="col" class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-green-50/50 dark:bg-green-900/10">UNIT COST</th>
                                <th scope="col" class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-green-50/50 dark:bg-green-900/10">TOTAL COST</th>
                                <!-- Issue columns -->
                                <th scope="col" class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-red-50/50 dark:bg-red-900/10">QTY.</th>
                                <th scope="col" class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-red-50/50 dark:bg-red-900/10">UNIT COST</th>
                                <th scope="col" class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-red-50/50 dark:bg-red-900/10">TOTAL COST</th>
                                <!-- Balance columns -->
                                <th scope="col" class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-blue-50/50 dark:bg-blue-900/10">QTY.</th>
                                <th scope="col" class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-blue-50/50 dark:bg-blue-900/10">UNIT COST</th>
                                <th scope="col" class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-blue-50/50 dark:bg-blue-900/10">TOTAL COST</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ledgerCardEntries as $entry)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-3 whitespace-nowrap border-r border-gray-100 dark:border-gray-700">
                                        {{ \Carbon\Carbon::parse($entry['date'])->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-3 border-r border-gray-100 dark:border-gray-700">
                                        @if($entry['reference'] === 'Beginning Balance')
                                            <span class="font-medium text-gray-800 dark:text-white">
                                                {{ $entry['reference'] }}
                                            </span>
                                        @else
                                            {{ $entry['reference'] }}
                                        @endif
                                    </td>
                                    <!-- Receipt columns -->
                                    <td class="px-4 py-3 text-center bg-green-50/30 dark:bg-green-900/5 border-r border-gray-100 dark:border-gray-700">
                                        @if($entry['receipt_qty'])
                                            <span class="font-medium text-green-600 dark:text-green-400">
                                                {{ number_format($entry['receipt_qty']) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center bg-green-50/30 dark:bg-green-900/5 border-r border-gray-100 dark:border-gray-700">
                                        @if($entry['receipt_unit_cost'])
                                            <span class="font-medium text-green-600 dark:text-green-400">
                                                {{ number_format($entry['receipt_unit_cost'], 2) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center bg-green-50/30 dark:bg-green-900/5 border-r border-gray-100 dark:border-gray-700">
                                        @if($entry['receipt_total_cost'])
                                            <span class="font-medium text-green-600 dark:text-green-400">
                                                ₱{{ number_format($entry['receipt_total_cost'], 2) }}
                                            </span>
                                        @endif
                                    </td>
                                    <!-- Issue columns -->
                                    <td class="px-4 py-3 text-center bg-red-50/30 dark:bg-red-900/5 border-r border-gray-100 dark:border-gray-700">
                                        @if($entry['issue_qty'])
                                            <span class="font-medium text-red-600 dark:text-red-400">
                                                {{ number_format($entry['issue_qty']) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center bg-red-50/30 dark:bg-red-900/5 border-r border-gray-100 dark:border-gray-700">
                                        @if($entry['issue_unit_cost'])
                                            <span class="font-medium text-red-600 dark:text-red-400">
                                                {{ number_format($entry['issue_unit_cost'], 2) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center bg-red-50/30 dark:bg-red-900/5 border-r border-gray-100 dark:border-gray-700">
                                        @if($entry['issue_total_cost'])
                                            <span class="font-medium text-red-600 dark:text-red-400">
                                                ₱{{ number_format($entry['issue_total_cost'], 2) }}
                                            </span>
                                        @endif
                                    </td>
                                    <!-- Balance columns -->
                                    <td class="px-4 py-3 text-center font-medium text-gray-800 dark:text-white bg-blue-50/30 dark:bg-blue-900/5 border-r border-gray-100 dark:border-gray-700">
                                        {{ number_format($entry['balance_qty']) }}
                                    </td>
                                    <!-- Balance unit cost column -->
                                    <!-- Balance unit cost column -->
                                    <td class="px-4 py-3 text-center font-medium text-gray-800 dark:text-white bg-blue-50/30 dark:bg-blue-900/5 border-r border-gray-100 dark:border-gray-700">
                                        @if($entry['balance_unit_cost'] !== null && $entry['balance_qty'] > 0)
                                            {{ number_format($entry['balance_unit_cost'], 2) }}
                                        @else
                                            0.00
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center font-medium text-gray-800 dark:text-white bg-blue-50/30 dark:bg-blue-900/5 border-r border-gray-100 dark:border-gray-700">
                                        ₱{{ number_format($entry['balance_total_cost'], 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        {{ $entry['days_to_consume'] ?? 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
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
