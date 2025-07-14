<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                RSMI Analytics - {{ $startDate->format('F Y') }}
                @if($fundCluster)
                    <span class="text-sm font-normal text-gray-600 dark:text-gray-400">(Fund Cluster: {{ $fundCluster }})</span>
                @else
                    <span class="text-sm font-normal text-gray-600 dark:text-gray-400">(All Fund Clusters)</span>
                @endif
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('rsmi.detailed') }}?month={{ $month }}&fund_cluster={{ $fundCluster }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors">
                    <span class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Back to Report</span>
                    </span>
                </a>
                <a href="{{ route('rsmi.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors">
                    Back to RSMI
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Fund Cluster Filter Buttons -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="mb-3 sm:mb-0">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Fund Cluster Filter</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Select fund cluster to view analytics</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <!-- All Fund Clusters Button -->
                        <a href="{{ route('rsmi.analytics') }}?month={{ $month }}&department_id={{ $departmentId }}"
                            class="px-4 py-2 text-sm font-medium rounded-lg border transition-colors duration-200
                            {{ !$fundCluster ? 'bg-gray-800 text-white border-gray-800 dark:bg-gray-200 dark:text-gray-800 dark:border-gray-200' : 'text-gray-700 bg-white border-gray-300 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600' }}">
                            <span class="flex items-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <span>All Fund Clusters</span>
                            </span>
                        </a>

                        <!-- Fund Cluster 101 Button -->
                        <a href="{{ route('rsmi.analytics') }}?month={{ $month }}&fund_cluster=101&department_id={{ $departmentId }}"
                            class="px-4 py-2 text-sm font-medium rounded-lg border transition-colors duration-200
                            {{ $fundCluster == '101' ? 'bg-gray-800 text-white border-gray-800 dark:bg-gray-200 dark:text-gray-800 dark:border-gray-200' : 'text-gray-700 bg-white border-gray-300 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600' }}">
                            <span class="flex items-center space-x-2">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                <span>Fund Cluster 101</span>
                            </span>
                        </a>

                        <!-- Fund Cluster 151 Button -->
                        <a href="{{ route('rsmi.analytics') }}?month={{ $month }}&fund_cluster=151&department_id={{ $departmentId }}"
                            class="px-4 py-2 text-sm font-medium rounded-lg border transition-colors duration-200
                            {{ $fundCluster == '151' ? 'bg-gray-800 text-white border-gray-800 dark:bg-gray-200 dark:text-gray-800 dark:border-gray-200' : 'text-gray-700 bg-white border-gray-300 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600' }}">
                            <span class="flex items-center space-x-2">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                <span>Fund Cluster 151</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Summary Cards - Minimal Dashboard Style -->
            <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 w-full">
                <!-- Total Value Card -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                    <div class="text-center">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Total Value</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white">₱{{ number_format($analyticsData['summary_stats']['total_value'], 2) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total issued amount</p>
                    </div>
                </div>

                <!-- Items Issued Card -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                    <div class="text-center">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Items Issued</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($analyticsData['summary_stats']['unique_items']) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Unique supplies issued</p>
                    </div>
                </div>

                <!-- Departments Card -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                    <div class="text-center">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Departments</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($analyticsData['summary_stats']['unique_departments']) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Departments served</p>
                    </div>
                </div>

                <!-- Transactions Card -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                    <div class="text-center">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Transactions</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($analyticsData['summary_stats']['total_transactions']) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total transactions</p>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Category Distribution Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Distribution by Category</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total cost breakdown by supply categories</p>
                    </div>
                    <div class="p-6">
                        <div class="h-80">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- OPTION 4: Supply Efficiency Chart (HTML) -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Supply Efficiency Analysis</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Transaction frequency vs average cost per transaction</p>
                    </div>
                    <div class="p-6">
                        <div class="h-80">
                            <canvas id="supplyEfficiencyChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Top Expensive Items Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top 10 Most Expensive Items</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Items with highest total cost</p>
                    </div>
                    <div class="p-6">
                        <div class="h-80">
                            <canvas id="topExpensiveChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Department Distribution Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Department Distribution</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Cost distribution across departments</p>
                    </div>
                    <div class="p-6">
                        <div class="h-80">
                            <canvas id="departmentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Tables Section -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Top RIS Summary -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top RIS by Value</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Highest value requisitions</p>
                    </div>
                    <div class="p-6">
                        <div class="overflow-y-auto max-h-96">
                            <table class="w-full text-sm">
                                <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 sticky top-0">
                                    <tr>
                                        <th class="px-3 py-3 text-left font-medium text-gray-700 dark:text-gray-300">RIS No.</th>
                                        <th class="px-3 py-3 text-right font-medium text-gray-700 dark:text-gray-300">Value</th>
                                        <th class="px-3 py-3 text-center font-medium text-gray-700 dark:text-gray-300">Items</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($analyticsData['ris_summary'] as $ris)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-3 py-3 font-medium text-gray-900 dark:text-white">{{ $ris['ris_no'] }}</td>
                                        <td class="px-3 py-3 text-right text-gray-800 dark:text-gray-200 font-medium">₱{{ number_format($ris['total_cost'], 2) }}</td>
                                        <td class="px-3 py-3 text-center text-gray-600 dark:text-gray-400">{{ $ris['item_count'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Unit Cost Variance -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Unit Cost Variance</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Items with cost variations</p>
                    </div>
                    <div class="p-6">
                        <div class="overflow-y-auto max-h-96">
                            @if($analyticsData['unit_cost_analysis']->count() > 0)
                            <div class="space-y-3">
                                @foreach($analyticsData['unit_cost_analysis'] as $item)
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                    <p class="font-medium text-sm text-gray-900 dark:text-white truncate" title="{{ $item['item_name'] }}">
                                        {{ Str::limit($item['item_name'], 35) }}
                                    </p>
                                    <div class="mt-2 grid grid-cols-2 gap-2 text-xs text-gray-600 dark:text-gray-400">
                                        <span>Min: ₱{{ number_format($item['min_cost'], 2) }}</span>
                                        <span>Max: ₱{{ number_format($item['max_cost'], 2) }}</span>
                                    </div>
                                    <div class="mt-2">
                                        <span class="text-xs bg-gray-200 text-gray-800 px-2 py-1 rounded dark:bg-gray-600 dark:text-gray-200">
                                            Variance: ₱{{ number_format($item['cost_variance'], 2) }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                                <svg class="mx-auto h-12 w-12 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <p class="mt-2 text-sm">No cost variations found</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Insights -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Insights</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Key metrics at a glance</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Avg Transaction Value</span>
                                <span class="font-medium text-gray-900 dark:text-white">₱{{ number_format($analyticsData['summary_stats']['avg_transaction_value'], 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Highest Single Transaction</span>
                                <span class="font-medium text-gray-800 dark:text-gray-200">₱{{ number_format($analyticsData['summary_stats']['highest_single_transaction'], 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Total Categories</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $analyticsData['categories']->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Report Period</span>
                                <span class="font-medium text-gray-900 dark:text-white text-xs">
                                    {{ $analyticsData['summary_stats']['date_range']['start'] }} -
                                    {{ $analyticsData['summary_stats']['date_range']['end'] }}
                                </span>
                            </div>
                            @if($analyticsData['categories']->count() > 0)
                            <div class="pt-2 border-t dark:border-gray-600">
                                <div class="p-3 bg-gray-100 dark:bg-gray-600 rounded-lg">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Top Category</span>
                                    <div class="mt-1">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $analyticsData['categories']->first()['category'] }}</span>
                                        <span class="text-xs text-gray-700 dark:text-gray-300 ml-2">₱{{ number_format($analyticsData['categories']->first()['total_cost'], 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const analyticsData = {!! json_encode($analyticsData) !!};

            // Minimal gray color palette
            const colors = [
                'rgba(107, 114, 128, 0.8)',   // Gray-500
                'rgba(75, 85, 99, 0.8)',     // Gray-600
                'rgba(55, 65, 81, 0.8)',     // Gray-700
                'rgba(31, 41, 55, 0.8)',     // Gray-800
                'rgba(17, 24, 39, 0.8)',     // Gray-900
                'rgba(156, 163, 175, 0.8)',  // Gray-400
                'rgba(209, 213, 219, 0.8)',  // Gray-300
                'rgba(229, 231, 235, 0.8)',  // Gray-200
                'rgba(243, 244, 246, 0.8)',  // Gray-100
                'rgba(249, 250, 251, 0.8)'   // Gray-50
            ];

            // 1. Category Distribution Doughnut Chart
            if (document.getElementById('categoryChart')) {
                const categoryCtx = document.getElementById('categoryChart').getContext('2d');
                new Chart(categoryCtx, {
                    type: 'doughnut',
                    data: {
                        labels: analyticsData.categories.map(item => item.category),
                        datasets: [{
                            data: analyticsData.categories.map(item => item.total_cost),
                            backgroundColor: colors.slice(0, analyticsData.categories.length),
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true,
                                    font: { size: 11 }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const value = context.raw;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return `${context.label}: ₱${value.toLocaleString()} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // OPTION 4: Supply Efficiency Chart JavaScript
            if (document.getElementById('supplyEfficiencyChart')) {
                const efficiencyCtx = document.getElementById('supplyEfficiencyChart').getContext('2d');
                new Chart(efficiencyCtx, {
                    type: 'scatter',
                    data: {
                        datasets: [{
                            label: 'Supply Items',
                            data: analyticsData.supply_efficiency.map(item => ({
                                x: item.transaction_frequency,
                                y: item.avg_cost_per_transaction,
                                itemName: item.item_name,
                                stockNo: item.stock_no,
                                totalCost: item.total_cost,
                                efficiencyScore: item.efficiency_score
                            })),
                            backgroundColor: function(context) {
                                const value = context.parsed.y;
                                if (value > 10000) return 'rgba(31, 41, 55, 0.7)';      // High cost - dark
                                else if (value > 5000) return 'rgba(75, 85, 99, 0.7)';  // Medium cost - gray
                                else if (value > 1000) return 'rgba(107, 114, 128, 0.7)'; // Low cost - light gray
                                else return 'rgba(156, 163, 175, 0.7)';                  // Very low cost - lighter
                            },
                            borderColor: 'rgba(75, 85, 99, 1)',
                            borderWidth: 1,
                            pointRadius: function(context) {
                                // Size points based on total cost
                                const totalCost = context.raw.totalCost;
                                if (totalCost > 50000) return 8;
                                else if (totalCost > 20000) return 6;
                                else if (totalCost > 10000) return 4;
                                else return 3;
                            }
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    title: function(context) {
                                        return context[0].raw.itemName;
                                    },
                                    label: function(context) {
                                        const data = context.raw;
                                        return [
                                            `Stock No: ${data.stockNo}`,
                                            `Frequency: ${data.x} transactions`,
                                            `Avg Cost/Transaction: ₱${data.y.toLocaleString()}`,
                                            `Total Cost: ₱${data.totalCost.toLocaleString()}`,
                                            `Efficiency Score: ${data.efficiencyScore.toFixed(2)}`
                                        ];
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                type: 'linear',
                                position: 'bottom',
                                title: {
                                    display: true,
                                    text: 'Transaction Frequency'
                                },
                                grid: { color: 'rgba(156, 163, 175, 0.1)' },
                                ticks: { font: { size: 10 } }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Average Cost per Transaction (₱)'
                                },
                                grid: { color: 'rgba(156, 163, 175, 0.1)' },
                                ticks: {
                                    callback: function(value) {
                                        return '₱' + value.toLocaleString();
                                    },
                                    font: { size: 10 }
                                }
                            }
                        }
                    }
                });
            }

            // 3. Top Expensive Items Horizontal Bar Chart
            if (document.getElementById('topExpensiveChart')) {
                const topExpensiveCtx = document.getElementById('topExpensiveChart').getContext('2d');
                new Chart(topExpensiveCtx, {
                    type: 'bar',
                    data: {
                        labels: analyticsData.top_expensive_items.map(item => {
                            const name = item.item_name;
                            return name.length > 25 ? name.substring(0, 25) + '...' : name;
                        }),
                        datasets: [{
                            label: 'Total Cost',
                            data: analyticsData.top_expensive_items.map(item => item.total_cost),
                            backgroundColor: 'rgba(107, 114, 128, 0.8)',
                            borderColor: 'rgba(75, 85, 99, 1)',
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    title: function(context) {
                                        const index = context[0].dataIndex;
                                        return analyticsData.top_expensive_items[index].item_name;
                                    },
                                    label: function(context) {
                                        const index = context.dataIndex;
                                        const item = analyticsData.top_expensive_items[index];
                                        return [
                                            `Cost: ₱${context.raw.toLocaleString()}`,
                                            `Quantity: ${item.quantity.toLocaleString()}`,
                                            `Stock No: ${item.stock_no}`
                                        ];
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                grid: { color: 'rgba(156, 163, 175, 0.1)' },
                                ticks: {
                                    callback: function(value) {
                                        return '₱' + value.toLocaleString();
                                    },
                                    font: { size: 10 }
                                }
                            },
                            y: {
                                grid: { display: false },
                                ticks: { font: { size: 9 } }
                            }
                        }
                    }
                });
            }

            // 4. Department Distribution Chart
            if (document.getElementById('departmentChart')) {
                const departmentCtx = document.getElementById('departmentChart').getContext('2d');
                new Chart(departmentCtx, {
                    type: 'bar',
                    data: {
                        labels: analyticsData.departments.map(item => {
                            const name = item.department;
                            return name.length > 15 ? name.substring(0, 15) + '...' : name;
                        }),
                        datasets: [{
                            label: 'Total Cost',
                            data: analyticsData.departments.map(item => item.total_cost),
                            backgroundColor: colors.slice(0, analyticsData.departments.length),
                            borderColor: colors.slice(0, analyticsData.departments.length).map(color => color.replace('0.8', '1')),
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    title: function(context) {
                                        const index = context[0].dataIndex;
                                        return analyticsData.departments[index].department;
                                    },
                                    label: function(context) {
                                        const index = context.dataIndex;
                                        const dept = analyticsData.departments[index];
                                        return [
                                            `Cost: ₱${context.raw.toLocaleString()}`,
                                            `Items: ${dept.item_count.toLocaleString()}`,
                                            `Unique Items: ${dept.unique_items.toLocaleString()}`
                                        ];
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(156, 163, 175, 0.1)' },
                                ticks: {
                                    callback: function(value) {
                                        return '₱' + value.toLocaleString();
                                    },
                                    font: { size: 10 }
                                }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 10 } }
                            }
                        }
                    }
                });
            }
        });
    </script>

</x-app-layout>
