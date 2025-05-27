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
            const requisitionNavLink = document.getElementById('requisition-nav-link');
            if (!requisitionNavLink) return;

            const isRequisitionPage = window.location.pathname.includes('/ris') ||
                                      window.location.href.includes('ris.index');

            // Poll for new drafts
            const checkPendingRequisitions = async () => {
                try {
                    if (isRequisitionPage) {
                        hideBadge();
                        markRequisitionsAsViewed();
                        return;
                    }

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

            const hideBadge = () => {
                const badge = document.getElementById('ris-notification-badge');
                if (badge) badge.classList.add('hidden');
            };

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
                } catch (error) {
                    console.error('Error marking requisitions as viewed:', error);
                }
            };

            if (!isRequisitionPage) {
                checkPendingRequisitions();
                setInterval(checkPendingRequisitions, 30000);
            } else {
                hideBadge();
                markRequisitionsAsViewed();
            }

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

{{-- Loader script
<script>
    function showLoader() {
        const loader = document.getElementById('loader-container');
        if (loader) loader.classList.add('show');
    }

    function hideLoader() {
        const loader = document.getElementById('loader-container');
        if (loader) loader.classList.remove('show');
    }

    document.addEventListener('DOMContentLoaded', function() {
        showLoader();
        setTimeout(hideLoader, 2000);
    });

    document.addEventListener('livewire:navigating', showLoader);
    document.addEventListener('livewire:navigated', () => setTimeout(hideLoader, 800));
    document.addEventListener('livewire:request-finished', () => setTimeout(hideLoader, 500));

    document.addEventListener('submit', function(e) {
        if (!e.target.hasAttribute('data-no-loader')) {
            showLoader();
        }
    });
</script> --}}

</html>
