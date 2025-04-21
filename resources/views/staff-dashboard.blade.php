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
                                <h3 class="text-md font-semibold px-6 py-3 border-b border-gray-200">Profile Navigation</h3>
                                <nav id="profile-nav" class="flex flex-col">
                                    <a href="#" class="profile-nav-link px-6 py-3 text-gray-700 hover:bg-gray-50 border-l-4 border-transparent transition duration-300 ease-in-out" data-target="requests">
                                        Requests
                                    </a>
                                    <a href="#" class="profile-nav-link px-6 py-3 text-gray-700 hover:bg-gray-50 border-l-4 border-transparent transition duration-300 ease-in-out" data-target="properties">
                                        Properties
                                    </a>
                                </nav>
                            </div>
                        </div>

                        <!-- Main content area -->
                        <div class="details col-span-4 sm:col-span-9">
                            <!-- Requests Section (initially hidden) -->
                            <div id="requests" class="content-section bg-white shadow rounded-lg p-6 hidden">

                                <a href="#" type="button" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Request Supply</a>

                                <h2 class="text-xl font-bold my-4">My Requests</h2>
                                <!-- Replace the following table with your dynamic request data -->


                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request ID</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">REQ-001</td>
                                            <td class="px-6 py-4 whitespace-nowrap">Request A</td>
                                            <td class="px-6 py-4 whitespace-nowrap">Pending</td>
                                        </tr>
                                        <!-- More static rows as needed -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Properties Section (initially hidden) -->
                            <div id="properties" class="content-section bg-white shadow rounded-lg p-6 hidden">
                                <h2 class="text-xl font-bold mb-4">My Properties</h2>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @forelse(Auth::user()->properties as $property)
                                        <a href="{{ route('property.view', $property->id) }}" class="block group">
                                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden transform transition-all duration-300 group-hover:shadow-lg group-hover:-translate-y-1">
                                                <div class="h-48 bg-gray-200 dark:bg-gray-700 relative">
                                                    @if($property->images->isNotEmpty())
                                                        <img src="{{ asset('storage/' . $property->images->first()->file_path) }}"
                                                            alt="Property Image" class="w-full h-full object-cover" />
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center bg-gray-300 dark:bg-gray-700">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <div class="absolute top-2 right-2">
                                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-600 text-white">
                                                            {{ $property->property_number }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="p-4">
                                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white truncate">{{ $property->item_name }}</h3>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                                                        {{ \Illuminate\Support\Str::limit($property->item_description ?? 'No description available', 60) }}
                                                    </p>
                                                    <div class="flex items-center justify-between mt-3">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $property->acquisition_date ? $property->acquisition_date->format('M Y') : 'Unknown date' }}
                                                        </span>
                                                        <span class="text-blue-600 dark:text-blue-400 text-sm font-medium group-hover:underline">View Details</span>
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
                navLinks[0].classList.add('active', 'text-blue-600', 'border-blue-600', 'bg-blue-50', 'font-medium');
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
                        nav.classList.remove('active', 'text-blue-600', 'border-blue-600', 'bg-blue-50', 'font-medium');
                        nav.classList.add('text-gray-700');
                    });

                    // Add active classes to the clicked link
                    this.classList.add('active', 'text-blue-600', 'border-blue-600', 'bg-blue-50', 'font-medium');
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
