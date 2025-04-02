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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.16.0/dist/sweetalert2.all.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">

    <x-loader />

    <x-banner />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- This is the Jetstream navigation with profile and logout -->
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

    <!-- Flowbite JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.0.0/flowbite.min.js"></script>
</body>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Success Message Script -->
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

<!-- Dark/Light Mode Toggle Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.getElementById('theme-toggle');
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
            themeToggleDarkIcon.classList.remove('hidden');
        } else {
            document.documentElement.classList.remove('dark');
            themeToggleLightIcon.classList.remove('hidden');
        }

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

<!-- Loader Script -->
<script>
    function showLoader() {
        const loader = document.getElementById('loader-container');
        if (loader) {
            loader.classList.add('show');
        }
    }

    function hideLoader() {
        const loader = document.getElementById('loader-container');
        if (loader) {
            loader.classList.remove('show');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        showLoader();
        setTimeout(function() {
            hideLoader();
        }, 2000);
    });

    document.addEventListener('livewire:navigating', function() {
        showLoader();
    });

    document.addEventListener('livewire:navigated', function() {
        setTimeout(function() {
            hideLoader();
        }, 800);
    });

    document.addEventListener('submit', function(e) {
        if (!e.target.hasAttribute('data-no-loader')) {
            showLoader();
        }
    });
</script>

</html>
