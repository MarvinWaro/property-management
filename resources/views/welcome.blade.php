<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>CISMS XII - Comprehensive Inventory and Supply Management System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

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
                background-color: #ffffff;
            }

            /* Container */
            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 1rem;
            }

            /* Navigation */
            .navbar {
                background: #ffffff;
                border-bottom: 1px solid #e5e7eb;
                padding: 1rem 0;
                position: sticky;
                top: 0;
                z-index: 100;
            }

            .nav-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .logo {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .logo img {
                width: 40px;
                height: 40px;
            }

            .logo-text {
                font-size: 1.25rem;
                font-weight: 700;
                color: #ce201f;
            }

            .nav-links {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .nav-link {
                color: #6b7280;
                text-decoration: none;
                font-weight: 500;
                padding: 0.5rem 1rem;
                border-radius: 0.375rem;
                transition: all 0.2s ease;
            }

            .nav-link:hover {
                color: #ce201f;
            }

            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.2s ease;
                border: none;
                cursor: pointer;
                font-size: 0.875rem;
            }

            .btn-primary {
                background: #ce201f;
                color: white;
            }

            .btn-primary:hover {
                background: #a01b1a;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(206, 32, 31, 0.3);
            }

            .btn-secondary {
                background: white;
                color: #ce201f;
                border: 2px solid #ce201f;
            }

            .btn-secondary:hover {
                background: #ce201f;
                color: white;
            }

            .btn-success {
                background: #10b981;
                color: white;
            }

            .btn-success:hover {
                background: #059669;
            }

            /* Hero Section */
            .hero {
                background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
                padding: 6rem 0;
                border-bottom: 1px solid #e5e7eb;
            }

            .hero-content {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 4rem;
                align-items: center;
            }

            .hero-text h1 {
                font-size: 3rem;
                font-weight: 700;
                color: #1f2937;
                margin-bottom: 1.5rem;
                line-height: 1.1;
            }

            .hero-text .highlight {
                color: #ce201f;
            }

            .hero-text p {
                font-size: 1.125rem;
                color: #6b7280;
                margin-bottom: 2rem;
                line-height: 1.7;
            }

            .hero-actions {
                display: flex;
                gap: 1rem;
                flex-wrap: wrap;
            }

            .hero-visual {
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .dashboard-mockup {
                background: white;
                border-radius: 1rem;
                padding: 2rem;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 500px;
            }

            .mockup-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1.5rem;
                padding-bottom: 1rem;
                border-bottom: 1px solid #e5e7eb;
            }

            .mockup-title {
                font-weight: 600;
                color: #1f2937;
            }

            .mockup-stats {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1rem;
                margin-bottom: 1.5rem;
            }

            .stat-card {
                padding: 1rem;
                border-radius: 0.5rem;
                text-align: center;
            }

            .stat-card.red {
                background: #fef2f2;
                border: 1px solid #fecaca;
            }

            .stat-card.green {
                background: #f0fdf4;
                border: 1px solid #bbf7d0;
            }

            .stat-card.amber {
                background: #fffbeb;
                border: 1px solid #fde68a;
            }

            .stat-number {
                font-size: 1.5rem;
                font-weight: 700;
                margin-bottom: 0.25rem;
            }

            .stat-card.red .stat-number {
                color: #ce201f;
            }

            .stat-card.green .stat-number {
                color: #10b981;
            }

            .stat-card.amber .stat-number {
                color: #f59e0b;
            }

            .stat-label {
                font-size: 0.75rem;
                color: #6b7280;
                font-weight: 500;
            }

            .mockup-chart {
                height: 120px;
                background: #f9fafb;
                border-radius: 0.5rem;
                display: flex;
                align-items: end;
                justify-content: space-around;
                padding: 1rem;
            }

            .chart-bar {
                width: 20px;
                border-radius: 2px;
                transition: all 0.3s ease;
            }

            .chart-bar:hover {
                transform: scaleY(1.1);
            }

            .chart-bar.red {
                background: #ce201f;
                height: 60%;
            }

            .chart-bar.green {
                background: #10b981;
                height: 80%;
            }

            .chart-bar.amber {
                background: #f59e0b;
                height: 45%;
            }

            /* Features Section */
            .features {
                padding: 6rem 0;
                background: white;
            }

            .section-header {
                text-align: center;
                margin-bottom: 4rem;
            }

            .section-title {
                font-size: 2.5rem;
                font-weight: 700;
                color: #1f2937;
                margin-bottom: 1rem;
            }

            .section-subtitle {
                font-size: 1.125rem;
                color: #6b7280;
                max-width: 600px;
                margin: 0 auto;
            }

            .features-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 2rem;
            }

            .feature-card {
                background: white;
                padding: 2rem;
                border-radius: 1rem;
                border: 1px solid #e5e7eb;
                transition: all 0.2s ease;
            }

            .feature-card:hover {
                border-color: #ce201f;
                box-shadow: 0 4px 20px rgba(206, 32, 31, 0.1);
            }

            .feature-icon {
                width: 3rem;
                height: 3rem;
                border-radius: 0.75rem;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 1.5rem;
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
            }

            .feature-description {
                color: #6b7280;
                line-height: 1.6;
            }

            /* Stats Section */
            .stats {
                padding: 6rem 0;
                background: #f9fafb;
            }

            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 2rem;
                text-align: center;
            }

            .stat-item {
                background: white;
                padding: 2rem;
                border-radius: 1rem;
                border: 1px solid #e5e7eb;
            }

            .stat-item-number {
                font-size: 2.5rem;
                font-weight: 700;
                color: #ce201f;
                margin-bottom: 0.5rem;
            }

            .stat-item-label {
                color: #6b7280;
                font-weight: 500;
            }

            /* CTA Section */
            .cta {
                padding: 6rem 0;
                background: #ce201f;
                color: white;
                text-align: center;
            }

            .cta h2 {
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 1rem;
            }

            .cta p {
                font-size: 1.125rem;
                opacity: 0.9;
                margin-bottom: 2rem;
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
            }

            .cta-actions {
                display: flex;
                gap: 1rem;
                justify-content: center;
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
            }

            .footer h3 {
                font-size: 1.25rem;
                font-weight: 700;
                margin-bottom: 1rem;
            }

            .footer p {
                color: #9ca3af;
                margin-bottom: 1rem;
            }

            .footer-info {
                font-size: 0.875rem;
                color: #6b7280;
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .hero-content {
                    grid-template-columns: 1fr;
                    gap: 2rem;
                    text-align: center;
                }

                .hero-text h1 {
                    font-size: 2rem;
                }

                .hero-actions {
                    justify-content: center;
                }

                .nav-links {
                    display: none;
                }

                .section-title {
                    font-size: 2rem;
                }

                .cta h2 {
                    font-size: 2rem;
                }

                .mockup-stats {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 480px) {
                .container {
                    padding: 0 0.75rem;
                }

                .hero {
                    padding: 4rem 0;
                }

                .features, .stats, .cta {
                    padding: 4rem 0;
                }

                .hero-actions {
                    flex-direction: column;
                    align-items: center;
                }

                .cta-actions {
                    flex-direction: column;
                    align-items: center;
                }
            }
        </style>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar">
            <div class="container">
                <div class="nav-content">
                    <div class="logo">
                        <img src="{{ asset('img/ched-logo.png') }}" alt="CHED Logo">
                        <span class="logo-text">CIMS XII</span>
                    </div>

                    @if (Route::has('login'))
                        <div class="nav-links">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="nav-link">
                                    Sign In
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn btn-primary">
                                        Get Started
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1>
                            <span class="highlight">CHEDRO XII</span> Inventory Management System
                        </h1>
                        <p>
                            Streamline your educational institution's inventory management with our professional digital solution designed specifically for Commission on Higher Education requirements.
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
                                <a href="#features" class="btn btn-secondary">
                                    Learn More
                                </a>
                            @endauth
                        </div>
                    </div>

                    <div class="hero-visual">
                        <div class="dashboard-mockup">
                            <div class="mockup-header">
                                <div class="mockup-title">Inventory Dashboard</div>
                                <div style="width: 8px; height: 8px; background: #10b981; border-radius: 50%;"></div>
                            </div>

                            <div class="mockup-stats">
                                <div class="stat-card red">
                                    <div class="stat-number">1,247</div>
                                    <div class="stat-label">Total Items</div>
                                </div>
                                <div class="stat-card green">
                                    <div class="stat-number">892</div>
                                    <div class="stat-label">In Stock</div>
                                </div>
                                <div class="stat-card amber">
                                    <div class="stat-number">45</div>
                                    <div class="stat-label">Low Stock</div>
                                </div>
                            </div>

                            <div class="mockup-chart">
                                <div class="chart-bar red"></div>
                                <div class="chart-bar green"></div>
                                <div class="chart-bar amber"></div>
                                <div class="chart-bar red"></div>
                                <div class="chart-bar green"></div>
                                <div class="chart-bar amber"></div>
                                <div class="chart-bar red"></div>
                                <div class="chart-bar green"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="features">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Powerful Features for Modern Inventory Management</h2>
                    <p class="section-subtitle">
                        Everything you need to efficiently manage your educational institution's inventory and supplies in one comprehensive platform.
                    </p>
                </div>

                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon red">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4 6h16v2H4zm0 5h16v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6zm2-7a2 2 0 012-2h8a2 2 0 012 2v1H6V4z"/>
                            </svg>
                        </div>
                        <h3 class="feature-title">Real-time Asset Tracking</h3>
                        <p class="feature-description">
                            Monitor all institutional assets with detailed information, locations, and status updates in real-time for complete visibility.
                        </p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon green">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 3v18h18v-2H5V3H3zm14 12h2V9h-2v6zm-4 2h2V7h-2v10zm-4 0h2v-4H9v4z"/>
                            </svg>
                        </div>
                        <h3 class="feature-title">Advanced Analytics</h3>
                        <p class="feature-description">
                            Generate comprehensive reports and analytics to make data-driven decisions about your inventory management and procurement.
                        </p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon amber">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                        <h3 class="feature-title">CHED Compliance</h3>
                        <p class="feature-description">
                            Ensure full compliance with CHED requirements and regulations for educational institutions with built-in compliance features.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="stats">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Lorem ipsum dolor sit amet consectetur adipisicing elit.</h2>
                </div>

                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-item-number">500+</div>
                        <div class="stat-item-label">Educational Institutions</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-item-number">50K+</div>
                        <div class="stat-item-label">Items Tracked Daily</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-item-number">99.9%</div>
                        <div class="stat-item-label">System Uptime</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-item-number">24/7</div>
                        <div class="stat-item-label">Support Available</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        {{-- <section class="cta">
            <div class="container">
                <h2>Ready to Transform Your Inventory Management?</h2>
                <p>
                    Join hundreds of educational institutions already using CIMS XII to streamline their inventory operations and ensure CHED compliance.
                </p>

                @auth
                    <div class="cta-actions">
                        <a href="{{ url('/dashboard') }}" class="btn btn-white">
                            Access Your Dashboard
                        </a>
                    </div>
                @else
                    <div class="cta-actions">
                        <a href="{{ route('register') }}" class="btn btn-white">
                            Start Free Today
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline">
                            Sign In
                        </a>
                    </div>
                @endauth
            </div>
        </section> --}}

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <h3>CIMS XII</h3>
                <p>
                    CHED Inventory and Supply Management System
                </p>
                <div class="footer-info">
                    <p>Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</p>
                    <p>&copy; 2025 Commission on Higher Education – Regional Office 12. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
