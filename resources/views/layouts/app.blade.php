<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link href="{{ asset('css/main.css') }}" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.0/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.0/dist/sweetalert2.all.min.js"></script>

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles

    <script>
        // Set up CSRF token for all Axios requests
        document.addEventListener('DOMContentLoaded', function() {
            window.axios = window.axios || {};
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content');
            window.axios.defaults.withCredentials = true;
        });
    </script>
</head>

<body class="font-sans antialiased">

    {{-- <x-loader /> --}}

    <x-banner />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @livewire('navigation-menu')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @stack('modals')

    @livewireScripts

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.0.0/flowbite.min.js"></script>

    @if(auth()->check() && auth()->user()->role === 'admin')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const requisitionNavLink = document.getElementById('requisition-nav-link');
            if (!requisitionNavLink) return;

            // Check for pending requisitions and update badge
            const checkPendingRequisitions = async () => {
                try {
                    const response = await fetch('/pending-requisitions', {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        credentials: 'include'
                    });

                    if (response.ok) {
                        const data = await response.json();
                        updateBadge(data.count);
                    }
                } catch (error) {
                    console.error('Error checking for pending requisitions:', error);
                }
            };

            // Update badge visibility based on count
            const updateBadge = (count) => {
                let badge = document.getElementById('ris-notification-badge');

                if (count > 0) {
                    if (!badge) {
                        // Create badge if it doesn't exist
                        badge = document.createElement('span');
                        badge.id = 'ris-notification-badge';
                        badge.className = 'absolute inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-[#f59e0b] border-2 border-white rounded-full -top-1 -right-1';
                        requisitionNavLink.appendChild(badge);
                    }
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.classList.remove('hidden');
                } else if (badge) {
                    badge.classList.add('hidden');
                }
            };

            // Optional: Mark requisitions as viewed (but don't call automatically)
            const markRequisitionsAsViewed = async () => {
                try {
                    await fetch('/mark-requisitions-viewed', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        credentials: 'include'
                    });
                    // After marking as viewed, still check the actual count
                    checkPendingRequisitions();
                } catch (error) {
                    console.error('Error marking requisitions as viewed:', error);
                }
            };

            // Initial check
            checkPendingRequisitions();

            // Poll every 30 seconds
            setInterval(checkPendingRequisitions, 30000);

            // Update when page becomes visible again
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    checkPendingRequisitions();
                }
            });

            // Optional: Add a manual "Mark as Read" functionality
            // You can add a button in your RIS index page that calls this function
            window.markAllRequisitionsAsRead = function() {
                markRequisitionsAsViewed();
            };
        });
    </script>
    @endif

</body>

{{-- chart js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Reusable SweetAlert Delete Confirmation -->
<script>
    /**
     * Show a delete confirmation alert and, if confirmed, submit the form.
     * @param {string} formId
     * @param {string} [itemName='this item']
     */
    function confirmDelete(formId, itemName = 'this item') {
        Swal.fire({
            title: "Are you sure?",
            html: `<p style="color:red;">You won't be able to revert ${itemName}!</p>`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#FF2D20",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>

{{-- Success flash message --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        @if (session('success'))
            Swal.fire({
                title: "Success!",
                text: "{{ session('success') }}",
                icon: "success",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            });
        @endif
    });
</script>

{{-- Dark/Light mode toggle --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle       = document.getElementById('theme-toggle');
        const darkIcon          = document.getElementById('theme-toggle-dark-icon');
        const lightIcon         = document.getElementById('theme-toggle-light-icon');

        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
            darkIcon.classList.remove('hidden');
        } else {
            document.documentElement.classList.remove('dark');
            lightIcon.classList.remove('hidden');
        }

        themeToggle.addEventListener('click', function() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                darkIcon.classList.add('hidden');
                lightIcon.classList.remove('hidden');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                darkIcon.classList.remove('hidden');
                lightIcon.classList.add('hidden');
            }
        });
    });
</script>

</html>
