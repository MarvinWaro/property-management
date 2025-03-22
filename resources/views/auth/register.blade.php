<x-guest-layout>
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        .register-container {
            position: relative;
            height: 100vh; /* Full viewport height */
            /* Gradient: light blue at top, dark blue at bottom, over the background image */
            background: linear-gradient(to bottom, rgba(173,216,230, 0.7), rgba(0,0,139, 0.8)),
                        url('{{ asset("assets/img/ched-building.jpg") }}') no-repeat center center;
            background-size: cover;
        }
        /* Particles.js container */
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
        }
        /* Center content above the particles */
        .content-container {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        /* Enhanced authentication card (glassmorphism style) */
        .authentication-card {
            background-color: rgba(255, 255, 255, 0.9) !important;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            border-radius: 1rem;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            padding: 2rem;
        }
        /* Input styling with focus effect */
        .custom-input {
            border: 1px solid #e2e8f0 !important;
            background-color: #f8fafc !important;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .custom-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
        }
        /* Enhanced button styling with gradient and hover effect */
        .register-button {
            background: linear-gradient(45deg, #3b82f6, #2563eb) !important;
            transition: background 0.3s;
        }
        .register-button:hover {
            background: linear-gradient(45deg, #2563eb, #1e40af) !important;
        }
        /* Title text style */
        .title-text {
            color: #1e3a8a;
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>

    <div class="register-container">
        <!-- Particles.js background -->
        <div id="particles-js"></div>

        <div class="content-container">
            <div class="w-full sm:max-w-md authentication-card">
                <div class="flex justify-center">
                    <x-authentication-card-logo />
                </div>

                <h1 class="title-text mt-2">Create Account</h1>

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-label for="name" value="{{ __('Name') }}" />
                        <x-input id="name" class="block mt-1 w-full h-10 custom-input"
                                 type="text"
                                 name="name"
                                 :value="old('name')"
                                 required autofocus autocomplete="name" />
                    </div>

                    <!-- Email -->
                    <div class="mt-4">
                        <x-label for="email" value="{{ __('Email') }}" />
                        <x-input id="email" class="block mt-1 w-full h-10 custom-input"
                                 type="email"
                                 name="email"
                                 :value="old('email')"
                                 required autocomplete="username" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-label for="password" value="{{ __('Password') }}" />
                        <x-input id="password" class="block mt-1 w-full h-10 custom-input"
                                 type="password"
                                 name="password"
                                 required autocomplete="new-password" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                        <x-input id="password_confirmation" class="block mt-1 w-full h-10 custom-input"
                                 type="password"
                                 name="password_confirmation"
                                 required autocomplete="new-password" />
                    </div>

                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="mt-4">
                            <x-label for="terms">
                                <div class="flex items-center">
                                    <x-checkbox name="terms" id="terms" required />
                                    <div class="ml-2">
                                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                        ]) !!}
                                    </div>
                                </div>
                            </x-label>
                        </div>
                    @endif

                    <!-- Register Button -->
                    <div class="mt-6">
                        <x-button class="w-full h-10 register-button">
                            <span class="block text-center w-full">{{ __('Register') }}</span>
                        </x-button>
                    </div>

                    <!-- Already Registered Prompt -->
                    <div class="mt-4 text-center">
                        <span class="text-sm text-gray-600">
                            {{ __('Already registered?') }}
                        </span>
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 ml-1"
                           href="{{ route('login') }}">
                            {{ __('Sign in') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include particles.js via CDN -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            particlesJS('particles-js', {
                "particles": {
                    "number": {
                        "value": 80,
                        "density": {
                            "enable": true,
                            "value_area": 800
                        }
                    },
                    "color": {
                        "value": "#ffffff"
                    },
                    "shape": {
                        "type": "circle",
                        "stroke": {
                            "width": 0,
                            "color": "#000000"
                        }
                    },
                    "opacity": {
                        "value": 0.5,
                        "random": false
                    },
                    "size": {
                        "value": 3,
                        "random": true
                    },
                    "line_linked": {
                        "enable": true,
                        "distance": 150,
                        "color": "#ffffff",
                        "opacity": 0.4,
                        "width": 1
                    },
                    "move": {
                        "enable": true,
                        "speed": 2,
                        "direction": "none",
                        "random": false,
                        "straight": false,
                        "out_mode": "out",
                        "bounce": false
                    }
                },
                "interactivity": {
                    "detect_on": "canvas",
                    "events": {
                        "onhover": {
                            "enable": true,
                            "mode": "grab"
                        },
                        "onclick": {
                            "enable": true,
                            "mode": "push"
                        },
                        "resize": true
                    },
                    "modes": {
                        "grab": {
                            "distance": 140,
                            "line_linked": {
                                "opacity": 1
                            }
                        },
                        "push": {
                            "particles_nb": 4
                        }
                    }
                },
                "retina_detect": true
            });
        });
    </script>
</x-guest-layout>
