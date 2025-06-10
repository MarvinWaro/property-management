<x-guest-layout>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Inter', sans-serif;
        }

        .login-container {
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

        /* Right Panel - Login Form */
        .login-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: #ffffff;
        }

        .login-form-container {
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .welcome-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .welcome-subtitle {
            color: #6b7280;
            font-size: 1rem;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 1rem;
            background-color: #f9fafb;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #b91c1c;
            box-shadow: 0 0 0 3px rgba(185, 28, 28, 0.1);
            background-color: #ffffff;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
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
            color: #6b7280;
        }

        .forgot-password {
            font-size: 0.875rem;
            color: #b91c1c;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .login-button {
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

        .login-button:hover {
            background: linear-gradient(135deg, #991b1b 0%, #7f1d1d 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(185, 28, 28, 0.3);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .signup-prompt {
            text-align: center;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .signup-link {
            color: #b91c1c;
            font-weight: 500;
            text-decoration: none;
        }

        .signup-link:hover {
            text-decoration: underline;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .login-container {
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

            .login-panel {
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

            .login-panel {
                padding: 1rem;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .login-panel {
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

            .form-input {
                background-color: #1f2937;
                border-color: #374151;
                color: #ffffff;
            }

            .form-input:focus {
                background-color: #1f2937;
                border-color: #b91c1c;
            }

            .remember-me label {
                color: #9ca3af;
            }

            .signup-prompt {
                color: #9ca3af;
            }
        }
    </style>

    <div class="login-container">
        <!-- Left Panel - Branding -->
        <div class="branding-panel">
            <!-- Particles.js background -->
            <div id="particles-js"></div>

            <div class="branding-content">
                <div class="logo-container">
                    <img src="{{ asset('img/ched-logo.png') }}"
                         alt="CHED Logo"
                         style="width: 100px; height: 100px;" />
                </div>

                <h1 class="system-title">CIMS XII</h1>
                <p class="system-subtitle">
                    CHED Inventory Management System
                </p>

                <div class="features-list">
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
                </div>
            </div>

            <div class="powered-by text-center">
                <p>&copy; 2025 Commission on Higher Education – Regional Office 12. All rights reserved.</p>
            </div>
        </div>

        <!-- Right Panel - Login Form -->
        <div class="login-panel">
            <div class="login-form-container">
                <div class="login-header">
                    <h2 class="welcome-title">Welcome Back</h2>
                    <p class="welcome-subtitle">Please sign in to your account</p>
                </div>

                <x-validation-errors class="mb-4" />

                @session('status')
                    <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                        {{ $value }}
                    </div>
                @endsession

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email" class="form-label">{{ __('Email') }}</label>
                        <input id="email"
                               class="form-input"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               autocomplete="username"
                               placeholder="Enter your email address" />
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password"
                               class="form-input"
                               type="password"
                               name="password"
                               required
                               autocomplete="current-password"
                               placeholder="Enter your password" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="form-row">
                        <label class="remember-me">
                            <input type="checkbox" id="remember_me" name="remember" />
                            <span class="text-white">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="forgot-password" href="{{ route('password.request') }}">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="login-button">
                        {{ __('Sign In') }}
                    </button>

                    <!-- Signup Prompt -->
                    <div class="signup-prompt">
                        {{ __("Don't have an account?") }}
                        <a class="signup-link" href="{{ route('register') }}">
                            {{ __('Sign up here') }}
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
