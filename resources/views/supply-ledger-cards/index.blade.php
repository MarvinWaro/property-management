<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Supply Ledger Cards') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Filter and Search Controls -->
                    <div class="flex items-center justify-between mb-6">
                        <form method="GET" action="{{ route('supply-ledger-cards.index') }}" class="w-full max-w-lg flex items-center space-x-2">
                            <div class="flex-1">
                                <input type="text" name="search" value="{{ request()->get('search') }}"
                                    placeholder="Search supplies..."
                                    class="px-4 py-2 w-full border text-sm font-medium border-gray-300 rounded-lg
                                    focus:ring-1 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800
                                    dark:border-gray-700 dark:text-white" />
                            </div>

                            <div>
                                <select name="fund_cluster" class="px-4 py-2 border border-gray-300 rounded-lg text-sm
                                    focus:ring-1 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800
                                    dark:border-gray-700 dark:text-white">
                                    <option value="">All Fund Clusters</option>
                                    @foreach($fundClusters as $cluster)
                                        <option value="{{ $cluster }}"
                                            {{ request('fund_cluster') == $cluster ? 'selected' : '' }}>
                                            {{ $cluster }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="px-4 py-2 text-white bg-orange-600 rounded-lg hover:bg-orange-800
                                focus:ring-1 focus:outline-none focus:ring-orange-300 dark:bg-orange-600
                                dark:hover:bg-orange-700 dark:focus:ring-orange-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </form>
                    </div>

                    <!-- Supply List Table -->
                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-transparent border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 font-bold">Stock No</th>
                                    <th scope="col" class="px-6 py-3 font-bold">Item Name</th>
                                    <th scope="col" class="px-6 py-3 font-bold">Category</th>
                                    <th scope="col" class="px-6 py-3 font-bold">Unit</th>
                                    <th scope="col" class="px-6 py-3 text-right font-bold">Current Stock</th>
                                    <th scope="col" class="px-6 py-3 text-center font-bold">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($supplies as $supply)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
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
                                            {{ $supply->total_stock > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ number_format($supply->total_stock ?? 0) }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('supply-ledger-cards.show', $supply->supply_id) }}"
                                                class="px-3 py-2 text-xs font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-800">
                                                View Ledger Card
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            No ledger cards found. Add some stock to supplies first.
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
