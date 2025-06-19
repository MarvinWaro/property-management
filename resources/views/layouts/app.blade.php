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

        /* Toast container styles */
        #toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 50;
            max-width: 384px;
            width: 100%;
        }

        .toast-slide-in {
            animation: slideInRight 0.3s ease-out;
        }

        .toast-slide-out {
            animation: slideOutRight 0.3s ease-in;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    </style>

</head>

<body class="font-sans antialiased">

    {{-- <x-loader /> --}}

    <x-banner />

    <!-- Toast Container -->
    <div id="toast-container"></div>

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
                encrypted: true,
                authEndpoint: '/broadcasting/auth',
                enabledTransports: ['ws', 'wss'],
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            });

            // Initialize notification sound
            const notificationSound = new Audio('/sounds/ding.mp3');
            notificationSound.volume = 0.7;

            // Track if user has interacted with the page
            let userHasInteracted = false;
            let soundEnabled = localStorage.getItem('notificationSoundEnabled') !== 'false';

            // Listen for first user interaction
            const enableAudioOnInteraction = () => {
                userHasInteracted = true;
                // Try to preload the sound after first interaction
                if (soundEnabled) {
                    notificationSound.load();
                }
                // Remove listeners after first interaction
                document.removeEventListener('click', enableAudioOnInteraction);
                document.removeEventListener('keydown', enableAudioOnInteraction);
                document.removeEventListener('touchstart', enableAudioOnInteraction);
            };

            // Add interaction listeners
            document.addEventListener('click', enableAudioOnInteraction);
            document.addEventListener('keydown', enableAudioOnInteraction);
            document.addEventListener('touchstart', enableAudioOnInteraction);

            // Play notification sound
            const playNotificationSound = () => {
                if (!soundEnabled) return;

                if (!userHasInteracted) {
                    console.log('🔊 Notification sound blocked - user interaction required first');
                    return;
                }

                const sound = notificationSound.cloneNode();
                sound.volume = notificationSound.volume;
                sound.play().catch(e => {
                    if (e.name === 'NotAllowedError') {
                        console.log('🔊 Audio blocked by browser - user needs to interact with page first');
                        // Show a one-time toast about enabling sounds
                        showAudioPermissionToast();
                    } else {
                        console.log('Could not play notification sound:', e.message);
                    }
                });
            };

            // Show toast asking user to enable sounds
            const showAudioPermissionToast = (() => {
                let hasShownToast = false;
                return () => {
                    if (hasShownToast) return;
                    hasShownToast = true;

                    showToastNotification(
                        'Enable Sound Notifications',
                        'Click anywhere on the page to enable notification sounds',
                        'info',
                        'System',
                        null
                    );
                };
            })();

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

            // Show Flowbite toast notification
            const showToastNotification = (title, text, icon = 'info', userName = 'System', userImage = null) => {
                const toastContainer = document.getElementById('toast-container');
                const toastId = 'toast-' + Date.now();

                // Define icon and colors based on type
                let iconHtml = '';
                let iconBgColor = '';

                switch(icon) {
                    case 'success':
                        iconBgColor = 'bg-green-600';
                        iconHtml = `
                            <svg class="w-3 h-3 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                            </svg>`;
                        break;
                    case 'error':
                        iconBgColor = 'bg-red-600';
                        iconHtml = `
                            <svg class="w-3 h-3 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                            </svg>`;
                        break;
                    case 'warning':
                        iconBgColor = 'bg-orange-600';
                        iconHtml = `
                            <svg class="w-3 h-3 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
                            </svg>`;
                        break;
                    default: // info
                        iconBgColor = 'bg-blue-600';
                        iconHtml = `
                            <svg class="w-3 h-3 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 18" fill="currentColor">
                                <path d="M18 4H16V9C16 10.0609 15.5786 11.0783 14.8284 11.8284C14.0783 12.5786 13.0609 13 12 13H9L6.846 14.615C7.17993 14.8628 7.58418 14.9977 8 15H11.667L15.4 17.8C15.5731 17.9298 15.7836 18 16 18C16.2652 18 16.5196 17.8946 16.7071 17.7071C16.8946 17.5196 17 17.2652 17 17V15H18C18.5304 15 19.0391 14.7893 19.4142 14.4142C19.7893 14.0391 20 13.5304 20 13V6C20 5.46957 19.7893 4.96086 19.4142 4.58579C19.0391 4.21071 18.5304 4 18 4Z"/>
                                <path d="M12 0H2C1.46957 0 0.960859 0.210714 0.585786 0.585786C0.210714 0.960859 0 1.46957 0 2V9C0 9.53043 0.210714 10.0391 0.585786 10.4142C0.960859 10.7893 1.46957 11 2 11H3V13C3 13.1857 3.05171 13.3678 3.14935 13.5257C3.24698 13.6837 3.38668 13.8114 3.55279 13.8944C3.71889 13.9775 3.90484 14.0126 4.08981 13.996C4.27477 13.9793 4.45143 13.9114 4.6 13.8L8.333 11H12C12.5304 11 13.0391 10.7893 13.4142 10.4142C13.7893 10.0391 14 9.53043 14 9V2C14 1.46957 13.7893 0.960859 13.4142 0.585786C13.0391 0.210714 12.5304 0 12 0Z"/>
                            </svg>`;
                        break;
                }

                // Determine the image source with better fallback logic
                let imageSrc = '/favicon.ico'; // Default fallback
                if (userImage && userImage.trim() !== '' && userImage !== 'null' && userImage !== 'undefined') {
                    imageSrc = userImage;
                }

                const toastHtml = `
                    <div id="${toastId}" class="w-full max-w-xs p-4 mb-4 text-gray-900 bg-white rounded-lg shadow-lg dark:bg-gray-800 dark:text-gray-300 toast-slide-in" role="alert">
                        <div class="flex items-center mb-3">
                            <span class="mb-1 text-sm font-semibold text-gray-900 dark:text-white">${title}</span>
                            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white justify-center items-center shrink-0 text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" onclick="closeToast('${toastId}')" aria-label="Close">
                                <span class="sr-only">Close</span>
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center">
                            <div class="relative inline-block shrink-0">
                                <img class="w-12 h-12 rounded-full object-cover"
                                     src="${imageSrc}"
                                     alt="${userName} image"
                                     onload="this.style.opacity='1'"
                                     onerror="if(this.src!='/favicon.ico'){this.src='/favicon.ico';} else{this.style.display='none'; this.nextElementSibling.style.display='block';}"
                                     style="opacity: 0; transition: opacity 0.3s;">
                                <div class="w-12 h-12 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-gray-600 dark:text-gray-300 font-semibold" style="display: none;">
                                    ${userName.charAt(0).toUpperCase()}
                                </div>
                                <span class="absolute bottom-0 right-0 inline-flex items-center justify-center w-6 h-6 ${iconBgColor} rounded-full">
                                    ${iconHtml}
                                    <span class="sr-only">${icon} icon</span>
                                </span>
                            </div>
                            <div class="ms-3 text-sm font-normal">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">${userName}</div>
                                <div class="text-sm font-normal">${text}</div>
                                <span class="text-xs font-medium text-blue-600 dark:text-blue-500">just now</span>
                            </div>
                        </div>
                    </div>
                `;

                toastContainer.insertAdjacentHTML('beforeend', toastHtml);

                // Auto close after 5 seconds
                setTimeout(() => {
                    closeToast(toastId);
                }, 5000);
            };

            // Close toast function
            window.closeToast = function(toastId) {
                const toast = document.getElementById(toastId);
                if (toast) {
                    toast.classList.remove('toast-slide-in');
                    toast.classList.add('toast-slide-out');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }
            };

            // Load saved notification preferences
            const loadNotificationPreferences = () => {
                const savedVolume = localStorage.getItem('notificationVolume');
                const soundEnabledSetting = localStorage.getItem('notificationSoundEnabled');

                if (savedVolume !== null) {
                    notificationSound.volume = parseFloat(savedVolume);
                }

                if (soundEnabledSetting !== null) {
                    soundEnabled = soundEnabledSetting !== 'false';
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
                        'info',
                        data.requester_name || 'User',
                        data.requester_photo || null
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
                        'success',
                        'CAO',
                        data.cao_photo || null
                    );
                    showBrowserNotification(
                        'Requisition Ready for Issuance',
                        data.message
                    );
                } else if (data.action === 'completed') {
                    showToastNotification(
                        'Supplies Received',
                        `Supplies for ${data.ris_no} have been received`,
                        'success',
                        data.receiver_name || 'User',
                        data.receiver_photo || null
                    );
                }
            });

            @elseif(auth()->check())
            // Regular user notification handling
            const userChannel = pusher.subscribe('private-user.{{ auth()->id() }}');

            // Only listen to UserNotificationUpdated to avoid duplicate notifications
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
                            'success',
                            'CAO',
                            data.notification.data.cao_photo || null
                        );
                        showBrowserNotification(
                            'Request Approved!',
                            `Your requisition ${data.notification.data.ris_no} has been approved`
                        );
                        break;
                    case 'supplies_ready':
                        showToastNotification(
                            'Supplies Ready!',
                            `Supplies for ${data.notification.data.ris_no} are ready for pickup`,
                            'info',
                            'Admin',
                            data.notification.data.admin_photo || null
                        );
                        showBrowserNotification(
                            'Supplies Ready for Pickup!',
                            `Supplies for ${data.notification.data.ris_no} are ready for pickup`
                        );
                        break;
                    case 'requisition_declined':
                        showToastNotification(
                            'Request Declined',
                            `Your requisition was declined: ${data.notification.data.reason || 'No reason provided'}`,
                            'error',
                            'CAO',
                            data.notification.data.cao_photo || null
                        );
                        showBrowserNotification(
                            'Request Declined',
                            `Your requisition was declined: ${data.notification.data.reason || 'No reason provided'}`
                        );
                        break;
                    case 'supplies_issued':
                        showToastNotification(
                            'Supplies Issued!',
                            `Your supplies for ${data.notification.data.ris_no} have been issued`,
                            'success',
                            'Admin',
                            data.notification.data.admin_photo || null
                        );
                        showBrowserNotification(
                            'Supplies Issued!',
                            `Your supplies for ${data.notification.data.ris_no} have been issued`
                        );
                        break;
                    case 'supplies_completed':
                        showToastNotification(
                            'Supplies Received',
                            `Supplies for ${data.notification.data.ris_no} have been received`,
                            'success',
                            data.notification.data.receiver_name || 'User',
                            data.notification.data.receiver_photo || null
                        );
                        showBrowserNotification(
                            'Supplies Received',
                            `Supplies for ${data.notification.data.ris_no} have been received`
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
                soundEnabled = enabled;
                localStorage.setItem('notificationSoundEnabled', enabled);
                console.log(`🔊 Notification sounds ${enabled ? 'enabled' : 'disabled'}`);
            };

            // Test notification sound
            window.testNotificationSound = function() {
                if (!userHasInteracted) {
                    console.log('🔊 Please click somewhere on the page first, then try again');
                    return;
                }
                playNotificationSound();
            };

            // Check sound status
            window.getSoundStatus = function() {
                return {
                    soundEnabled: soundEnabled,
                    userHasInteracted: userHasInteracted,
                    volume: notificationSound.volume
                };
            };

            // Test toast notification function (for development)
            window.testToastNotification = function() {
                showToastNotification(
                    'Test Notification',
                    'This is a test notification to see how it looks!',
                    'success',
                    '{{ auth()->user()->name ?? "Test User" }}',
                    '{{ auth()->user()->profile_photo_url ?? null }}'
                );
            };

            // Test supplies received notification
            window.testSuppliesReceived = function() {
                showToastNotification(
                    'Supplies Received',
                    'Supplies for RIS 2025-06-024 have been received',
                    'success',
                    '{{ auth()->user()->name ?? "Test User" }}',
                    '{{ auth()->user()->profile_photo_url ?? null }}'
                );
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
