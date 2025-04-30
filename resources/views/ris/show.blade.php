<!-- resources/views/ris/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Requisition and Issue Slip') }}: {{ $risSlip->ris_no }}
            </h2>
            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <!-- Status / Action Bar -->
                <div class="p-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                    <div class="flex items-center">
                        <span class="mr-2 text-gray-700 dark:text-gray-300">Status:</span>
                        @if($risSlip->status === 'draft')
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-200 text-gray-800">Draft</span>
                        @elseif($risSlip->status === 'approved')
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-200 text-blue-800">Approved</span>
                        @elseif($risSlip->status === 'posted')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-200 text-green-800">Issued</span>
                        @endif
                    </div>

                    <a href="{{ route('ris.print', $risSlip) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-2">
                        Print RIS
                    </a>

                    @if(auth()->user()->hasRole('admin') && $risSlip->status === 'draft')
                        <button id="openApproveModal" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Approve Request
                        </button>
                    @endif

                    @if(auth()->user()->hasRole('admin') && $risSlip->status === 'approved')
                        <button id="openIssueModal" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Process Issuance
                        </button>
                    @endif
                </div>

                <!-- Approve Modal -->
                @if(auth()->user()->hasRole('admin'))
                    <div id="approveModal" class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center hidden">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md">
                            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Approve RIS #{{ $risSlip->ris_no }}
                                </h3>
                                <button id="closeApproveModal" class="text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-gray-200">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <form action="{{ route('ris.approve', $risSlip) }}" method="POST">
                                @csrf
                                <div class="p-6">
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fund Cluster</label>
                                        <select name="fund_cluster" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                                            <option value="101" {{ $risSlip->fund_cluster == "101" ? 'selected' : '' }}>101</option>
                                            <option value="151" {{ $risSlip->fund_cluster == "151" ? 'selected' : '' }}>151</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                                    <button type="button" id="cancelApprove" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 mr-2">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-green-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Approve Request
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                <div class="p-6">
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Header Information -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Entity Name</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $risSlip->entity_name }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Fund Cluster</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $risSlip->fund_cluster }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">RIS Date</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $risSlip->ris_date->format('F d, Y') }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Division</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $risSlip->department->name ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Office</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $risSlip->office ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Responsibility Center Code</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $risSlip->responsibility_center_code ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Purpose -->
                    <div class="mb-6">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Purpose</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $risSlip->purpose }}</p>
                    </div>

                    <!-- Items Table -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Requested Items</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stock No.</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity Requested</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stock Available?</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity Issued</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                                    @foreach($risSlip->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $item->supply->stock_no ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $item->supply->item_name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $item->quantity_requested }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if($item->stock_available)
                                                    <span class="px-2 py-1 text-xs rounded-full bg-green-200 text-green-800">Yes</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs rounded-full bg-red-200 text-red-800">No</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $item->quantity_issued ?? 'Pending' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $item->remarks ?? '' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Signatures -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Requested By:</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">{{ $risSlip->requester->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ optional($risSlip->requester)->designation->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $risSlip->created_at->format('M d, Y h:i A') }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Approved By:</p>
                            @if($risSlip->approved_by)
                                <p class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">{{ optional($risSlip->approver)->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ optional($risSlip->approver)->designation->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $risSlip->approved_at->format('M d, Y h:i A') }}</p>
                            @else
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pending</p>
                            @endif
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Issued By:</p>
                            @if($risSlip->issued_by)
                                <p class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">{{ optional($risSlip->issuer)->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ optional($risSlip->issuer)->designation->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $risSlip->issued_at->format('M d, Y h:i A') }}</p>
                            @else
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pending</p>
                            @endif
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Received By:</p>
                            @if($risSlip->received_by)
                                <p class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">{{ optional($risSlip->receiver)->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ optional($risSlip->receiver)->designation->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $risSlip->received_at->format('M d, Y h:i A') }}</p>
                            @else
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pending</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Issue Modal -->
    @if(auth()->user()->hasRole('admin'))
        <div id="issueModal" class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl max-h-screen overflow-y-auto">
                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Process Issuance for RIS #{{ $risSlip->ris_no }}
                    </h3>
                    <button id="closeIssueModal" class="text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-gray-200">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('ris.issue', $risSlip) }}" method="POST">
                    @csrf
                    <div class="p-6">
                        <p class="mb-4 text-sm text-gray-700 dark:text-gray-300">
                            Specify the quantities to be issued for each requested item.
                        </p>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Item</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Requested</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Available</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Issue Quantity</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                                    @foreach($risSlip->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                                {{ $item->supply->item_name ?? 'N/A' }}
                                                <input type="hidden" name="items[{{ $loop->index }}][item_id]" value="{{ $item->item_id }}">
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                                {{ $item->quantity_requested }}
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                @php
                                                    // Get total availability across all fund clusters
                                                    $totalAvailable = App\Models\SupplyStock::where('supply_id', $item->supply_id)
                                                        ->where('status', 'available')
                                                        ->sum('quantity_on_hand');

                                                    // Get available in the specified fund cluster
                                                    $matchingFundAvailable = App\Models\SupplyStock::where('supply_id', $item->supply_id)
                                                        ->where('status', 'available')
                                                        ->where('fund_cluster', $risSlip->fund_cluster)
                                                        ->sum('quantity_on_hand');
                                                @endphp

                                                @if($totalAvailable >= $item->quantity_requested)
                                                    <span class="px-2 py-1 text-xs rounded-full bg-green-200 text-green-800">{{ $totalAvailable }}</span>
                                                @elseif($totalAvailable > 0)
                                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-200 text-yellow-800">{{ $totalAvailable }}</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs rounded-full bg-red-200 text-red-800">0</span>
                                                @endif

                                                @if($matchingFundAvailable < $totalAvailable)
                                                    <p class="mt-1 text-xs text-gray-500">
                                                        ({{ $matchingFundAvailable }} from fund {{ $risSlip->fund_cluster }})
                                                    </p>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <input type="number" name="items[{{ $loop->index }}][quantity_issued]"
                                                    class="w-full px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white"
                                                    min="0" max="{{ min($totalAvailable, $item->quantity_requested) }}"
                                                    value="{{ min($totalAvailable, $item->quantity_requested) }}">
                                            </td>
                                            <td class="px-6 py-4">
                                                <input type="text" name="items[{{ $loop->index }}][remarks]"
                                                    class="w-full px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white"
                                                    placeholder="Remarks">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Received By (Select User)</label>
                            <select name="received_by" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                                <option value="">Select recipient...</option>
                                @foreach(App\Models\User::orderBy('name')->get() as $user)
                                    <option value="{{ $user->id }}" {{ $risSlip->requested_by == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} - {{ optional($user->department)->name ?? 'No Department' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                        <button type="button" id="cancelIssue" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 mr-2">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Process Issuance
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const issueModal      = document.getElementById('issueModal');
            const openIssueBtn    = document.getElementById('openIssueModal');
            const closeIssueBtn   = document.getElementById('closeIssueModal');
            const cancelIssueBtn  = document.getElementById('cancelIssue');

            if (openIssueBtn) {
                openIssueBtn.addEventListener('click', function() {
                    issueModal.classList.remove('hidden');
                });
            }

            const hideIssueModal = () => issueModal.classList.add('hidden');

            closeIssueBtn?.addEventListener('click', hideIssueModal);
            cancelIssueBtn?.addEventListener('click', hideIssueModal);
        });
    </script>

    <!-- Approve Modal Controls (fixed) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const approveModal      = document.getElementById('approveModal');
            const openApproveBtn    = document.getElementById('openApproveModal');
            const closeApproveBtn   = document.getElementById('closeApproveModal');
            const cancelApproveBtn  = document.getElementById('cancelApprove');

            // Open the modal
            if (openApproveBtn) {
                openApproveBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    approveModal.classList.remove('hidden');
                });
            }

            // Helper to hide
            const hideApproveModal = () => approveModal.classList.add('hidden');

            // Close buttons
            closeApproveBtn?.addEventListener('click', function(e) {
                e.preventDefault();
                hideApproveModal();
            });
            cancelApproveBtn?.addEventListener('click', function(e) {
                e.preventDefault();
                hideApproveModal();
            });
        });
    </script>

</x-app-layout>
