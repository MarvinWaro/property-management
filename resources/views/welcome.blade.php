<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>CISMS XII - CHED Inventory and Supply Management System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <link rel="icon" type="image/png" href="{{ asset('img/ched-logo.png') }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ asset('img/ched-logo.png') }}">

        <!-- Styles -->
        <style>
            /* Reset and Base Styles */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Inter', sans-serif;
                line-height: 1.6;
                color: #374151;
                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 50%, #ffffff 100%);
                text-align: center;
                display: flex;
                flex-direction: column;
                min-height: 100vh;
                justify-content: center;
                align-items: center;
            }

            /* Animated Background Elements */
            body::before {
                content: '';
                position: fixed;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle at 20% 80%, rgba(206, 32, 31, 0.03) 0%, transparent 50%),
                           radial-gradient(circle at 80% 20%, rgba(16, 185, 129, 0.03) 0%, transparent 50%),
                           radial-gradient(circle at 40% 40%, rgba(245, 158, 11, 0.02) 0%, transparent 50%);
                animation: float 20s ease-in-out infinite;
                z-index: -1;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                33% { transform: translateY(-20px) rotate(1deg); }
                66% { transform: translateY(-10px) rotate(-1deg); }
            }

            /* Container */
            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 1rem;
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
            }

            /* Logo Styles */
            .logo {
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
                margin-bottom: 2rem;
                text-align: center;
            }

            .logo:hover {
                transform: translateY(-2px);
            }

            .logo img {
                width: 80px;
                height: 80px;
                transition: all 0.3s ease;
                filter: drop-shadow(0 6px 12px rgba(206, 32, 31, 0.2));
            }

            .logo:hover img {
                transform: rotate(5deg) scale(1.05);
            }

            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.75rem 1.5rem;
                border-radius: 0.75rem;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                border: none;
                cursor: pointer;
                font-size: 0.875rem;
                position: relative;
                overflow: hidden;
                text-align: center;
            }

            .btn::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.6s ease;
            }

            .btn:hover::before {
                left: 100%;
            }

            .btn-primary {
                background: linear-gradient(135deg, #ce201f 0%, #a01b1a 100%);
                color: white;
                box-shadow: 0 4px 14px rgba(206, 32, 31, 0.25);
            }

            .btn-primary:hover {
                background: linear-gradient(135deg, #a01b1a 0%, #8b1a1a 100%);
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(206, 32, 31, 0.4);
            }

            .btn-secondary {
                background: rgba(255, 255, 255, 0.9);
                color: #ce201f;
                border: 2px solid #ce201f;
                backdrop-filter: blur(10px);
            }

            .btn-secondary:hover {
                background: #ce201f;
                color: white;
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(206, 32, 31, 0.3);
            }

            /* Main Content */
            .main-content {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                padding: 4rem 0;
                min-height: 100vh;
            }

            .hero-content {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                gap: 2rem;
                max-width: 800px;
                margin: 0 auto;
                animation: fadeInUp 1s ease-out;
                width: 100%;
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .hero-text {
                text-align: center;
                margin: 0 auto;
            }

            .hero-text h1 {
                font-size: 3.5rem;
                font-weight: 800;
                color: #1f2937;
                margin-bottom: 1.5rem;
                line-height: 1.1;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                animation: slideInUp 1.2s ease-out;
                text-align: center;
            }

            @keyframes slideInUp {
                from {
                    opacity: 0;
                    transform: translateY(50px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .hero-text .highlight {
                background: linear-gradient(135deg, #ce201f 0%, #e53e3e 50%, #f56565 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                position: relative;
            }

            .hero-text .highlight::after {
                content: '';
                position: absolute;
                bottom: -4px;
                left: 50%;
                transform: translateX(-50%);
                width: 80%;
                height: 4px;
                background: linear-gradient(135deg, #ce201f 0%, #e53e3e 100%);
                border-radius: 2px;
                opacity: 0.3;
            }

            .hero-text p {
                font-size: 1.25rem;
                color: #64748b;
                margin-bottom: 2.5rem;
                line-height: 1.8;
                animation: slideInUp 1.2s ease-out 0.2s both;
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
                text-align: center;
            }

            .hero-actions {
                display: flex;
                gap: 1.5rem;
                flex-wrap: wrap;
                justify-content: center;
                align-items: center;
                animation: slideInUp 1.2s ease-out 0.4s both;
            }

            /* Features Section */
            .features {
                padding: 6rem 0;
                background: white;
                text-align: center;
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            .section-header {
                text-align: center;
                margin-bottom: 4rem;
                max-width: 800px;
                margin-left: auto;
                margin-right: auto;
            }

            .section-title {
                font-size: 2.5rem;
                font-weight: 700;
                color: #1f2937;
                margin-bottom: 1rem;
                text-align: center;
            }

            .section-subtitle {
                font-size: 1.125rem;
                color: #6b7280;
                max-width: 600px;
                margin: 0 auto;
                text-align: center;
            }

            .features-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 2rem;
                justify-items: center;
                align-items: center;
                width: 100%;
                max-width: 1200px;
                margin: 0 auto;
            }

            .feature-card {
                background: white;
                padding: 2rem;
                border-radius: 1rem;
                border: 1px solid #e5e7eb;
                transition: all 0.2s ease;
                text-align: center;
                max-width: 400px;
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            .feature-card:hover {
                border-color: #ce201f;
                box-shadow: 0 4px 20px rgba(206, 32, 31, 0.1);
                transform: translateY(-2px);
            }

            .feature-icon {
                width: 3rem;
                height: 3rem;
                border-radius: 0.75rem;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1.5rem auto;
                font-size: 1.5rem;
                text-align: center;
            }

            .feature-icon.red {
                background: #fef2f2;
                color: #ce201f;
            }

            .feature-icon.green {
                background: #f0fdf4;
                color: #10b981;
            }

            .feature-icon.amber {
                background: #fffbeb;
                color: #f59e0b;
            }

            .feature-title {
                font-size: 1.25rem;
                font-weight: 700;
                color: #1f2937;
                margin-bottom: 0.75rem;
                text-align: center;
            }

            .feature-description {
                color: #6b7280;
                line-height: 1.6;
                text-align: center;
            }

            /* CTA Section */
            .cta {
                padding: 6rem 0;
                background: #ce201f;
                color: white;
                text-align: center;
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            .cta h2 {
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 1rem;
                text-align: center;
            }

            .cta p {
                font-size: 1.125rem;
                opacity: 0.9;
                margin-bottom: 2rem;
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
                text-align: center;
            }

            .cta-actions {
                display: flex;
                gap: 1rem;
                justify-content: center;
                align-items: center;
                flex-wrap: wrap;
            }

            .btn-white {
                background: white;
                color: #ce201f;
            }

            .btn-white:hover {
                background: #f9fafb;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
            }

            .btn-outline {
                background: transparent;
                color: white;
                border: 2px solid white;
            }

            .btn-outline:hover {
                background: white;
                color: #ce201f;
            }

            /* Footer */
            .footer {
                background: #1f2937;
                color: white;
                padding: 3rem 0;
                text-align: center;
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            .footer h3 {
                font-size: 1.25rem;
                font-weight: 700;
                margin-bottom: 1rem;
                text-align: center;
            }

            .footer p {
                color: #9ca3af;
                margin-bottom: 1rem;
                text-align: center;
            }

            .footer-info {
                font-size: 0.875rem;
                color: #6b7280;
                text-align: center;
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .main-content {
                    padding: 3rem 0;
                }

                .hero-content {
                    gap: 1.5rem;
                }

                .hero-text h1 {
                    font-size: 2.5rem;
                }

                .logo img {
                    width: 70px;
                    height: 70px;
                }

                .features-grid {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 480px) {
                .container {
                    padding: 0 0.75rem;
                }

                .main-content {
                    padding: 2rem 0;
                }

                .hero-content {
                    gap: 1.25rem;
                }

                .hero-text h1 {
                    font-size: 2rem;
                }

                .hero-text p {
                    font-size: 1.1rem;
                }

                .hero-actions {
                    flex-direction: column;
                    align-items: center;
                    gap: 1rem;
                }

                .btn {
                    width: 100%;
                    max-width: 280px;
                }

                .logo img {
                    width: 60px;
                    height: 60px;
                }
            }
        </style>
    </head>
    <body>
        <!-- Main Content -->
        <main class="main-content">
            <div class="container">
                <div class="hero-content">
                    <!-- Logo -->
                    <div class="logo">
                        <img src="{{ asset('img/ched-logo.png') }}" alt="CHED Logo">
                    </div>

                    <div class="hero-text">
                        <h1>
                            <span class="highlight">CHEDRO XII</span> Inventory Management System
                        </h1>
                        <p>
                            CHEDRO 12 Digital Inventory System for streamlined requests, automated reports, and seamless management.
                        </p>
                        <div class="hero-actions">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                                    Go to Dashboard
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="btn btn-primary">
                                    Get Started
                                </a>
                                <a href="{{ route('login') }}" class="btn btn-secondary">
                                    Sign In
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
