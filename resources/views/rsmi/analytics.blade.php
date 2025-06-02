<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                RSMI Analytics - {{ $startDate->format('F Y') }}
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Value</dt>
                                <dd class="text-2xl font-bold text-gray-900 dark:text-white">₱{{ number_format($analyticsData['summary_stats']['total_value'], 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Items Issued</dt>
                                <dd class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($analyticsData['summary_stats']['unique_items']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Departments</dt>
                                <dd class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($analyticsData['summary_stats']['unique_departments']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Transactions</dt>
                                <dd class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($analyticsData['summary_stats']['total_transactions']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Category Distribution Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Distribution by Category</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total cost breakdown by supply categories</p>
                    </div>
                    <div class="p-6">
                        <div class="h-80">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Daily Trend Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Daily Issuance Trend</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Daily total cost of issued supplies</p>
                    </div>
                    <div class="p-6">
                        <div class="h-80">
                            <canvas id="dailyTrendChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Top Expensive Items Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Top 10 Most Expensive Items</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Items with highest total cost</p>
                    </div>
                    <div class="p-6">
                        <div class="h-80">
                            <canvas id="topExpensiveChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Department Distribution Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Department Distribution</h3>
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
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
                <!-- Top RIS Summary -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Top RIS by Value</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Highest value requisitions</p>
                    </div>
                    <div class="p-6">
                        <div class="overflow-y-auto max-h-96">
                            <table class="w-full text-sm">
                                <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 sticky top-0">
                                    <tr>
                                        <th class="px-3 py-3 text-left font-medium text-gray-500 dark:text-gray-400">RIS No.</th>
                                        <th class="px-3 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Value</th>
                                        <th class="px-3 py-3 text-center font-medium text-gray-500 dark:text-gray-400">Items</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($analyticsData['ris_summary'] as $ris)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-3 py-3 font-medium text-gray-900 dark:text-white">{{ $ris['ris_no'] }}</td>
                                        <td class="px-3 py-3 text-right text-green-600 dark:text-green-400 font-medium">₱{{ number_format($ris['total_cost'], 2) }}</td>
                                        <td class="px-3 py-3 text-center text-gray-600 dark:text-gray-400">{{ $ris['item_count'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Unit Cost Variance -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Unit Cost Variance</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Items with cost variations</p>
                    </div>
                    <div class="p-6">
                        <div class="overflow-y-auto max-h-96">
                            @if($analyticsData['unit_cost_analysis']->count() > 0)
                            <div class="space-y-4">
                                @foreach($analyticsData['unit_cost_analysis'] as $item)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <p class="font-medium text-sm text-gray-900 dark:text-white truncate" title="{{ $item['item_name'] }}">
                                        {{ Str::limit($item['item_name'], 40) }}
                                    </p>
                                    <div class="mt-2 grid grid-cols-2 gap-2 text-xs text-gray-500 dark:text-gray-400">
                                        <span>Min: ₱{{ number_format($item['min_cost'], 2) }}</span>
                                        <span>Max: ₱{{ number_format($item['max_cost'], 2) }}</span>
                                    </div>
                                    <div class="mt-2">
                                        <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded dark:bg-red-900 dark:text-red-300">
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
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Quick Insights</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Key metrics at a glance</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Avg Transaction Value</span>
                                <span class="font-medium text-gray-900 dark:text-white">₱{{ number_format($analyticsData['summary_stats']['avg_transaction_value'], 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Highest Single Transaction</span>
                                <span class="font-medium text-green-600 dark:text-green-400">₱{{ number_format($analyticsData['summary_stats']['highest_single_transaction'], 2) }}</span>
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
                            <div class="pt-2 border-t dark:border-gray-700">
                                <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Top Category</span>
                                    <div class="mt-1">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $analyticsData['categories']->first()['category'] }}</span>
                                        <span class="text-xs text-green-600 dark:text-green-400 ml-2">₱{{ number_format($analyticsData['categories']->first()['total_cost'], 2) }}</span>
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

            // Color palette
            const colors = [
                'rgba(59, 130, 246, 0.8)',   // Blue
                'rgba(16, 185, 129, 0.8)',   // Green
                'rgba(245, 158, 11, 0.8)',   // Yellow
                'rgba(239, 68, 68, 0.8)',    // Red
                'rgba(139, 92, 246, 0.8)',   // Purple
                'rgba(236, 72, 153, 0.8)',   // Pink
                'rgba(14, 165, 233, 0.8)',   // Sky
                'rgba(34, 197, 94, 0.8)',    // Emerald
                'rgba(249, 115, 22, 0.8)',   // Orange
                'rgba(168, 85, 247, 0.8)'    // Violet
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

            // 2. Daily Trend Line Chart
            if (document.getElementById('dailyTrendChart')) {
                const dailyCtx = document.getElementById('dailyTrendChart').getContext('2d');
                new Chart(dailyCtx, {
                    type: 'line',
                    data: {
                        labels: analyticsData.daily_trend.map(item => {
                            const date = new Date(item.date);
                            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                        }),
                        datasets: [{
                            label: 'Daily Cost',
                            data: analyticsData.daily_trend.map(item => item.total_cost),
                            borderColor: 'rgba(59, 130, 246, 1)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `Cost: ₱${context.raw.toLocaleString()}`;
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
                            backgroundColor: 'rgba(239, 68, 68, 0.8)',
                            borderColor: 'rgba(239, 68, 68, 1)',
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
