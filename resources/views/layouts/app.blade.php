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

    <!-- Loading Screen (Hidden by Default) -->
        <!-- Loader HTML -->
        {{-- <div id="loading-screen"
        class="loader-container fixed inset-0 bg-gray-700 bg-opacity-50 flex justify-center items-center z-50 hidden">
        <!-- Loader 1 -->
        <div class="loader">
            <svg viewBox="0 0 80 80">
                <circle r="32" cy="40" cx="40" id="test"></circle>
            </svg>
        </div>

        <!-- Loader 2: Triangle -->
        <div class="loader triangle">
            <svg viewBox="0 0 86 80">
                <polygon points="43 8 79 72 7 72"></polygon>
            </svg>
        </div>

        <!-- Loader 3: Rectangle -->
        <div class="loader">
            <svg viewBox="0 0 80 80">
                <rect height="64" width="64" y="8" x="8"></rect>
            </svg>
        </div>
    </div> --}}

    <!-- Loader Container -->
    <div id="loading-screen" class="fixed inset-0 bg-gray-700 bg-opacity-50 flex justify-center items-center z-50 hidden">
        <!-- Sharingan Loader -->
        <div class="sharingon">
            <div class="ring">
                <div class="to"></div>
                <div class="to"></div>
                <div class="to"></div>
                <div class="circle"></div>
            </div>
        </div>
    </div>

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
</body>

{{-- for loader --}}

<script>
    // Show the loader
    function showLoader() {
        document.getElementById('loading-screen').classList.remove('hidden');
    }

    // Hide the loader
    function hideLoader() {
        document.getElementById('loading-screen').classList.add('hidden');
    }

    // Show loader when page loads and hide after 3 seconds
    window.onload = function() {
        showLoader();
        setTimeout(hideLoader, 3000); // Adjust if needed
    };
</script>


{{-- <script>
    // Show the loader
    function showLoader() {
        document.getElementById('loading-screen').classList.remove('hidden');
    }

    // Hide the loader
    function hideLoader() {
        document.getElementById('loading-screen').classList.add('hidden');
    }

    // Example: Show loader when the page is loading and hide it after 3 seconds
    window.onload = function() {
        showLoader(); // Show the loader when the page starts loading
        setTimeout(function() {
            hideLoader(); // Hide the loader after 3 seconds
        }, 3000); // You can adjust this duration as per your need
    };
</script> --}}

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




</html>
