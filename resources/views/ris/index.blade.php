<!-- resources/views/ris/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Requisition and Issue Slips') }}
            </h2>
            <span class="text-sm text-gray-600 dark:text-gray-400">
                Manage supply requisitions
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Card -->
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                <!-- Filter & Controls Section -->
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <!-- Stats Summary -->
                        <div class="flex flex-wrap gap-3">
                            <div
                                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                <span class="mr-1">Total:</span>
                                <span class="font-semibold">{{ $risSlips->total() }}</span>
                            </div>

                            <div
                                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                <span class="w-3 h-3 mr-2 rounded-full bg-gray-400 dark:bg-gray-500"></span>
                                <span>Pending: </span>
                                <span
                                    class="font-semibold ml-1">{{ $risSlips->where('status', 'draft')->count() }}</span>
                            </div>

                            <div
                                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                <span class="w-3 h-3 mr-2 rounded-full bg-blue-400 dark:bg-blue-500"></span>
                                <span>Approved: </span>
                                <span
                                    class="font-semibold ml-1">{{ $risSlips->where('status', 'approved')->count() }}</span>
                            </div>

                            <div
                                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                <span class="w-3 h-3 mr-2 rounded-full bg-green-400 dark:bg-green-500"></span>
                                <span>Issued: </span>
                                <span
                                    class="font-semibold ml-1">{{ $risSlips->where('status', 'posted')->count() }}</span>
                            </div>
                        </div>

                        <!-- Search Form -->
                        <form method="GET" action="{{ route('ris.index') }}"
                            class="w-full max-w-sm flex items-center space-x-2">
                            <div class="relative flex-grow">
                                <input type="text" name="search" id="search-input"
                                    value="{{ request()->get('search') }}" oninput="toggleClearButton()"
                                    placeholder="Search RIS number, requestor..."
                                    class="px-4 py-2 w-full border text-sm font-medium border-gray-300 rounded-lg
                                         focus:ring-1 focus:ring-blue-500 focus:border-blue-500
                                         dark:bg-gray-800 dark:border-gray-700 dark:text-white
                                         dark:focus:ring-blue-500 dark:focus:border-blue-500" />

                                <!-- The 'X' Button (hidden by default) -->
                                <button type="button" id="clearButton" onclick="clearSearch()" style="display: none;"
                                    class="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-red-500 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x">
                                        <line x1="18" x2="6" y1="6" y2="18" />
                                        <line x1="6" x2="18" y1="6" y2="18" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Separate Search Button -->
                            <button type="submit"
                                class="px-3 py-2 text-sm text-white bg-blue-700 rounded-lg
                                       hover:bg-blue-800 focus:ring-1 focus:outline-none
                                       focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700
                                       dark:focus:ring-blue-800 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="size-5">
                                    <path fill-rule="evenodd"
                                        d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="p-5">
                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div id="alert-success"
                            class="flex items-center p-4 mb-5 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                            role="alert">
                            <svg class="flex-shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                            </svg>
                            <span class="sr-only">Success</span>
                            <div class="ml-3 text-sm font-medium">{{ session('success') }}</div>
                            <button type="button"
                                class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700"
                                data-dismiss-target="#alert-success" aria-label="Close">
                                <span class="sr-only">Close</span>
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                            </button>
                        </div>
                    @endif

                    <!-- RIS Table -->
                    <div class="overflow-hidden shadow-md sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="overflow-x-auto">
                            <div class="overflow-y-auto max-h-[500px]">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="text-xs text-white uppercase bg-gradient-to-r from-blue-600 to-blue-800 dark:from-blue-700 dark:to-blue-900 sticky top-0 z-10">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">RIS No</th>
                                            <th scope="col" class="px-6 py-3">Date</th>
                                            <th scope="col" class="px-6 py-3">Division</th>
                                            <th scope="col" class="px-6 py-3">Requested By</th>
                                            <th scope="col" class="px-6 py-3">Status</th>
                                            <th scope="col" class="px-6 py-3">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($risSlips as $ris)
                                            <tr
                                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                                    {{ $ris->ris_no }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    {{ $ris->ris_date->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    {{-- changed from division->name --}}
                                                    {{ $ris->department->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    {{ $ris->requester->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if ($ris->status === 'draft')
                                                        <span
                                                            class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                                                            <span class="relative flex h-2 w-2 mr-1">
                                                                <span
                                                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-gray-400 opacity-75"></span>
                                                                <span
                                                                    class="relative inline-flex rounded-full h-2 w-2 bg-gray-500"></span>
                                                            </span>
                                                            Pending
                                                        </span>
                                                    @elseif($ris->status === 'approved')
                                                        <span
                                                            class="bg-blue-100 text-blue-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                                            <svg class="w-3 h-3 mr-1" aria-hidden="true"
                                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path
                                                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                                                            </svg>
                                                            Approved
                                                        </span>
                                                    @elseif($ris->status === 'posted')
                                                        <span
                                                            class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                                            <svg class="w-3 h-3 mr-1" aria-hidden="true"
                                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path
                                                                    d="m18.774 8.245-.892-.893a1.5 1.5 0 0 1-.437-1.052V5.036a2.484 2.484 0 0 0-2.48-2.48H13.7a1.5 1.5 0 0 1-1.052-.438l-.893-.892a2.484 2.484 0 0 0-3.51 0l-.893.892a1.5 1.5 0 0 1-1.052.437H5.036a2.484 2.484 0 0 0-2.48 2.481V6.3a1.5 1.5 0 0 1-.438 1.052l-.892.893a2.484 2.484 0 0 0 0 3.51l.892.893a1.5 1.5 0 0 1 .437 1.052v1.264a2.484 2.484 0 0 0 2.481 2.481H6.3a1.5 1.5 0 0 1 1.052.437l.893.892a2.484 2.484 0 0 0 3.51 0l.893-.892a1.5 1.5 0 0 1 1.052-.437h1.264a2.484 2.484 0 0 0 2.481-2.48V13.7a1.5 1.5 0 0 1 .437-1.052l.892-.893a2.484 2.484 0 0 0 0-3.51Z" />
                                                                <path
                                                                    d="M8 13a1 1 0 0 1-.707-.293l-2-2a1 1 0 1 1 1.414-1.414L8 10.586l4.293-4.293a1 1 0 0 1 1.414 1.414l-5 5A1 1 0 0 1 8 13Z" />
                                                            </svg>
                                                            Issued
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4">
                                                    <a href="{{ route('ris.show', $ris->ris_id) }}"
                                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                                        View Details
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-6 py-8 text-center">
                                                    <div class="flex flex-col items-center justify-center py-8">
                                                        <svg class="w-12 h-12 text-gray-400 mb-4" aria-hidden="true"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24">
                                                            <path stroke="currentColor" stroke-linecap="round"
                                                                stroke-linejoin="round" stroke-width="1.5"
                                                                d="M4 4v15a1 1 0 0 0 1 1h15M8 16l2.5-5.5 3 3L17.3 7 20 9.7" />
                                                        </svg>
                                                        <p
                                                            class="text-lg font-medium text-gray-500 dark:text-gray-400">
                                                            No requisitions found</p>
                                                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">There
                                                            are no requisition slips in the system yet.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <nav class="flex items-center justify-between pt-4 mb-3" aria-label="Table navigation">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            @if ($risSlips->count() > 0)
                                Showing {{ $risSlips->firstItem() }} to {{ $risSlips->lastItem() }} of
                                {{ $risSlips->total() }} requisitions
                            @endif
                        </div>
                        <div class="mt-2 sm:mt-0">
                            {{ $risSlips->links() }}
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Alert Dismissal -->
    <script>
        // Alert auto-dismissal after 5 seconds
        document.addEventListener('DOMContentLoaded', () => {
            const alert = document.getElementById('alert-success');
            if (alert) {
                setTimeout(() => {
                    alert.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }, 5000);
            }
        });

        // Functions for search input
        function toggleClearButton() {
            const input = document.getElementById('search-input');
            const clearBtn = document.getElementById('clearButton');
            if (input && clearBtn) {
                clearBtn.style.display = input.value.trim().length > 0 ? 'flex' : 'none';
            }
        }

        function clearSearch() {
            const input = document.getElementById('search-input');
            if (input) {
                input.value = '';
                document.getElementById('clearButton').style.display = 'none';
                window.location.href = window.location.pathname;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            toggleClearButton();
        });
    </script>
</x-app-layout>
