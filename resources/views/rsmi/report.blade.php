<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Report of Supplies and Materials Issued - {{ $startDate->format('F Y') }}
                @if($fundCluster)
                    <span class="text-sm font-normal text-gray-600 dark:text-gray-400">(Fund Cluster: {{ $fundCluster }})</span>
                @else
                    <span class="text-sm font-normal text-gray-600 dark:text-gray-400">(All Fund Clusters)</span>
                @endif
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('rsmi.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-all duration-200">
                    Back to RSMI
                </a>

                {{-- Export PDF Button --}}
                <a href="{{ route('rsmi.export-pdf-formatted') }}?month={{ $month }}&fund_cluster={{ $fundCluster }}&department_id={{ $departmentId }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-2 focus:outline-none focus:ring-red-400/30 transition-all duration-200 shadow-sm">
                    <span class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Export PDF</span>
                    </span>
                </a>

                {{-- Export Excel Button --}}
                <a href="{{ route('rsmi.export-excel') }}?month={{ $month }}&fund_cluster={{ $fundCluster }}&department_id={{ $departmentId }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-[#217346] rounded-lg hover:bg-[#164e2e] focus:ring-2 focus:outline-none focus:ring-[#217346]/30 transition-all duration-200 shadow-sm">
                    <span class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Export Excel</span>
                    </span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm mb-6">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707v4.586l-4-2v-2.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter Report
                    </h3>
                    <form method="GET" action="{{ route('rsmi.generate') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Month Filter -->
                            <div>
                                <label for="filter_month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Month</label>
                                <select id="filter_month" name="month" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                    focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600
                                    dark:border-gray-500 dark:text-white transition-all duration-200">
                                    @if(isset($availableMonths))
                                        @forelse($availableMonths as $availableMonth)
                                            <option value="{{ $availableMonth }}" {{ $month == $availableMonth ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::parse($availableMonth . '-01')->format('F Y') }}
                                            </option>
                                        @empty
                                            <option value="{{ now()->format('Y-m') }}">{{ now()->format('F Y') }}</option>
                                        @endforelse
                                    @else
                                        <option value="{{ $month }}" selected>{{ $startDate->format('F Y') }}</option>
                                    @endif
                                </select>
                            </div>

                            <!-- Fund Cluster Filter -->
                            <div>
                                <label for="filter_fund_cluster" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fund Cluster</label>
                                <select id="filter_fund_cluster" name="fund_cluster" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                    focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600
                                    dark:border-gray-500 dark:text-white transition-all duration-200">
                                    <option value="">All Fund Clusters</option>
                                    @if(isset($fundClusters))
                                        @foreach($fundClusters as $cluster)
                                            <option value="{{ $cluster }}" {{ $fundCluster == $cluster ? 'selected' : '' }}>
                                                Fund Cluster: {{ $cluster }}
                                            </option>
                                        @endforeach
                                    @else
                                        @foreach(['101', '151'] as $cluster)
                                            <option value="{{ $cluster }}" {{ $fundCluster == $cluster ? 'selected' : '' }}>
                                                Fund Cluster: {{ $cluster }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <!-- Apply Filter Button -->
                            <div class="flex items-end">
                                <button type="submit" class="w-full px-4 py-2 text-white bg-gray-800 rounded-lg hover:bg-gray-900
                                    focus:ring-2 focus:outline-none focus:ring-gray-500/30 transition-all duration-200
                                    flex items-center justify-center shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707v4.586l-4-2v-2.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                    </svg>
                                    Apply Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Report Header - CLEAN DASHBOARD STYLE -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm mb-6">
                <!-- Clean header matching dashboard style -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="text-center space-y-3">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white">
                            Report of Supplies and Materials Issued
                        </h3>

                        <div class="space-y-1 text-gray-600 dark:text-gray-300">
                            <p class="font-medium text-lg">{{ $entityName }}</p>

                            <div class="flex items-center justify-center space-x-6 text-sm">
                                @if($fundCluster)
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2 h-2 rounded-full {{ $fundCluster == '101' ? 'bg-blue-500' : 'bg-green-500' }}"></span>
                                        <span>Fund Cluster: {{ $fundCluster }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2 h-2 rounded-full bg-gray-500"></span>
                                        <span>All Fund Clusters</span>
                                    </div>
                                @endif

                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ $startDate->format('F Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Cards matching dashboard style -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <!-- Total Items Card -->
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                            <div class="text-center">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Total Items Issued</p>
                                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($summary['total_items']) }}</p>
                            </div>
                        </div>

                        <!-- Total Cost Card -->
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                            <div class="text-center">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Total Cost</p>
                                <p class="text-2xl font-bold text-gray-800 dark:text-white">₱{{ number_format($summary['total_cost'], 2) }}</p>
                            </div>
                        </div>

                        <!-- Unique Supplies Card -->
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                            <div class="text-center">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Unique Supplies</p>
                                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($summary['unique_supplies']) }}</p>
                            </div>
                        </div>

                        <!-- RIS Count Card -->
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                            <div class="text-center">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">RIS Count</p>
                                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($summary['ris_count'] ?? 0) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Report Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-gray-800 dark:text-gray-200">RIS No.</th>
                                @if(!$fundCluster)
                                    <th scope="col" class="px-6 py-4 text-center font-semibold text-gray-800 dark:text-gray-200">Fund Cluster</th>
                                @endif
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-gray-800 dark:text-gray-200">Responsibility<br>Center Code</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-gray-800 dark:text-gray-200">Stock No.</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-gray-800 dark:text-gray-200">Item</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-gray-800 dark:text-gray-200">Unit</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-gray-800 dark:text-gray-200">Quantity<br>Issued</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-gray-800 dark:text-gray-200">Unit Cost</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold text-gray-800 dark:text-gray-200">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportData as $risData)
                                @foreach($risData['items'] as $index => $item)
                                    <tr class="bg-white border-b border-gray-100 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                        <td class="px-6 py-4 text-center font-medium text-gray-800 dark:text-gray-200">
                                            @if($index === 0)
                                                {{ $risData['ris_no'] }}
                                            @endif
                                        </td>
                                        @if(!$fundCluster)
                                            <td class="px-6 py-4 text-center">
                                                @if($index === 0)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                        {{ $risData['fund_cluster'] == '101' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' :
                                                           ($risData['fund_cluster'] == '151' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' :
                                                           'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                                        {{ $risData['fund_cluster'] ?? 'N/A' }}
                                                    </span>
                                                @endif
                                            </td>
                                        @endif
                                        <td class="px-6 py-4 text-center text-gray-500">
                                            @if($index === 0)
                                                &nbsp; {{-- Responsibility Center Code intentionally left blank --}}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center font-mono text-sm text-gray-700 dark:text-gray-300">{{ $item['stock_no'] }}</td>
                                        <td class="px-6 py-4 text-gray-800 dark:text-gray-200">{{ $item['item_name'] }}@if(!empty($item['description'])), {{ $item['description'] }}@endif</td>
                                        <td class="px-6 py-4 text-center text-gray-700 dark:text-gray-300">{{ $item['unit'] }}</td>
                                        <td class="px-6 py-4 text-center font-semibold text-gray-800 dark:text-gray-200">{{ number_format($item['quantity_issued']) }}</td>
                                        <td class="px-6 py-4 text-center text-gray-700 dark:text-gray-300">₱{{ number_format($item['unit_cost'], 2) }}</td>
                                        <td class="px-6 py-4 text-center font-semibold text-gray-800 dark:text-gray-200">₱{{ number_format($item['total_cost'], 2) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50 dark:bg-gray-700 border-t-2 border-gray-200 dark:border-gray-600">
                                <td colspan="{{ !$fundCluster ? '8' : '7' }}" class="px-6 py-4 text-right font-semibold text-gray-800 dark:text-gray-200">GRAND TOTAL:</td>
                                <td class="px-6 py-4 text-center font-bold text-green-600 dark:text-green-400 text-lg">
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
