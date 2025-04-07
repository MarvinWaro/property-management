<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Dashboard Cards Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <section class="grid gap-6 lg:grid-cols-2 p-4 lg:p-8 w-full">
                    <!-- Employees Average Request per Month (Purple) -->
                    <div
                        class="p-6 bg-white shadow rounded-2xl dark:bg-gray-900 border-l-4 border-purple-500
                    transition-all duration-300 hover:shadow-lg hover:translate-y-1 hover:border-l-6 cursor-pointer group">
                        <div class="flex justify-between">
                            <dl class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    Employees average request per month
                                </dt>
                                <dd class="text-4xl font-light md:text-5xl dark:text-white">
                                    30
                                </dd>
                                @if ($lastUpdated)
                                    <dd
                                        class="flex items-center space-x-1 text-sm font-medium text-green-500 dark:text-green-400">
                                        <span>Updated {{ $lastUpdated->diffForHumans() }}</span>
                                    </dd>
                                @endif
                            </dl>
                            <div
                                class="rounded-full p-3 bg-purple-100 dark:bg-purple-900 h-fit
                                    transition-all duration-300 group-hover:bg-purple-200 dark:group-hover:bg-purple-800">
                                <svg class="w-8 h-8 text-purple-500 dark:text-purple-300"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
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
                        class="p-6 bg-white shadow rounded-2xl dark:bg-gray-900 border-l-4 border-blue-500
                    transition-all duration-300 hover:shadow-lg hover:translate-y-1 hover:border-l-6 cursor-pointer group">
                        <div class="flex justify-between">
                            <dl class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    Requests per Month
                                </dt>
                                <dd class="text-4xl font-light md:text-5xl dark:text-white">
                                    1,205
                                </dd>
                                <dd
                                    class="flex items-center space-x-1 text-sm font-medium text-red-500 dark:text-red-400">
                                    <span>3% decrease</span>
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17.25 8.75V17.25H8.75" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17L6.75 6.75" />
                                    </svg>
                                </dd>
                            </dl>
                            <div
                                class="rounded-full p-3 bg-blue-100 dark:bg-blue-900 h-fit
                           transition-all duration-300 group-hover:bg-blue-200 dark:group-hover:bg-blue-800">
                                <svg class="w-8 h-8 text-blue-500 dark:text-blue-300" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
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
                        class="p-6 bg-white shadow rounded-2xl dark:bg-gray-900 border-l-4 border-teal-500
                    transition-all duration-300 hover:shadow-lg hover:translate-y-1 hover:border-l-6 cursor-pointer group">
                        <div class="flex justify-between">
                            <dl class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    Total Transaction Cost
                                </dt>
                                <dd class="text-4xl font-light md:text-5xl dark:text-white">
                                    601,000.00
                                </dd>
                                <dd
                                    class="flex items-center space-x-1 text-sm font-medium text-green-500 dark:text-green-400">
                                    <span>2 new</span>
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17.25 15.25V6.75H8.75" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 7L6.75 17.25" />
                                    </svg>
                                </dd>
                            </dl>
                            <div
                                class="rounded-full p-3 bg-teal-100 dark:bg-teal-900 h-fit
                           transition-all duration-300 group-hover:bg-teal-200 dark:group-hover:bg-teal-800">
                                <svg class="w-8 h-8 text-teal-500 dark:text-teal-300" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
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
                        class="p-6 bg-white shadow rounded-2xl dark:bg-gray-900 border-l-4 border-orange-500
                    transition-all duration-300 hover:shadow-lg hover:translate-y-1 hover:border-l-6 cursor-pointer group">
                        <div class="flex justify-between">
                            <dl class="space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    Requests per Month
                                </dt>
                                <dd class="text-4xl font-light md:text-5xl dark:text-white">
                                    1,205
                                </dd>
                                <dd
                                    class="flex items-center space-x-1 text-sm font-medium text-red-500 dark:text-red-400">
                                    <span>3% decrease</span>
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17.25 8.75V17.25H8.75" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17L6.75 6.75" />
                                    </svg>
                                </dd>
                            </dl>
                            <div
                                class="rounded-full p-3 bg-orange-100 dark:bg-orange-900 h-fit
                           transition-all duration-300 group-hover:bg-orange-200 dark:group-hover:bg-orange-800">
                                <svg class="w-8 h-8 text-orange-500 dark:text-orange-300"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
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
                </section>
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

            <!-- New Section: List of Registered Users -->
            <div class="relative overflow-x-auto mt-8">

                <!-- A button somewhere near the top of your user listing -->
                <div class="mt-4">
                    <button type="button" data-modal-target="createUserModal" data-modal-toggle="createUserModal"
                        class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                        Add New User
                    </button>
                </div>

                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <caption
                        class="p-5 text-lg font-semibold text-left text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                        Registered Users
                        <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">
                            A list of all users (staff/admin) in the system.
                        </p>
                    </caption>
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">ID</th>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3">Department</th>
                            <th class="px-6 py-3">Designation</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $user->id }}
                                </td>
                                <td class="px-6 py-4">{{ $user->name }}</td>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4">{{ ucfirst($user->role) }}</td>
                                <td class="px-6 py-4">
                                    {{ $user->department ? $user->department->name : 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $user->designation ? $user->designation->name : 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($user->status)
                                        <span class="text-green-500">Active</span>
                                    @else
                                        <span class="text-red-500">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-2 py-4">
                                    <!-- EDIT ICON => triggers the modal -->
                                    <button type="button" data-modal-target="editUserModal{{ $user->id }}"
                                        data-modal-toggle="editUserModal{{ $user->id }}"
                                        class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-user-round-pen">
                                            <path d="M2 21a8 8 0 0 1 10.821-7.487" />
                                            <path d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5
                                                     0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z" />
                                            <circle cx="10" cy="8" r="5" />
                                        </svg>
                                    </button>

                                    <!-- The Modal for this user -->
                                    <div id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true"
                                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50
                                                justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                        <div class="relative p-4 w-full max-w-2xl max-h-full">
                                            <!-- Modal content -->
                                            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                                                <!-- Modal header -->
                                                <div
                                                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t
                                                            dark:border-gray-600 border-gray-200">
                                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                        Edit User {{ $user->name }}
                                                    </h3>
                                                    <button type="button"
                                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900
                                                                   rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center
                                                                   dark:hover:bg-gray-600 dark:hover:text-white"
                                                        data-modal-hide="editUserModal{{ $user->id }}">
                                                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg"
                                                            fill="none" viewBox="0 0 14 14">
                                                            <path stroke="currentColor" stroke-linecap="round"
                                                                stroke-linejoin="round" stroke-width="2"
                                                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                        </svg>
                                                        <span class="sr-only">Close modal</span>
                                                    </button>
                                                </div>

                                                <!-- Modal body: The Form -->
                                                <form action="{{ route('users.update', $user->id) }}" method="POST"
                                                    class="p-4 md:p-5 space-y-4">
                                                    @csrf
                                                    @method('PUT')

                                                    <!-- Role -->
                                                    <div>
                                                        <label for="role_{{ $user->id }}"
                                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                            Role
                                                        </label>
                                                        <select id="role_{{ $user->id }}" name="role"
                                                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm
                                                                       focus:ring-blue-500 focus:border-blue-500
                                                                       dark:bg-gray-800 dark:text-white dark:border-gray-600">
                                                            <option value="admin"
                                                                {{ $user->role === 'admin' ? 'selected' : '' }}>Admin
                                                            </option>
                                                            <option value="staff"
                                                                {{ $user->role === 'staff' ? 'selected' : '' }}>Staff
                                                            </option>
                                                            <!-- Add more roles if you have them -->
                                                        </select>
                                                    </div>

                                                    <!-- Department -->
                                                    <div>
                                                        <label for="department_id_{{ $user->id }}"
                                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                            Department
                                                        </label>
                                                        <select id="department_id_{{ $user->id }}"
                                                            name="department_id"
                                                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm
                                                                       focus:ring-blue-500 focus:border-blue-500
                                                                       dark:bg-gray-800 dark:text-white dark:border-gray-600">
                                                            @foreach ($departments as $dept)
                                                                <option value="{{ $dept->id }}"
                                                                    {{ $dept->id == $user->department_id ? 'selected' : '' }}>
                                                                    {{ $dept->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Designation -->
                                                    <div>
                                                        <label for="designation_id_{{ $user->id }}"
                                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                            Designation
                                                        </label>
                                                        <select id="designation_id_{{ $user->id }}"
                                                            name="designation_id"
                                                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm
                                                                       focus:ring-blue-500 focus:border-blue-500
                                                                       dark:bg-gray-800 dark:text-white dark:border-gray-600">
                                                            @foreach ($designations as $desig)
                                                                <option value="{{ $desig->id }}"
                                                                    {{ $desig->id == $user->designation_id ? 'selected' : '' }}>
                                                                    {{ $desig->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Status -->
                                                    <div>
                                                        <label for="status_{{ $user->id }}"
                                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                            Status
                                                        </label>
                                                        <select id="status_{{ $user->id }}" name="status"
                                                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm
                                                                       focus:ring-blue-500 focus:border-blue-500
                                                                       dark:bg-gray-800 dark:text-white dark:border-gray-600">
                                                            <option value="1"
                                                                {{ $user->status ? 'selected' : '' }}>Active</option>
                                                            <option value="0"
                                                                {{ !$user->status ? 'selected' : '' }}>Inactive
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <!-- Modal footer -->
                                                    <div
                                                        class="flex items-center pt-4 mt-4 border-t border-gray-200 dark:border-gray-600">
                                                        <button data-modal-hide="editUserModal{{ $user->id }}"
                                                            type="submit"
                                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none
                                                                       focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center
                                                                       dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                            Save Changes
                                                        </button>
                                                        <button data-modal-hide="editUserModal{{ $user->id }}"
                                                            type="button"
                                                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none
                                                                       bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700
                                                                       focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700
                                                                       dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600
                                                                       dark:hover:text-white dark:hover:bg-gray-700">
                                                            Cancel
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of Modal -->
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($errors->any())
                <div class="text-red-500">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <!-- CREATE USER MODAL -->
            <div id="createUserModal" tabindex="-1" aria-hidden="true"
                class="hidden fixed top-0 right-0 left-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full justify-center items-center">

                <div class="relative w-full max-w-2xl max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 border-b dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Create New User
                            </h3>
                            <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900
                                rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center
                                dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-hide="createUserModal">
                                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -> Form -->
                        <form action="{{ route('users.store') }}" method="POST" class="p-4 space-y-4">
                            @csrf

                            <!-- Name -->
                            <div>
                                <label for="name"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    Name
                                </label>
                                <input type="text" name="name" id="name"
                                    class="block w-full border-gray-300 rounded-md shadow-sm
                                        focus:ring-blue-500 focus:border-blue-500
                                        dark:bg-gray-800 dark:text-white dark:border-gray-600" />
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    Email
                                </label>
                                <input type="email" name="email" id="email"
                                    class="block w-full border-gray-300 rounded-md shadow-sm
                                        focus:ring-blue-500 focus:border-blue-500
                                        dark:bg-gray-800 dark:text-white dark:border-gray-600" />
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    Password
                                </label>
                                <input type="password" name="password" id="password" value="12345678"
                                    class="block w-full border-gray-300 rounded-md shadow-sm
                                        focus:ring-blue-500 focus:border-blue-500
                                        dark:bg-gray-800 dark:text-white dark:border-gray-600" />
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    Confirm Password
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    value="12345678"
                                    class="block w-full border-gray-300 rounded-md shadow-sm
                                        focus:ring-blue-500 focus:border-blue-500
                                        dark:bg-gray-800 dark:text-white dark:border-gray-600" />
                            </div>

                            <!-- Role -->
                            <div>
                                <label for="role"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    Role
                                </label>
                                <select name="role" id="role"
                                    class="block w-full border-gray-300 rounded-md shadow-sm
                                            focus:ring-blue-500 focus:border-blue-500
                                            dark:bg-gray-800 dark:text-white dark:border-gray-600">
                                    <!-- Placeholder option -->
                                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select role</option>
                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff</option>

                                </select>
                            </div>


                            <!-- Department -->
                            <div class="mb-4">
                                <label for="department_id"
                                    class="block mb-1 font-medium text-gray-700 dark:text-gray-300">
                                    Department
                                </label>
                                <select name="department_id" id="department_id"
                                    class="block w-full border-gray-300 rounded-md shadow-sm
                                        focus:ring-blue-500 focus:border-blue-500
                                        dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                    <option value="" disabled selected>Select department</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Designation -->
                            <div class="mb-4">
                                <label for="designation_id"
                                    class="block mb-1 font-medium text-gray-700 dark:text-gray-300">
                                    Designation
                                </label>
                                <select name="designation_id" id="designation_id"
                                    class="block w-full border-gray-300 rounded-md shadow-sm
                                        focus:ring-blue-500 focus:border-blue-500
                                        dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                    <option value="" disabled selected>Select designation</option>
                                    @foreach ($designations as $desig)
                                        <option value="{{ $desig->id }}">{{ $desig->name }}</option>
                                    @endforeach
                                </select>
                                @error('designation_id')
                                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Modal Footer -->
                            <div class="flex items-center pt-4 mt-4 border-t border-gray-200 dark:border-gray-600">
                                <button type="submit" data-modal-hide="createUserModal"
                                    class="text-white bg-blue-700 hover:bg-blue-800
                                        focus:ring-4 focus:outline-none focus:ring-blue-300
                                        font-medium rounded-lg text-sm px-5 py-2.5 text-center
                                        dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Create User
                                </button>
                                <button type="button" data-modal-hide="createUserModal"
                                    class="py-2.5 px-5 ml-3 text-sm font-medium text-gray-900 focus:outline-none
                                        bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700
                                        focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700
                                        dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600
                                        dark:hover:text-white dark:hover:bg-gray-700">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

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
