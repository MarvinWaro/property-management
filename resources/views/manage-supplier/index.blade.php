<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Suppliers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="section-container p-5">
                    <!-- Button and Search Bar Container -->
                    <div class="flex items-center justify-between mb-2 mt-2 space-x-2 w-full">
                        <!-- Search Bar Container -->
                        <form method="GET" action="#"
                            class="w-full max-w-sm flex items-center space-x-2">
                            <div class="relative flex-grow">
                                <input type="text" name="search" id="search-input"
                                    value="{{ request()->get('search') }}" oninput="toggleClearButton()"
                                    placeholder="Search..."
                                    class="px-4 py-2 w-full border text-sm font-medium border-gray-300 rounded-lg
                                        focus:ring-1 focus:ring-blue-500 focus:border-blue-500
                                        dark:bg-gray-800 dark:border-gray-700 dark:text-white
                                        dark:focus:ring-blue-500 dark:focus:border-blue-500" />

                                <!-- The 'X' Button (hidden by default) -->
                                <button type="button" id="clearButton" onclick="clearSearch()" style="display: none;"
                                    class="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-red-500 focus:outline-none">
                                    <!-- X Icon -->
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
                                <!-- Search Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="size-5">
                                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11
                                        5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1
                                        12.452 4.391l3.328 3.329a.75.75
                                        0 1 1-1.06 1.06l-3.329-3.328A7
                                        7 0 0 1 2 9Z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </form>

                        <a href="{{ route('supplier.create') }}" type="button"
                            class="px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-1 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 flex items-center ms-5">
                            <span class="mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="size-5">
                                    <path
                                        d="M10 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM1.615 16.428a1.224 1.224 0 0 1-.569-1.175 6.002 6.002 0 0 1 11.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 0 1 7 18a9.953 9.953 0 0 1-5.385-1.572ZM16.25 5.75a.75.75 0 0 0-1.5 0v2h-2a.75.75 0 0 0 0 1.5h2v2a.75.75 0 0 0 1.5 0v-2h2a.75.75 0 0 0 0-1.5h-2v-2Z" />
                                </svg>
                            </span>
                            <!-- Text (Visible on larger screens) -->
                            <span class="hidden sm:inline-flex">
                                Add New Supplier
                            </span>
                        </a>

                    </div>

                    {{-- table --}}
                    <div class="relative overflow-x-auto">
                        <!-- Table with dynamic content -->
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                                CHED Suppliers
                                <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">
                                    Below is the list of registered suppliers.
                                </p>
                            </caption>
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">#</th>
                                    <th scope="col" class="px-6 py-3">Name / Email</th>
                                    <th scope="col" class="px-6 py-3">Contact No</th>
                                    <th scope="col" class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $supplier)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $supplier->id }}
                                    </th>
                                    <td class="px-6 py-4">
                                        <div>{{ $supplier->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $supplier->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">{{ $supplier->contact_number }}</td>
                                    <td class="px-2 py-4">
                                        <!-- Dropdown Button for Actions (Edit/Delete) -->
                                        <button id="dropdownMenuButton{{ $supplier->id }}" data-dropdown-toggle="dropdownMenu{{ $supplier->id }}"
                                            class="inline-flex items-center p-2 text-sm font-medium text-gray-900 bg-white rounded-lg hover:bg-gray-100 focus:ring-1 dark:text-white dark:bg-gray-800 dark:hover:bg-gray-700">
                                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 3">
                                                <path d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z" />
                                            </svg>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div id="dropdownMenu{{ $supplier->id }}"
                                            class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200 shadow-xl">
                                                <!-- Edit Action -->
                                                <li>
                                                    <a href="{{ route('supplier.edit', $supplier->id) }}" class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil me-3">
                                                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                                                            <path d="m15 5 4 4" />
                                                        </svg>
                                                        Edit
                                                    </a>
                                                </li>
                                                <hr class="border-gray-300 dark:border-gray-600">
                                                <li>
                                                    <form id="deleteForm{{ $supplier->id }}" action="{{ route('supplier.destroy', $supplier->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                                onclick="confirmDelete('deleteForm{{ $supplier->id }}', '{{ $supplier->name }}')"
                                                                class="flex items-center px-4 py-2 text-red-500 hover:text-red-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-red-400 w-full text-left">
                                                            <svg class="w-5 h-5 mr-2"
                                                                 xmlns="http://www.w3.org/2000/svg"
                                                                 viewBox="0 0 16 16" fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                      d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z"
                                                                      clip-rule="evenodd"/>
                                                            </svg>
                                                            Delete
                                                        </button>
                                                    </form>
                                                </li>


                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>


                    <!-- Pagination -->
                    <nav class="flex items-center justify-between pt-4 mb-3" aria-label="Table navigation">
                        <!-- On the left side (optional) -->
                        <div class="text-sm text-gray-500">
                            {{-- Put something like "Showing 1–5 of 20" if you want, or leave empty --}}
                        </div>

                        <!-- On the right side -->
                        <div class="mt-2 sm:mt-0">
                            {{ $suppliers->links('pagination::tailwind') }}
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to Show/Hide X button and Clear Input -->
    <script>
        function toggleClearButton() {
            const input = document.getElementById('search-input');
            const clearBtn = document.getElementById('clearButton');

            // If input has text, show X; else hide
            if (input.value.trim().length > 0) {
                clearBtn.style.display = 'flex';
            } else {
                clearBtn.style.display = 'none';
            }
        }

        function clearSearch() {
            const input = document.getElementById('search-input');
            input.value = '';
            document.getElementById('clearButton').style.display = 'none';
            // Optionally, submit form automatically after clearing:
            // document.querySelector('form').submit();
        }

        // On page load, call toggleClearButton to show X if there's existing text
        document.addEventListener('DOMContentLoaded', () => {
            toggleClearButton();
        });
    </script>

</x-app-layout>
