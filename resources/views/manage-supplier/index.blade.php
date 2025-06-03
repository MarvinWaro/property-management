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
                    <div class="flex items-center justify-between mb-4 mt-2 space-x-2 w-full">
                        <!-- Search Bar Container -->
                        <form method="GET" action="{{ route('supplier.index') }}"
                            class="w-full max-w-sm flex items-center space-x-2">
                            <div class="relative flex-grow">
                                <input type="text" name="search" id="search-input"
                                    value="{{ request()->get('search') }}" oninput="toggleClearButton()"
                                    placeholder="Search..."
                                    class="px-4 py-2 w-full border text-sm font-medium border-gray-300 rounded-lg
                                        focus:ring-1 focus:ring-orange-500 focus:border-orange-500
                                        dark:bg-gray-800 dark:border-gray-700 dark:text-white
                                        dark:focus:ring-orange-500 dark:focus:border-orange-500" />

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
                                class="px-3 py-2 text-sm text-white bg-orange-600 rounded-lg
                                    hover:bg-orange-800 focus:ring-1 focus:outline-none
                                    focus:ring-orange-300 dark:bg-orange-600 dark:hover:bg-orange-700
                                    dark:focus:ring-orange-800 flex items-center">
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

                        <!-- Flat Icon Button for Adding Supplier -->
                        <button data-modal-target="createSupplierModal" data-modal-toggle="createSupplierModal"
                            type="button"
                            class="py-2 px-3 text-white bg-orange-600 hover:bg-orange-700 hover:shadow-lg transform hover:scale-105 rounded-lg text-sm font-medium focus:ring-4 focus:outline-none focus:ring-orange-300 dark:focus:ring-orange-800 transition-all duration-200 ml-2 inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="hidden sm:inline-block">Add Supplier</span>
                        </button>
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

                    <!-- Table Description Caption -->
                    <div class="p-4 mb-4 text-sm text-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-gray-300">
                        <h3 class="text-lg font-semibold mb-1 text-gray-900 dark:text-white">CHED Supplier Details</h3>
                        <p>
                            This section lists accredited suppliers' names, emails, and contact
                            numbers, facilitating efficient supplier management and
                            streamlined communication within CHED.
                        </p>
                    </div>

                    {{-- SUPPLIER TABLE --}}
                    <div class="overflow-hidden shadow-md sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="overflow-x-auto">
                            <div class="overflow-y-auto max-h-[500px]">
                                <table class="w-full text-sm text-left">
                                    <thead
                                        <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">ID</th>
                                            <th scope="col" class="px-6 py-3">Name / Email</th>
                                            <th scope="col" class="px-6 py-3">Contact Person</th>
                                            <th scope="col" class="px-6 py-3">Contact No</th>
                                            <th scope="col" class="px-6 py-3 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($suppliers as $supplier)
                                            <tr
                                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <th scope="row"
                                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {{ $supplier->id }}
                                                </th>
                                                <td class="px-6 py-4 font-medium">
                                                    <div class="text-gray-900 dark:text-white">{{ $supplier->name }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $supplier->email ?? 'No email provided' }}</div>
                                                </td>
                                                <td class="px-6 py-4 text-gray-900 dark:text-white">
                                                    {{ $supplier->contact_person }}</td>
                                                <td class="px-6 py-4 text-gray-900 dark:text-white">
                                                    {{ $supplier->contact_number }}</td>
                                                <td class="px-2 py-4">
                                                    <div class="flex items-center justify-center space-x-2">
                                                        <!-- View Button -->
                                                        <button type="button"
                                                            data-modal-target="viewSupplierModal{{ $supplier->id }}"
                                                            data-modal-toggle="viewSupplierModal{{ $supplier->id }}"
                                                            class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800 transition-all duration-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path
                                                                    d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                                                <circle cx="12" cy="12" r="3" />
                                                            </svg>
                                                        </button>

                                                        <!-- Edit Button -->
                                                        <button type="button"
                                                            data-modal-target="editSupplierModal{{ $supplier->id }}"
                                                            data-modal-toggle="editSupplierModal{{ $supplier->id }}"
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
                                                        </button>

                                                        <!-- Delete Button -->
                                                        <button type="button"
                                                            data-modal-target="deleteSupplierModal{{ $supplier->id }}"
                                                            data-modal-toggle="deleteSupplierModal{{ $supplier->id }}"
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
                                                    </div>

                                                    <!-- View Modal (updated with Contact Person) -->
                                                    <div id="viewSupplierModal{{ $supplier->id }}" tabindex="-1" aria-hidden="true"
                                                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                                        <!-- Modal content -->
                                                        <div class="relative p-4 w-full max-w-md max-h-full">
                                                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                                <!-- Modal header -->
                                                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 bg-gradient-to-r from-blue-500 to-blue-700">
                                                                    <h3 class="text-lg font-semibold text-white">
                                                                        Supplier Details
                                                                    </h3>
                                                                    <button type="button"
                                                                        class="text-white bg-transparent hover:bg-blue-800 hover:text-white rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600"
                                                                        data-modal-hide="viewSupplierModal{{ $supplier->id }}">
                                                                        <svg class="w-3 h-3" aria-hidden="true"
                                                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                                        </svg>
                                                                        <span class="sr-only">Close modal</span>
                                                                    </button>
                                                                </div>
                                                                <!-- Modal body -->
                                                                <div class="p-4 md:p-5 space-y-4">
                                                                    <!-- Supplier Name -->
                                                                    <div class="space-y-2">
                                                                        <div class="flex items-center">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round" stroke-linejoin="round" class="text-blue-500 mr-2">
                                                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                                                <circle cx="12" cy="7" r="4"></circle>
                                                                            </svg>
                                                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</p>
                                                                        </div>
                                                                        <p class="text-base font-semibold text-gray-900 dark:text-white">
                                                                            {{ $supplier->name }}
                                                                        </p>
                                                                    </div>
                                                                    <!-- Supplier Email -->
                                                                    <div class="space-y-2">
                                                                        <div class="flex items-center">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round" stroke-linejoin="round" class="text-blue-500 mr-2">
                                                                                <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                                                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                                                            </svg>
                                                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</p>
                                                                        </div>
                                                                        <p class="text-base font-semibold text-gray-900 dark:text-white">
                                                                            {{ $supplier->email ?? 'Not provided' }}
                                                                        </p>
                                                                    </div>
                                                                    <!-- New: Supplier Contact Person -->
                                                                    <div class="space-y-2">
                                                                        <div class="flex items-center">
                                                                            <!-- A generic user icon for contact person -->
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round" stroke-linejoin="round" class="text-blue-500 mr-2">
                                                                                <path d="M16 21v-2a4 4 0 0 0-4-4H12a4 4 0 0 0-4 4v2"></path>
                                                                                <circle cx="12" cy="7" r="4"></circle>
                                                                            </svg>
                                                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Contact Person</p>
                                                                        </div>
                                                                        <p class="text-base font-semibold text-gray-900 dark:text-white">
                                                                            {{ $supplier->contact_person ?? 'Not provided' }}
                                                                        </p>
                                                                    </div>
                                                                    <!-- Supplier Contact Number -->
                                                                    <div class="space-y-2">
                                                                        <div class="flex items-center">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round" stroke-linejoin="round" class="text-blue-500 mr-2">
                                                                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                                                            </svg>
                                                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Contact Number</p>
                                                                        </div>
                                                                        <p class="text-base font-semibold text-gray-900 dark:text-white">
                                                                            {{ $supplier->contact_number }}
                                                                        </p>
                                                                    </div>
                                                                    <!-- Supplier Created On -->
                                                                    <div class="space-y-2">
                                                                        <div class="flex items-center">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round" stroke-linejoin="round" class="text-blue-500 mr-2">
                                                                                <circle cx="12" cy="12" r="10"></circle>
                                                                                <polyline points="12 6 12 12 16 14"></polyline>
                                                                            </svg>
                                                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Created On</p>
                                                                        </div>
                                                                        <p class="text-base font-semibold text-gray-900 dark:text-white">
                                                                            {{ $supplier->created_at->format('M d, Y') }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <!-- Modal footer -->
                                                                <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                                                    <button data-modal-hide="viewSupplierModal{{ $supplier->id }}" type="button"
                                                                        class="py-2.5 px-5 ms-3 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                                        Close
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Edit Modal -->
                                                    <div id="editSupplierModal{{ $supplier->id }}" tabindex="-1"
                                                        aria-hidden="true"
                                                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                                        <div class="relative p-4 w-full max-w-md max-h-full">
                                                            <div
                                                                class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                                <div
                                                                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 bg-gradient-to-r from-yellow-400 to-yellow-600">
                                                                    <h3 class="text-lg font-semibold text-white">
                                                                        Edit Supplier
                                                                    </h3>
                                                                    <button type="button"
                                                                        class="text-white bg-transparent hover:bg-yellow-600 hover:text-white rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600"
                                                                        data-modal-hide="editSupplierModal{{ $supplier->id }}">
                                                                        <svg class="w-3 h-3" aria-hidden="true"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 14 14">
                                                                            <path stroke="currentColor"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                                        </svg>
                                                                        <span class="sr-only">Close modal</span>
                                                                    </button>
                                                                </div>
                                                                <!-- Edit Form -->
                                                                <form
                                                                    action="{{ route('supplier.update', $supplier->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="p-4 md:p-5 space-y-4">
                                                                        <div>
                                                                            <label for="edit-name-{{ $supplier->id }}"
                                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                                                                            <input type="text" name="name"
                                                                                id="edit-name-{{ $supplier->id }}"
                                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                                                value="{{ $supplier->name }}"
                                                                                required>
                                                                        </div>
                                                                        <div>
                                                                            <label for="edit-email-{{ $supplier->id }}"
                                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                                                                            <input type="email" name="email"
                                                                                id="edit-email-{{ $supplier->id }}"
                                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                                                value="{{ $supplier->email }}">
                                                                        </div>
                                                                        <div>
                                                                            <label for="edit-contact-{{ $supplier->id }}"
                                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contact
                                                                                Number</label>
                                                                            <input type="text" name="contact_no"
                                                                                id="edit-contact-{{ $supplier->id }}"
                                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                                                value="{{ $supplier->contact_number }}"
                                                                                required>
                                                                        </div>
                                                                        <!-- New Field: Address -->
                                                                        <div>
                                                                            <label for="edit-address-{{ $supplier->id }}"
                                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                                                                            <input type="text" name="address"
                                                                                id="edit-address-{{ $supplier->id }}"
                                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                                                value="{{ $supplier->address }}"
                                                                                placeholder="Enter address">
                                                                        </div>
                                                                        <!-- New Field: Contact Person -->
                                                                        <div>
                                                                            <label for="edit-contact-person-{{ $supplier->id }}"
                                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contact Person</label>
                                                                            <input type="text" name="contact_person"
                                                                                id="edit-contact-person-{{ $supplier->id }}"
                                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                                                value="{{ $supplier->contact_person }}"
                                                                                placeholder="Enter contact person">
                                                                        </div>
                                                                    </div>
                                                                    <!-- Modal footer -->
                                                                    <div
                                                                        class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                                                        <button
                                                                            data-modal-hide="editSupplierModal{{ $supplier->id }}"
                                                                            type="button"
                                                                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancel</button>
                                                                        <button type="submit"
                                                                            class="py-2.5 px-5 ms-3 text-sm font-medium text-white bg-yellow-600 rounded-lg hover:bg-yellow-700 focus:ring-4 focus:outline-none focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">Update
                                                                            Supplier</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Delete Modal (keep as is) -->
                                                    <div id="deleteSupplierModal{{ $supplier->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                                        <div class="relative p-4 w-full max-w-md max-h-full">
                                                            <div
                                                                class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                                <div
                                                                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 bg-gradient-to-r from-red-500 to-red-700">
                                                                    <h3
                                                                        class="text-lg font-semibold text-white flex items-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="20" height="20"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round" class="mr-2">
                                                                            <path d="M3 6h18"></path>
                                                                            <path
                                                                                d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                            </path>
                                                                            <path
                                                                                d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2">
                                                                            </path>
                                                                            <line x1="10" y1="11"
                                                                                x2="10" y2="17"></line>
                                                                            <line x1="14" y1="11"
                                                                                x2="14" y2="17"></line>
                                                                        </svg>
                                                                        Delete Supplier
                                                                    </h3>
                                                                    <button type="button"
                                                                        class="text-white bg-transparent hover:bg-red-800 hover:text-white rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                                                        data-modal-hide="deleteSupplierModal{{ $supplier->id }}">
                                                                        <svg class="w-3 h-3" aria-hidden="true"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 14 14">
                                                                            <path stroke="currentColor"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                                        </svg>
                                                                        <span class="sr-only">Close modal</span>
                                                                    </button>
                                                                </div>
                                                                <div class="p-6">
                                                                    <div class="mb-5 text-center">
                                                                        <div
                                                                            class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                class="h-10 w-10 text-red-600"
                                                                                fill="none" viewBox="0 0 24 24"
                                                                                stroke="currentColor">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                            </svg>
                                                                        </div>
                                                                        <h3
                                                                            class="text-xl font-semibold text-gray-900 dark:text-white mt-3">
                                                                            Confirm Deletion</h3>
                                                                        <div
                                                                            class="mt-2 text-gray-600 dark:text-gray-400">
                                                                            <p>Are you sure you want to delete supplier:
                                                                            </p>
                                                                            <p
                                                                                class="font-semibold text-gray-800 dark:text-white mt-1">
                                                                                "{{ $supplier->name }}"</p>
                                                                        </div>
                                                                        <p class="mt-3 text-sm text-red-500">This
                                                                            action cannot be undone.</p>
                                                                    </div>

                                                                    <form
                                                                        action="{{ route('supplier.destroy', $supplier->id) }}"
                                                                        method="POST" class="mt-6">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <div
                                                                            class="flex items-center justify-center space-x-4">
                                                                            <button
                                                                                data-modal-hide="deleteSupplierModal{{ $supplier->id }}"
                                                                                type="button"
                                                                                class="py-2.5 px-5 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                                                                Cancel
                                                                            </button>
                                                                            <button type="submit"
                                                                                class="py-2.5 px-5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 inline-flex items-center">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    width="16" height="16"
                                                                                    viewBox="0 0 24 24" fill="none"
                                                                                    stroke="currentColor"
                                                                                    stroke-width="2"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    class="mr-2">
                                                                                    <path d="M3 6h18"></path>
                                                                                    <path
                                                                                        d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6">
                                                                                    </path>
                                                                                </svg>
                                                                                Delete Permanently
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-8 text-center">
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
                                                        <p
                                                            class="text-lg font-medium text-gray-500 dark:text-gray-400">
                                                            No suppliers found</p>
                                                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Get
                                                            started by adding a new supplier</p>
                                                        <button type="button" data-modal-target="createSupplierModal"
                                                            data-modal-toggle="createSupplierModal"
                                                            class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg transition-colors shadow-sm focus:ring-4 focus:ring-blue-300">
                                                            <svg class="w-4 h-4 mr-2" fill="currentColor"
                                                                viewBox="0 0 20 20"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                            Add Supplier
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

                    <!-- Pagination -->
                    <nav class="flex items-center justify-between pt-4 mb-3" aria-label="Table navigation">
                        <!-- On the left side (optional) -->
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            @if ($suppliers->count() > 0)
                                Showing {{ $suppliers->firstItem() }} to {{ $suppliers->lastItem() }} of
                                {{ $suppliers->total() }} suppliers
                            @endif
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

    <!-- Create Supplier Modal -->
    <div id="createSupplierModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div
                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 bg-gradient-to-r from-green-500 to-green-700">
                    <h3 class="text-lg font-semibold text-white">
                        Add New Supplier
                    </h3>
                    <button type="button"
                        class="text-white bg-transparent hover:bg-green-800 hover:text-white rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600"
                        data-modal-hide="createSupplierModal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('supplier.store') }}" method="POST">
                    @csrf
                    <div class="p-4 md:p-5 space-y-4">
                        <div>
                            <label for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Enter supplier name" required>
                        </div>
                        <div>
                            <label for="email"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                            <input type="email" name="email" id="email"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Enter email address">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Optional. Leave blank if not
                                available.</p>
                        </div>
                        <div>
                            <label for="contact_no"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contact Number
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="contact_no" id="contact_no"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Enter contact number" required>
                        </div>
                        <!-- New Field: Address -->
                        <div>
                            <label for="address"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                            <input type="text" name="address" id="address"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Enter address">
                        </div>
                        <!-- New Field: Contact Person -->
                        <div>
                            <label for="contact_person"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contact Person</label>
                            <input type="text" name="contact_person" id="contact_person"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Enter contact person">
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button data-modal-hide="createSupplierModal" type="button"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancel</button>
                        <button type="submit"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Add
                            Supplier</button>
                    </div>
                </form>
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
            document.querySelector('form').submit();
        }

        // On page load, call toggleClearButton to show X if there's existing text
        document.addEventListener('DOMContentLoaded', () => {
            toggleClearButton();
        });
    </script>
</x-app-layout>
