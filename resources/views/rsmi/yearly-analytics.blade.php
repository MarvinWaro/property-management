<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                RSMI Yearly Analytics - {{ $year }}
            </h2>
            <div class="flex space-x-2">
                <!-- Year Selector -->
                <form method="GET" class="flex items-center space-x-2">
                    <select name="year" onchange="this.form.submit()"
                        class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <select name="fund_cluster" onchange="this.form.submit()"
                        class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="101" {{ $fundCluster == '101' ? 'selected' : '' }}>Fund Cluster 101</option>
                        <option value="102" {{ $fundCluster == '102' ? 'selected' : '' }}>Fund Cluster 102</option>
                    </select>
                </form>

                <a href="{{ route('rsmi.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors">
                    Back to RSMI
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Yearly Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
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
                                <dd class="text-2xl font-bold text-gray-900 dark:text-white">₱{{ number_format($yearlyData['summary']['total_value'], 2) }}</dd>
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
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Items</dt>
                                <dd class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($yearlyData['summary']['total_items']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total RIS</dt>
                                <dd class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($yearlyData['summary']['total_ris']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2h8a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm2 0h8v6H6V5z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Avg Monthly</dt>
                                <dd class="text-2xl font-bold text-gray-900 dark:text-white">₱{{ number_format($yearlyData['summary']['avg_monthly'], 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Peak Month</dt>
                                <dd class="text-lg font-bold text-gray-900 dark:text-white">{{ $yearlyData['summary']['peak_month'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Monthly Trend Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Monthly Spending Trend</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total cost per month throughout the year</p>
                    </div>
                    <div class="p-6">
                        <div class="h-80">
                            <canvas id="monthlyTrendChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Quarterly Comparison Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Quarterly Comparison</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Spending comparison by quarters</p>
                    </div>
                    <div class="p-6">
                        <div class="h-80">
                            <canvas id="quarterlyChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Category Yearly Distribution -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Category Distribution (Yearly)</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total spending by category for the entire year</p>
                    </div>
                    <div class="p-6">
                        <div class="h-80">
                            <canvas id="yearlyCategoryChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Top Items (Yearly) Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Top 15 Items (Yearly)</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Most expensive items across the entire year</p>
                    </div>
                    <div class="p-6">
                        <div class="h-80">
                            <canvas id="yearlyTopItemsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Tables Section -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
                <!-- Monthly Breakdown Table -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Monthly Breakdown</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Detailed monthly statistics</p>
                    </div>
                    <div class="p-6">
                        <div class="overflow-y-auto max-h-96">
                            <table class="w-full text-sm">
                                <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 sticky top-0">
                                    <tr>
                                        <th class="px-3 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Month</th>
                                        <th class="px-3 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Total</th>
                                        <th class="px-3 py-3 text-center font-medium text-gray-500 dark:text-gray-400">Items</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($yearlyData['monthly_breakdown'] as $month)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-3 py-3 font-medium text-gray-900 dark:text-white">{{ $month['month'] }}</td>
                                        <td class="px-3 py-3 text-right text-green-600 dark:text-green-400 font-medium">₱{{ number_format($month['total'], 2) }}</td>
                                        <td class="px-3 py-3 text-center text-gray-600 dark:text-gray-400">{{ $month['items'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Top Categories (Yearly) -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Top Categories</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Highest spending categories</p>
                    </div>
                    <div class="p-6">
                        <div class="overflow-y-auto max-h-96">
                            <div class="space-y-4">
                                @foreach($yearlyData['top_categories'] as $category)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="flex justify-between items-start">
                                        <span class="font-medium text-sm text-gray-900 dark:text-white">{{ $category['category'] }}</span>
                                        <span class="text-sm font-bold text-green-600 dark:text-green-400">₱{{ number_format($category['total'], 2) }}</span>
                                    </div>
                                    <div class="mt-2 grid grid-cols-2 gap-2 text-xs text-gray-500 dark:text-gray-400">
                                        <span>Items: {{ $category['items'] }}</span>
                                        <span>RIS: {{ $category['ris_count'] }}</span>
                                    </div>
                                    <div class="mt-2 w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($category['total'] / $yearlyData['summary']['total_value']) * 100 }}%"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Yearly Insights -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Yearly Insights</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Key trends and patterns</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Busiest Month</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $yearlyData['insights']['busiest_month'] }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-green-50 dark:bg-green-900/30 rounded-lg">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Most Expensive Item</span>
                                <span class="font-medium text-gray-900 dark:text-white text-xs">{{ Str::limit($yearlyData['insights']['most_expensive_item'], 20) }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-purple-50 dark:bg-purple-900/30 rounded-lg">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Most Used Category</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $yearlyData['insights']['most_used_category'] }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Growth vs Last Year</span>
                                <span class="font-medium {{ $yearlyData['insights']['growth_rate'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $yearlyData['insights']['growth_rate'] >= 0 ? '+' : '' }}{{ number_format($yearlyData['insights']['growth_rate'], 1) }}%
                                </span>
                            </div>
                            <div class="pt-2 border-t dark:border-gray-700">
                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Strongest Quarter</span>
                                    <div class="mt-1">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $yearlyData['insights']['strongest_quarter'] }}</span>
                                        <span class="text-xs text-blue-600 dark:text-blue-400 ml-2">₱{{ number_format($yearlyData['insights']['strongest_quarter_value'], 2) }}</span>
                                    </div>
                                </div>
                            </div>
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
            const yearlyData = {!! json_encode($yearlyData) !!};

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

            // 1. Monthly Trend Chart
            if (document.getElementById('monthlyTrendChart')) {
                const monthlyCtx = document.getElementById('monthlyTrendChart').getContext('2d');
                new Chart(monthlyCtx, {
                    type: 'line',
                    data: {
                        labels: yearlyData.monthly_breakdown.map(item => item.month),
                        datasets: [{
                            label: 'Monthly Spending',
                            data: yearlyData.monthly_breakdown.map(item => item.total),
                            borderColor: 'rgba(59, 130, 246, 1)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 5
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
                                        return `Total: ₱${context.raw.toLocaleString()}`;
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

            // 2. Quarterly Chart
            if (document.getElementById('quarterlyChart')) {
                const quarterlyCtx = document.getElementById('quarterlyChart').getContext('2d');
                new Chart(quarterlyCtx, {
                    type: 'bar',
                    data: {
                        labels: yearlyData.quarterly_data.map(item => item.quarter),
                        datasets: [{
                            label: 'Quarterly Spending',
                            data: yearlyData.quarterly_data.map(item => item.total),
                            backgroundColor: colors.slice(0, 4),
                            borderColor: colors.slice(0, 4).map(color => color.replace('0.8', '1')),
                            borderWidth: 1,
                            borderRadius: 6
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
                                        return `Total: ₱${context.raw.toLocaleString()}`;
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

            // 3. Yearly Category Distribution
            if (document.getElementById('yearlyCategoryChart')) {
                const yearlyCategoryCtx = document.getElementById('yearlyCategoryChart').getContext('2d');
                new Chart(yearlyCategoryCtx, {
                    type: 'doughnut',
                    data: {
                        labels: yearlyData.top_categories.map(item => item.category),
                        datasets: [{
                            data: yearlyData.top_categories.map(item => item.total),
                            backgroundColor: colors.slice(0, yearlyData.top_categories.length),
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

            // 4. Yearly Top Items Chart
            if (document.getElementById('yearlyTopItemsChart')) {
                const yearlyTopItemsCtx = document.getElementById('yearlyTopItemsChart').getContext('2d');
                new Chart(yearlyTopItemsCtx, {
                    type: 'bar',
                    data: {
                        labels: yearlyData.top_items.map(item => {
                            const name = item.item_name;
                            return name.length > 20 ? name.substring(0, 20) + '...' : name;
                        }),
                        datasets: [{
                            label: 'Total Cost',
                            data: yearlyData.top_items.map(item => item.total),
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            borderColor: 'rgba(16, 185, 129, 1)',
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
                                        return yearlyData.top_items[index].item_name;
                                    },
                                    label: function(context) {
                                        const index = context.dataIndex;
                                        const item = yearlyData.top_items[index];
                                        return [
                                            `Total: ₱${context.raw.toLocaleString()}`,
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
        });
    </script>



</x-app-layout>
