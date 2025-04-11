<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Assets Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <!-- Dashboard Cards Section -->
                <!-- Blue Gradient Banner -->
                <div class="relative w-full mb-8 py-12 sm:py-16 px-4 sm:px-6 lg:px-8 overflow-hidden bg-gradient-to-br from-blue-500 via-blue-600 to-blue-900 rounded-xl shadow-xl">
                    <!-- Wave SVG Decorations - Top -->
                    <div class="absolute top-0 left-0 right-0 h-20 overflow-hidden">
                        <svg class="absolute bottom-0 w-full h-20 text-white/10 fill-current" viewBox="0 0 1440 120" preserveAspectRatio="none">
                            <path d="M0,64L48,80C96,96,192,128,288,128C384,128,480,96,576,85.3C672,75,768,85,864,96C960,107,1056,117,1152,112C1248,107,1344,85,1392,74.7L1440,64L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path>
                        </svg>
                    </div>

                    <!-- Wave SVG Decorations - Bottom -->
                    <div class="absolute bottom-0 left-0 right-0 h-20 overflow-hidden">
                        <svg class="absolute bottom-0 w-full h-20 text-white/10 fill-current transform rotate-180" viewBox="0 0 1440 120" preserveAspectRatio="none">
                            <path d="M0,64L60,69.3C120,75,240,85,360,90.7C480,96,600,96,720,96C840,96,960,96,1080,85.3C1200,75,1320,53,1380,42.7L1440,32L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z"></path>
                        </svg>
                    </div>

                    <!-- Floating Decorative Elements - Responsive sizes -->
                    <div class="absolute inset-0 overflow-hidden pointer-events-none">
                        <!-- Box/Package Icon - Responsive sizing and positioning -->
                        <div class="absolute top-1/4 right-5 sm:right-8 lg:right-12 w-10 h-10 sm:w-16 sm:h-16 text-white/20 dark:text-white/10 transform transition-all duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full">
                                <path d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375z" />
                                <path fill-rule="evenodd" d="M3.087 9l.54 9.176A3 3 0 006.62 21h10.757a3 3 0 002.995-2.824L20.913 9H3.087zM12 10.5a.75.75 0 01.75.75v4.94l1.72-1.72a.75.75 0 111.06 1.06l-3 3a.75.75 0 01-1.06 0l-3-3a.75.75 0 111.06-1.06l1.72 1.72v-4.94a.75.75 0 01.75-.75z" clip-rule="evenodd" />
                            </svg>
                        </div>

                        <!-- Barcode Icon - Responsive sizing and positioning -->
                        <div class="absolute bottom-1/4 left-4 sm:left-8 lg:left-12 w-12 h-12 sm:w-16 sm:h-16 text-white/15 dark:text-white/5 transform transition-all duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full">
                                <path d="M4 5h2v14H4V5zm4 0h1v14H8V5zm2 0h3v14h-3V5zm4 0h1v14h-1V5zm3 0h2v14h-2V5zm3 0h1v14h-1V5z"/>
                            </svg>
                        </div>

                        <!-- Clipboard/Inventory Icon - Only visible on larger screens -->
                        <div class="hidden sm:block absolute top-1/2 right-1/3 transform -translate-y-1/2 w-10 h-10 lg:w-14 lg:h-14 text-white/10 dark:text-white/5 transition-all duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full">
                                <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0118 9.375v9.375a3 3 0 003-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 00-.673-.05A3 3 0 0015 1.5h-1.5a3 3 0 00-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6zM13.5 3A1.5 1.5 0 0012 4.5h4.5A1.5 1.5 0 0015 3h-1.5z" clip-rule="evenodd" />
                                <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 013 20.625V9.375zM6 12a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V12zm2.25 0a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75zM6 15a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V15zm2.25 0a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75zM6 18a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V18zm2.25 0a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                            </svg>
                        </div>

                        <!-- Additional decorative shapes that hide/show based on screen size -->
                        <div class="hidden md:block absolute bottom-1/3 right-1/4 w-20 h-20 bg-blue-300 rounded-full opacity-10 blur-xl"></div>
                        <div class="absolute top-1/3 left-1/3 w-12 h-12 sm:w-24 sm:h-24 bg-blue-200 rounded-full opacity-10 blur-2xl"></div>
                    </div>

                    <!-- Admin Welcome Message -->
                    <div class="relative text-center z-10">
                        <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">Welcome, Admin</h1>
                        <p class="text-blue-100 max-w-2xl mx-auto">Your inventory management dashboard is ready. Track stock levels, monitor transactions, and manage your inventory with ease.</p>
                    </div>
                </div>

                <!-- Floating Dashboard Cards - Using the same padding as the banner to align with its edges -->
                <div class="grid gap-6 grid-cols-1 lg:grid-cols-2">
                    <!-- Properties Card -->
                    <a href="{{ route('property.index') }}" class="block">
                        <div class="p-6 rounded-2xl shadow-xl dark:shadow-gray-900/30 cursor-pointer group relative overflow-hidden transition-all duration-300 hover:shadow-2xl hover:translate-y-1">
                            <!-- Background with gradient and subtle pattern -->
                            <div class="absolute inset-0 bg-gradient-to-r from-purple-100 to-purple-200 dark:from-purple-900 dark:to-purple-800 opacity-90"></div>
                            <!-- Decorative shapes -->
                            <div class="absolute -bottom-6 -right-6 w-24 h-24 rounded-full bg-purple-300 dark:bg-purple-700 opacity-40"></div>
                            <div class="absolute top-0 right-0 w-16 h-16 rounded-full bg-purple-400 dark:bg-purple-600 opacity-20 transform translate-x-6 -translate-y-6"></div>

                            <div class="flex justify-between relative z-10">
                                <dl class="space-y-2">
                                    <dt class="text-sm font-medium text-gray-700 dark:text-gray-300">Properties</dt>
                                    <dd class="text-4xl font-light md:text-5xl text-gray-900 dark:text-white">{{ $totalProperties }}</dd>
                                    <dd class="flex items-center space-x-1 text-sm font-medium text-green-600 dark:text-green-400">
                                        <span>5% increase</span>
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.25 15.25V6.75H8.75"></path>
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 7L6.75 17.25"></path>
                                        </svg>
                                    </dd>
                                </dl>
                                <div class="rounded-full p-3 bg-white dark:bg-gray-800 h-fit shadow-md transition-all duration-300 group-hover:bg-purple-500 group-hover:text-white dark:group-hover:bg-purple-600">
                                    <svg class="w-8 h-8 text-purple-500 dark:text-purple-400 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Locations Card -->
                    <a href="{{ route('location.index') }}" class="block">
                        <div class="p-6 rounded-2xl shadow-xl dark:shadow-gray-900/30 cursor-pointer group relative overflow-hidden transition-all duration-300 hover:shadow-2xl hover:translate-y-1">
                            <!-- Background with gradient and subtle pattern -->
                            <div class="absolute inset-0 bg-gradient-to-r from-emerald-100 to-emerald-200 dark:from-emerald-900 dark:to-emerald-800 opacity-90"></div>
                            <!-- Decorative shapes -->
                            <div class="absolute -bottom-6 -right-6 w-24 h-24 rounded-full bg-emerald-300 dark:bg-emerald-700 opacity-40"></div>
                            <div class="absolute top-0 right-0 w-16 h-16 rounded-full bg-emerald-400 dark:bg-emerald-600 opacity-20 transform translate-x-6 -translate-y-6"></div>

                            <div class="flex justify-between relative z-10">
                                <dl class="space-y-2">
                                    <dt class="text-sm font-medium text-gray-700 dark:text-gray-300">Locations</dt>
                                    <dd class="text-4xl font-light md:text-5xl text-gray-900 dark:text-white">{{ $totalLocations }}</dd>
                                    <dd class="flex items-center space-x-1 text-sm font-medium text-green-600 dark:text-green-400">
                                        <span>2 new</span>
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.25 15.25V6.75H8.75"></path>
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 7L6.75 17.25"></path>
                                        </svg>
                                    </dd>
                                </dl>
                                <div class="rounded-full p-3 bg-white dark:bg-gray-800 h-fit shadow-md transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white dark:group-hover:bg-emerald-600">
                                    <svg class="w-8 h-8 text-emerald-500 dark:text-emerald-400 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
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
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-gray-700 dark:text-gray-300"><strong>John Doe</strong> added a new employee.</p>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">2 hours ago</span>
                                    </div>
                                </li>
                                <!-- Activity: Edited -->
                                <li class="flex items-center">
                                    <div class="p-2 bg-yellow-100 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6-6m-6 6l-4 4v4h4l4-4"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-gray-700 dark:text-gray-300"><strong>Jane Smith</strong> edited property details.</p>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">5 hours ago</span>
                                    </div>
                                </li>
                                <!-- Activity: Removed -->
                                <li class="flex items-center">
                                    <div class="p-2 bg-red-100 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v2H9V4a1 1 0 011-1z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-gray-700 dark:text-gray-300"><strong>Admin</strong> removed a supply item.</p>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">1 day ago</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<!-- Include Chart.js via CDN -->

<script>
    document.addEventListener("DOMContentLoaded", function(){
        // Helper function to update chart text colors based on current mode
        function updateChartColors() {
            const isDark = document.documentElement.classList.contains('dark');
            const newLegendColor = isDark ? '#fff' : '#000';
            const newTickColor = isDark ? '#fff' : '#000';

            if(lineChart) {
                lineChart.options.plugins.legend.labels.color = newLegendColor;
                if(lineChart.options.scales.x) {
                    lineChart.options.scales.x.ticks.color = newTickColor;
                }
                if(lineChart.options.scales.y) {
                    lineChart.options.scales.y.ticks.color = newTickColor;
                }
                lineChart.update();
            }
            if(doughnutChart) {
                doughnutChart.options.plugins.legend.labels.color = newLegendColor;
                doughnutChart.update();
            }
            if(pieChart) {
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
        observer.observe(document.documentElement, { attributes: true });
    });
</script>

