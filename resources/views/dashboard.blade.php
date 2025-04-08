<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Floating Cards Section -->
            <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 w-full">
                <!-- Employees Average Request per Month (Purple) -->
                <div
                    class="p-3 sm:p-4 lg:p-6 bg-white shadow-xl rounded-2xl dark:bg-gray-800 border-l-4 border-purple-500
                    transition-all duration-300 hover:shadow-2xl hover:translate-y-1 hover:border-l-6 cursor-pointer group">
                    <div class="flex justify-between">
                        <dl class="space-y-2">
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Employees
                            </dt>
                            <dd class="text-2xl sm:text-3xl lg:text-4xl font-light dark:text-white">
                                30
                            </dd>
                            @if ($lastUpdated)
                                <dd
                                    class="flex items-center space-x-1 text-xs sm:text-sm font-medium text-green-500 dark:text-green-400">
                                    <span>Updated {{ $lastUpdated->diffForHumans() }}</span>
                                </dd>
                            @endif
                        </dl>
                        <div
                            class="rounded-full p-2 sm:p-2.5 lg:p-3 bg-purple-100 dark:bg-purple-800 h-fit
                            transition-all duration-300 group-hover:bg-purple-200 dark:group-hover:bg-purple-700">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 text-purple-500 dark:text-purple-300"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.75h4.5m0 0c.621 0 1.125.504 1.125 1.125m-5.625-.75v.75c0
                                .621-.504 1.125-1.125 1.125H4.125C3.504 6 3 6.504 3
                                7.125v10.125c0 .621.504 1.125 1.125 1.125h15.75c.621
                                0 1.125-.504 1.125-1.125V7.125c0-.621-.504-1.125-1.125-1.125h-4.125a1.125
                                1.125 0 01-1.125-1.125V3.75M9 12h6m-6 3h3" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Requests per Month (Blue) -->
                <div
                    class="p-3 sm:p-4 lg:p-6 bg-white shadow-xl rounded-2xl dark:bg-gray-800 border-l-4 border-blue-500
                    transition-all duration-300 hover:shadow-2xl hover:translate-y-1 hover:border-l-6 cursor-pointer group">
                    <div class="flex justify-between">
                        <dl class="space-y-2">
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Requests per Month
                            </dt>
                            <dd class="text-2xl sm:text-3xl lg:text-4xl font-light dark:text-white">
                                1,205
                            </dd>
                            <dd
                                class="flex items-center space-x-1 text-xs sm:text-sm font-medium text-red-500 dark:text-red-400">
                                <span>3% decrease</span>
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.75V17.25H8.75" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17L6.75 6.75" />
                                </svg>
                            </dd>
                        </dl>
                        <div
                            class="rounded-full p-2 sm:p-2.5 lg:p-3 bg-blue-100 dark:bg-blue-800 h-fit
                            transition-all duration-300 group-hover:bg-blue-200 dark:group-hover:bg-blue-700">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 text-blue-500 dark:text-blue-300"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 4.5h17.25c.621 0 1.125.504
                                1.125 1.125v1.5c0 .621-.504 1.125-1.125
                                1.125h-.375v9.75c0 .621-.504 1.125-1.125
                                1.125H4.875c-.621 0-1.125-.504-1.125-1.125v-9.75H3.375c-.621
                                0-1.125-.504-1.125-1.125v-1.5c0-.621.504-1.125
                                1.125-1.125zM9.75 9.75h4.5m-4.5 3h4.5" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Transaction Cost (Teal) -->
                <div
                    class="p-3 sm:p-4 lg:p-6 bg-white shadow-xl rounded-2xl dark:bg-gray-800 border-l-4 border-teal-500
                    transition-all duration-300 hover:shadow-2xl hover:translate-y-1 hover:border-l-6 cursor-pointer group">
                    <div class="flex justify-between">
                        <dl class="space-y-2">
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Total Transaction Cost
                            </dt>
                            <dd class="text-2xl sm:text-3xl lg:text-4xl font-light dark:text-white">
                                9,789
                            </dd>
                            <dd
                                class="flex items-center space-x-1 text-xs sm:text-sm font-medium text-green-500 dark:text-green-400">
                                <span>2 new</span>
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 15.25V6.75H8.75" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 7L6.75 17.25" />
                                </svg>
                            </dd>
                        </dl>
                        <div
                            class="rounded-full p-2 sm:p-2.5 lg:p-3 bg-teal-100 dark:bg-teal-800 h-fit
                            transition-all duration-300 group-hover:bg-teal-200 dark:group-hover:bg-teal-700">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 text-teal-500 dark:text-teal-300"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12c0-1.657-1.343-3-3-3H6c-1.657
                                0-3 1.343-3 3v6c0 1.657 1.343 3 3
                                3h12c1.657 0 3-1.343 3-3v-6zM3
                                9V6c0-1.657 1.343-3 3-3h12c1.657
                                0 3 1.343 3 3v3M8.25 12a2.25 2.25
                                0 104.5 0 2.25 2.25 0 00-4.5 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Requests per Month (Orange) -->
                <div
                    class="p-3 sm:p-4 lg:p-6 bg-white shadow-xl rounded-2xl dark:bg-gray-800 border-l-4 border-orange-500
                    transition-all duration-300 hover:shadow-2xl hover:translate-y-1 hover:border-l-6 cursor-pointer group">
                    <div class="flex justify-between">
                        <dl class="space-y-2">
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Requests per Month
                            </dt>
                            <dd class="text-2xl sm:text-3xl lg:text-4xl font-light dark:text-white">
                                1,205
                            </dd>
                            <dd
                                class="flex items-center space-x-1 text-xs sm:text-sm font-medium text-red-500 dark:text-red-400">
                                <span>3% decrease</span>
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.75V17.25H8.75" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17L6.75 6.75" />
                                </svg>
                            </dd>
                        </dl>
                        <div
                            class="rounded-full p-2 sm:p-2.5 lg:p-3 bg-orange-100 dark:bg-orange-800 h-fit
                            transition-all duration-300 group-hover:bg-orange-200 dark:group-hover:bg-orange-700">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8 text-orange-500 dark:text-orange-300"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 4.5h17.25c.621 0 1.125.504
                                1.125 1.125v1.5c0 .621-.504 1.125-1.125
                                1.125h-.375v9.75c0 .621-.504 1.125-1.125
                                1.125H4.875c-.621 0-1.125-.504-1.125-1.125v-9.75H3.375c-.621
                                0-1.125-.504-1.125-1.125v-1.5c0-.621.504-1.125
                                1.125-1.125zM9.75 9.75h4.5m-4.5 3h4.5" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Section: List of Registered Users -->
            <div class="px-4 py-6 bg-white dark:bg-gray-800 shadow-md rounded-lg my-7">
                <!-- Table Header with Search and Add Button -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-white inline-flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z">
                                </path>
                            </svg>
                            Registered Users
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            A list of all users (staff/admin) in the system.
                        </p>
                    </div>

                    <div class="mt-4 md:mt-0 flex flex-col sm:flex-row gap-3">
                        <!-- Search Input -->
                        <div class="relative">
                            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center">
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                        </svg>
                                    </div>
                                    <input type="search" id="user-search" name="search"
                                        value="{{ $search ?? '' }}"
                                        class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                        placeholder="Search users...">
                                </div>
                                <button type="submit"
                                    class="inline-flex items-center py-3.5 px-3.5 ml-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                {{-- @if (isset($search) && !empty($search))
                                    <a href="{{ route('dashboard') }}"
                                        class="inline-flex items-center py-2.5 px-3 ml-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100">
                                        Clear
                                    </a>
                                @endif --}}
                            </form>
                        </div>

                        <!-- Add Button -->
                        <button type="button" data-modal-target="createUserModal"
                            data-modal-toggle="createUserModal"
                            class="inline-flex items-center py-2.5 px-3.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-plus">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <line x1="19" x2="19" y1="8" y2="14"/>
                                <line x1="22" x2="16" y1="11" y2="11"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
                        role="alert">
                        <div class="font-medium">Please fix the following errors:</div>
                        <ul class="mt-1.5 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Filters and Bulk Actions -->
                <div class="flex flex-wrap gap-3 mb-4">
                    <select id="role-filter"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        <option value="">Filter by Role</option>
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                    </select>

                    <select id="status-filter"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        <option value="">Filter by Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>

                    <select id="department-filter"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        <option value="">Filter by Department</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Table Component with Fixed Height and Scrolling -->
                <div class="overflow-hidden shadow-md sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="overflow-x-auto">
                        <div class="overflow-y-auto max-h-[430px]"> <!-- Height for approximately 5 rows -->
                            <table class="w-full text-sm text-left">
                                <thead
                                    class="text-xs text-white uppercase bg-gradient-to-r from-blue-600 to-blue-800 dark:from-blue-700 dark:to-blue-900 sticky top-0 z-10">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">ID</th>
                                        <th scope="col" class="px-6 py-3">Name</th>
                                        <th scope="col" class="px-6 py-3">Email</th>
                                        <th scope="col" class="px-6 py-3">Role</th>
                                        <th scope="col" class="px-6 py-3">Department</th>
                                        <th scope="col" class="px-6 py-3">Designation</th>
                                        <th scope="col" class="px-6 py-3">Status</th>
                                        <th scope="col" class="px-6 py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr
                                            class="bg-white border-b hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                                            <td
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ $user->id }}
                                            </td>
                                            <td class="px-6 py-4 font-medium">
                                                <div class="flex items-center">
                                                    <!-- User avatar placeholder - replace with actual avatar if available -->
                                                    <div
                                                        class="w-8 h-8 mr-3 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-300 font-bold">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="text-gray-900 dark:text-white">{{ $user->name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">{{ $user->email }}</td>
                                            <td class="px-6 py-4">
                                                @if ($user->role === 'admin')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd"
                                                                d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        Admin
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z">
                                                            </path>
                                                        </svg>
                                                        Staff
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if ($user->department)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                        {{ $user->department->name }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-500 dark:text-gray-400">N/A</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if ($user->designation)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                        {{ $user->designation->name }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-500 dark:text-gray-400">N/A</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if ($user->status)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                        <span class="w-2 h-2 mr-1 bg-green-500 rounded-full"></span>
                                                        Active
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                                        <span class="w-2 h-2 mr-1 bg-red-500 rounded-full"></span>
                                                        Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <!-- Edit Button -->
                                                    <button type="button"
                                                        data-modal-target="editUserModal{{ $user->id }}"
                                                        data-modal-toggle="editUserModal{{ $user->id }}"
                                                        class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800 transition-all duration-200">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-pen-square">
                                                            <path
                                                                d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                            <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z" />
                                                        </svg>
                                                    </button>

                                                    <!-- View Button -->
                                                    <button type="button"
                                                        class="p-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-300 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800 transition-all duration-200">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-eye">
                                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                                            <circle cx="12" cy="12" r="3" />
                                                        </svg>
                                                    </button>

                                                    <!-- Dropdown for More Actions -->
                                                    <div class="relative inline-block text-left">
                                                        <button id="dropdownButton-{{ $user->id }}"
                                                            data-dropdown-toggle="dropdown-{{ $user->id }}"
                                                            type="button"
                                                            class="p-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-all duration-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-more-horizontal">
                                                                <circle cx="12" cy="12" r="1" />
                                                                <circle cx="19" cy="12" r="1" />
                                                                <circle cx="5" cy="12" r="1" />
                                                            </svg>
                                                        </button>
                                                        <!-- Dropdown menu -->
                                                        <div id="dropdown-{{ $user->id }}"
                                                            class="hidden z-10 bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                                                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                                                aria-labelledby="dropdownButton-{{ $user->id }}">
                                                                <li>
                                                                    <a href="#"
                                                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                                        <svg class="w-4 h-4 mr-2 inline-block"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                            </path>
                                                                        </svg>
                                                                        User Details
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="#"
                                                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                                        <svg class="w-4 h-4 mr-2 inline-block"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                                            </path>
                                                                        </svg>
                                                                        Reset Password
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="#"
                                                                        class="block px-4 py-2 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30">
                                                                        <svg class="w-4 h-4 mr-2 inline-block"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                            </path>
                                                                        </svg>
                                                                        Delete User
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Edit User Modal (Same as in your original code) -->
                                                <div id="editUserModal{{ $user->id }}" tabindex="-1"
                                                    aria-hidden="true"
                                                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50
                                                                    justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                                    <div class="relative p-4 w-full max-w-2xl max-h-full">
                                                        <!-- Modal content -->
                                                        <div
                                                            class="relative bg-white rounded-lg shadow-lg dark:bg-gray-700">
                                                            <!-- Modal header -->
                                                            <div
                                                                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t
                                                                dark:border-gray-600 border-gray-200 bg-gradient-to-r from-blue-600 to-blue-800">
                                                                <h3
                                                                    class="text-xl font-semibold text-white flex items-center">
                                                                    <svg class="w-5 h-5 mr-2" fill="currentColor"
                                                                        viewBox="0 0 20 20"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                                        </path>
                                                                    </svg>
                                                                    Edit User: {{ $user->name }}
                                                                </h3>
                                                                <button type="button"
                                                                    class="text-white bg-blue-700 hover:bg-blue-800 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                                                    dark:hover:bg-gray-600 transition-all duration-200"
                                                                    data-modal-hide="editUserModal{{ $user->id }}">
                                                                    <svg class="w-5 h-5"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                    <span class="sr-only">Close modal</span>
                                                                </button>
                                                            </div>

                                                            <!-- Modal body: The Form with improved styling -->
                                                            <form action="{{ route('users.update', $user->id) }}"
                                                                method="POST"
                                                                class="p-4 md:p-5 space-y-4 bg-gray-50 dark:bg-gray-800">
                                                                @csrf
                                                                @method('PUT')

                                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                    <!-- Left Column -->
                                                                    <div>
                                                                        <!-- User Basic Info (Readonly) -->
                                                                        <div
                                                                            class="mb-4 p-4 bg-white rounded-lg shadow-sm dark:bg-gray-700">
                                                                            <h4
                                                                                class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                                                                                User Information
                                                                            </h4>

                                                                            <div class="flex items-center mb-3">
                                                                                <!-- Avatar placeholder -->
                                                                                <div
                                                                                    class="w-10 h-10 mr-3 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-300 font-bold">
                                                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                                                </div>
                                                                                <div>
                                                                                    <p
                                                                                        class="text-sm font-medium text-gray-900 dark:text-white">
                                                                                        {{ $user->name }}</p>
                                                                                    <p
                                                                                        class="text-xs text-gray-500 dark:text-gray-400">
                                                                                        {{ $user->email }}</p>
                                                                                </div>
                                                                            </div>

                                                                            <div
                                                                                class="text-xs text-gray-500 dark:text-gray-400">
                                                                                <p>User ID: #{{ $user->id }}</p>
                                                                                <p>Created:
                                                                                    {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}
                                                                                </p>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Role -->
                                                                        <div class="mb-4">
                                                                            <label for="role_{{ $user->id }}"
                                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Role
                                                                            </label>
                                                                            <div class="relative">
                                                                                <div
                                                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                                        fill="currentColor"
                                                                                        viewBox="0 0 20 20"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path
                                                                                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z">
                                                                                        </path>
                                                                                    </svg>
                                                                                </div>
                                                                                <select id="role_{{ $user->id }}"
                                                                                    name="role"
                                                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                                focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                                                dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                                                    <option value="admin"
                                                                                        {{ $user->role === 'admin' ? 'selected' : '' }}>
                                                                                        Admin</option>
                                                                                    <option value="staff"
                                                                                        {{ $user->role === 'staff' ? 'selected' : '' }}>
                                                                                        Staff</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Right Column -->
                                                                    <div>
                                                                        <!-- Department -->
                                                                        <div class="mb-4">
                                                                            <label
                                                                                for="department_id_{{ $user->id }}"
                                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Department
                                                                            </label>
                                                                            <div class="relative">
                                                                                <div
                                                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                                        fill="currentColor"
                                                                                        viewBox="0 0 20 20"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path fill-rule="evenodd"
                                                                                            d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4zm3 1h6v4H7V5zm8 8v2h1v1H4v-1h1v-2H4v-1h16v1h-1z"
                                                                                            clip-rule="evenodd"></path>
                                                                                    </svg>
                                                                                </div>
                                                                                <select
                                                                                    id="department_id_{{ $user->id }}"
                                                                                    name="department_id"
                                                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                                focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                                                dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                                                    @foreach ($departments as $dept)
                                                                                        <option
                                                                                            value="{{ $dept->id }}"
                                                                                            {{ $dept->id == $user->department_id ? 'selected' : '' }}>
                                                                                            {{ $dept->name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Designation -->
                                                                        <div class="mb-4">
                                                                            <label
                                                                                for="designation_id_{{ $user->id }}"
                                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Designation
                                                                            </label>
                                                                            <div class="relative">
                                                                                <div
                                                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                                        fill="currentColor"
                                                                                        viewBox="0 0 20 20"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path fill-rule="evenodd"
                                                                                            d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5z"
                                                                                            clip-rule="evenodd"></path>
                                                                                    </svg>
                                                                                </div>
                                                                                <select
                                                                                    id="designation_id_{{ $user->id }}"
                                                                                    name="designation_id"
                                                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                                focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                                                dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                                                    @foreach ($designations as $desig)
                                                                                        <option
                                                                                            value="{{ $desig->id }}"
                                                                                            {{ $desig->id == $user->designation_id ? 'selected' : '' }}>
                                                                                            {{ $desig->name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Status -->
                                                                        <div class="mb-4">
                                                                            <label for="status_{{ $user->id }}"
                                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                                Status
                                                                            </label>
                                                                            <div class="relative">
                                                                                <div
                                                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                                        fill="currentColor"
                                                                                        viewBox="0 0 20 20"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path fill-rule="evenodd"
                                                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                                                            clip-rule="evenodd"></path>
                                                                                    </svg>
                                                                                </div>
                                                                                <select
                                                                                    id="status_{{ $user->id }}"
                                                                                    name="status"
                                                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                                                focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                                                dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                                                    <option value="1"
                                                                                        {{ $user->status ? 'selected' : '' }}>
                                                                                        Active</option>
                                                                                    <option value="0"
                                                                                        {{ !$user->status ? 'selected' : '' }}>
                                                                                        Inactive</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Notes -->
                                                                <div
                                                                    class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg dark:bg-yellow-900/20 dark:border-yellow-900">
                                                                    <div class="flex items-center mb-2">
                                                                        <svg class="w-5 h-5 mr-2 text-yellow-600 dark:text-yellow-500"
                                                                            fill="currentColor" viewBox="0 0 20 20"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path fill-rule="evenodd"
                                                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                                                clip-rule="evenodd"></path>
                                                                        </svg>
                                                                        <h5
                                                                            class="text-sm font-medium text-yellow-700 dark:text-yellow-500">
                                                                            Important Notice</h5>
                                                                    </div>
                                                                    <p
                                                                        class="text-xs text-yellow-700 dark:text-yellow-500">
                                                                        Changing a user's role will affect their
                                                                        permissions in the system. Make sure you verify
                                                                        this change before saving.
                                                                    </p>
                                                                </div>

                                                                <!-- Modal footer -->
                                                                <div
                                                                    class="flex items-center justify-end pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                                                                    <button
                                                                        data-modal-hide="editUserModal{{ $user->id }}"
                                                                        type="button"
                                                                        class="py-2.5 px-5 mr-3 text-sm font-medium text-gray-900 focus:outline-none
                                                                    bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700
                                                                    focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700
                                                                    dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600
                                                                    dark:hover:text-white dark:hover:bg-gray-700 transition-all duration-200">
                                                                        Cancel
                                                                    </button>
                                                                    <button
                                                                        data-modal-hide="editUserModal{{ $user->id }}"
                                                                        type="submit"
                                                                        class="text-white bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900
                                                                    focus:ring-4 focus:outline-none focus:ring-blue-300
                                                                    font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center
                                                                    dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-all duration-200">
                                                                        <svg class="w-4 h-4 mr-2" fill="currentColor"
                                                                            viewBox="0 0 20 20"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path fill-rule="evenodd"
                                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                                clip-rule="evenodd"></path>
                                                                        </svg>
                                                                        Save Changes
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End of Edit Modal -->
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="px-6 py-8 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                                        </path>
                                                    </svg>
                                                    <p class="text-lg font-medium text-gray-500 dark:text-gray-400">No
                                                        users found</p>
                                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Get
                                                        started by creating a new user</p>
                                                    <button type="button" data-modal-target="createUserModal"
                                                        data-modal-toggle="createUserModal"
                                                        class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg transition-colors shadow-sm focus:ring-4 focus:ring-blue-300">
                                                        <svg class="w-4 h-4 mr-2" fill="currentColor"
                                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd"
                                                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        Add User
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pagination (if pagination exists in the original code) -->
                @if (method_exists($users, 'links'))
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

            <!-- CREATE USER MODAL -->
            <div id="createUserModal" tabindex="-1" aria-hidden="true"
                class="hidden fixed top-0 right-0 left-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-full max-h-full flex justify-center items-center bg-gray-900 bg-opacity-50">

                <div class="relative w-full max-w-3xl max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-800 overflow-hidden">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b dark:border-gray-700 bg-gradient-to-r from-blue-600 to-blue-800">
                            <h3 class="text-2xl font-bold text-white flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zm0 2a6 6 0 016 6H2a6 6 0 016-6z"></path>
                                </svg>
                                Create New User
                            </h3>
                            <button type="button"
                                class="text-white bg-blue-700 hover:bg-blue-800 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                dark:hover:bg-gray-600 transition-all duration-200"
                                data-modal-hide="createUserModal">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>

                        <!-- Modal body -> Form -->
                        <form action="{{ route('users.store') }}" method="POST"
                            class="p-6 bg-gray-50 dark:bg-gray-800">
                            @csrf
                            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Fill in the information below to
                                create a new user account.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Left Column -->
                                <div class="space-y-5">
                                    <!-- Personal Information Section -->
                                    <div class="p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                                        <h4
                                            class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor"
                                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Personal Information
                                        </h4>

                                        <!-- Name -->
                                        <div class="mb-4">
                                            <label for="name"
                                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Full Name <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                        fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <input type="text" name="name" id="name"
                                                    placeholder="John Doe"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                        focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    required />
                                            </div>
                                        </div>

                                        <!-- Email -->
                                        <div class="mb-4">
                                            <label for="email"
                                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Email Address <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                        fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                                                        </path>
                                                        <path
                                                            d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <input type="email" name="email" id="email"
                                                    placeholder="john@example.com"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                        focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    required />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Password Section -->
                                    <div class="p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                                        <h4
                                            class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor"
                                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Security
                                        </h4>

                                        <!-- Password -->
                                        <div class="mb-4">
                                            <label for="password"
                                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Password <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                        fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <input type="password" name="password" id="password"
                                                    value="12345678"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                        focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    required />
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Password must be
                                                at least 8 characters</p>
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="mb-4">
                                            <label for="password_confirmation"
                                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Confirm Password <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                        fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <input type="password" name="password_confirmation"
                                                    id="password_confirmation" value="12345678"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                        focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    required />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="space-y-5">
                                    <!-- Role & Organization Section -->
                                    <div class="p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                                        <h4
                                            class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor"
                                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Role & Organization
                                        </h4>

                                        <!-- Role -->
                                        <div class="mb-4">
                                            <label for="role"
                                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Role <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                        fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <select name="role" id="role"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                        focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    required>
                                                    <option value="" disabled
                                                        {{ old('role') ? '' : 'selected' }}>Select role</option>
                                                    <option value="admin"
                                                        {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                                    <option value="staff"
                                                        {{ old('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Department -->
                                        <div class="mb-4">
                                            <label for="department_id"
                                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Department <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                        fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4zm3 1h6v4H7V5zm8 8v2h1v1H4v-1h1v-2H4v-1h16v1h-1z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <select name="department_id" id="department_id"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                        focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    required>
                                                    <option value="" disabled selected>Select department</option>
                                                    @foreach ($departments as $dept)
                                                        <option value="{{ $dept->id }}">{{ $dept->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('department_id')
                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Designation -->
                                        <div class="mb-4">
                                            <label for="designation_id"
                                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Designation <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                        fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"
                                                            clip-rule="evenodd"></path>
                                                        <path
                                                            d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <select name="designation_id" id="designation_id"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                        focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5
                                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    required>
                                                    <option value="" disabled selected>Select designation
                                                    </option>
                                                    @foreach ($designations as $desig)
                                                        <option value="{{ $desig->id }}">{{ $desig->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('designation_id')
                                                <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Notes & Tips -->
                                    <div
                                        class="p-4 bg-blue-50 dark:bg-gray-700 rounded-lg border border-blue-200 dark:border-blue-900">
                                        <h4
                                            class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-2 flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Important Information
                                        </h4>
                                        <ul class="text-xs text-blue-700 dark:text-blue-300 space-y-1 ml-6 list-disc">
                                            <li>Default password will be set to "12345678"</li>
                                            <li>New users will be prompted to update their password on first login</li>
                                            <li>All fields marked with <span class="text-red-500">*</span> are required
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div
                                class="flex items-center justify-end pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                                <button type="button" data-modal-hide="createUserModal"
                                    class="py-2.5 px-5 mr-3 text-sm font-medium text-gray-900 focus:outline-none
                                        bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700
                                        focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700
                                        dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600
                                        dark:hover:text-white dark:hover:bg-gray-700 transition-all duration-200">
                                    Cancel
                                </button>
                                <button type="submit" data-modal-hide="createUserModal"
                                    class="text-white bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900
                                        focus:ring-4 focus:outline-none focus:ring-blue-300
                                        font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center
                                        dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Create User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Existing Charts Section -->
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

            <!-- New Section: Pie Chart and Recent Activities (4:8 grid, leveled) -->
            <div class="grid grid-cols-12 gap-6 mt-8">
                <!-- Pie Chart (4 columns) -->
                <div class="col-span-12 md:col-span-4">
                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-4 h-64">
                        <canvas id="pieChart" class="w-full h-full"></canvas>
                    </div>
                </div>
                <!-- Recent Activities (8 columns) -->
                <div class="col-span-12 md:col-span-8">
                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-4 h-64 flex flex-col">
                        <h3 class="text-lg font-semibold mb-4 dark:text-white">Recent Activities</h3>
                        <ul class="space-y-4 flex-1">
                            <!-- Activity: Added -->
                            <li class="flex items-center">
                                <div class="p-2 bg-green-100 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-700 dark:text-gray-300"><strong>John Doe</strong> added a new
                                        employee.</p>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">2 hours ago</span>
                                </div>
                            </li>
                            <!-- Activity: Edited -->
                            <li class="flex items-center">
                                <div class="p-2 bg-yellow-100 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536M9 11l6-6m-6 6l-4 4v4h4l4-4" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-700 dark:text-gray-300"><strong>Jane Smith</strong> edited
                                        property details.</p>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">5 hours ago</span>
                                </div>
                            </li>
                            <!-- Activity: Removed -->
                            <li class="flex items-center">
                                <div class="p-2 bg-red-100 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v2H9V4a1 1 0 011-1z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-700 dark:text-gray-300"><strong>Admin</strong> removed a supply
                                        item.</p>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">1 day ago</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- ... your existing dashboard code above ... -->

        </div>
    </div>
</x-app-layout>


<!-- Include Chart.js via CDN -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Helper function to update chart text colors based on current mode
        function updateChartColors() {
            const isDark = document.documentElement.classList.contains('dark');
            const newLegendColor = isDark ? '#fff' : '#000';
            const newTickColor = isDark ? '#fff' : '#000';

            if (lineChart) {
                lineChart.options.plugins.legend.labels.color = newLegendColor;
                if (lineChart.options.scales.x) {
                    lineChart.options.scales.x.ticks.color = newTickColor;
                }
                if (lineChart.options.scales.y) {
                    lineChart.options.scales.y.ticks.color = newTickColor;
                }
                lineChart.update();
            }
            if (doughnutChart) {
                doughnutChart.options.plugins.legend.labels.color = newLegendColor;
                doughnutChart.update();
            }
            if (pieChart) {
                pieChart.options.plugins.legend.labels.color = newLegendColor;
                pieChart.update();
            }
        }

        let lineChart, doughnutChart, pieChart;

        // Line Chart Initialization
        const ctxLine = document.getElementById("lineChart").getContext("2d");
        lineChart = new Chart(ctxLine, {
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
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000'
                        }
                    },
                    y: {
                        ticks: {
                            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000'
                        }
                    }
                }
            }
        });

        // Doughnut Chart Initialization
        const ctxDoughnut = document.getElementById("doughnutChart").getContext("2d");
        doughnutChart = new Chart(ctxDoughnut, {
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
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000'
                        }
                    }
                }
            }
        });

        // Pie Chart Initialization
        const ctxPie = document.getElementById("pieChart").getContext("2d");
        pieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Added', 'Edited', 'Removed'],
                datasets: [{
                    label: 'Activities',
                    data: [12, 7, 3],
                    backgroundColor: [
                        'rgb(34, 197, 94)',
                        'rgb(234, 179, 8)',
                        'rgb(239, 68, 68)'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000'
                        }
                    }
                }
            }
        });

        // Set up a MutationObserver to watch for class changes (dark mode toggle)
        const observer = new MutationObserver((mutationsList) => {
            for (const mutation of mutationsList) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    updateChartColors();
                }
            }
        });
        observer.observe(document.documentElement, {
            attributes: true
        });
    });
</script>
