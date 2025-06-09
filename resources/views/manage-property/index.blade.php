<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Property') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="section-container p-5">
                    <!-- Button and Search Bar Container -->
                    <div class="flex items-center justify-between mb-4 mt-2 space-x-2 w-full">
                        <!-- Search Bar Container -->
                        <form method="GET" action="{{ route('property.index') }}"
                              class="w-full max-w-sm flex items-center space-x-2">
                            <div class="relative flex-grow">
                                <input type="text" name="search" id="propertySearchInput"
                                    value="{{ request()->get('search') }}" oninput="togglePropertyClearButton()"
                                    placeholder="Search..."
                                    class="px-4 py-2 w-full border text-sm font-medium border-gray-300 rounded-lg
                                            focus:ring-1 focus:ring-blue-500 focus:border-blue-500
                                            dark:bg-gray-800 dark:border-gray-700 dark:text-white
                                            dark:focus:ring-blue-500 dark:focus:border-blue-500" />

                                <!-- The 'X' Button (hidden by default) -->
                                <button type="button" id="propertyClearBtn" onclick="clearPropertySearch()" style="display: none;"
                                        class="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-red-500 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x">
                                        <line x1="18" x2="6" y1="6" y2="18"/>
                                        <line x1="6" x2="18" y1="6" y2="18"/>
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
                                          clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </form>

                        <!-- Add New Property Button -->
                        <a href="{{ route('property.create') }}" type="button"
                           class="py-2 px-3 text-white bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 rounded-lg text-sm font-medium focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 transition-all duration-200 ml-2 inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="hidden sm:inline-block">Add New Property</span>
                        </a>
                    </div>

                    <!-- Flash Messages -->
                    @if (session()->has('success'))
                        <div id="flashMessage"
                            class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                            role="alert">
                            <span class="font-medium">Success!</span> {{ session('success') }}
                        </div>
                        <script>
                            setTimeout(() => {
                                document.getElementById('flashMessage').style.display = 'none';
                            }, 3000);
                        </script>
                    @endif

                    @if (session()->has('deleted'))
                        <div id="flashMessage"
                            class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                            role="alert">
                            <span class="font-medium">Success!</span> {{ session('deleted') }}
                        </div>
                        <script>
                            setTimeout(() => {
                                document.getElementById('flashMessage').style.display = 'none';
                            }, 3000);
                        </script>
                    @endif

                    <!-- Table Description Caption -->
                    <div class="p-4 mb-4 text-sm text-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-gray-300">
                        <h3 class="text-lg font-semibold mb-1 text-gray-900 dark:text-white">CHED Property Details</h3>
                        <p>
                            This section provides a comprehensive overview of institutional assets
                            managed by CHED. It includes essential information such as property numbers,
                            detailed descriptions, physical condition, and the assigned personnel.
                            Designed to ensure accountability, proper utilization, and seamless
                            asset handover, this system supports CHED's commitment to operational
                            excellence and transparency.
                        </p>
                    </div>

                    <!-- Property Table - Enhanced table -->
                    <div class="overflow-hidden shadow-md sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="overflow-x-auto">
                            <div class="overflow-y-auto max-h-[600px]">
                                <table class="w-full text-sm text-left">
                                    <thead
                                        class="text-xs text-white uppercase bg-gradient-to-r from-blue-600 to-blue-800 dark:from-blue-700 dark:to-blue-900 sticky top-0 z-10">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">ID</th>
                                            <th scope="col" class="px-6 py-3">Property Number</th>
                                            <th scope="col" class="px-6 py-3">Item Name</th>
                                            <th scope="col" class="px-6 py-3">Fund</th>
                                            <th scope="col" class="px-6 py-3">Assigned To</th>
                                            <th scope="col" class="px-6 py-3">Condition</th>
                                            <th scope="col" class="px-6 py-3 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($properties as $property)
                                            <tr
                                                class="{{ $property->user && $property->user->excluded ? 'bg-red-100 dark:bg-red-950' : 'bg-white dark:bg-gray-800' }} border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <th scope="row"
                                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {{ $property->id }}
                                                </th>
                                                <td class="px-6 py-4 font-medium dark:text-gray-200">
                                                    {{ $property->property_number }}
                                                </td>
                                                <td class="px-6 py-4 dark:text-gray-200">
                                                    {{ $property->item_name }}
                                                </td>
                                                <td class="px-6 py-4 dark:text-gray-200">
                                                    {{ $property->fund ?? 'TBD' }}
                                                </td>
                                                <td class="px-6 py-4 dark:text-gray-200">
                                                    {{ $property->user->name ?? 'TBD' }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span
                                                        class="px-2 py-1 rounded-full text-xs font-medium
                                                        {{ strtolower($property->condition) == 'serviceable' ? 'bg-green-500 text-white' : (strtolower($property->condition) == 'unserviceable' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-white') }}">
                                                        {{ $property->condition ?: 'TBD/NA' }}
                                                    </span>
                                                </td>
                                                <td class="px-2 py-4">
                                                    <div class="flex items-center justify-center space-x-2">
                                                        <!-- View Button -->
                                                        <a href="{{ route('property.view', $property->id) }}"
                                                           class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800 transition-all duration-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                 height="16" viewBox="0 0 24 24" fill="none"
                                                                 stroke="currentColor" stroke-width="2"
                                                                 stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z" />
                                                                <circle cx="12" cy="12" r="3" />
                                                            </svg>
                                                        </a>

                                                        <!-- Edit Button -->
                                                        <a href="{{ route('property.edit', $property) }}" 
                                                           class="p-2 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-yellow-300 dark:bg-yellow-900 dark:text-yellow-300 dark:hover:bg-yellow-800 transition-all duration-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                 height="16" viewBox="0 0 24 24" fill="none"
                                                                 stroke="currentColor" stroke-width="2"
                                                                 stroke-linecap="round" stroke-linejoin="round">
                                                                <path
                                                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                                <path
                                                                    d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z" />
                                                            </svg>
                                                        </a>

                                                        <!-- Delete Button -->
                                                        <button type="button"
                                                            data-modal-target="deletePropertyModal{{ $property->id }}"
                                                            data-modal-toggle="deletePropertyModal{{ $property->id }}"
                                                            class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-300 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800 transition-all duration-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M3 6h18" />
                                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                                <line x1="14" x2="14" y1="11" y2="17" />
                                                            </svg>
                                                        </button>

                                                        <!-- Hidden Delete Form -->
                                                        <form id="deleteForm{{ $property->id }}"
                                                              action="{{ route('property.destroy', $property->id) }}"
                                                              method="POST" class="hidden">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="px-6 py-8 text-center">
                                                    <!-- Empty state content -->
                                                    <div class="flex flex-col items-center justify-center">
                                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M18 6h-4V2a1 1 0 00-1-1H7a1 1 0 00-1 1v4H2a1 1 0 00-1 1v11a1 1 0 001 1h16a1 1 0 001-1V7a1 1 0 00-1-1z">
                                                            </path>
                                                        </svg>
                                                        <p class="text-lg font-medium text-gray-500 dark:text-gray-400">
                                                            No properties found</p>
                                                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Get
                                                            started by adding a new property</p>
                                                        <a href="{{ route('property.create') }}"
                                                           class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg transition-colors shadow-sm focus:ring-4 focus:ring-blue-300">
                                                            <svg class="w-4 h-4 mr-2" fill="currentColor"
                                                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                            Add New Property
                                                        </a>
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
                        <!-- On the left side (optional) -->
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            @if ($properties->count() > 0)
                                Showing {{ $properties->firstItem() }} to {{ $properties->lastItem() }} of
                                {{ $properties->total() }} properties
                            @endif
                        </div>

                        <!-- On the right side -->
                        <div class="mt-2 sm:mt-0">
                            {{ $properties->links('pagination::tailwind') }}
                        </div>
                    </nav>

                </div>
            </div>

            <!-- Delete Property Modals -->
            @foreach ($properties as $property)
                <div id="deletePropertyModal{{ $property->id }}" tabindex="-1" aria-hidden="true"
                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-md max-h-full">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <!-- Modal header -->
                            <div
                                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 bg-gradient-to-r from-red-500 to-red-700">
                                <h3 class="text-lg font-semibold text-white flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="mr-2">
                                        <path d="M3 6h18"></path>
                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                    Delete Property
                                </h3>
                                <button type="button"
                                    class="text-white bg-transparent hover:bg-red-800 hover:text-white rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                    data-modal-hide="deletePropertyModal{{ $property->id }}">
                                    <svg class="w-3 h-3" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2"
                                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>

                            <!-- Modal body -->
                            <div class="p-6">
                                <div class="mb-5 text-center">
                                    <div
                                        class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mt-3">
                                        Confirm Deletion</h3>
                                    <div class="mt-2 text-gray-600 dark:text-gray-400">
                                        <p>Are you sure you want to delete property:</p>
                                        <p class="font-semibold text-gray-800 dark:text-white mt-1">
                                            "Property #{{ $property->property_number }}"</p>
                                    </div>
                                    <p class="mt-3 text-sm text-red-500">This action cannot be undone.</p>
                                </div>

                                <form action="{{ route('property.destroy', $property->id) }}"
                                    method="POST" class="mt-6">
                                    @csrf
                                    @method('DELETE')
                                    <div class="flex items-center justify-center space-x-4">
                                        <button data-modal-hide="deletePropertyModal{{ $property->id }}"
                                            type="button"
                                            class="py-2.5 px-5 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                            Cancel
                                        </button>
                                        <button type="submit"
                                            class="py-2.5 px-5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 inline-flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="mr-2">
                                                <path d="M3 6h18"></path>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                            </svg>
                                            Delete Permanently
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Footer / Copyright -->
            {{-- <div class="mt-6 border-t pt-4 text-center text-sm text-gray-500">
                Copyright © CHED Property Management System
            </div> --}}
        </div>
    </div>

    <!-- JavaScript for toggling X icon and clearing input -->
    <script>
        function togglePropertyClearButton() {
            const input = document.getElementById('propertySearchInput');
            const clearBtn = document.getElementById('propertyClearBtn');
            // Show X if there's text
            if (input.value.trim().length > 0) {
                clearBtn.style.display = 'flex';
            } else {
                clearBtn.style.display = 'none';
            }
        }

        function clearPropertySearch() {
            const input = document.getElementById('propertySearchInput');
            input.value = '';
            document.getElementById('propertyClearBtn').style.display = 'none';
            // Redirect to the index page without search params
            window.location.href = window.location.pathname;
        }

        // Function to handle delete confirmation
        function confirmDelete(formId, propertyName) {
            if (confirm(`Are you sure you want to delete ${propertyName}? This action cannot be undone.`)) {
                document.getElementById(formId).submit();
            }
        }

        // On page load, display the X if there's an existing search term
        document.addEventListener('DOMContentLoaded', () => {
            togglePropertyClearButton();
        });
    </script>
</x-app-layout>
