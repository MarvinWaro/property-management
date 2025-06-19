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

    <!-- Pusher JS -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

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
            window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            window.axios.defaults.withCredentials = true;
        });
    </script>

    <style>
        /* Tooltip styles */
        .badge-tooltip {
            position: relative;
        }

        .badge-tooltip:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #1f2937;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 10;
        }

        .badge-tooltip:hover::before {
            content: "";
            position: absolute;
            bottom: 115%;
            left: 50%;
            transform: translateX(-50%);
            border-width: 5px;
            border-style: solid;
            border-color: #1f2937 transparent transparent transparent;
            z-index: 10;
        }
    </style>

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

    <!-- Pusher Configuration and Notification Scripts -->
    @auth
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Pusher
            const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
                cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                },
                forceTLS: true,                     // always use wss://
                disableStats: true,                 // disable pusher's stats requests
                enabledTransports: ['ws', 'wss']    // <--- ONLY pure WebSocket, disables SockJS/fallbacks
            });
            // Initialize notification sound
            const notificationSound = new Audio('/sounds/ding.mp3');
            notificationSound.volume = 0.7;

            // Play notification sound
            const playNotificationSound = () => {
                const sound = notificationSound.cloneNode();
                sound.volume = notificationSound.volume;
                sound.play().catch(e => {
                    console.log('Could not play notification sound:', e);
                });
            };

            // Show browser notification
            const showBrowserNotification = (title, body) => {
                if (!("Notification" in window)) {
                    return;
                }

                if (Notification.permission === "granted") {
                    new Notification(title, {
                        body: body,
                        icon: '/favicon.ico',
                        tag: 'ris-notification',
                        renotify: true
                    });
                } else if (Notification.permission !== "denied") {
                    Notification.requestPermission().then(permission => {
                        if (permission === "granted") {
                            new Notification(title, {
                                body: body,
                                icon: '/favicon.ico',
                                tag: 'ris-notification',
                                renotify: true
                            });
                        }
                    });
                }
            };

            // Show toast notification with SweetAlert2
            const showToastNotification = (title, text, icon = 'info') => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: icon,
                    title: title,
                    text: text
                });
            };

            // Load saved notification preferences
            const loadNotificationPreferences = () => {
                const savedVolume = localStorage.getItem('notificationVolume');
                const soundEnabled = localStorage.getItem('notificationSoundEnabled');

                if (savedVolume !== null) {
                    notificationSound.volume = parseFloat(savedVolume);
                }

                if (soundEnabled === 'false') {
                    notificationSound.volume = 0;
                }
            };

            // Initialize preferences
            loadNotificationPreferences();

            // Update badges function
            const updateBadges = (counts, role) => {
                const requisitionNavLink = document.getElementById('requisition-nav-link');
                if (!requisitionNavLink) return;

                let badgeContainer = document.getElementById('ris-badges-container');
                if (!badgeContainer) {
                    badgeContainer = document.createElement('div');
                    badgeContainer.id = 'ris-badges-container';
                    badgeContainer.className = 'absolute -top-1 -right-1 flex space-x-1';
                    requisitionNavLink.appendChild(badgeContainer);
                }

                if (role === 'cao') {
                    // CAO sees only draft (pending approval)
                    updateBadge('ris-draft-badge', counts.draft_count, '#f59e0b', badgeContainer);
                    // Remove approved badge for CAO
                    const approvedBadge = document.getElementById('ris-approved-badge');
                    if (approvedBadge) approvedBadge.remove();
                } else if (role === 'admin') {
                    // Admin sees only approved (pending issuance)
                    updateBadge('ris-approved-badge', counts.approved_count, '#6366f1', badgeContainer);
                    // Remove draft badge for Admin
                    const draftBadge = document.getElementById('ris-draft-badge');
                    if (draftBadge) draftBadge.remove();
                }
            };

            const updateBadge = (badgeId, count, bgColor, container) => {
                let badge = document.getElementById(badgeId);

                if (count > 0) {
                    if (!badge) {
                        badge = document.createElement('span');
                        badge.id = badgeId;
                        badge.className = `inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white border-2 border-white rounded-full`;
                        badge.style.backgroundColor = bgColor;
                        container.appendChild(badge);
                    }
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.style.display = 'inline-flex';
                    badge.classList.add('animate-pulse');
                    setTimeout(() => badge.classList.remove('animate-pulse'), 3000);
                } else if (badge) {
                    badge.remove();
                }
            };

            @if(auth()->check() && auth()->user()->role === 'cao')
            // CAO notification handling
            const caoChannel = pusher.subscribe('private-cao-notifications');

            caoChannel.bind('App\\Events\\RequisitionStatusUpdated', function(data) {
                console.log('CAO notification received:', data);

                // Update badges
                updateBadges(data.counts, 'cao');

                // Always play sound for CAO notifications
                playNotificationSound();

                // Show notifications for new requisitions
                if (data.action === 'created') {
                    showToastNotification(
                        'New Requisition Request',
                        `${data.requester_name} submitted requisition ${data.ris_no}`,
                        'info'
                    );
                    showBrowserNotification(
                        'New Requisition Request',
                        data.message
                    );
                }
            });

            @elseif(auth()->check() && auth()->user()->role === 'admin')
            // Admin notification handling
            const adminChannel = pusher.subscribe('private-admin-notifications');

            adminChannel.bind('App\\Events\\RequisitionStatusUpdated', function(data) {
                console.log('Admin notification received:', data);

                // Update badges
                updateBadges(data.counts, 'admin');

                // Always play sound for admin notifications
                playNotificationSound();

                // Show notifications for approved requisitions needing issuance
                if (data.action === 'approved') {
                    showToastNotification(
                        'Requisition Approved',
                        `Requisition ${data.ris_no} is ready for issuance`,
                        'success'
                    );
                    showBrowserNotification(
                        'Requisition Ready for Issuance',
                        data.message
                    );
                } else if (data.action === 'completed') {
                    showToastNotification(
                        'Supplies Received',
                        `Supplies for ${data.ris_no} have been received`,
                        'success'
                    );
                }
            });

            @elseif(auth()->check())
            // Regular user notification handling
            const userChannel = pusher.subscribe('private-user.{{ auth()->id() }}');

            userChannel.bind('App\\Events\\RequisitionStatusUpdated', function(data) {
                console.log('User notification received:', data);

                // Always play sound for user notifications
                playNotificationSound();

                switch(data.action) {
                    case 'approved':
                        showToastNotification(
                            'Request Approved!',
                            `Your requisition ${data.ris_no} has been approved`,
                            'success'
                        );
                        showBrowserNotification(
                            'Request Approved!',
                            data.message
                        );
                        break;
                    case 'declined':
                        showToastNotification(
                            'Request Declined',
                            `Your requisition ${data.ris_no} was declined`,
                            'error'
                        );
                        showBrowserNotification(
                            'Request Declined',
                            data.message
                        );
                        break;
                    case 'issued':
                        showToastNotification(
                            'Supplies Ready!',
                            `Your supplies for ${data.ris_no} are ready for pickup`,
                            'success'
                        );
                        showBrowserNotification(
                            'Supplies Ready for Pickup!',
                            data.message
                        );
                        break;
                }
            });

            userChannel.bind('App\\Events\\UserNotificationUpdated', function(data) {
                console.log('User notification update received:', data);

                // Update user badges
                updateUserBadges(data.counts);

                // Play sound
                playNotificationSound();

                // Show appropriate notification
                switch(data.notification.type) {
                    case 'requisition_approved':
                        showToastNotification(
                            'Request Approved!',
                            `Your requisition ${data.notification.data.ris_no} has been approved`,
                            'success'
                        );
                        break;
                    case 'supplies_ready':
                        showToastNotification(
                            'Supplies Ready!',
                            `Supplies for ${data.notification.data.ris_no} are ready for pickup`,
                            'info'
                        );
                        break;
                    case 'requisition_declined':
                        showToastNotification(
                            'Request Declined',
                            `Your requisition was declined: ${data.notification.data.reason}`,
                            'error'
                        );
                        break;
                }
            });

            // Update user badges
            const updateUserBadges = (counts) => {
                updateUserBadge('user-approved-badge', counts.approved_requests_count, '#6366f1');
                updateUserBadge('user-pending-badge', counts.pending_receipt_count, '#f59e0b', true);
            };

            const updateUserBadge = (badgeId, count, bgColor, shouldPulse = false) => {
                let badge = document.getElementById(badgeId);
                const parentLink = badge ? badge.closest('a') : document.querySelector(`a[data-target="${badgeId.includes('approved') ? 'requests' : 'received-supplies'}"]`);

                if (count > 0) {
                    if (!badge && parentLink) {
                        badge = document.createElement('span');
                        badge.id = badgeId;
                        badge.className = `inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white rounded-full${shouldPulse ? ' animate-pulse' : ''}`;
                        badge.style.backgroundColor = bgColor;
                        parentLink.appendChild(badge);
                    }

                    if (badge) {
                        badge.textContent = count > 99 ? '99+' : count;
                        badge.style.display = 'inline-flex';

                        if (shouldPulse) {
                            badge.classList.add('animate-pulse');
                        }
                    }
                } else if (badge) {
                    badge.style.display = 'none';
                }
            };
            @endif

            // Expose sound control functions
            window.setNotificationVolume = function(volume) {
                notificationSound.volume = volume;
                localStorage.setItem('notificationVolume', volume);
            };

            window.toggleNotificationSound = function(enabled) {
                localStorage.setItem('notificationSoundEnabled', enabled);
                if (!enabled) {
                    notificationSound.volume = 0;
                } else {
                    loadNotificationPreferences();
                }
            };

            // Test notification sound
            window.testNotificationSound = function() {
                playNotificationSound();
            };
        });
    </script>
    @endauth

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
