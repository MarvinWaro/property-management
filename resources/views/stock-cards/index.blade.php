<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Stock Cards') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <!-- Filter and Search Controls -->
                    <div class="flex items-center justify-between mb-6">
                        <form method="GET" action="{{ route('stock-cards.index') }}" class="w-full max-w-lg flex items-center space-x-2">
                            <div class="flex-1">
                                <input type="text" name="search" value="{{ request()->get('search') }}"
                                    placeholder="Search supplies..."
                                    class="px-4 py-2 w-full border text-sm font-medium border-gray-300 rounded-lg
                                    focus:ring-1 focus:ring-[#ce201f] focus:border-[#ce201f] dark:bg-gray-800
                                    dark:border-gray-700 dark:text-white transition-all duration-200" />
                            </div>

                            <div>
                                <select name="fund_cluster" class="px-4 py-2 border border-gray-300 rounded-lg text-sm
                                    focus:ring-1 focus:ring-[#ce201f] focus:border-[#ce201f] dark:bg-gray-800
                                    dark:border-gray-700 dark:text-white transition-all duration-200">
                                    <option value="">All Fund Clusters</option>
                                    @foreach($fundClusters as $cluster)
                                        <option value="{{ $cluster }}"
                                            {{ request('fund_cluster') == $cluster ? 'selected' : '' }}>
                                            {{ $cluster }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="px-4 py-2 text-white bg-[#ce201f] rounded-lg hover:bg-[#a01b1a]
                                focus:ring-2 focus:outline-none focus:ring-[#ce201f]/30 transition-all duration-200 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </form>
                    </div>

                    <!-- Supply List Table -->
                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">Stock No</th>
                                    <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">Item Name</th>
                                    <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">Category</th>
                                    <th scope="col" class="px-6 py-3 font-bold text-gray-800 dark:text-gray-200">Unit</th>
                                    <th scope="col" class="px-6 py-3 text-right font-bold text-gray-800 dark:text-gray-200">Current Stock</th>
                                    <th scope="col" class="px-6 py-3 text-center font-bold text-gray-800 dark:text-gray-200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($supplies as $supply)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $supply->stock_no }}
                                        </td>
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $supply->item_name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $supply->category->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $supply->unit_of_measurement }}
                                        </td>
                                        <td class="px-6 py-4 text-right font-semibold
                                            {{ $supply->total_stock > 0 ? 'text-[#10b981]' : 'text-[#ce201f]' }}">
                                            {{ number_format($supply->total_stock ?? 0) }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('stock-cards.show', $supply->supply_id) }}"
                                            class="inline-block w-auto whitespace-nowrap
                                                    px-3 py-2 text-xs font-medium text-white
                                                    bg-[#10b981] rounded-lg
                                                    hover:bg-[#059669]
                                                    focus:ring-2 focus:outline-none focus:ring-[#10b981]/30
                                                    transition-all duration-200 shadow-sm">
                                                View Stock Card
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center">
                                            <!-- Empty state content -->
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                    </path>
                                                </svg>
                                                <p class="text-lg font-medium text-gray-500 dark:text-gray-400">
                                                    No stock cards found</p>
                                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">
                                                    Add some stock to supplies first to view stock cards</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $supplies->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
