{{-- <div class="loader-container" id="loader-container">
    <div class="loader-backdrop"></div>
    <div class="loader">
        <svg height="0" width="0" viewBox="0 0 64 64" class="absolute">
            <defs>
                <linearGradient gradientUnits="userSpaceOnUse" y2="2" x2="0" y1="62" x1="0" id="b">
                    <stop stop-color="#973BED"></stop>
                    <stop stop-color="#007CFF" offset="1"></stop>
                </linearGradient>
                <linearGradient gradientUnits="userSpaceOnUse" y2="0" x2="0" y1="64" x1="0" id="c">
                    <stop stop-color="#FFC800"></stop>
                    <stop stop-color="#F0F" offset="1"></stop>
                    <animateTransform repeatCount="indefinite"
                        keySplines=".42,0,.58,1;.42,0,.58,1;.42,0,.58,1;.42,0,.58,1;.42,0,.58,1;.42,0,.58,1;.42,0,.58,1;.42,0,.58,1"
                        keyTimes="0; 0.125; 0.25; 0.375; 0.5; 0.625; 0.75; 0.875; 1" dur="8s"
                        values="0 32 32;-270 32 32;-270 32 32;-540 32 32;-540 32 32;-810 32 32;-810 32 32;-1080 32 32;-1080 32 32"
                        type="rotate" attributeName="gradientTransform"></animateTransform>
                </linearGradient>
                <linearGradient gradientUnits="userSpaceOnUse" y2="2" x2="0" y1="62" x1="0" id="d">
                    <stop stop-color="#00E0ED"></stop>
                    <stop stop-color="#00DA72" offset="1"></stop>
                </linearGradient>
            </defs>
        </svg>

        <!-- I Letter -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 64 64" height="64" width="64"
            class="inline-block">
            <path stroke-linejoin="round" stroke-linecap="round" stroke-width="8" stroke="url(#b)" d="M 32,4 v 56"
                class="dash" id="i" pathLength="360"></path>
        </svg>

        <!-- S Letter -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
            style="--rotation-duration:0ms; --rotation-direction:normal;" viewBox="0 0 64 64" height="64"
            width="64" class="inline-block">
            <path stroke-linejoin="round" stroke-linecap="round" stroke-width="8" stroke="url(#c)"
                d="M 52,16 C 52,8 44,4 32,4 20,4 12,8 12,16 c 0,8 8,12 20,12 12,0 20,4 20,12 0,8 -8,12 -20,12 -12,0 -20,-4 -20,-12"
                class="dash" id="s1" pathLength="360"></path>
        </svg>

        <!-- M Letter -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
            style="--rotation-duration:0ms; --rotation-direction:normal;" viewBox="0 0 64 64" height="64"
            width="64" class="inline-block">
            <path stroke-linejoin="round" stroke-linecap="round" stroke-width="8" stroke="url(#d)"
                d="M 8,60 V 4 L 32,40 56,4 v 56" class="dash" id="m" pathLength="360"></path>
        </svg>

        <!-- S Letter (Again) -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
            style="--rotation-duration:0ms; --rotation-direction:normal;" viewBox="0 0 64 64" height="64"
            width="64" class="inline-block">
            <path stroke-linejoin="round" stroke-linecap="round" stroke-width="8" stroke="url(#b)"
                d="M 52,16 C 52,8 44,4 32,4 20,4 12,8 12,16 c 0,8 8,12 20,12 12,0 20,4 20,12 0,8 -8,12 -20,12 -12,0 -20,-4 -20,-12"
                class="dash" id="s2" pathLength="360"></path>
        </svg>
    </div>
</div>

<style>
    .absolute {
        position: absolute;
    }

    .inline-block {
        display: inline-block;
    }

    .loader-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s, visibility 0.3s;
    }

    .loader-container.show {
        opacity: 1;
        visibility: visible;
    }

    .loader-backdrop {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(8px);
    }

    .dark .loader-backdrop {
        background-color: rgba(17, 24, 39, 0.8);
    }

    .loader {
        display: flex;
        margin: 0.25em 0;
        position: relative;
        z-index: 1;
    }

    .w-2 {
        width: 0.5em;
    }

    .dash {
        animation: dashArray 2s ease-in-out infinite,
            dashOffset 2s linear infinite;
    }

    .spin {
        animation: spinDashArray 2s ease-in-out infinite,
            spin 8s ease-in-out infinite,
            dashOffset 2s linear infinite;
        transform-origin: center;
    }

    @keyframes dashArray {
        0% {
            stroke-dasharray: 0 1 359 0;
        }

        50% {
            stroke-dasharray: 0 359 1 0;
        }

        100% {
            stroke-dasharray: 359 1 0 0;
        }
    }

    @keyframes spinDashArray {
        0% {
            stroke-dasharray: 270 90;
        }

        50% {
            stroke-dasharray: 0 360;
        }

        100% {
            stroke-dasharray: 270 90;
        }
    }

    @keyframes dashOffset {
        0% {
            stroke-dashoffset: 365;
        }

        100% {
            stroke-dashoffset: 5;
        }
    }

    @keyframes spin {
        0% {
            rotate: 0deg;
        }

        12.5%,
        25% {
            rotate: 270deg;
        }

        37.5%,
        50% {
            rotate: 540deg;
        }

        62.5%,
        75% {
            rotate: 810deg;
        }

        87.5%,
        100% {
            rotate: 1080deg;
        }
    }
</style> --}}
