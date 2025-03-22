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
                <section class="grid gap-6 md:grid-cols-4 p-4 md:p-8 w-full">

                    <!-- Users Card -->
                    <div class="p-6 bg-white shadow rounded-2xl dark:bg-gray-900 border-l-4 border-blue-500 transition-all duration-300 hover:shadow-lg hover:translate-y-1 hover:border-l-6 cursor-pointer">
                        <div class="flex justify-between">
                            <dl class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Employees</dt>
                                <dd class="text-4xl font-light md:text-5xl dark:text-white">{{ $totalUsers }}</dd>
                                @if($lastUpdated)
                                <dd class="flex items-center space-x-1 text-sm font-medium text-green-500 dark:text-green-400">
                                    <span>Updated {{ $lastUpdated->diffForHumans() }}</span>
                                </dd>
                                @endif
                            </dl>
                            <div class="rounded-full p-3 bg-blue-100 dark:bg-blue-900 h-fit transition-all duration-300 group-hover:bg-blue-200 dark:group-hover:bg-blue-800">
                                <svg class="w-8 h-8 text-blue-500 dark:text-blue-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Properties Card -->
                    <div class="p-6 bg-white shadow rounded-2xl dark:bg-gray-900 border-l-4 border-purple-500 transition-all duration-300 hover:shadow-lg hover:translate-y-1 hover:border-l-6 cursor-pointer">
                        <div class="flex justify-between">
                            <dl class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Properties</dt>
                                <dd class="text-4xl font-light md:text-5xl dark:text-white">873</dd>
                                <dd class="flex items-center space-x-1 text-sm font-medium text-green-500 dark:text-green-400">
                                    <span>5% increase</span>
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.25 15.25V6.75H8.75"></path>
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 7L6.75 17.25"></path>
                                    </svg>
                                </dd>
                            </dl>
                            <div class="rounded-full p-3 bg-purple-100 dark:bg-purple-900 h-fit transition-all duration-300 group-hover:bg-purple-200 dark:group-hover:bg-purple-800">
                                <svg class="w-8 h-8 text-purple-500 dark:text-purple-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Supplies Card -->
                    <div class="p-6 bg-white shadow rounded-2xl dark:bg-gray-900 border-l-4 border-amber-500 transition-all duration-300 hover:shadow-lg hover:translate-y-1 hover:border-l-6 cursor-pointer">
                        <div class="flex justify-between">
                            <dl class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Supplies in Storage</dt>
                                <dd class="text-4xl font-light md:text-5xl dark:text-white">1,205</dd>
                                <dd class="flex items-center space-x-1 text-sm font-medium text-red-500 dark:text-red-400">
                                    <span>3% decrease</span>
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.25 8.75V17.25H8.75"></path>
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 17L6.75 6.75"></path>
                                    </svg>
                                </dd>
                            </dl>
                            <div class="rounded-full p-3 bg-amber-100 dark:bg-amber-900 h-fit transition-all duration-300 group-hover:bg-amber-200 dark:group-hover:bg-amber-800">
                                <svg class="w-8 h-8 text-amber-500 dark:text-amber-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Locations Card -->
                    <div class="p-6 bg-white shadow rounded-2xl dark:bg-gray-900 border-l-4 border-emerald-500 transition-all duration-300 hover:shadow-lg hover:translate-y-1 hover:border-l-6 cursor-pointer">
                        <div class="flex justify-between">
                            <dl class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Locations</dt>
                                <dd class="text-4xl font-light md:text-5xl dark:text-white">42</dd>
                                <dd class="flex items-center space-x-1 text-sm font-medium text-green-500 dark:text-green-400">
                                    <span>2 new</span>
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.25 15.25V6.75H8.75"></path>
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 7L6.75 17.25"></path>
                                    </svg>
                                </dd>
                            </dl>
                            <div class="rounded-full p-3 bg-emerald-100 dark:bg-emerald-900 h-fit transition-all duration-300 group-hover:bg-emerald-200 dark:group-hover:bg-emerald-800">
                                <svg class="w-8 h-8 text-emerald-500 dark:text-emerald-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Charts Section -->
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

        </div>
    </div>
</x-app-layout>

<!-- Include Chart.js via CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function(){
    // Line Chart Initialization
    const ctxLine = document.getElementById("lineChart").getContext("2d");
    const lineChart = new Chart(ctxLine, {
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
            maintainAspectRatio: false
        }
    });

    // Doughnut Chart Initialization
    const ctxDoughnut = document.getElementById("doughnutChart").getContext("2d");
    const doughnutChart = new Chart(ctxDoughnut, {
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
            maintainAspectRatio: false
        }
    });
});
</script>
