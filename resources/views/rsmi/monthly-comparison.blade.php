<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Monthly Comparison - {{ $year }}
            </h2>
            <a href="{{ route('rsmi.index') }}"
                class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200 dark:text-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to RSMI
                </div>
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow-md">
                <div class="p-6">
                    <!-- Filter Controls -->
                    <div class="mb-8 bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <form method="GET" action="{{ route('rsmi.monthly-comparison') }}" class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-6">
                            <div class="flex items-center space-x-3">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 w-16">Year:</label>
                                <select name="year" onchange="this.form.submit()"
                                    class="flex-grow px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-[#ce201f] focus:border-[#ce201f] dark:bg-gray-800 dark:border-gray-700 dark:text-white transition-all duration-200">
                                    @php
                                        $currentYear = date('Y');
                                        // Always show from current year down to 2024
                                        $startYear = 2024;
                                        $years = range($currentYear, $startYear);
                                    @endphp
                                    @foreach($years as $y)
                                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center space-x-3">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">Fund Cluster:</label>
                                <select name="fund_cluster" onchange="this.form.submit()"
                                    class="flex-grow px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-[#ce201f] focus:border-[#ce201f] dark:bg-gray-800 dark:border-gray-700 dark:text-white transition-all duration-200">
                                    <option value="101" {{ $fundCluster == '101' ? 'selected' : '' }}>101</option>
                                    <option value="151" {{ $fundCluster == '151' ? 'selected' : '' }}>151</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    <!-- Chart Container -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700 mb-8">
                        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">Monthly Issuance Trend - {{ $year }}</h3>
                        <div class="h-[300px] relative" id="chart-container">
                            <canvas id="monthlyChart"></canvas>
                            <!-- Fallback message if chart doesn't render -->
                            <div id="chart-fallback" class="absolute inset-0 flex items-center justify-center text-gray-500 dark:text-gray-400 hidden">
                                <div class="text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <p>Chart data is loading or no data available</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Data Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border-collapse">
                            <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">Month</th>
                                    <th scope="col" class="px-6 py-3 text-right font-bold text-gray-800 dark:text-gray-200">Total Amount Issued</th>
                                    <th scope="col" class="px-6 py-3 text-center font-bold text-gray-800 dark:text-gray-200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $yearTotal = 0; @endphp
                                @foreach($monthlyData as $data)
                                    @php $yearTotal += $data['total']; @endphp
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $data['month'] }}
                                        </td>
                                        <td class="px-6 py-4 text-right {{ $data['total'] > 0 ? 'text-[#10b981] font-medium' : 'text-gray-600 dark:text-gray-400' }}">
                                            ₱{{ number_format($data['total'], 4) }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($data['total'] > 0)
                                                <a href="{{ route('rsmi.generate') }}?month={{ $year }}-{{ str_pad(array_search($data['month'], ['January','February','March','April','May','June','July','August','September','October','November','December']) + 1, 2, '0', STR_PAD_LEFT) }}"
                                                    class="p-2 text-[#10b981] hover:bg-[#10b981]/10 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#10b981]/30 transition-all duration-200 inline-flex items-center justify-center"
                                                    data-tooltip-target="tooltip-view-report-{{ str_replace(' ', '-', strtolower($data['month'])) }}"
                                                    data-tooltip-placement="left">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                        <polyline points="14 2 14 8 20 8"></polyline>
                                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                                        <polyline points="10 9 9 9 8 9"></polyline>
                                                    </svg>
                                                </a>
                                                <div id="tooltip-view-report-{{ str_replace(' ', '-', strtolower($data['month'])) }}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                    View {{ $data['month'] }} Report
                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                </div>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">No data</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-50 dark:bg-gray-700 font-bold">
                                    <td class="px-6 py-4 text-gray-800 dark:text-gray-200">YEAR TOTAL</td>
                                    <td class="px-6 py-4 text-right text-[#10b981] font-bold">
                                        ₱{{ number_format($yearTotal, 4) }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if the container exists
            const chartContainer = document.getElementById('chart-container');
            const fallbackMessage = document.getElementById('chart-fallback');
            const ctx = document.getElementById('monthlyChart');

            if (!ctx) {
                console.error('Chart canvas element not found');
                if (fallbackMessage) fallbackMessage.classList.remove('hidden');
                return;
            }

            try {
                // Chart configuration
                const monthlyLabels = {!! json_encode($monthlyData->pluck('month')) !!};
                const monthlyValues = {!! json_encode($monthlyData->pluck('total')) !!};

                // Check if we have valid data
                if (!monthlyLabels || !monthlyValues || monthlyLabels.length === 0) {
                    throw new Error('No chart data available');
                }

                const monthlyChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: monthlyLabels,
                        datasets: [{
                            label: 'Total Amount Issued',
                            data: monthlyValues,
                            backgroundColor: 'rgba(16, 185, 129, 0.2)',
                            borderColor: 'rgb(16, 185, 129)',
                            borderWidth: 1,
                            borderRadius: 4,
                            hoverBackgroundColor: 'rgba(16, 185, 129, 0.4)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let value = context.raw;
                                        return '₱' + value.toLocaleString(undefined, {
                                            minimumFractionDigits: 4,
                                            maximumFractionDigits: 4
                                        });
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(156, 163, 175, 0.1)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return '₱' + value.toLocaleString();
                                    },
                                    font: {
                                        size: 10
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 10
                                    }
                                }
                            }
                        }
                    }
                });

                // Set a custom attribute to indicate the chart is loaded
                ctx.setAttribute('data-chart-loaded', 'true');

            } catch (error) {
                console.error('Error initializing chart:', error);
                if (fallbackMessage) fallbackMessage.classList.remove('hidden');
            }
        });
    </script>

</x-app-layout>
