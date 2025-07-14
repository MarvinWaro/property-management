<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detailed RSMI Report by Item - {{ $startDate->format('F Y') }}
                @if($fundCluster)
                    <span class="text-sm font-normal text-gray-600 dark:text-gray-400">(Fund Cluster: {{ $fundCluster }})</span>
                @endif
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('rsmi.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors duration-200">
                    Back to RSMI
                </a>

                <a href="{{ route('rsmi.analytics') }}?month={{ $month }}&fund_cluster={{ $fundCluster }}{{ $departmentId ? '&department_id=' . $departmentId : '' }}"
                    class="px-4 py-2 text-sm font-medium text-orange-600 bg-white border border-orange-300 rounded-lg hover:bg-orange-50 dark:bg-gray-700 dark:text-orange-400 dark:border-orange-600 dark:hover:bg-gray-600 transition-colors duration-200">
                    <span class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Analytics</span>
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
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Filter Options</h3>
                    <form method="GET" action="{{ route('rsmi.detailed') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Month Filter -->
                            <div>
                                <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Month</label>
                                <select id="month" name="month" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                    focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600
                                    dark:border-gray-500 dark:text-white transition-all duration-200">
                                    @forelse($availableMonths as $availableMonth)
                                        <option value="{{ $availableMonth }}" {{ $month == $availableMonth ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::parse($availableMonth . '-01')->format('F Y') }}
                                        </option>
                                    @empty
                                        <option value="{{ now()->format('Y-m') }}">{{ now()->format('F Y') }}</option>
                                    @endforelse
                                </select>
                            </div>

                            <!-- Fund Cluster Filter -->
                            <div>
                                <label for="fund_cluster" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fund Cluster</label>
                                <select id="fund_cluster" name="fund_cluster" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                    focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600
                                    dark:border-gray-500 dark:text-white transition-all duration-200">
                                    <option value="">All Fund Clusters</option>
                                    @foreach($fundClusters as $cluster)
                                        <option value="{{ $cluster }}" {{ $fundCluster == $cluster ? 'selected' : '' }}>
                                            Fund Cluster: {{ $cluster }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Button -->
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

                <!-- Search Bar -->
                <div class="p-4">
                    <div class="flex flex-col md:flex-row md:items-center md:space-x-4">
                        <div class="w-full">
                            <div class="relative">
                                <input type="text" id="rsmi-search"
                                    class="block w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                    placeholder="Search by item name, stock number, RIS number, or fund cluster...">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 md:mt-0 flex space-x-2">
                            <button id="search-button"
                                class="px-4 py-2 text-sm font-medium text-white bg-gray-800 rounded-lg hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500/30 transition-all duration-200 shadow-sm">
                                <span class="flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <span>Search</span>
                                </span>
                            </button>
                            <button id="reset-search"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors duration-200">
                                <span class="flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <span>Reset</span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div id="search-results-summary" class="mt-2 text-sm text-gray-600 dark:text-gray-400 hidden">
                        Showing <span id="visible-count" class="font-medium">0</span> of <span id="total-count" class="font-medium">0</span> items
                    </div>
                </div>
            </div>

            <!-- Supply Cards Container -->
            <div id="supply-cards-container">
                @foreach($reportData as $supplyData)
                    <!-- Supply Card -->
                    <div class="supply-card bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm mb-6"
                         data-stock-no="{{ $supplyData['stock_no'] }}"
                         data-item-name="{{ $supplyData['item_name'] }}"
                         data-ris-numbers="{{ $supplyData['transactions']->pluck('ris_no')->implode(' ') }}"
                         data-category="{{ $supplyData['category'] }}"
                         data-fund-clusters="{{ $supplyData['transactions']->pluck('fund_cluster')->unique()->implode(' ') }}">

                        <!-- Clean card header matching dashboard style -->
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">{{ $supplyData['item_name'] }}</h3>
                                    <div class="space-y-1 text-sm text-gray-600 dark:text-gray-300">
                                        <p><span class="font-medium">Stock No:</span> {{ $supplyData['stock_no'] }}</p>
                                        <p><span class="font-medium">Category:</span> {{ $supplyData['category'] }}</p>
                                        @if($supplyData['transactions']->pluck('fund_cluster')->unique()->count() > 1)
                                            <p><span class="font-medium">Fund Clusters:</span> {{ $supplyData['transactions']->pluck('fund_cluster')->unique()->implode(', ') }}</p>
                                        @elseif($supplyData['transactions']->first()['fund_cluster'])
                                            <p><span class="font-medium">Fund Cluster:</span> {{ $supplyData['transactions']->first()['fund_cluster'] }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right mt-4 md:mt-0 md:ml-6">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Quantity Issued</p>
                                    <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ number_format($supplyData['total_quantity']) }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">{{ $supplyData['unit'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <!-- Summary Stats - minimal dashboard style -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <!-- Average Unit Cost Card -->
                                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <div class="text-center">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Average Unit Cost</p>
                                        <p class="text-xl font-bold text-gray-800 dark:text-white">₱{{ number_format($supplyData['average_unit_cost'], 2) }}</p>
                                    </div>
                                </div>

                                <!-- Total Cost Card -->
                                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <div class="text-center">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Total Cost</p>
                                        <p class="text-xl font-bold text-gray-800 dark:text-white">₱{{ number_format($supplyData['total_cost'], 2) }}</p>
                                    </div>
                                </div>

                                <!-- Transactions Card -->
                                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <div class="text-center">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Transactions</p>
                                        <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $supplyData['transactions']->count() }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Transaction Details -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-600">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 font-semibold text-gray-800 dark:text-gray-200">RIS No.</th>
                                            <th scope="col" class="px-6 py-3 font-semibold text-gray-800 dark:text-gray-200">Fund Cluster</th>
                                            <th scope="col" class="px-6 py-3 font-semibold text-gray-800 dark:text-gray-200">Division</th>
                                            <th scope="col" class="px-6 py-3 font-semibold text-gray-800 dark:text-gray-200">Date</th>
                                            <th scope="col" class="px-6 py-3 text-right font-semibold text-gray-800 dark:text-gray-200">Quantity</th>
                                            <th scope="col" class="px-6 py-3 text-right font-semibold text-gray-800 dark:text-gray-200">Unit Cost</th>
                                            <th scope="col" class="px-6 py-3 text-right font-semibold text-gray-800 dark:text-gray-200">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($supplyData['transactions'] as $txn)
                                            <tr class="bg-white border-b border-gray-100 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                                <td class="px-6 py-4 font-medium text-gray-800 dark:text-gray-200">{{ $txn['ris_no'] }}</td>
                                                <td class="px-6 py-4">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                        {{ $txn['fund_cluster'] == '101' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' :
                                                           ($txn['fund_cluster'] == '151' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' :
                                                           'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                                        {{ $txn['fund_cluster'] ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $txn['department'] }}</td>
                                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($txn['date'])->format('M d, Y') }}</td>
                                                <td class="px-6 py-4 text-right font-semibold text-gray-800 dark:text-gray-200">{{ number_format($txn['quantity']) }}</td>
                                                <td class="px-6 py-4 text-right text-gray-700 dark:text-gray-300">₱{{ number_format($txn['unit_cost'], 2) }}</td>
                                                <td class="px-6 py-4 text-right font-semibold text-gray-800 dark:text-gray-200">₱{{ number_format($txn['total'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- No Results Message -->
            <div id="no-results-message" class="hidden bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No results found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your search criteria or clear the search to show all items.</p>
                <div class="mt-6">
                    <button id="clear-search-btn" type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500/30 transition-all duration-200">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                        </svg>
                        Show All Items
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('rsmi-search');
            const searchButton = document.getElementById('search-button');
            const resetButton = document.getElementById('reset-search');
            const clearSearchBtn = document.getElementById('clear-search-btn');
            const supplyCards = document.querySelectorAll('.supply-card');
            const noResultsMessage = document.getElementById('no-results-message');
            const searchResultsSummary = document.getElementById('search-results-summary');
            const visibleCountElement = document.getElementById('visible-count');
            const totalCountElement = document.getElementById('total-count');
            const totalItems = supplyCards.length;

            totalCountElement.textContent = totalItems;

            // Function to perform search
            function performSearch() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                let visibleCount = 0;

                if (searchTerm === '') {
                    // If search is empty, show all cards
                    supplyCards.forEach(card => {
                        card.classList.remove('hidden');
                    });
                    noResultsMessage.classList.add('hidden');
                    searchResultsSummary.classList.add('hidden');
                    return;
                }

                supplyCards.forEach(card => {
                    const stockNo = card.getAttribute('data-stock-no').toLowerCase();
                    const itemName = card.getAttribute('data-item-name').toLowerCase();
                    const risNumbers = card.getAttribute('data-ris-numbers').toLowerCase();
                    const category = card.getAttribute('data-category').toLowerCase();
                    const fundClusters = card.getAttribute('data-fund-clusters').toLowerCase();

                    if (stockNo.includes(searchTerm) ||
                        itemName.includes(searchTerm) ||
                        risNumbers.includes(searchTerm) ||
                        category.includes(searchTerm) ||
                        fundClusters.includes(searchTerm)) {
                        card.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        card.classList.add('hidden');
                    }
                });

                // Update the count and display
                visibleCountElement.textContent = visibleCount;
                searchResultsSummary.classList.remove('hidden');

                // Show or hide no results message
                if (visibleCount === 0) {
                    noResultsMessage.classList.remove('hidden');
                } else {
                    noResultsMessage.classList.add('hidden');
                }
            }

            // Add event listeners
            searchButton.addEventListener('click', performSearch);

            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });

            resetButton.addEventListener('click', function() {
                searchInput.value = '';
                supplyCards.forEach(card => {
                    card.classList.remove('hidden');
                });
                noResultsMessage.classList.add('hidden');
                searchResultsSummary.classList.add('hidden');
            });

            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                supplyCards.forEach(card => {
                    card.classList.remove('hidden');
                });
                noResultsMessage.classList.add('hidden');
                searchResultsSummary.classList.add('hidden');
            });
        });
    </script>
</x-app-layout>
