<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Monthly Comparison - {{ $year }}
            </h2>
            <a href="{{ route('rsmi.index') }}"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                Back to RSMI
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                <div class="p-6">
                    <!-- Year Selection -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('rsmi.monthly-comparison') }}" class="flex items-center space-x-4">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Year:</label>
                            <select name="year" onchange="this.form.submit()"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Fund Cluster:</label>
                            <select name="fund_cluster" onchange="this.form.submit()"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                                <option value="101" {{ $fundCluster == '101' ? 'selected' : '' }}>101</option>
                                <option value="102" {{ $fundCluster == '102' ? 'selected' : '' }}>102</option>
                            </select>
                        </form>
                    </div>

                    <!-- Chart Container -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <canvas id="monthlyChart"></canvas>
                    </div>

                    <!-- Monthly Data Table -->
                    <div class="mt-6 overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Month</th>
                                    <th scope="col" class="px-6 py-3 text-right">Total Amount Issued</th>
                                    <th scope="col" class="px-6 py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $yearTotal = 0; @endphp
                                @foreach($monthlyData as $data)
                                    @php $yearTotal += $data['total']; @endphp
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4 font-medium">{{ $data['month'] }}</td>
                                        <td class="px-6 py-4 text-right {{ $data['total'] > 0 ? 'text-green-600 dark:text-green-400' : '' }}">
                                            ₱{{ number_format($data['total'], 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($data['total'] > 0)
                                                <a href="{{ route('rsmi.generate') }}?month={{ $year }}-{{ str_pad(array_search($data['month'], ['January','February','March','April','May','June','July','August','September','October','November','December']) + 1, 2, '0', STR_PAD_LEFT) }}"
                                                    class="text-blue-600 hover:text-blue-900">View Report</a>
                                            @else
                                                <span class="text-gray-400">No data</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-100 dark:bg-gray-700 font-bold">
                                    <td class="px-6 py-4">YEAR TOTAL</td>
                                    <td class="px-6 py-4 text-right text-blue-600 dark:text-blue-400">
                                        ₱{{ number_format($yearTotal, 2) }}
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

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyData->pluck('month')) !!},
                datasets: [{
                    label: 'Total Amount Issued',
                    data: {!! json_encode($monthlyData->pluck('total')) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
