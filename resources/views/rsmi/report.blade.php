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
            <!-- Report Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow-md mb-6">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-[#ce201f]">
                    <div class="text-white text-center">
                        <h3 class="text-xl font-bold">REPORT OF SUPPLIES AND MATERIALS ISSUED</h3>
                        <p class="text-white/90 mt-1">{{ $entityName }}</p>
                        @if($fundCluster)
                            <p class="text-white/90">Fund Cluster: {{ $fundCluster }}</p>
                        @else
                            <p class="text-white/90">All Fund Clusters</p>
                        @endif
                        <p class="text-white/90">Date: {{ $startDate->format('F Y') }}</p>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Items Issued</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ number_format($summary['total_items']) }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Cost</p>
                            <p class="text-2xl font-bold text-[#10b981]">₱{{ number_format($summary['total_cost'], 2) }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Unique Supplies</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ number_format($summary['unique_supplies']) }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                            <p class="text-sm text-gray-600 dark:text-gray-400">RIS Count</p>
                            <p class="text-2xl font-bold text-[#f59e0b]">{{ number_format($summary['ris_count'] ?? 0) }}</p>
                        </div>
                    </div>

                    @if(!$fundCluster && isset($summary['fund_clusters_used']) && $summary['fund_clusters_used']->count() > 0)
                    <!-- Fund Clusters Summary (only show when "All Fund Clusters" is selected) -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fund Clusters in this Report:</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($summary['fund_clusters_used'] as $cluster)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $cluster == '101' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                                       ($cluster == '151' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                       'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200') }}">
                                    Fund Cluster {{ $cluster }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Report Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow-md">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-center font-bold text-gray-800 dark:text-gray-200">RIS No.</th>
                                @if(!$fundCluster)
                                    <th scope="col" class="px-4 py-3 text-center font-bold text-gray-800 dark:text-gray-200">Fund Cluster</th>
                                @endif
                                <th scope="col" class="px-4 py-3 text-center font-bold text-gray-800 dark:text-gray-200">Responsibility<br>Center Code</th>
                                <th scope="col" class="px-4 py-3 text-center font-bold text-gray-800 dark:text-gray-200">Stock No.</th>
                                <th scope="col" class="px-4 py-3 font-bold text-gray-800 dark:text-gray-200">Item</th>
                                <th scope="col" class="px-4 py-3 text-center font-bold text-gray-800 dark:text-gray-200">Unit</th>
                                <th scope="col" class="px-4 py-3 text-center font-bold text-gray-800 dark:text-gray-200">Quantity<br>Issued</th>
                                <th scope="col" class="px-4 py-3 text-center font-bold text-gray-800 dark:text-gray-200">Unit Cost</th>
                                <th scope="col" class="px-4 py-3 text-center font-bold text-gray-800 dark:text-gray-200">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportData as $risData)
                                @foreach($risData['items'] as $index => $item)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td class="px-4 py-3 text-center">
                                            @if($index === 0)
                                                {{ $risData['ris_no'] }}
                                            @endif
                                        </td>
                                        @if(!$fundCluster)
                                            <td class="px-4 py-3 text-center">
                                                @if($index === 0)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        {{ $risData['fund_cluster'] == '101' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                                                           ($risData['fund_cluster'] == '151' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                                           'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200') }}">
                                                        {{ $risData['fund_cluster'] ?? 'N/A' }}
                                                    </span>
                                                @endif
                                            </td>
                                        @endif
                                        <td class="px-4 py-3 text-center">
                                            @if($index === 0)
                                                &nbsp; {{-- Responsibility Center Code intentionally left blank --}}
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">{{ $item['stock_no'] }}</td>
                                        <td class="px-4 py-3">{{ $item['item_name'] }}@if(!empty($item['description'])), {{ $item['description'] }}@endif</td>
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
                                <td colspan="{{ !$fundCluster ? '8' : '7' }}" class="px-4 py-3 text-right">GRAND TOTAL:</td>
                                <td class="px-4 py-3 text-center text-[#10b981]">
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
