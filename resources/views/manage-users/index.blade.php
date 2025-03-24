<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="section-container p-5">
                    <!-- Button and Search Bar Container -->
                    <div class="flex items-center justify-between mb-2 mt-2 space-x-2 w-full">
                        <!-- Search bar (with X icon) -->
                        <form method="GET" action="{{ route('end_users.index') }}"
                              class="w-full max-w-sm flex items-center space-x-2">
                            <!-- Relative container for input + clear button -->
                            <div class="relative flex-grow">
                                <!-- Text input -->
                                <input type="text" name="search" id="endUserSearchInput"
                                       value="{{ request()->get('search') }}" oninput="toggleEndUserClearButton()"
                                       placeholder="Search..."
                                       class="px-4 py-2 w-full border text-sm font-medium border-gray-300 rounded-lg
                                              focus:ring-1 focus:ring-blue-500 focus:border-blue-500
                                              dark:bg-gray-800 dark:border-gray-700 dark:text-white
                                              dark:focus:ring-blue-500 dark:focus:border-blue-500" />

                                <!-- Clear (X) button, hidden by default -->
                                <button type="button" id="endUserClearBtn" onclick="clearEndUserSearch()"
                                        style="display: none;"
                                        class="absolute inset-y-0 right-2 flex items-center text-gray-500
                                               hover:text-red-500 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x">
                                        <line x1="18" x2="6" y1="6" y2="18" />
                                        <line x1="6" x2="18" y1="6" y2="18" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Search icon button -->
                            <button type="submit"
                                    class="px-3 py-2 text-sm text-white bg-blue-700 rounded-lg
                                           hover:bg-blue-800 focus:ring-1 focus:outline-none
                                           focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700
                                           dark:focus:ring-blue-800 flex items-center">
                                <!-- Search Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                     class="size-5">
                                    <path fill-rule="evenodd"
                                          d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5
                                          5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452
                                          4.391l3.328 3.329a.75.75 0 1 1-1.06
                                          1.06l-3.329-3.328A7 7 0 0 1 2 9Z"
                                          clip-rule="evenodd" />
                                </svg>
                            </button>
                        </form>

                        <a href="{{ route('end_users.create') }}" type="button"
                           class="px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800
                                  focus:ring-1 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700
                                  dark:focus:ring-blue-800 flex items-center ms-5">
                            <span class="mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                     class="size-5">
                                    <path
                                        d="M10 5a3 3 0 1 1-6 0 3 3 0 0
                                           1 6 0ZM1.615 16.428a1.224 1.224
                                           0 0 1-.569-1.175 6.002 6.002
                                           0 0 1 11.908 0c.058.467-.172.92-.57
                                           1.174A9.953 9.953 0 0 1 7
                                           18a9.953 9.953 0 0 1-5.385-1.572ZM16.25
                                           5.75a.75.75 0 0 0-1.5 0v2h-2a.75.75
                                           0 0 0 0 1.5h2v2a.75.75
                                           0 0 0 1.5 0v-2h2a.75.75
                                           0 0 0 0-1.5h-2v-2Z" />
                                </svg>
                            </span>
                            <!-- Text (Visible on larger screens) -->
                            <span class="hidden sm:inline-flex">
                                Add New End User
                            </span>
                        </a>
                    </div>

                    <div class="relative overflow-x-auto">
                        <!-- Table with dynamic content -->
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <caption
                                class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                                Our Valued Employees
                                <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">
                                    These are CHED personnel responsible for managing institutional properties, ensuring
                                    accountability and proper utilization before their transition or departure, allowing
                                    seamless asset handover and continued operational efficiency.
                                </p>
                            </caption>
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <!-- Photo -->
                                    <th scope="col" class="px-6 py-3">Photo</th>
                                    <th scope="col" class="px-6 py-3">ID</th>
                                    <th scope="col" class="px-6 py-3">Name</th>
                                    <th scope="col" class="px-6 py-3">Email</th>
                                    <th scope="col" class="px-6 py-3">Department</th>
                                    <!-- New designation column -->
                                    <th scope="col" class="px-6 py-3">Designation</th>

                                    <th scope="col" class="px-6 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($endUsers as $endUser)
                                    <tr class="{{ $endUser->excluded ? 'bg-red-200 dark:bg-red-900' : 'bg-white dark:bg-gray-800' }}
                                               border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <!-- Photo Column -->
                                        <td class="px-6 py-4">
                                            <img class="w-10 h-10 rounded-full object-cover"
                                                 src="{{ $endUser->picture ? asset('storage/' . $endUser->picture) : asset('img/ched-logo.png') }}"
                                                 alt="{{ $endUser->name }}'s Profile Picture">
                                        </td>

                                        <!-- ID -->
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $endUser->id }}
                                        </th>

                                        <!-- Name -->
                                        <td class="px-6 py-4">{{ $endUser->name }}</td>

                                        <!-- Email -->
                                        <td class="px-6 py-4">{{ $endUser->email }}</td>

                                        <!-- Department -->
                                        <td class="px-6 py-4">{{ $endUser->department }}</td>

                                        <!-- Designation (New) -->
                                        <td class="px-6 py-4">
                                            {{ $endUser->designation ?? '—' }}
                                            <!-- Fallback dash if no designation is set -->
                                        </td>

                                        <!-- Action Buttons -->
                                        <td class="px-2 py-4">
                                            <!-- Dropdown Button -->
                                            <button id="dropdownMenuButton{{ $endUser->id }}"
                                                data-dropdown-toggle="dropdownMenu{{ $endUser->id }}"
                                                class="inline-flex items-center p-2 text-sm font-medium text-center
                                                       text-gray-900 bg-white rounded-lg hover:bg-gray-100 focus:ring-1
                                                       focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-800
                                                       dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                                                type="button">
                                                <svg class="w-5 h-5" aria-hidden="true"
                                                     xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                     viewBox="0 0 16 3">
                                                    <path d="M2 0a1.5 1.5 0 1 1 0 3
                                                             1.5 1.5 0 0 1 0-3Zm6.041 0a1.5
                                                             1.5 0 1 1 0 3 1.5 1.5 0 0 1
                                                             0-3ZM14 0a1.5 1.5 0 1 1 0 3
                                                             1.5 1.5 0 0 1 0-3Z" />
                                                </svg>
                                            </button>

                                            <!-- Dropdown Menu -->
                                            <div id="dropdownMenu{{ $endUser->id }}"
                                                 class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm
                                                        w-44 dark:bg-gray-700 dark:divide-gray-600">
                                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200 shadow-xl sm:rounded-lg">
                                                    <!-- Edit Action -->
                                                    <li>
                                                        <a href="{{ route('end_users.edit', $endUser->id) }}"
                                                           class="flex items-center px-4 py-2 hover:bg-gray-100
                                                                  dark:hover:bg-gray-600 dark:hover:text-white">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"
                                                                 stroke-linecap="round" stroke-linejoin="round"
                                                                 class="lucide lucide-pencil me-3">
                                                                <path d="M21.174 6.812a1 1 0 0
                                                                         0-3.986-3.987L3.842
                                                                         16.174a2 2 0 0
                                                                         0-.5.83l-1.321
                                                                         4.352a.5.5 0 0 0
                                                                         .623.622l4.353-1.32a2
                                                                         2 0 0 0 .83-.497z" />
                                                                <path d="m15 5 4 4" />
                                                            </svg>
                                                            Edit
                                                        </a>
                                                    </li>
                                                    <hr class="border-gray-300 dark:border-gray-600">
                                                    <!-- Delete Action -->
                                                    <li>
                                                        <form id="deleteForm{{ $endUser->id }}"
                                                              action="{{ route('end_users.destroy', $endUser->id) }}"
                                                              method="POST"
                                                              class="flex items-center px-4 py-2 hover:bg-gray-100
                                                                     dark:hover:bg-gray-600 dark:hover:text-white">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                    class="flex items-center w-full text-left text-red-500"
                                                                    onclick="confirmDelete({{ $endUser->id }})">
                                                                <svg class="w-5 h-5 mr-2"
                                                                     xmlns="http://www.w3.org/2000/svg"
                                                                     viewBox="0 0 16 16" fill="currentColor">
                                                                    <path fill-rule="evenodd"
                                                                          d="M5 3.25V4H2.75a.75.75 0 0
                                                                             0 0 1.5h.3l.815
                                                                             8.15A1.5 1.5 0 0 0 5.357
                                                                             15h5.285a1.5 1.5 0 0 0
                                                                             1.493-1.35l.815-8.15h.3a.75.75
                                                                             0 0 0 0-1.5H11v-.75A2.25
                                                                             2.25 0 0 0 8.75 1h-1.5A2.25
                                                                             2.25 0 0 0 5
                                                                             3.25Zm2.25-.75a.75.75 0 0
                                                                             0-.75.75V4h3v-.75a.75.75 0
                                                                             0 0-.75-.75h-1.5ZM6.05 6a.75.75
                                                                             0 0 1 .787.713l.275
                                                                             5.5a.75.75 0 0
                                                                             1-1.498.075l-.275-5.5A.75.75
                                                                             0 0 1 6.05 6Zm3.9 0a.75.75 0
                                                                             0 1 .712.787l-.275
                                                                             5.5a.75.75 0 0
                                                                             1-1.498-.075l.275-5.5a.75.75
                                                                             0 0 1 .786-.711Z"
                                                                          clip-rule="evenodd" />
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
                            <!-- e.g., "Showing 1–5 of 20" if desired -->
                        </div>

                        <!-- On the right side -->
                        <div class="mt-2 sm:mt-0">
                            {{ $endUsers->links('pagination::tailwind') }}
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(userId) {
            Swal.fire({
                title: "Are you sure?",
                text: "HINDI MO NA MAARING BALIKAN ANG MGA BAGAY NA TAPOS NA",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm' + userId).submit();
                }
            });
        }

        // Show success alert if session deleted exists
        document.addEventListener("DOMContentLoaded", function() {
            @if (session('deleted'))
                setTimeout(() => {
                    Swal.fire({
                        title: "Success!",
                        text: "{{ session('deleted') }}",
                        icon: "success",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "OK"
                    });
                }, 500);
            @endif
        });

        function toggleEndUserClearButton() {
            const input = document.getElementById('endUserSearchInput');
            const clearBtn = document.getElementById('endUserClearBtn');
            // if user typed something, show X; otherwise hide
            if (input.value.trim().length > 0) {
                clearBtn.style.display = 'flex';
            } else {
                clearBtn.style.display = 'none';
            }
        }

        function clearEndUserSearch() {
            const input = document.getElementById('endUserSearchInput');
            input.value = '';
            document.getElementById('endUserClearBtn').style.display = 'none';
            // optional: auto-submit after clearing
            // document.querySelector('form.w-full.max-w-sm').submit();
        }

        document.addEventListener('DOMContentLoaded', () => {
            toggleEndUserClearButton();
        });
    </script>
</x-app-layout>
