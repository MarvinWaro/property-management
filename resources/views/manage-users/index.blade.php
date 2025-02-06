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
                    <div class="relative overflow-x-auto">
                        <!-- Button and Search Bar Container -->
                        <div class="flex items-center justify-between mb-2 mt-2 space-x-2 w-full">
                            <!-- Search Bar on the left -->
                            <!-- Search Bar on the left -->
                            <form method="GET" action="{{ route('end_users.index') }}" class="w-full max-w-sm">
                                <input type="text" name="search" value="{{ request()->get('search') }}" placeholder="Search..."
                                    class="px-4 py-2 w-full border text-sm font-medium border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </form>
                            <!-- Add New User Button on the right -->
                            <button type="button" id="addNewUserBtn" class="px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 flex items-center">
                                <!-- Icon -->
                                <span class="mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                        <path d="M10 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM1.615 16.428a1.224 1.224 0 0 1-.569-1.175 6.002 6.002 0 0 1 11.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 0 1 7 18a9.953 9.953 0 0 1-5.385-1.572ZM16.25 5.75a.75.75 0 0 0-1.5 0v2h-2a.75.75 0 0 0 0 1.5h2v2a.75.75 0 0 0 1.5 0v-2h2a.75.75 0 0 0 0-1.5h-2v-2Z" />
                                    </svg>
                                </span>
                                <!-- Text (Visible on larger screens) -->
                                <span class="hidden sm:inline-flex">
                                    Add New End User
                                </span>
                            </button>
                        </div>

                        <!-- Modal -->
                        <div id="addUserModal" class="fixed inset-0 flex justify-center items-center bg-gray-900 bg-opacity-50 hidden">
                            <div class="bg-white p-6 rounded-lg w-1/3">
                                <h2 class="text-xl font-semibold mb-4">Add New End User</h2>
                                <form id="addUserForm" action="{{ route('end-users.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                        <input type="text" id="name" name="name" class="mt-1 block w-full border border-gray-300 rounded-md" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" id="email" name="email" class="mt-1 block w-full border border-gray-300 rounded-md" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                        <input type="text" id="phone_number" name="phone_number" class="mt-1 block w-full border border-gray-300 rounded-md" required>
                                    </div>
                                    <div class="mb-4 text-right">
                                        <button type="submit" class="text-xs px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save</button>
                                        <button type="button" class="text-xs ml-2 px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700" id="closeModalBtn">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Table with dynamic content -->
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                                Our Valued End Users
                                <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">
                                    These are CHED personnel responsible for managing institutional properties, ensuring accountability and proper utilization before their transition or departure, allowing seamless asset handover and continued operational efficiency.
                                </p>
                            </caption>
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">ID</th>
                                    <th scope="col" class="px-6 py-3">Name</th>
                                    <th scope="col" class="px-6 py-3">Email</th>
                                    <th scope="col" class="px-6 py-3">Active Cell Phone Number</th>
                                    <th scope="col" class="px-6 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($endUsers as $endUser)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $endUser->id }}
                                        </th>
                                        <td class="px-6 py-4">{{ $endUser->name }}</td>
                                        <td class="px-6 py-4">{{ $endUser->email }}</td>
                                        <td class="px-6 py-4">{{ $endUser->phone_number }}</td>
                                        <td class="px-2 py-4 flex">
                                            <a href="#" class="px-3 py-1 text-blue-500 hover:text-blue-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-5">
                                                    <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                                                    <path fill-rule="evenodd" d="M1.38 8.28a.87.87 0 0 1 0-.566 7.003 7.003 0 0 1 13.238.006.87.87 0 0 1 0 .566A7.003 7.003 0 0 1 1.379 8.28ZM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                            <a href="#" class="px-3 py-1 text-blue-500 hover:text-yellow-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-5">
                                                    <path fill-rule="evenodd" d="M11.013 2.513a1.75 1.75 0 0 1 2.475 2.474L6.226 12.25a2.751 2.751 0 0 1-.892.596l-2.047.848a.75.75 0 0 1-.98-.98l.848-2.047a2.75 2.75 0 0 1 .596-.892l7.262-7.261Z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                            <form action="#" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 text-blue-500 hover:text-red-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-5">
                                                        <path fill-rule="evenodd" d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <nav class="flex items-center flex-column flex-wrap md:flex-row justify-between pt-4 mb-3" aria-label="Table navigation">
                            <!-- Entries Indicator -->
                            <span class="text-sm font-normal text-gray-500 dark:text-gray-400 mb-4 md:mb-0 block w-full md:inline md:w-auto">
                                Showing
                                <span class="font-semibold text-gray-900 dark:text-white">
                                    {{ $endUsers->firstItem() }}-{{ $endUsers->lastItem() }}
                                </span>
                                of
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $endUsers->total() }}</span>
                            </span>

                            <!-- Pagination Links -->
                            <ul class="inline-flex -space-x-px rtl:space-x-reverse text-sm h-8 mb-5">
                                <!-- Previous Page Link -->
                                @if ($endUsers->onFirstPage())
                                    <li>
                                        <a href="#" class="flex items-center justify-center px-3 h-8 text-gray-500 bg-white border border-gray-300 rounded-s-lg cursor-not-allowed">Previous</a>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ $endUsers->previousPageUrl() }}" class="flex items-center justify-center px-3 h-8 text-gray-500 bg-white border border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
                                    </li>
                                @endif

                                {{-- <!-- Pagination Links -->
                                {{ $endUsers->links('pagination::tailwind') }} --}}

                                <!-- Next Page Link -->
                                @if ($endUsers->hasMorePages())
                                    <li>
                                        <a href="{{ $endUsers->nextPageUrl() }}" class="flex items-center justify-center px-3 h-8 text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
                                    </li>
                                @else
                                    <li>
                                        <a href="#" class="flex items-center justify-center px-3 h-8 text-gray-500 bg-white border border-gray-300 rounded-e-lg cursor-not-allowed">Next</a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show Modal
        document.getElementById('addNewUserBtn').addEventListener('click', function() {
            document.getElementById('addUserModal').classList.remove('hidden');
        });

        // Close Modal
        document.getElementById('closeModalBtn').addEventListener('click', function() {
            document.getElementById('addUserModal').classList.add('hidden');
        });

        // Optionally, you can close the modal when the form is submitted (successful).
        document.getElementById('addUserForm').addEventListener('submit', function() {
            // You can hide the modal after submission (e.g., on successful creation)
            document.getElementById('addUserModal').classList.add('hidden');
        });
    </script>



</x-app-layout>
