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


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">

    <x-loader />

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
            // Only run this script if the requisition nav link exists
            const requisitionNavLink = document.getElementById('requisition-nav-link');
            if (!requisitionNavLink) return;

            // Check if we're currently on the requisitions page
            const isRequisitionPage = window.location.pathname.includes('/ris') || window.location.href.includes('ris.index');

            // Function to check for pending requisitions
            const checkPendingRequisitions = async () => {
                try {
                    // If we're on the requisition page, don't show the badge and mark as viewed
                    if (isRequisitionPage) {
                        hideBadge();
                        markRequisitionsAsViewed();
                        return;
                    }

                    const response = await fetch('/api/pending-requisitions', {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    });

                    if (response.ok) {
                        const data = await response.json();
                        updateBadge(data.count);
                    }
                } catch (error) {
                    console.error('Error checking for pending requisitions:', error);
                }
            };

            // Function to update the badge
            const updateBadge = (count) => {
                const badge = document.getElementById('ris-notification-badge');
                if (!badge) return;

                if (count > 0) {
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            };

            // Function to hide the badge
            const hideBadge = () => {
                const badge = document.getElementById('ris-notification-badge');
                if (badge) {
                    badge.classList.add('hidden');
                }
            };

            // Function to mark requisitions as viewed
            const markRequisitionsAsViewed = async () => {
                try {
                    await fetch('/api/mark-requisitions-viewed', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        credentials: 'same-origin'
                    });
                } catch (error) {
                    console.error('Error marking requisitions as viewed:', error);
                }
            };

            // Check immediately and then every 30 seconds, but only if we're not on the requisition page
            if (!isRequisitionPage) {
                checkPendingRequisitions();
                setInterval(checkPendingRequisitions, 30000);
            } else {
                hideBadge();
                markRequisitionsAsViewed();
            }

            // Add click event to the requisition link to hide badge when clicked
            requisitionNavLink.addEventListener('click', function() {
                hideBadge();
                markRequisitionsAsViewed();
            });
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
         * @param {string} formId - The id of the form to submit on confirmation.
         * @param {string} [itemName='this item'] - Optional name of the item to be deleted.
         */
        function confirmDelete(formId, itemName = 'this item') {
            Swal.fire({
                title: "Are you sure?",
                html: `<p style="color:red;">You won't be able to revert ${itemName}!</p>`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#FF2D20", // Red tone
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>


{{-- This is for the success message --}}
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

{{-- SCRIPT FOR DARK AND LIGHT MODE --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.getElementById('theme-toggle');
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Check localStorage for theme preference
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
            themeToggleDarkIcon.classList.remove('hidden');
        } else {
            document.documentElement.classList.remove('dark');
            themeToggleLightIcon.classList.remove('hidden');
        }

        // Toggle theme when button is clicked
        themeToggle.addEventListener('click', function() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                themeToggleDarkIcon.classList.add('hidden');
                themeToggleLightIcon.classList.remove('hidden');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                themeToggleDarkIcon.classList.remove('hidden');
                themeToggleLightIcon.classList.add('hidden');
            }
        });
    });
</script>

{{-- Loader Script --}}
<script>
    // Function to show the loader
    function showLoader() {
        const loader = document.getElementById('loader-container');
        if (loader) {
            loader.classList.add('show');
        }
    }

    // Function to hide the loader
    function hideLoader() {
        const loader = document.getElementById('loader-container');
        if (loader) {
            loader.classList.remove('show');
        }
    }

    // Show loader on page load
    document.addEventListener('DOMContentLoaded', function() {
        showLoader();

        // Hide loader after 2 seconds
        setTimeout(function() {
            hideLoader();
        }, 2000);
    });

    // Show loader on Livewire route navigation
    document.addEventListener('livewire:navigating', function() {
        showLoader();
    });

    // Hide loader when Livewire finishes route navigation
    document.addEventListener('livewire:navigated', function() {
        setTimeout(function() {
            hideLoader();
        }, 800);
    });

    // **NEW**: Hide loader after any Livewire AJAX request finishes,
    // even if we did NOT navigate away
    document.addEventListener('livewire:request-finished', function() {
        setTimeout(function() {
            hideLoader();
        }, 500);
    });

    // Show loader on standard form submissions
    document.addEventListener('submit', function(e) {
        // If a form has data-no-loader, skip showing loader
        if (!e.target.hasAttribute('data-no-loader')) {
            showLoader();
        }
    });
</script>


<!-- Reusable SweetAlert Scripts -->
{{-- <script>
    // Generic Delete Confirmation
    function confirmDelete(formId, itemName = 'this item') {
        Swal.fire({
            title: "Are you sure?",
            text: `You won't be able to revert ${itemName}!`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }

    // Show Success Alert for Deletion or Other Messages
    document.addEventListener("DOMContentLoaded", function() {
        @if (session('success') || session('deleted'))
            setTimeout(() => {
                Swal.fire({
                    title: "Success!",
                    text: "{{ session('success') ?? session('deleted') }}",
                    icon: "success",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "OK"
                });
            }, 500);
        @endif
    });
</script> --}}

</html>
