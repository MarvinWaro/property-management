<!-- resources/views/supply-transaction/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Supply Transactions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-5">
                    <form method="GET" action="{{ route('supply-transactions.index') }}" class="flex flex-wrap gap-2 mb-4">
                        <input type="date" name="from" value="{{ request('from') }}" class="px-4 py-2 border rounded-lg" />
                        <input type="date" name="to"   value="{{ request('to') }}" class="px-4 py-2 border rounded-lg" />
                        <select name="type" class="px-4 py-2 border rounded-lg">
                            <option value="">All Types</option>
                            <option value="receipt" @selected(request('type')=='receipt')>Receipt</option>
                            <option value="issue"   @selected(request('type')=='issue')>Issue</option>
                            <option value="adjustment" @selected(request('type')=='adjustment')>Adjustment</option>
                        </select>
                        <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded-lg">Filter</button>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-white uppercase bg-gradient-to-r from-blue-600 to-blue-800 sticky top-0">
                                <tr>
                                    <th class="px-6 py-3">Date</th>
                                    <th class="px-6 py-3">Type</th>
                                    <th class="px-6 py-3">Stock No</th>
                                    <th class="px-6 py-3">Item Name</th>
                                    <th class="px-6 py-3">Qty</th>
                                    <th class="px-6 py-3">Balance</th>
                                    <th class="px-6 py-3">Ref No</th>
                                    <th class="px-6 py-3">Department</th>
                                    <th class="px-6 py-3">User</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                @forelse($txns as $txn)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4">{{ $txn->transaction_date->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4">{{ ucfirst($txn->transaction_type) }}</td>
                                        <td class="px-6 py-4">{{ $txn->supply->stock_no }}</td>
                                        <td class="px-6 py-4">{{ $txn->supply->item_name }}</td>
                                        <td class="px-6 py-4">{{ $txn->quantity }}</td>
                                        <td class="px-6 py-4">{{ $txn->balance_quantity }}</td>
                                        <td class="px-6 py-4">{{ $txn->reference_no }}</td>
                                        <td class="px-6 py-4">{{ $txn->department->name }}</td>
                                        <td class="px-6 py-4">{{ $txn->user->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-8 text-center text-gray-500">No transactions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $txns->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
