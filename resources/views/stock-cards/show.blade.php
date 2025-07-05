<!-- stock-cards/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <!-- Replace your current single button with this section -->
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Stock Card: {{ $supply->item_name }}
            </h2>

            <div class="flex space-x-2">
                <!-- PDF Export Button -->
                <a href="{{ route('stock-cards.export-pdf', $supply->supply_id) }}?fund_cluster={{ $fundCluster }}&year={{ $selectedYear }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                    <span class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span>Export as PDF</span>
                    </span>
                </a>

                <!-- Excel Export Button -->
                <a href="{{ route('stock-cards.export-excel', $supply->supply_id) }}?fund_cluster={{ $fundCluster }}&year={{ $selectedYear }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                    <span class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Export as Excel</span>
                    </span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Supply Details Card (Palette Styled) -->
            <div
                class="bg-white dark:bg-[#0a0a0a] overflow-hidden rounded-2xl border border-[#e5e7eb] dark:border-[#374151] shadow mb-6">
                <!-- Header -->
                <div class="p-5 border-b border-[#e5e7eb] dark:border-[#374151] bg-[#a01b1a] dark:bg-[#a01b1a]">
                    <div class="flex flex-wrap justify-between items-center">
                        <div class="text-white">
                            <h3 class="text-xl font-bold">{{ $supply->item_name }}</h3>
                            <p class="text-[#f9fafb] opacity-70">Stock No: {{ $supply->stock_no }}</p>
                        </div>
                        <div class="mt-2 md:mt-0 flex space-x-2">
                            <!-- Year Selector -->
                            <form method="GET" action="{{ route('stock-cards.show', $supply->supply_id) }}"
                                class="flex space-x-2">
                                <input type="hidden" name="fund_cluster" value="{{ $fundCluster }}">
                                <input type="hidden" name="month" value="{{ $selectedMonth }}">
                                <select name="year" onchange="this.form.submit()"
                                    class="px-4 py-2 rounded-lg text-sm
                                        bg-white text-[#a01b1a] border border-[#a01b1a]
                                        focus:outline-none focus:ring-2 focus:ring-[#a01b1a]">
                                    @foreach ($availableYears as $year)
                                        <option value="{{ $year }}"
                                            {{ $selectedYear == $year ? 'selected' : '' }}>
                                            Year: {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>

                            <!-- Month Selector (NEW) -->
                            @if($availableMonths->isNotEmpty())
                                <form method="GET" action="{{ route('stock-cards.show', $supply->supply_id) }}"
                                    class="flex space-x-2">
                                    <input type="hidden" name="fund_cluster" value="{{ $fundCluster }}">
                                    <input type="hidden" name="year" value="{{ $selectedYear }}">
                                    <select name="month" onchange="this.form.submit()"
                                        class="px-4 py-2 rounded-lg text-sm
                                            bg-white text-[#a01b1a] border border-[#a01b1a]
                                            focus:outline-none focus:ring-2 focus:ring-[#a01b1a]">
                                        <option value="">All Months</option>
                                        @foreach ($availableMonths as $month)
                                            <option value="{{ $month['value'] }}"
                                                {{ $selectedMonth == $month['value'] ? 'selected' : '' }}>
                                                {{ $month['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            @endif

                            <!-- Fund Cluster Selector -->
                            <form method="GET" action="{{ route('stock-cards.show', $supply->supply_id) }}"
                                class="flex space-x-2">
                                <input type="hidden" name="year" value="{{ $selectedYear }}">
                                <input type="hidden" name="month" value="{{ $selectedMonth }}">
                                <select name="fund_cluster" onchange="this.form.submit()"
                                    class="px-4 py-2 rounded-lg text-sm
                                        bg-white text-[#a01b1a] border border-[#a01b1a]
                                        focus:outline-none focus:ring-2 focus:ring-[#a01b1a]">
                                    @foreach ($fundClusters as $fc)
                                        <option value="{{ $fc }}"
                                            {{ $fundCluster == $fc ? 'selected' : '' }}>
                                            Fund Cluster: {{ $fc }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Details Section -->
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <!-- Unit -->
                        <div class="bg-[#f3f4f6] dark:bg-[#111827] p-3 rounded-xl">
                            <p class="text-sm text-[#374151] dark:text-[#9ca3af]">Unit</p>
                            <p class="font-medium text-[#111827] dark:text-[#f9fafb]">
                                {{ $supply->unit_of_measurement }}</p>
                        </div>
                        <!-- Category -->
                        <div class="bg-[#f3f4f6] dark:bg-[#111827] p-3 rounded-xl">
                            <p class="text-sm text-[#374151] dark:text-[#9ca3af]">Category</p>
                            <p class="font-medium text-[#111827] dark:text-[#f9fafb]">
                                {{ $supply->category->name ?? 'N/A' }}</p>
                        </div>
                        <!-- Current Balance -->
                        <div class="bg-[#f3f4f6] dark:bg-[#111827] p-3 rounded-xl">
                            <p class="text-sm text-[#374151] dark:text-[#9ca3af]">Current Balance</p>
                            <p class="font-medium text-[#111827] dark:text-[#f9fafb]">
                                {{ number_format($currentStock) }}</p>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Stock Card Table -->
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Stock Card - {{ $selectedYear }}
                    </h3>
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
                                <th scope="col"
                                    class="px-4 py-3 border-b border-r border-gray-200 dark:border-gray-600 text-center">
                                    DATE</th>
                                <th scope="col"
                                    class="px-4 py-3 border-b border-r border-gray-200 dark:border-gray-600 text-center">
                                    REFERENCE</th>
                                <th scope="col"
                                    class="px-4 py-3 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-green-50 dark:bg-green-900/20">
                                    RECEIPT<br />QTY.
                                </th>
                                <th scope="col" colspan="2"
                                    class="px-4 py-3 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-red-50 dark:bg-red-900/20">
                                    ISSUE
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-blue-50 dark:bg-blue-900/20">
                                    BALANCE<br />QTY.
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 border-b border-gray-200 dark:border-gray-600 text-center">
                                    DAYS TO<br />CONSUME
                                </th>
                            </tr>

                            <!-- Subheader for issue columns -->
                            <tr class="text-xs text-gray-700 bg-gray-50 dark:bg-gray-700/70 dark:text-gray-400">
                                <th scope="col"
                                    class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600"></th>
                                <th scope="col"
                                    class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600"></th>
                                <th scope="col"
                                    class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600 bg-green-50/50 dark:bg-green-900/10">
                                </th>
                                <th scope="col"
                                    class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-red-50/50 dark:bg-red-900/10">
                                    QTY.</th>
                                <th scope="col"
                                    class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600 text-center bg-red-50/50 dark:bg-red-900/10">
                                    OFFICE</th>
                                <th scope="col"
                                    class="px-4 py-2 border-b border-r border-gray-200 dark:border-gray-600 bg-blue-50/50 dark:bg-blue-900/10">
                                </th>
                                <th scope="col" class="px-4 py-2 border-b border-gray-200 dark:border-gray-600"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stockCardEntries as $entry)
                                <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td
                                        class="px-4 py-3 whitespace-nowrap border-r border-gray-100 dark:border-gray-700">
                                        {{ \Carbon\Carbon::parse($entry['date'])->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-3 border-r border-gray-100 dark:border-gray-700">
                                        @if ($entry['reference'] === 'Beginning Balance')
                                            <span class="font-medium text-gray-800 dark:text-white">
                                                {{ $entry['reference'] }}
                                            </span>
                                        @else
                                            {{ $entry['reference'] }}
                                        @endif
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center bg-green-50/30 dark:bg-green-900/5 border-r border-gray-100 dark:border-gray-700">
                                        @if ($entry['receipt_qty'])
                                            <span class="font-medium text-green-600 dark:text-green-400">
                                                {{ number_format($entry['receipt_qty']) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center bg-red-50/30 dark:bg-red-900/5 border-r border-gray-100 dark:border-gray-700">
                                        @if ($entry['issue_qty'])
                                            <span class="font-medium text-red-600 dark:text-red-400">
                                                {{ number_format($entry['issue_qty']) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td
                                        class="px-4 py-3 bg-red-50/30 dark:bg-red-900/5 border-r border-gray-100 dark:border-gray-700">
                                        {{ $entry['issue_office'] }}
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-medium text-gray-800 dark:text-white bg-blue-50/30 dark:bg-blue-900/5 border-r border-gray-100 dark:border-gray-700">
                                        {{ number_format($entry['balance_qty']) }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        {{ $entry['days_to_consume'] ?? 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
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
