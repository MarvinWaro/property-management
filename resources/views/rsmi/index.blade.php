<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Report of Supplies and Materials Issued (RSMI)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Quick Actions -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-300 mb-2">Generate Monthly Report</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">View supplies issued for a specific month</p>
                            <form method="GET" action="{{ route('rsmi.generate') }}" class="space-y-3">
                                <select name="month" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                    focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800
                                    dark:border-gray-700 dark:text-white">
                                    @forelse($availableMonths as $month)
                                        <option value="{{ $month }}" {{ $selectedMonth == $month ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}
                                        </option>
                                    @empty
                                        <option value="{{ now()->format('Y-m') }}">{{ now()->format('F Y') }}</option>
                                    @endforelse
                                </select>
                                <select name="fund_cluster" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                    focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800
                                    dark:border-gray-700 dark:text-white">
                                    @foreach($fundClusters as $cluster)
                                        <option value="{{ $cluster }}" {{ $selectedFundCluster == $cluster ? 'selected' : '' }}>
                                            Fund Cluster: {{ $cluster }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="w-full px-4 py-2 text-white bg-blue-700 rounded-lg hover:bg-blue-800
                                    focus:ring-1 focus:outline-none focus:ring-blue-300 dark:bg-blue-600
                                    dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Generate Report
                                </button>
                            </form>
                        </div>

                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-green-800 dark:text-green-300 mb-2">Detailed Report by Item</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">View issuances grouped by supply item</p>
                            <form method="GET" action="{{ route('rsmi.detailed') }}" class="space-y-3">
                                <select name="month" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                    focus:ring-1 focus:ring-green-500 focus:border-green-500 dark:bg-gray-800
                                    dark:border-gray-700 dark:text-white">
                                    @forelse($availableMonths as $month)
                                        <option value="{{ $month }}">
                                            {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}
                                        </option>
                                    @empty
                                        <option value="{{ now()->format('Y-m') }}">{{ now()->format('F Y') }}</option>
                                    @endforelse
                                </select>
                                <button type="submit" class="w-full px-4 py-2 text-white bg-green-700 rounded-lg hover:bg-green-800
                                    focus:ring-1 focus:outline-none focus:ring-green-300 dark:bg-green-600
                                    dark:hover:bg-green-700 dark:focus:ring-green-800">
                                    View Detailed Report
                                </button>
                            </form>
                        </div>

                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-purple-800 dark:text-purple-300 mb-2">Department Filter</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Generate report for specific department</p>
                            <form method="GET" action="{{ route('rsmi.generate') }}" class="space-y-3">
                                <select name="department_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                    focus:ring-1 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-800
                                    dark:border-gray-700 dark:text-white">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->department_id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="month" value="{{ $selectedMonth }}">
                                <button type="submit" class="w-full px-4 py-2 text-white bg-purple-700 rounded-lg hover:bg-purple-800
                                    focus:ring-1 focus:outline-none focus:ring-purple-300 dark:bg-purple-600
                                    dark:hover:bg-purple-700 dark:focus:ring-purple-800">
                                    Filter by Department
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Recent Issuances Summary -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Monthly Comparison</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                View year-round comparison of supplies issued
                            </p>
                            <a href="{{ route('rsmi.monthly-comparison') }}"
                                class="mt-3 inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                View Comparison Chart
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
