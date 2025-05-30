<x-guest-layout>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .register-container {
            position: relative;
            min-height: 100vh;
            width: 100%;
            background: linear-gradient(to bottom, rgba(173,216,230, 0.7), rgba(0,0,139, 0.8)),
                        url('{{ asset("img/bg-login.jpg") }}') no-repeat center center;
            background-size: cover;
            padding: 1rem;
        }
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
        }
        .content-container {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            width: 100%;
            padding: 1rem 0;
        }
        .authentication-card {
            background-color: rgba(255, 255, 255, 0.92) !important;
            box-shadow: 0 10px 35px rgba(31, 38, 135, 0.45);
            border-radius: 1.25rem;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 1.5rem;
            width: 100%;
            max-width: 700px;
            margin: 1rem auto;
        }
        @media (min-width: 640px) {
            .authentication-card {
                padding: 2rem;
            }
        }
        @media (min-width: 768px) {
            .authentication-card {
                padding: 2.5rem;
            }
        }
        .custom-input {
            border: 1px solid #e2e8f0 !important;
            background-color: #f8fafc !important;
            border-radius: 0.5rem !important;
            padding: 0.625rem 1rem !important;
            transition: all 0.3s ease;
            height: 3rem !important;
            font-size: 0.95rem;
            width: 100%;
        }
        .custom-input:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25) !important;
            transform: translateY(-1px);
        }
        .custom-select {
            border: 1px solid #e2e8f0 !important;
            background-color: #f8fafc !important;
            border-radius: 0.5rem !important;
            padding: 0.625rem 1rem !important;
            transition: all 0.3s ease;
            height: 3rem !important;
            font-size: 0.95rem;
            width: 100%;
        }
        .custom-select:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25) !important;
            transform: translateY(-1px);
        }
        .register-button {
            background: linear-gradient(45deg, #3b82f6, #2563eb) !important;
            transition: all 0.3s ease;
            border-radius: 0.5rem !important;
            height: 3rem !important;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-button:hover {
            background: linear-gradient(45deg, #2563eb, #1e40af) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        }
        .register-button:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
        }
        .title-text {
            color: #1e3a8a;
            text-align: center;
            margin-bottom: 1.25rem;
            font-size: 1.5rem;
            font-weight: bold;
        }
        @media (min-width: 640px) {
            .title-text {
                font-size: 1.75rem;
                margin-bottom: 1.75rem;
            }
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }
        .logo-container {
            max-width: 120px;
            margin: 0 auto;
        }
        @media (max-width: 639px) {
            .logo-container {
                max-width: 100px;
            }
        }
    </style>

    <div class="register-container">
        <!-- Particles.js background -->
        <div id="particles-js"></div>

        <div class="content-container">
            <div class="authentication-card">
                <div class="flex justify-center logo-container">
                    <x-authentication-card-logo />
                </div>

                <h1 class="title-text mt-3">Create Account</h1>

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Form Fields - Responsive Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <!-- Name -->
                        <div class="form-group">
                            <x-label for="name" value="{{ __('Name') }}" class="form-label" />
                            <x-input id="name" class="block w-full custom-input"
                                     type="text"
                                     name="name"
                                     :value="old('name')"
                                     required autofocus autocomplete="name"
                                     placeholder="Enter your full name" />
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <x-label for="email" value="{{ __('Email') }}" class="form-label" />
                            <x-input id="email" class="block w-full custom-input"
                                     type="email"
                                     name="email"
                                     :value="old('email')"
                                     required autocomplete="username"
                                     placeholder="your.email@example.com" />
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <x-label for="password" value="{{ __('Password') }}" class="form-label" />
                            <x-input id="password" class="block w-full custom-input"
                                     type="password"
                                     name="password"
                                     required autocomplete="new-password"
                                     placeholder="••••••••" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="form-label" />
                            <x-input id="password_confirmation" class="block w-full custom-input"
                                     type="password"
                                     name="password_confirmation"
                                     required autocomplete="new-password"
                                     placeholder="••••••••" />
                        </div>

                        <!-- Department Dropdown -->
                        <div class="form-group">
                            <x-label for="department_id" value="{{ __('Department') }}" class="form-label" />
                            <select id="department_id" name="department_id" required
                                    class="block w-full custom-select">
                                <option value="">{{ __('Select Department') }}</option>
                                @foreach(\App\Models\Department::all() as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Designation Dropdown -->
                        <div class="form-group">
                            <x-label for="designation_id" value="{{ __('Designation') }}" class="form-label" />
                            <select id="designation_id" name="designation_id" required
                                    class="block w-full custom-select">
                                <option value="">{{ __('Select Designation') }}</option>
                                @foreach(\App\Models\Designation::all() as $designation)
                                    <option value="{{ $designation->id }}" {{ old('designation_id') == $designation->id ? 'selected' : '' }}>
                                        {{ $designation->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('designation_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="mt-4">
                            <x-label for="terms">
                                <div class="flex items-center">
                                    <x-checkbox name="terms" id="terms" required />
                                    <div class="ml-2 text-sm text-gray-700">
                                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-blue-600 hover:text-blue-800">'.__('Terms of Service').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-blue-600 hover:text-blue-800">'.__('Privacy Policy').'</a>',
                                        ]) !!}
                                    </div>
                                </div>
                            </x-label>
                        </div>
                    @endif

                    <!-- Register Button -->
                    <div class="mt-6">
                        <x-button class="w-full register-button">
                            <span class="flex items-center justify-center w-full">{{ __('Register') }}</span>
                        </x-button>
                    </div>

                    <!-- Already Registered Prompt -->
                    <div class="mt-4 text-center">
                        <span class="text-sm text-gray-600">
                            {{ __('Already registered?') }}
                        </span>
                        <a class="text-sm text-blue-600 hover:text-blue-800 hover:underline ml-1 font-medium"
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
            // Adjust particle density based on screen size
            const particleCount = window.innerWidth < 768 ? 40 : 80;

            particlesJS('particles-js', {
                "particles": {
                    "number": {
                        "value": particleCount,
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
                        "speed": window.innerWidth < 768 ? 1 : 2,
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

            // Adjust particle config on window resize
            window.addEventListener('resize', function() {
                if (typeof pJSDom !== 'undefined' && pJSDom.length > 0) {
                    pJSDom[0].pJS.particles.number.value = window.innerWidth < 768 ? 40 : 80;
                    pJSDom[0].pJS.particles.move.speed = window.innerWidth < 768 ? 1 : 2;
                    pJSDom[0].pJS.fn.particlesRefresh();
                }
            });
        });
    </script>
</x-guest-layout>
