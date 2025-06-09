<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detailed RSMI Report by Item - {{ $startDate->format('F Y') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('rsmi.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors duration-200">
                    Back to RSMI
                </a>

                <a href="{{ route('rsmi.analytics') }}?month={{ $month }}&fund_cluster={{ $fundCluster }}{{ $departmentId ? '&department_id=' . $departmentId : '' }}"
                    class="px-4 py-2 text-sm font-medium text-[#f59e0b] bg-white border border-[#f59e0b] rounded-lg hover:bg-[#f59e0b]/10 dark:bg-gray-700 dark:text-[#fbbf24] dark:border-[#f59e0b] dark:hover:bg-gray-600 transition-colors duration-200">
                    <span class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Analytics</span>
                    </span>
                </a>
                {{-- <a href="{{ route('rsmi.export-pdf') }}?month={{ $month }}&fund_cluster={{ $fundCluster }}&format=detailed"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                    <span class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span>Export as PDF</span>
                    </span>
                </a> --}}
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Bar -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow-md mb-6">
                <div class="p-4">
                    <div class="flex flex-col md:flex-row md:items-center md:space-x-4">
                        <div class="w-full">
                            <div class="relative">
                                <input type="text" id="rsmi-search"
                                    class="block w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 focus:ring-1 focus:ring-[#ce201f] focus:border-[#ce201f] transition-all duration-200"
                                    placeholder="Search by item name, stock number, or RIS number...">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 md:mt-0 flex space-x-2">
                            <button id="search-button"
                                class="px-4 py-2 text-sm font-medium text-white bg-[#ce201f] rounded-lg hover:bg-[#a01b1a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#ce201f]/30 transition-all duration-200 shadow-sm">
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
                    <div class="supply-card bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow-md mb-6"
                         data-stock-no="{{ $supplyData['stock_no'] }}"
                         data-item-name="{{ $supplyData['item_name'] }}"
                         data-ris-numbers="{{ $supplyData['transactions']->pluck('ris_no')->implode(' ') }}"
                         data-category="{{ $supplyData['category'] }}">
                        <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-[#ce201f]">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center text-white">
                                <div>
                                    <h3 class="text-lg font-bold">{{ $supplyData['item_name'] }}</h3>
                                    <p class="text-white/90">Stock No: {{ $supplyData['stock_no'] }} | Category: {{ $supplyData['category'] }}</p>
                                </div>
                                <div class="text-right mt-2 md:mt-0">
                                    <p class="text-sm text-white/90">Total Quantity Issued</p>
                                    <p class="text-2xl font-bold">{{ number_format($supplyData['total_quantity']) }} {{ $supplyData['unit'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-5">
                            <!-- Summary Stats -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Average Unit Cost</p>
                                    <p class="font-medium text-gray-800 dark:text-white">₱{{ number_format($supplyData['average_unit_cost'], 2) }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Cost</p>
                                    <p class="font-medium text-[#10b981]">₱{{ number_format($supplyData['total_cost'], 2) }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Transactions</p>
                                    <p class="font-medium text-gray-800 dark:text-white">{{ $supplyData['transactions']->count() }}</p>
                                </div>
                            </div>

                            <!-- Transaction Details -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                                        <tr>
                                            <th scope="col" class="px-4 py-2 font-bold text-gray-800 dark:text-gray-200">RIS No.</th>
                                            <th scope="col" class="px-4 py-2 font-bold text-gray-800 dark:text-gray-200">Department</th>
                                            <th scope="col" class="px-4 py-2 font-bold text-gray-800 dark:text-gray-200">Date</th>
                                            <th scope="col" class="px-4 py-2 text-right font-bold text-gray-800 dark:text-gray-200">Quantity</th>
                                            <th scope="col" class="px-4 py-2 text-right font-bold text-gray-800 dark:text-gray-200">Unit Cost</th>
                                            <th scope="col" class="px-4 py-2 text-right font-bold text-gray-800 dark:text-gray-200">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($supplyData['transactions'] as $txn)
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
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

            <!-- No Results Message -->
            <div id="no-results-message" class="hidden bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow-md p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No results found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your search criteria or clear the search to show all items.</p>
                <div class="mt-6">
                    <button id="clear-search-btn" type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#ce201f] hover:bg-[#a01b1a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#ce201f]/30 transition-all duration-200">
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

                    if (stockNo.includes(searchTerm) ||
                        itemName.includes(searchTerm) ||
                        risNumbers.includes(searchTerm) ||
                        category.includes(searchTerm)) {
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
