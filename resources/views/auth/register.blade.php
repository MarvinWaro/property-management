<x-guest-layout>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Inter', sans-serif;
        }

        .register-container {
            display: flex;
            min-height: 100vh;
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        }

        /* Left Panel - Branding */
        .branding-panel {
            flex: 1;
            background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 50%, #b91c1c 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .branding-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('{{ asset("img/bg-login.jpg") }}') center/cover;
            opacity: 0.1;
            z-index: 1;
        }

        /* Particles.js container */
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 2;
        }

        .branding-content {
            position: relative;
            z-index: 3;
            text-align: center;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 60vh;
        }

        .logo-container {
            margin-bottom: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo-container img,
        .logo-container svg {
            width: 100px;
            height: 100px;
            /* Use filter if logo appears too dark on red background */
            /* filter: brightness(0) invert(1); */
        }

        .system-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
            text-align: center;
        }

        .system-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            max-width: 400px;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
        }

        .features-list {
            text-align: left;
            max-width: 350px;
            margin: 0 auto;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            opacity: 0.9;
        }

        .feature-icon {
            width: 20px;
            height: 20px;
            margin-right: 0.75rem;
            color: #34d399;
        }

        .powered-by {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
            z-index: 3;
        }

        /* Right Panel - Register Form */
        .register-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: #ffffff !important; /* Force white background */
            overflow-y: auto;
        }

        .register-form-container {
            width: 100%;
            max-width: 500px;
        }

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .welcome-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1f2937 !important; /* Force dark text */
            margin-bottom: 0.5rem;
        }

        .welcome-subtitle {
            color: #6b7280 !important; /* Force gray text */
            font-size: 1rem;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151 !important; /* Force dark text */
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 1rem;
            background-color: #f9fafb !important; /* Force light background */
            color: #1f2937 !important; /* Force dark text */
            transition: all 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #b91c1c;
            box-shadow: 0 0 0 3px rgba(185, 28, 28, 0.1);
            background-color: #ffffff !important; /* Force white background on focus */
        }

        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 1rem;
            background-color: #f9fafb !important; /* Force light background */
            color: #1f2937 !important; /* Force dark text */
            transition: all 0.2s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        .form-select:focus {
            outline: none;
            border-color: #b91c1c;
            box-shadow: 0 0 0 3px rgba(185, 28, 28, 0.1);
            background-color: #ffffff !important; /* Force white background on focus */
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }

        @media (min-width: 640px) {
            .form-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        .form-grid-full {
            grid-column: 1 / -1;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input[type="checkbox"] {
            margin-right: 0.5rem;
            accent-color: #b91c1c;
        }

        .remember-me label {
            font-size: 0.875rem;
            color: #6b7280 !important; /* Force gray text */
        }

        .register-button {
            width: 100%;
            padding: 0.75rem 1rem;
            background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 1.5rem;
        }

        .register-button:hover {
            background: linear-gradient(135deg, #991b1b 0%, #7f1d1d 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(185, 28, 28, 0.3);
        }

        .register-button:active {
            transform: translateY(0);
        }

        .signin-prompt {
            text-align: center;
            font-size: 0.875rem;
            color: #6b7280 !important; /* Force gray text */
        }

        .signin-link {
            color: #b91c1c;
            font-weight: 500;
            text-decoration: none;
        }

        .signin-link:hover {
            text-decoration: underline;
        }

        .terms-link {
            color: #b91c1c;
            text-decoration: underline;
        }

        .terms-link:hover {
            color: #991b1b;
        }

        .text-red-500 {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .register-container {
                flex-direction: column;
            }

            .branding-panel {
                min-height: 40vh;
                padding: 2rem 1rem;
            }

            .branding-content {
                min-height: 30vh;
            }

            .system-title {
                font-size: 2rem;
            }

            .features-list {
                display: none;
            }

            .register-panel {
                padding: 1.5rem;
            }

            .powered-by {
                position: relative;
                bottom: auto;
                left: auto;
                transform: none;
                margin-top: 2rem;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .branding-panel {
                padding: 1.5rem 1rem;
            }

            .branding-content {
                min-height: 25vh;
            }

            .system-title {
                font-size: 1.75rem;
            }

            .register-panel {
                padding: 1rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Dark mode override - commented out to force white background */
        /*
        @media (prefers-color-scheme: dark) {
            .register-panel {
                background: #111827;
            }

            .welcome-title {
                color: #ffffff;
            }

            .welcome-subtitle {
                color: #9ca3af;
            }

            .form-label {
                color: #d1d5db;
            }

            .form-input, .form-select {
                background-color: #1f2937;
                border-color: #374151;
                color: #ffffff;
            }

            .form-input:focus, .form-select:focus {
                background-color: #1f2937;
                border-color: #b91c1c;
            }

            .remember-me label {
                color: #9ca3af;
            }

            .signin-prompt {
                color: #9ca3af;
            }
        }
        */
    </style>

    <div class="register-container">
        <!-- Left Panel - Branding -->
        <div class="branding-panel">
            <!-- Particles.js background -->
            <div id="particles-js"></div>

            <div class="branding-content">
                <div class="logo-container">
                    <img src="{{ asset('img/ched-logo.png') }}" alt="CHED Logo" />
                </div>

                <h1 class="system-title">CIMS XII</h1>
                <p class="system-subtitle">
                    Comprehensive Inventory and Supply Management System
                </p>

                {{-- <div class="features-list">
                    <div class="feature-item">
                        <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Streamlined inventory management</span>
                    </div>
                    <div class="feature-item">
                        <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <span>Real-time stock monitoring</span>
                    </div>
                    <div class="feature-item">
                        <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span>Secure data management</span>
                    </div>
                </div> --}}
            </div>

            <div class="powered-by text-center">
                <p>&copy; 2025 Commission on Higher Education – Regional Office 12. All rights reserved.</p>
            </div>


        </div>

        <!-- Right Panel - Register Form -->
        <div class="register-panel">
            <div class="register-form-container">
                <div class="register-header">
                    <h2 class="welcome-title">Create Account</h2>
                    <p class="welcome-subtitle">Join our inventory management system</p>
                </div>

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-grid">
                        <!-- Name -->
                        <div class="form-group">
                            <label for="name" class="form-label">{{ __('Full Name') }}</label>
                            <input id="name"
                                class="form-input"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Enter your full name" />
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email"
                                   class="form-input"
                                   type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autocomplete="username"
                                   placeholder="your.email@example.com" />
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password"
                                   class="form-input"
                                   type="password"
                                   name="password"
                                   required
                                   autocomplete="new-password"
                                   placeholder="Create a secure password" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                            <input id="password_confirmation"
                                   class="form-input"
                                   type="password"
                                   name="password_confirmation"
                                   required
                                   autocomplete="new-password"
                                   placeholder="Confirm your password" />
                        </div>

                        <!-- Department -->
                        <div class="form-group">
                            <label for="department_id" class="form-label">{{ __('Department') }}</label>
                            <select id="department_id" name="department_id" required class="form-select">
                                <option value="">{{ __('Select Department') }}</option>
                                @foreach(\App\Models\Department::all() as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <p class="text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Designation -->
                        <div class="form-group">
                            <label for="designation_id" class="form-label">{{ __('Designation') }}</label>
                            <select id="designation_id" name="designation_id" required class="form-select">
                                <option value="">{{ __('Select Designation') }}</option>
                                @foreach(\App\Models\Designation::all() as $designation)
                                    <option value="{{ $designation->id }}" {{ old('designation_id') == $designation->id ? 'selected' : '' }}>
                                        {{ $designation->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('designation_id')
                                <p class="text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="form-group">
                            <label class="remember-me">
                                <input type="checkbox" name="terms" id="terms" required />
                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="terms-link">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="terms-link">'.__('Privacy Policy').'</a>',
                                    ]) !!}
                                </span>
                            </label>
                        </div>
                    @endif

                    <!-- Register Button -->
                    <button type="submit" class="register-button">
                        {{ __('Create Account') }}
                    </button>

                    <!-- Already Registered Prompt -->
                    <div class="signin-prompt">
                        {{ __('Already have an account?') }}
                        <a class="signin-link" href="{{ route('login') }}">
                            {{ __('Sign in here') }}
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
                            "value_area": 1000
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
                        "value": 0.6,
                        "random": true,
                        "anim": {
                            "enable": true,
                            "speed": 1,
                            "opacity_min": 0.3,
                            "sync": false
                        }
                    },
                    "size": {
                        "value": 3,
                        "random": true,
                        "anim": {
                            "enable": true,
                            "speed": 2,
                            "size_min": 1,
                            "sync": false
                        }
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
                        "random": true,
                        "straight": false,
                        "out_mode": "out",
                        "bounce": false,
                        "attract": {
                            "enable": false,
                            "rotateX": 600,
                            "rotateY": 1200
                        }
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
                            "mode": "bubble"
                        },
                        "resize": true
                    },
                    "modes": {
                        "grab": {
                            "distance": 140,
                            "line_linked": {
                                "opacity": 0.8
                            }
                        },
                        "bubble": {
                            "distance": 200,
                            "size": 6,
                            "duration": 2,
                            "opacity": 0.8,
                            "speed": 3
                        }
                    }
                },
                "retina_detect": true
            });
        });
    </script>

</x-guest-layout>
