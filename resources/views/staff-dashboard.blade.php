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
                        <!-- Left sidebar with profile and navigation -->
                        <div class="col-span-4 sm:col-span-3">
                            <!-- Dynamic Profile Card -->
                            <div class="bg-white shadow rounded-lg p-6 mb-6">
                                <div class="flex flex-col items-center">
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"class="w-32 h-32 object-cover object-center bg-gray-300 rounded-full mb-4 shrink-0">
                                    <h1 class="text-xl font-bold">{{ Auth::user()->name }}</h1>
                                    <p class="text-gray-700">{{ optional(Auth::user()->designation)->name }}</p>
                                    <p class="text-gray-700">{{ optional(Auth::user()->department)->name }}</p>
                                </div>
                            </div>

                            <!-- Navigation Menu -->
                            <div class="bg-white shadow rounded-lg overflow-hidden">
                                <h3 class="text-md font-semibold px-6 py-3 border-b border-gray-200">Profile Navigation</h3>
                                <nav id="profile-nav" class="flex flex-col">
                                    <a href="#" class="profile-nav-link px-6 py-3 text-gray-700 hover:bg-gray-50 border-l-4 border-transparent transition duration-300 ease-in-out" data-target="about">
                                        About Me
                                    </a>
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
                            <!-- About Me Section -->
                            <div id="about" class="content-section bg-white shadow rounded-lg p-6">
                                <h2 class="text-xl font-bold mb-4">About Me</h2>
                                <p class="text-gray-700">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed finibus est vitae tortor ullamcorper, ut vestibulum velit convallis.
                                </p>
                            </div>

                            <!-- Requests Section (initially hidden) -->
                            <div id="requests" class="content-section bg-white shadow rounded-lg p-6 hidden">
                                <h2 class="text-xl font-bold mb-4">My Requests</h2>
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
                                <!-- Replace the following content with your dynamic property data -->
                                <p class="text-gray-700">List of properties goes here.</p>
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
