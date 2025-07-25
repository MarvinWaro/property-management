<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Report on the Physical Count of Inventories (RPCI)') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('stock-cards.index') }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-[#ce201f] rounded-lg hover:bg-[#a01b1a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#ce201f]/30 transition-all duration-200 shadow-sm">
                    <span class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Stock Cards</span>
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
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">RPCI Reports</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Generate and export physical count inventory reports for semester periods comparing book quantities to physical counts
                        </p>
                    </div>

                    <!-- Report Generation Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Semester Report Card -->
                        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 p-6">
                            <div class="flex items-start mb-4">
                                <div class="bg-gray-100 dark:bg-gray-600 p-2 rounded-lg mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Generate Semester Report</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Compare physical count to book quantities for a specific semester with fund cluster details</p>
                                </div>
                            </div>

                            <form method="GET" action="{{ route('rpci.generate') }}" class="space-y-4">
                                <div>
                                    <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Year</label>
                                    <select id="year" name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                        focus:ring-1 focus:ring-[#ce201f] focus:border-[#ce201f] dark:bg-gray-600
                                        dark:border-gray-500 dark:text-white transition-all duration-200">
                                        @forelse($availableYears as $year)
                                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @empty
                                            <option value="{{ now()->year }}">{{ now()->year }}</option>
                                        @endforelse
                                    </select>
                                </div>

                                <div>
                                    <label for="semester" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Semester</label>
                                    <select id="semester" name="semester" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                        focus:ring-1 focus:ring-[#ce201f] focus:border-[#ce201f] dark:bg-gray-600
                                        dark:border-gray-500 dark:text-white transition-all duration-200">
                                        <option value="1" {{ $selectedSemester == 1 ? 'selected' : '' }}>
                                            1st Semester (Jan - Jun)
                                        </option>
                                        <option value="2" {{ $selectedSemester == 2 ? 'selected' : '' }}>
                                            2nd Semester (Jul - Dec)
                                        </option>
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

                        <!-- Direct Export Card -->
                        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 p-6">
                            <div class="flex items-start mb-4">
                                <div class="bg-gray-100 dark:bg-gray-600 p-2 rounded-lg mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Export Excel Report</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Directly export RPCI report to Excel format with physical count comparison</p>
                                </div>
                            </div>

                            <form method="GET" action="{{ route('rpci.export-excel') }}" class="space-y-4">
                                <div>
                                    <label for="export_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Year</label>
                                    <select id="export_year" name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                        focus:ring-1 focus:ring-[#ce201f] focus:border-[#ce201f] dark:bg-gray-600
                                        dark:border-gray-500 dark:text-white transition-all duration-200">
                                        @forelse($availableYears as $year)
                                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @empty
                                            <option value="{{ now()->year }}">{{ now()->year }}</option>
                                        @endforelse
                                    </select>
                                </div>

                                <div>
                                    <label for="export_semester" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Semester</label>
                                    <select id="export_semester" name="semester" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                        focus:ring-1 focus:ring-[#ce201f] focus:border-[#ce201f] dark:bg-gray-600
                                        dark:border-gray-500 dark:text-white transition-all duration-200">
                                        <option value="1" {{ $selectedSemester == 1 ? 'selected' : '' }}>
                                            1st Semester (Jan - Jun)
                                        </option>
                                        <option value="2" {{ $selectedSemester == 2 ? 'selected' : '' }}>
                                            2nd Semester (Jul - Dec)
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label for="export_fund_cluster" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fund Cluster</label>
                                    <select id="export_fund_cluster" name="fund_cluster" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
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

                                <button type="submit" class="w-full px-4 py-2.5 text-white bg-[#217346] rounded-lg hover:bg-[#164e2e]
                                    focus:ring-2 focus:outline-none focus:ring-[#217346]/30 transition-all duration-200
                                    flex items-center justify-center shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Export Excel
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Information Section -->
                    <div class="mt-8">
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            <h4 class="font-medium text-gray-800 dark:text-gray-200 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#f59e0b]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                About RPCI Reports
                            </h4>
                            <ul class="mt-2 space-y-1 text-sm text-gray-600 dark:text-gray-400 ml-7 list-disc">
                                <li>RPCI reports are generated every semester (6 months) to compare physical inventory counts with book quantities</li>
                                <li>First semester covers January to June, second semester covers July to December</li>
                                <li>Reports show variances between actual physical count and calculated book quantities from transactions</li>
                                <li>Select "All Fund Clusters" to see combined data from both 101 and 151</li>
                                <li>Filter by specific fund cluster (101 or 151) to separate budget allocations</li>
                                <li>Variance analysis helps identify discrepancies that require investigation or adjustment</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
