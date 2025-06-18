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

    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'cao']))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const requisitionNavLink = document.getElementById('requisition-nav-link');
            if (!requisitionNavLink) return;

            // Initialize notification sound
            const notificationSound = new Audio('/sounds/ding.mp3'); // Update with your file extension
            notificationSound.volume = 1.0; // Adjust volume (0.0 to 1.0)

            // Store previous counts to detect new requests
            let previousDraftCount = 0;
            let previousApprovedCount = 0;
            let isFirstCheck = true;

            // Check for pending requisitions and update badges
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

                        // Check if there are new requests
                        const hasNewDrafts = data.draft_count > previousDraftCount;
                        const hasNewApproved = data.approved_count > previousApprovedCount;

                        // Play sound if there are new requests (but not on first load)
                        if (!isFirstCheck && (hasNewDrafts || hasNewApproved)) {
                            playNotificationSound();

                            // Optional: Show browser notification
                            if (hasNewDrafts) {
                                showBrowserNotification(
                                    'New Requisition Request',
                                    `You have ${data.draft_count - previousDraftCount} new requisition(s) pending approval`
                                );
                            }
                            if (hasNewApproved) {
                                showBrowserNotification(
                                    'Requisition Approved',
                                    `${data.approved_count - previousApprovedCount} requisition(s) approved and awaiting issue`
                                );
                            }
                        }

                        // Update the badges
                        updateBadges(data);

                        // Store current counts for next comparison
                        previousDraftCount = data.draft_count;
                        previousApprovedCount = data.approved_count;
                        isFirstCheck = false;
                    } else {
                        console.error('Error response:', response.status, response.statusText);
                    }
                } catch (error) {
                    console.error('Error checking for pending requisitions:', error);
                }
            };

            // Play notification sound
            const playNotificationSound = () => {
                // Clone the audio to allow multiple plays
                const sound = notificationSound.cloneNode();
                sound.volume = notificationSound.volume;
                sound.play().catch(e => {
                    console.log('Could not play notification sound:', e);
                });
            };

            // Show browser notification (optional)
            const showBrowserNotification = (title, body) => {
                // Check if browser supports notifications
                if (!("Notification" in window)) {
                    return;
                }

                // Check if permission is granted
                if (Notification.permission === "granted") {
                    new Notification(title, {
                        body: body,
                        icon: '/favicon.ico', // Update with your app icon
                        tag: 'ris-notification', // Prevents duplicate notifications
                        renotify: true
                    });
                }
                // Request permission if not denied
                else if (Notification.permission !== "denied") {
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

            // Update badges visibility based on counts
            const updateBadges = (data) => {
                // Get or create badge container
                let badgeContainer = document.getElementById('ris-badges-container');
                if (!badgeContainer) {
                    badgeContainer = document.createElement('div');
                    badgeContainer.id = 'ris-badges-container';
                    badgeContainer.className = 'absolute -top-1 -right-1 flex space-x-1';
                    requisitionNavLink.appendChild(badgeContainer);
                }

                // Update draft badge (orange)
                updateBadge('ris-draft-badge', data.draft_count, '#f59e0b', badgeContainer);

                // Update approved badge (blue/indigo)
                updateBadge('ris-approved-badge', data.approved_count, '#6366f1', badgeContainer);
            };

            // Helper function to update individual badge
            const updateBadge = (badgeId, count, bgColor, container) => {
                let badge = document.getElementById(badgeId);

                if (count > 0) {
                    if (!badge) {
                        // Create badge if it doesn't exist
                        badge = document.createElement('span');
                        badge.id = badgeId;
                        badge.className = `inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white border-2 border-white rounded-full`;
                        badge.style.backgroundColor = bgColor;
                        container.appendChild(badge);
                    }
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.style.display = 'inline-flex';

                    // Add subtle animation for new items
                    if (badgeId === 'ris-draft-badge' && count > previousDraftCount) {
                        badge.classList.add('animate-pulse');
                        setTimeout(() => badge.classList.remove('animate-pulse'), 3000);
                    }
                } else if (badge) {
                    badge.remove();
                }
            };

            // Optional: Mark requisitions as viewed
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
                    // After marking as viewed, still check the actual counts
                    checkPendingRequisitions();
                } catch (error) {
                    console.error('Error marking requisitions as viewed:', error);
                }
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

            // Initial check
            checkPendingRequisitions();

            // Poll every 5 seconds
            setInterval(checkPendingRequisitions, 20000);

            // Update when page becomes visible again
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    checkPendingRequisitions();
                }
            });

            // Optional: Add a manual "Mark as Read" functionality
            window.markAllRequisitionsAsRead = function() {
                markRequisitionsAsViewed();
            };

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
    @endif

    <!-- Add this JavaScript to your user dashboard or main layout for non-admin users -->
    @if(auth()->check() && !in_array(auth()->user()->role, ['admin', 'cao']))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize notification sound
            const notificationSound = new Audio('/sounds/ding.mp3');
            notificationSound.volume = 0.5;

            // Store previous counts to detect changes
            let previousApprovedCount = 0;
            let previousPendingReceiptCount = 0;
            let isFirstCheck = true;

            // Check for user notifications
            const checkUserNotifications = async () => {
                try {
                    const response = await fetch('/user-notifications', {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        credentials: 'include'
                    });

                    if (response.ok) {
                        const data = await response.json();

                        // Check for new notifications
                        const hasNewApproved = data.approved_requests_count > previousApprovedCount;
                        const hasNewPending = data.pending_receipt_count > previousPendingReceiptCount;

                        // Play sound if there are new notifications (but not on first load)
                        if (!isFirstCheck && (hasNewApproved || hasNewPending)) {
                            playNotificationSound();

                            // Show browser notification
                            if (hasNewApproved) {
                                showBrowserNotification(
                                    'Request Approved!',
                                    `Your requisition has been approved and is ready for issuance.`
                                );
                            }
                            if (hasNewPending) {
                                showBrowserNotification(
                                    'Supplies Ready for Pickup!',
                                    `You have supplies waiting to be received.`
                                );
                            }
                        }

                        // Update badges
                        updateUserBadges(data);

                        // Store current counts
                        previousApprovedCount = data.approved_requests_count;
                        previousPendingReceiptCount = data.pending_receipt_count;
                        isFirstCheck = false;
                    }
                } catch (error) {
                    console.error('Error checking user notifications:', error);
                }
            };

            // Update user badges
            const updateUserBadges = (data) => {
                // Update approved requests badge
                updateBadge('user-approved-badge', data.approved_requests_count, '#6366f1');

                // Update pending receipt badge (with pulse animation)
                updateBadge('user-pending-badge', data.pending_receipt_count, '#f59e0b', true);
            };

            // Helper function to update individual badge
            const updateBadge = (badgeId, count, bgColor, shouldPulse = false) => {
                let badge = document.getElementById(badgeId);
                const parentLink = badge ? badge.closest('a') : document.querySelector(`a[data-target="${badgeId.includes('approved') ? 'requests' : 'received-supplies'}"]`);

                if (count > 0) {
                    if (!badge && parentLink) {
                        // Create badge if it doesn't exist
                        badge = document.createElement('span');
                        badge.id = badgeId;
                        badge.className = `inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white rounded-full${shouldPulse ? ' animate-pulse' : ''}`;
                        badge.style.backgroundColor = bgColor;
                        parentLink.appendChild(badge);
                    }

                    if (badge) {
                        badge.textContent = count > 99 ? '99+' : count;
                        badge.style.display = 'inline-flex';

                        // Add pulse animation for urgent items
                        if (shouldPulse && count > previousPendingReceiptCount) {
                            badge.classList.add('animate-pulse');
                        }
                    }
                } else if (badge) {
                    badge.style.display = 'none';
                }
            };

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
                        tag: 'user-notification',
                        renotify: true
                    });
                } else if (Notification.permission !== "denied") {
                    Notification.requestPermission().then(permission => {
                        if (permission === "granted") {
                            new Notification(title, {
                                body: body,
                                icon: '/favicon.ico',
                                tag: 'user-notification',
                                renotify: true
                            });
                        }
                    });
                }
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

            // Initialize
            loadNotificationPreferences();
            checkUserNotifications();

            // Check every 30 seconds
            setInterval(checkUserNotifications, 20000);

            // Update when page becomes visible
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    checkUserNotifications();
                }
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

</html>
