<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Dashboard Cards Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <section class="grid gap-6 lg:grid-cols-3 p-4 lg:p-8 w-full">
                    <!-- Employees Average Request per Month -->
                    <div
                        class="p-6 bg-white shadow rounded-2xl dark:bg-gray-900 border-l-4 border-purple-500
                               transition-all duration-300 hover:shadow-lg hover:translate-y-1 hover:border-l-6 cursor-pointer group">
                        <div class="flex justify-between">
                            <dl class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    Employees average request per month
                                </dt>
                                <dd class="text-4xl font-light md:text-5xl dark:text-white">
                                    30
                                </dd>
                                @if ($lastUpdated)
                                    <dd
                                        class="flex items-center space-x-1 text-sm font-medium text-green-500 dark:text-green-400">
                                        <span>Updated {{ $lastUpdated->diffForHumans() }}</span>
                                    </dd>
                                @endif
                            </dl>
                            <!-- Icon container -->
                            <div
                                class="rounded-full p-3 bg-purple-100 dark:bg-purple-900 h-fit
                                       transition-all duration-300 group-hover:bg-purple-200 dark:group-hover:bg-purple-800">
                                <!-- Example icon: Clipboard Document List (Heroicons) -->
                                <svg class="w-8 h-8 text-purple-500 dark:text-purple-300"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.75h4.5m0 0c.621 0 1.125.504 1.125 1.125m-5.625-.75v.75c0
                                             .621-.504 1.125-1.125 1.125H4.125C3.504 6 3 6.504 3
                                             7.125v10.125c0 .621.504 1.125 1.125 1.125h15.75c.621
                                             0 1.125-.504 1.125-1.125V7.125c0-.621-.504-1.125-1.125-1.125h-4.125a1.125
                                             1.125 0 01-1.125-1.125V3.75M9 12h6m-6 3h3" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Supplies in Storage -->
                    <div
                        class="p-6 bg-white shadow rounded-2xl dark:bg-gray-900 border-l-4 border-orange-500
                               transition-all duration-300 hover:shadow-lg hover:translate-y-1 hover:border-l-6 cursor-pointer group">
                        <div class="flex justify-between">
                            <dl class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    Supplies in Storage
                                </dt>
                                <dd class="text-4xl font-light md:text-5xl dark:text-white">
                                    1,205
                                </dd>
                                <dd
                                    class="flex items-center space-x-1 text-sm font-medium text-red-500 dark:text-red-400">
                                    <span>3% decrease</span>
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17.25 8.75V17.25H8.75" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17L6.75 6.75" />
                                    </svg>
                                </dd>
                            </dl>
                            <!-- Icon container -->
                            <div
                                class="rounded-full p-3 bg-orange-100 dark:bg-orange-900 h-fit
                                       transition-all duration-300 group-hover:bg-orange-200 dark:group-hover:bg-orange-800">
                                <!-- Example icon: Archive Box (Heroicons) -->
                                <svg class="w-8 h-8 text-orange-500 dark:text-orange-300"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 4.5h17.25c.621 0 1.125.504
                                             1.125 1.125v1.5c0 .621-.504 1.125-1.125
                                             1.125h-.375v9.75c0 .621-.504 1.125-1.125
                                             1.125H4.875c-.621 0-1.125-.504-1.125-1.125v-9.75H3.375c-.621
                                             0-1.125-.504-1.125-1.125v-1.5c0-.621.504-1.125
                                             1.125-1.125zM9.75 9.75h4.5m-4.5 3h4.5" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Total Transaction Cost -->
                    <div
                        class="p-6 bg-white shadow rounded-2xl dark:bg-gray-900 border-l-4 border-teal-500
                               transition-all duration-300 hover:shadow-lg hover:translate-y-1 hover:border-l-6 cursor-pointer group">
                        <div class="flex justify-between">
                            <dl class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    Total Transaction Cost
                                </dt>
                                <dd class="text-4xl font-light md:text-5xl dark:text-white">
                                    601,000.00
                                </dd>
                                <dd
                                    class="flex items-center space-x-1 text-sm font-medium text-green-500 dark:text-green-400">
                                    <span>2 new</span>
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17.25 15.25V6.75H8.75" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 7L6.75 17.25" />
                                    </svg>
                                </dd>
                            </dl>
                            <!-- Icon container -->
                            <div
                                class="rounded-full p-3 bg-teal-100 dark:bg-teal-900 h-fit
                                    transition-all duration-300 group-hover:bg-teal-200 dark:group-hover:bg-teal-800">
                                <!-- Example icon: Banknotes (Heroicons) -->
                                <svg class="w-8 h-8 text-teal-500 dark:text-teal-300" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12c0-1.657-1.343-3-3-3H6c-1.657
                                            0-3 1.343-3 3v6c0 1.657 1.343 3 3
                                            3h12c1.657 0 3-1.343 3-3v-6zM3
                                            9V6c0-1.657 1.343-3 3-3h12c1.657
                                            0 3 1.343 3 3v3M8.25 12a2.25 2.25
                                            0 104.5 0 2.25 2.25 0 00-4.5 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
            <!-- Existing Charts Section -->
            <div class="grid grid-cols-12 gap-6 mt-8">
                <!-- Line Chart (8 columns) -->
                <div class="col-span-12 md:col-span-8">
                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-4">
                        <canvas id="lineChart" class="w-full h-64"></canvas>
                    </div>
                </div>
                <!-- Doughnut Chart (4 columns) -->
                <div class="col-span-12 md:col-span-4">
                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-4">
                        <canvas id="doughnutChart" class="w-full h-64"></canvas>
                    </div>
                </div>
            </div>
            <!-- New Section: Pie Chart and Recent Activities (4:8 grid, leveled) -->
            <div class="grid grid-cols-12 gap-6 mt-8">
                <!-- Pie Chart (4 columns) -->
                <div class="col-span-12 md:col-span-4">
                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-4 h-64">
                        <canvas id="pieChart" class="w-full h-full"></canvas>
                    </div>
                </div>
                <!-- Recent Activities (8 columns) -->
                <div class="col-span-12 md:col-span-8">
                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-4 h-64 flex flex-col">
                        <h3 class="text-lg font-semibold mb-4 dark:text-white">Recent Activities</h3>
                        <ul class="space-y-4 flex-1">
                            <!-- Activity: Added -->
                            <li class="flex items-center">
                                <div class="p-2 bg-green-100 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-700 dark:text-gray-300"><strong>John Doe</strong> added a new
                                        employee.</p>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">2 hours ago</span>
                                </div>
                            </li>
                            <!-- Activity: Edited -->
                            <li class="flex items-center">
                                <div class="p-2 bg-yellow-100 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536M9 11l6-6m-6 6l-4 4v4h4l4-4" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-700 dark:text-gray-300"><strong>Jane Smith</strong> edited
                                        property details.</p>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">5 hours ago</span>
                                </div>
                            </li>
                            <!-- Activity: Removed -->
                            <li class="flex items-center">
                                <div class="p-2 bg-red-100 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v2H9V4a1 1 0 011-1z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-700 dark:text-gray-300"><strong>Admin</strong> removed a supply
                                        item.</p>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">1 day ago</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<!-- Include Chart.js via CDN -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Helper function to update chart text colors based on current mode
        function updateChartColors() {
            const isDark = document.documentElement.classList.contains('dark');
            const newLegendColor = isDark ? '#fff' : '#000';
            const newTickColor = isDark ? '#fff' : '#000';

            if (lineChart) {
                lineChart.options.plugins.legend.labels.color = newLegendColor;
                if (lineChart.options.scales.x) {
                    lineChart.options.scales.x.ticks.color = newTickColor;
                }
                if (lineChart.options.scales.y) {
                    lineChart.options.scales.y.ticks.color = newTickColor;
                }
                lineChart.update();
            }
            if (doughnutChart) {
                doughnutChart.options.plugins.legend.labels.color = newLegendColor;
                doughnutChart.update();
            }
            if (pieChart) {
                pieChart.options.plugins.legend.labels.color = newLegendColor;
                pieChart.update();
            }
        }

        let lineChart, doughnutChart, pieChart;

        // Line Chart Initialization
        const ctxLine = document.getElementById("lineChart").getContext("2d");
        lineChart = new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: ["January", "February", "March", "April", "May", "June"],
                datasets: [{
                    label: "User Growth",
                    data: [100, 150, 200, 180, 220, 300],
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000'
                        }
                    },
                    y: {
                        ticks: {
                            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000'
                        }
                    }
                }
            }
        });

        // Doughnut Chart Initialization
        const ctxDoughnut = document.getElementById("doughnutChart").getContext("2d");
        doughnutChart = new Chart(ctxDoughnut, {
            type: 'doughnut',
            data: {
                labels: ["Properties", "Supplies", "Locations"],
                datasets: [{
                    label: "Distribution",
                    data: [300, 50, 100],
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(75, 192, 192)'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000'
                        }
                    }
                }
            }
        });

        // Pie Chart Initialization
        const ctxPie = document.getElementById("pieChart").getContext("2d");
        pieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Added', 'Edited', 'Removed'],
                datasets: [{
                    label: 'Activities',
                    data: [12, 7, 3],
                    backgroundColor: [
                        'rgb(34, 197, 94)',
                        'rgb(234, 179, 8)',
                        'rgb(239, 68, 68)'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000'
                        }
                    }
                }
            }
        });

        // Set up a MutationObserver to watch for class changes (dark mode toggle)
        const observer = new MutationObserver((mutationsList) => {
            for (const mutation of mutationsList) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    updateChartColors();
                }
            }
        });
        observer.observe(document.documentElement, {
            attributes: true
        });
    });
</script>
