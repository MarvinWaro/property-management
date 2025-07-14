<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Report of Supplies and Materials Issued (RSMI)') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('rsmi.yearly-analytics') }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-[#ce201f] rounded-lg hover:bg-[#a01b1a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#ce201f]/30 transition-all duration-200 shadow-sm">
                    <span class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Yearly Analytics</span>
                    </span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Content Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <!-- Title and Description -->
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">RSMI Reports</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Generate and view reports of supplies and materials issued for specific periods
                        </p>
                    </div>

                    <!-- Report Generation Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Monthly Report Card -->
                        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 p-6">
                            <div class="flex items-start mb-4">
                                <div class="bg-gray-100 dark:bg-gray-600 p-2 rounded-lg mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Generate Monthly Report</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View supplies issued for a specific month with fund cluster details</p>
                                </div>
                            </div>

                            <form method="GET" action="{{ route('rsmi.generate') }}" class="space-y-4">
                                <div>
                                    <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Month</label>
                                    <select id="month" name="month" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                        focus:ring-1 focus:ring-[#ce201f] focus:border-[#ce201f] dark:bg-gray-600
                                        dark:border-gray-500 dark:text-white transition-all duration-200">
                                        @forelse($availableMonths as $month)
                                            <option value="{{ $month }}" {{ $selectedMonth == $month ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}
                                            </option>
                                        @empty
                                            <option value="{{ now()->format('Y-m') }}">{{ now()->format('F Y') }}</option>
                                        @endforelse
                                    </select>
                                </div>

                                <div>
                                    <label for="fund_cluster" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fund Cluster</label>
                                    <select id="fund_cluster" name="fund_cluster" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                        focus:ring-1 focus:ring-[#ce201f] focus:border-[#ce201f] dark:bg-gray-600
                                        dark:border-gray-500 dark:text-white transition-all duration-200">
                                        <option value="">All Fund Clusters</option>
                                        @foreach($fundClusters as $cluster)
                                            <option value="{{ $cluster }}" {{ $selectedFundCluster == $cluster ? 'selected' : '' }}>
                                                Fund Cluster: {{ $cluster }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="w-full px-4 py-2.5 text-white bg-[#ce201f] rounded-lg hover:bg-[#a01b1a]
                                    focus:ring-2 focus:outline-none focus:ring-[#ce201f]/30 transition-all duration-200
                                    flex items-center justify-center shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Generate Report
                                </button>
                            </form>
                        </div>

                        <!-- Detailed Report Card -->
                        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 p-6">
                            <div class="flex items-start mb-4">
                                <div class="bg-gray-100 dark:bg-gray-600 p-2 rounded-lg mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Detailed Report by Item</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View issuances grouped by supply item with comprehensive details</p>
                                </div>
                            </div>

                            <form method="GET" action="{{ route('rsmi.detailed') }}" class="space-y-4">
                                <div>
                                    <label for="detailed_month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Month</label>
                                    <select id="detailed_month" name="month" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                        focus:ring-1 focus:ring-[#ce201f] focus:border-[#ce201f] dark:bg-gray-600
                                        dark:border-gray-500 dark:text-white transition-all duration-200">
                                        @forelse($availableMonths as $month)
                                            <option value="{{ $month }}">
                                                {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}
                                            </option>
                                        @empty
                                            <option value="{{ now()->format('Y-m') }}">{{ now()->format('F Y') }}</option>
                                        @endforelse
                                    </select>
                                </div>

                                <div>
                                    <label for="detailed_fund_cluster" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fund Cluster</label>
                                    <select id="detailed_fund_cluster" name="fund_cluster" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                        focus:ring-1 focus:ring-[#ce201f] focus:border-[#ce201f] dark:bg-gray-600
                                        dark:border-gray-500 dark:text-white transition-all duration-200">
                                        <option value="">All Fund Clusters</option>
                                        @foreach($fundClusters as $cluster)
                                            <option value="{{ $cluster }}">
                                                Fund Cluster: {{ $cluster }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="w-full px-4 py-2.5 text-white bg-[#10b981] rounded-lg hover:bg-[#059669]
                                    focus:ring-2 focus:outline-none focus:ring-[#10b981]/30 transition-all duration-200
                                    flex items-center justify-center shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m0 5l4.879-4.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242z" />
                                    </svg>
                                    View Detailed Report
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Monthly Comparison Section -->
                    <div class="mt-8 bg-white dark:bg-gray-700 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 p-6">
                        <div class="flex items-start md:items-center justify-between flex-col md:flex-row mb-6 md:mb-0">
                            <div class="flex items-start mb-4 md:mb-0">
                                <div class="bg-gray-100 dark:bg-gray-600 p-2 rounded-lg mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Monthly Comparison</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View year-round comparison of supplies issued with trend analysis</p>
                                </div>
                            </div>

                            <a href="{{ route('rsmi.monthly-comparison') }}"
                                class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-[#f59e0b] rounded-lg hover:bg-[#d97706]
                                focus:ring-2 focus:outline-none focus:ring-[#f59e0b]/30 transition-all duration-200 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                View Comparison Chart
                            </a>
                        </div>
                    </div>

                    <!-- Quick Tips Section -->
                    <div class="mt-8">
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            <h4 class="font-medium text-gray-800 dark:text-gray-200 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#f59e0b]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Tips for RSMI Reports
                            </h4>
                            <ul class="mt-2 space-y-1 text-sm text-gray-600 dark:text-gray-400 ml-7 list-disc">
                                <li>Generate monthly reports for official documentation and record-keeping</li>
                                <li>Use detailed reports to track specific item movements across departments</li>
                                <li>Select "All Fund Clusters" to see combined data from both 101 and 151</li>
                                <li>Filter by specific fund cluster (101 or 151) to separate budget allocations</li>
                                <li>The comparison chart helps identify usage patterns and plan future procurement</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
