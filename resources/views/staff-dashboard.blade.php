<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Welcome Back!') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-100">
                <div class="container mx-auto py-8">
                    <div class="grid grid-cols-4 sm:grid-cols-12 gap-6 px-4">


                        @if ($forceChangePassword)
                            <script>
                                Swal.fire({
                                    title: 'Change Your Password',
                                    text: 'You still have the default password (12345678). Please change it now.',
                                    icon: 'warning',
                                    showCancelButton: false,
                                    confirmButtonText: 'Change Password'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Redirect them to the password change page
                                        window.location.href = "{{ route('user.force-change-password') }}";
                                    }
                                });
                            </script>
                        @endif


                        <!-- Left sidebar with profile and navigation -->
                        <div class="col-span-4 sm:col-span-3">
                            <!-- Dynamic Profile Card -->
                            <div class="bg-white shadow rounded-lg p-6 mb-6">
                                <div class="flex flex-col items-center">
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
                                        class="w-32 h-32 object-cover object-center bg-gray-300 rounded-full mb-4 shrink-0">
                                    <h1 class="text-xl font-bold">{{ Auth::user()->name }}</h1>
                                    <p class="text-gray-700">{{ optional(Auth::user()->designation)->name }}</p>
                                    <p class="text-gray-700">{{ optional(Auth::user()->department)->name }}</p>
                                </div>
                            </div>

                            <!-- Navigation Menu -->
                            <div class="bg-white shadow rounded-lg overflow-hidden">
                                <h3 class="text-md font-semibold px-6 py-3 border-b border-gray-200">Profile Navigation
                                </h3>
                                <nav id="profile-nav" class="flex flex-col">
                                    <a href="#"
                                        class="profile-nav-link px-6 py-3 text-gray-700 hover:bg-gray-50 border-l-4 border-transparent transition duration-300 ease-in-out"
                                        data-target="requests">
                                        Requests
                                    </a>
                                    <a href="#"
                                        class="profile-nav-link px-6 py-3 text-gray-700 hover:bg-gray-50 border-l-4 border-transparent transition duration-300 ease-in-out"
                                        data-target="properties">
                                        Properties
                                    </a>
                                </nav>
                            </div>
                        </div>

                        <!-- Main content area -->
                        <div class="details col-span-4 sm:col-span-9">
                            <!-- Requests Section (initially hidden) -->

                            @if (session('success'))
                                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                    <span class="block sm:inline">{{ session('success') }}</span>
                                </div>
                            @endif

                            <div id="requests" class="content-section bg-white shadow rounded-lg p-6 hidden">

                                <!-- ... inside #requests section -->
                                <button id="openRequestModal"
                                    class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br
                                    focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800
                                    font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-4">
                                    Request Supply
                                </button>

                                <h2 class="text-xl font-bold my-4">My Requests</h2>
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RIS NO</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DATE</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STATUS</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ACTIONS</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($myRequests as $request)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $request->ris_no }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $request->ris_date->format('M d, Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($request->status === 'draft')
                                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-200 text-yellow-800">Pending</span>
                                                    @elseif($request->status === 'approved')
                                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-200 text-blue-800">Approved</span>
                                                    @elseif($request->status === 'posted')
                                                        <span class="px-2 py-1 text-xs rounded-full bg-green-200 text-green-800">Issued</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <a href="{{ route('ris.show', $request->ris_id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No requests found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <!-- RIS Request Modal -->
                                <div id="requestModal"
                                    class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center hidden">
                                    <div
                                        class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-screen overflow-y-auto">
                                        <div class="flex items-center justify-between p-4 border-b">
                                            <h3 class="text-xl font-semibold text-gray-900">
                                                Create Requisition and Issue Slip
                                            </h3>
                                            <button id="closeRequestModal" class="text-gray-400 hover:text-gray-500">
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>

                                        <form action="{{ route('ris.store') }}" method="POST">
                                            @csrf
                                            <div class="p-6">
                                                <!-- Header Information -->
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 mb-1">Entity
                                                            Name</label>
                                                        <input type="text" name="entity_name"
                                                            value="{{ config('app.name', 'Your Organization') }}"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                            required>
                                                    </div>

                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Fund
                                                            Cluster</label>
                                                        <select name="fund_cluster"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                            <option value="101">101</option>
                                                            <option value="151">151</option>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 mb-1">Division</label>
                                                        <select name="division"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                            required>
                                                            @foreach ($departments as $department)
                                                                <option value="{{ $department->id }}"
                                                                    {{ Auth::user()->department_id == $department->id ? 'selected' : '' }}>
                                                                    {{ $department->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 mb-1">Office</label>
                                                        <input type="text" name="office"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 mb-1">Responsibility
                                                            Center Code</label>
                                                        <input type="text" name="responsibility_center_code"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                    </div>
                                                </div>

                                                <!-- Purpose -->
                                                <div class="mb-6">
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                                                    <textarea name="purpose" rows="2"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                        required></textarea>
                                                </div>

                                                <!-- Supply Items -->
                                                <div class="mb-6">
                                                    <div class="flex justify-between items-center mb-2">
                                                        <h4 class="text-lg font-medium">Requested Items</h4>
                                                        <button type="button" id="addItem"
                                                            class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                                                            Add Item
                                                        </button>
                                                    </div>

                                                    <div class="overflow-x-auto">
                                                        <table class="min-w-full divide-y divide-gray-200">
                                                            <thead>
                                                                <tr>
                                                                    <th
                                                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                        Item</th>
                                                                    <th
                                                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                        Quantity</th>
                                                                    <th
                                                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                        Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="itemsContainer"
                                                                class="divide-y divide-gray-200">
                                                                <!-- Item rows will be added here -->
                                                                <tr class="item-row">
                                                                    <select name="supplies[0][supply_id]"
                                                                        class="w-full px-2 py-1 border border-gray-300 rounded"
                                                                        required>
                                                                        <option value="">Select an item</option>
                                                                        @foreach ($stocks as $stock)
                                                                            <option value="{{ $stock->supply_id }}">
                                                                                {{ $stock->supply->item_name }}
                                                                                ({{ $stock->quantity_on_hand }}
                                                                                available)
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    </td>
                                                                    <td class="px-4 py-2">
                                                                        <input type="number"
                                                                            name="supplies[0][quantity]"
                                                                            min="1" value="1"
                                                                            class="w-full px-2 py-1 border border-gray-300 rounded"
                                                                            required>
                                                                    </td>
                                                                    <td class="px-4 py-2">
                                                                        <button type="button"
                                                                            class="text-red-500 hover:text-red-700 remove-item"
                                                                            disabled>
                                                                            <svg class="h-5 w-5" fill="none"
                                                                                stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M6 18L18 6M6 6l12 12" />
                                                                            </svg>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="px-6 py-3 border-t flex justify-end">
                                                <button type="button" id="cancelRequest"
                                                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 mr-2">
                                                    Cancel
                                                </button>
                                                <button type="submit"
                                                    class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    Submit Request
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        // Modal Controls
                                        const requestModal = document.getElementById('requestModal');
                                        const openRequestModal = document.getElementById('openRequestModal');
                                        const closeRequestModal = document.getElementById('closeRequestModal');
                                        const cancelRequest = document.getElementById('cancelRequest');

                                        openRequestModal.addEventListener('click', function() {
                                            requestModal.classList.remove('hidden');
                                        });

                                        function closeModal() {
                                            requestModal.classList.add('hidden');
                                        }

                                        closeRequestModal.addEventListener('click', closeModal);
                                        cancelRequest.addEventListener('click', closeModal);

                                        // Add/Remove Item Functionality
                                        const addItemBtn = document.getElementById('addItem');
                                        const itemsContainer = document.getElementById('itemsContainer');

                                        addItemBtn.addEventListener('click', function() {
                                            const itemRows = document.querySelectorAll('.item-row');
                                            const newIndex = itemRows.length;

                                            const newRow = document.createElement('tr');
                                            newRow.className = 'item-row';
                                            newRow.innerHTML = `
                                                <td class="px-4 py-2">
                                                    <select name="supplies[${newIndex}][supply_id]" class="w-full px-2 py-1 border border-gray-300 rounded" required>
                                                        <option value="">Select an item</option>
                                                        @foreach($stocks as $stock)
                                                            <option value="{{ $stock->supply_id }}">
                                                                {{ $stock->supply->item_name }} ({{ $stock->quantity_on_hand }} available)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="px-4 py-2">
                                                    <input type="number" name="supplies[${newIndex}][quantity]" min="1" value="1"
                                                        class="w-full px-2 py-1 border border-gray-300 rounded" required>
                                                </td>
                                                <td class="px-4 py-2">
                                                    <button type="button" class="text-red-500 hover:text-red-700 remove-item">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </button>
                                                </td>
                                            `;

                                            itemsContainer.appendChild(newRow);

                                            // Enable the first row's remove button if we now have more than one row
                                            if (newIndex === 1) {
                                                document.querySelector('.remove-item').removeAttribute('disabled');
                                            }

                                            // Add event listener to the new remove button
                                            newRow.querySelector('.remove-item').addEventListener('click', function() {
                                                removeItem(this);
                                            });
                                        });

                                        // Function to remove an item row
                                        function removeItem(button) {
                                            const row = button.closest('.item-row');
                                            row.remove();

                                            // If only one row left, disable its remove button
                                            const itemRows = document.querySelectorAll('.item-row');
                                            if (itemRows.length === 1) {
                                                itemRows[0].querySelector('.remove-item').setAttribute('disabled', 'disabled');
                                            }

                                            // Reindex the remaining rows
                                            itemRows.forEach((row, index) => {
                                                const selectInput = row.querySelector('select');
                                                const quantityInput = row.querySelector('input[type="number"]');

                                                selectInput.name = `supplies[${index}][supply_id]`;
                                                quantityInput.name = `supplies[${index}][quantity]`;
                                            });
                                        }

                                        // Add event listener to the first row's remove button
                                        document.querySelector('.remove-item').addEventListener('click', function() {
                                            removeItem(this);
                                        });
                                    });
                                </script>


                            </div>

                            <!-- Properties Section (initially hidden) -->
                            <div id="properties" class="content-section bg-white shadow rounded-lg p-6 hidden">
                                <h2 class="text-xl font-bold mb-4">My Properties</h2>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @forelse(Auth::user()->properties as $property)
                                        <a href="{{ route('property.view', $property->id) }}" class="block group">
                                            <div
                                                class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden transform transition-all duration-300 group-hover:shadow-lg group-hover:-translate-y-1">
                                                <div class="h-48 bg-gray-200 dark:bg-gray-700 relative">
                                                    @if ($property->images->isNotEmpty())
                                                        <img src="{{ asset('storage/' . $property->images->first()->file_path) }}"
                                                            alt="Property Image" class="w-full h-full object-cover" />
                                                    @else
                                                        <div
                                                            class="w-full h-full flex items-center justify-center bg-gray-300 dark:bg-gray-700">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-12 w-12 text-gray-400" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <div class="absolute top-2 right-2">
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-600 text-white">
                                                            {{ $property->property_number }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="p-4">
                                                    <h3
                                                        class="text-lg font-medium text-gray-900 dark:text-white truncate">
                                                        {{ $property->item_name }}</h3>
                                                    <p
                                                        class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                                                        {{ \Illuminate\Support\Str::limit($property->item_description ?? 'No description available', 60) }}
                                                    </p>
                                                    <div class="flex items-center justify-between mt-3">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $property->acquisition_date ? $property->acquisition_date->format('M Y') : 'Unknown date' }}
                                                        </span>
                                                        <span
                                                            class="text-blue-600 dark:text-blue-400 text-sm font-medium group-hover:underline">View
                                                            Details</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <p class="text-gray-700">No properties assigned to you.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Tab Navigation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('#profile-nav a.profile-nav-link');
            // If no link is active by default, activate the first one
            if (navLinks.length && !document.querySelector('#profile-nav a.profile-nav-link.active')) {
                navLinks[0].classList.add('active', 'text-blue-600', 'border-blue-600', 'bg-blue-50',
                    'font-medium');
                navLinks[0].classList.remove('text-gray-700');
                const defaultTarget = navLinks[0].getAttribute('data-target');
                if (defaultTarget) {
                    document.getElementById(defaultTarget).classList.remove('hidden');
                }
            }

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Remove active classes from all nav links
                    navLinks.forEach(nav => {
                        nav.classList.remove('active', 'text-blue-600', 'border-blue-600',
                            'bg-blue-50', 'font-medium');
                        nav.classList.add('text-gray-700');
                    });

                    // Add active classes to the clicked link
                    this.classList.add('active', 'text-blue-600', 'border-blue-600', 'bg-blue-50',
                        'font-medium');
                    this.classList.remove('text-gray-700');

                    // Hide all content sections
                    document.querySelectorAll('.content-section').forEach(section => {
                        section.classList.add('hidden');
                    });

                    // Show the targeted section
                    const targetId = this.getAttribute('data-target');
                    const targetSection = document.getElementById(targetId);
                    if (targetSection) {
                        targetSection.classList.remove('hidden');
                    }
                });
            });
        });
    </script>
</x-app-layout>
