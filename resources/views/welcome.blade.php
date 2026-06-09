<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Assignment Management System') }} | Academic Excellence</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Swiper CSS for Slideshow -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        :root {
            --primary: #4F46E5;
            --primary-dark: #4338CA;
            --secondary: #7C3AED;
            --accent: #F59E0B;
            --dark: #1F2937;
            --light: #F3F4F6;
            --success: #10B981;
            --danger: #EF4444;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.05)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.3;
            pointer-events: none;
        }

        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: -0.02em;
        }

        .nav-link {
            font-weight: 500;
            color: var(--dark) !important;
            margin: 0 8px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 80%;
        }

        .nav-link:hover {
            color: var(--primary) !important;
        }

        /* Buttons */
        .btn-outline-custom {
            border: 2px solid var(--primary);
            color: var(--primary);
            padding: 8px 28px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-outline-custom:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 70, 229, 0.3);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 8px 28px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
            color: white;
        }

        .btn-dashboard {
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
            color: white;
            padding: 10px 32px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-dashboard:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
            color: white;
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 80px;
            position: relative;
        }

        .hero-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            padding: 40px 50px;  /* Reduced padding from 60px to 40px 50px */
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            animation: fadeInUp 0.8s ease;
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

        .hero-title {
            font-size: 3rem;  /* Reduced from 3.5rem to 3rem */
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 1rem;  /* Reduced from 1.5rem to 1rem */
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 1rem;  /* Reduced from 1.1rem to 1rem */
            color: #6B7280;
            line-height: 1.5;  /* Reduced from 1.6 to 1.5 */
        }

        /* ========== SLIDESHOW STYLES ========== */
        .slideshow-section {
            margin-top: 30px;  /* Reduced from 40px to 30px */
            overflow: hidden;
            position: relative;
        }

        .slideshow-wrapper {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .swiper {
            width: 100%;
            height: 100%;
            border-radius: 20px;
        }

        .swiper-slide {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 30px;  /* Reduced from 40px to 30px */
            text-align: center;
            color: white;
        }

        .slide-icon {
            font-size: 2.5rem;  /* Reduced from 3rem to 2.5rem */
            margin-bottom: 15px;  /* Reduced from 20px to 15px */
        }

        .slide-title {
            font-size: 1.3rem;  /* Reduced from 1.5rem to 1.3rem */
            font-weight: 700;
            margin-bottom: 10px;  /* Reduced from 15px to 10px */
        }

        .slide-description {
            font-size: 0.9rem;  /* Reduced from 1rem to 0.9rem */
            opacity: 0.9;
            max-width: 80%;
            margin: 0 auto;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: white;
            background: rgba(0,0,0,0.3);
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 18px;
        }

        .swiper-pagination-bullet {
            background: white;
            opacity: 0.5;
        }

        .swiper-pagination-bullet-active {
            background: white;
            opacity: 1;
        }

        /* Feature Cards */
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 35px 25px;
            text-align: center;
            transition: all 0.4s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 40px -12px rgba(0,0,0,0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 2rem;
        }

        .feature-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--dark);
        }

        .feature-desc {
            color: #6B7280;
            line-height: 1.6;
            font-size: 0.9rem;
        }

        /* Statistics Section */
        .stats-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            padding: 60px 40px;
            margin: 40px 0;
        }

        .stat-card {
            text-align: center;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: scale(1.05);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #6B7280;
            font-weight: 500;
            margin-bottom: 0;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 30px;
            padding: 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .cta-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .cta-text {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        .btn-cta {
            background: white;
            color: var(--primary);
            padding: 12px 40px;
            border-radius: 50px;
            font-weight: 700;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .btn-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        /* Footer */
        .footer {
            background: var(--dark);
            color: white;
            padding: 30px 0;
            margin-top: 60px;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            .hero-content {
                padding: 25px 30px;
                margin: 20px;
            }
            .stats-section {
                padding: 30px 20px;
                margin: 20px;
            }
            .stat-number {
                font-size: 1.8rem;
            }
            .cta-section {
                padding: 40px 20px;
                margin: 20px;
            }
            .cta-title {
                font-size: 1.5rem;
            }
            .btn-outline-custom, .btn-primary-custom {
                padding: 5px 20px;
                font-size: 0.8rem;
            }
            .slide-title {
                font-size: 1.2rem;
            }
            .slide-description {
                font-size: 0.8rem;
                max-width: 95%;
            }
            .swiper-slide {
                padding: 20px;
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 1.5rem;
            }
            .feature-card {
                padding: 25px 20px;
            }
            .slide-icon {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    @php
        $totalUsers = \App\Models\User::count();
        $totalCourses = \App\Models\Course::count();
        $totalAssignments = \App\Models\Assignment::count();
        $totalSubmissions = \App\Models\Submission::count();
    @endphp

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-graduation-cap me-2"></i>
                AssignFlow
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#stats">Statistics</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="btn-dashboard" href="{{ url('/dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                    @else
                        <li class="nav-item me-2">
                            <a class="btn-outline-custom" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-2"></i> Sign In
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn-primary-custom" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-2"></i> Get Started
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Slideshow -->
    <section id="home" class="hero">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10" data-aos="fade-up">
                    <div class="hero-content">
                        <div class="text-center">
                            <div class="mb-3">
                                <i class="fas fa-tasks fa-2x" style="color: var(--primary);"></i>
                            </div>
                            <h1 class="hero-title">Streamline Academic Excellence</h1>
                            <p class="hero-subtitle">
                                A comprehensive assignment management platform designed to enhance learning,
                                streamline submissions, and foster academic success for educators and students alike.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slideshow Section -->
            <div class="row justify-content-center mt-4">
                <div class="col-lg-10" data-aos="fade-up" data-aos-delay="200">
                    <div class="slideshow-section">
                        <div class="slideshow-wrapper">
                            <div class="swiper mySwiper">
                                <div class="swiper-wrapper">
                                    <!-- Slide 1 -->
                                    <div class="swiper-slide">
                                        <div class="slide-icon">
                                            <i class="fas fa-chalkboard-user"></i>
                                        </div>
                                        <h3 class="slide-title">For Educators</h3>
                                        <p class="slide-description">Create courses, design assignments, grade submissions, and provide meaningful feedback to students effortlessly.</p>
                                    </div>
                                    <!-- Slide 2 -->
                                    <div class="swiper-slide">
                                        <div class="slide-icon">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                        <h3 class="slide-title">For Students</h3>
                                        <p class="slide-description">Submit assignments on time, track your grades, receive feedback, and monitor your academic progress.</p>
                                    </div>
                                    <!-- Slide 3 -->
                                    <div class="swiper-slide">
                                        <div class="slide-icon">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                        <h3 class="slide-title">Analytics & Reports</h3>
                                        <p class="slide-description">Generate comprehensive reports, track performance metrics, and gain valuable insights.</p>
                                    </div>
                                    <!-- Slide 4 -->
                                    <div class="swiper-slide">
                                        <div class="slide-icon">
                                            <i class="fas fa-shield-alt"></i>
                                        </div>
                                        <h3 class="slide-title">Secure & Reliable</h3>
                                        <p class="slide-description">Enterprise-grade security ensuring your data is always safe and protected.</p>
                                    </div>
                                    <!-- Slide 5 -->
                                    <div class="swiper-slide">
                                        <div class="slide-icon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <h3 class="slide-title">24/7 Accessibility</h3>
                                        <p class="slide-description">Access your assignments and courses anytime, anywhere, on any device.</p>
                                    </div>
                                </div>
                                <!-- Navigation Arrows -->
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                                <!-- Pagination Dots -->
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold text-white" style="font-size: 2.5rem;">Powerful Features</h2>
                <p class="text-white-50">Everything you need for effective assignment management</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon" style="background: linear-gradient(135deg, #667eea20 0%, #764ba220 100%);">
                            <i class="fas fa-chalkboard-user fa-2x" style="color: var(--primary);"></i>
                        </div>
                        <h5 class="feature-title">For Educators</h5>
                        <p class="feature-desc">Create courses, design engaging assignments, grade submissions efficiently, and provide constructive feedback to students.</p>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon" style="background: linear-gradient(135deg, #10b98120 0%, #05966920 100%);">
                            <i class="fas fa-user-graduate fa-2x" style="color: var(--success);"></i>
                        </div>
                        <h5 class="feature-title">For Students</h5>
                        <p class="feature-desc">Submit assignments on time, track grades, receive personalized feedback, and monitor academic progress in real-time.</p>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon" style="background: linear-gradient(135deg, #f59e0b20 0%, #d9770620 100%);">
                            <i class="fas fa-chart-line fa-2x" style="color: var(--accent);"></i>
                        </div>
                        <h5 class="feature-title">Analytics & Reports</h5>
                        <p class="feature-desc">Generate comprehensive reports, track performance metrics, and gain valuable insights into learning outcomes.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section id="stats" class="py-3">
        <div class="container">
            <div class="stats-section" data-aos="fade-up">
                <div class="row g-4">
                    <div class="col-md-3 col-6">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-users" style="color: var(--primary);"></i>
                            </div>
                            <h3 class="stat-number" id="userCount">0</h3>
                            <p class="stat-label">Active Users</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-book" style="color: var(--success);"></i>
                            </div>
                            <h3 class="stat-number" id="courseCount">0</h3>
                            <p class="stat-label">Courses</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-tasks" style="color: var(--accent);"></i>
                            </div>
                            <h3 class="stat-number" id="assignmentCount">0</h3>
                            <p class="stat-label">Assignments</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-paper-plane" style="color: #8B5CF6;"></i>
                            </div>
                            <h3 class="stat-number" id="submissionCount">0</h3>
                            <p class="stat-label">Submissions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="hero-content" style="background: rgba(255,255,255,0.95);">
                        <h2 class="fw-bold mb-4" style="color: var(--dark);">Why Choose AssignFlow?</h2>
                        <div class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Streamlined Workflow</strong> - Intuitive interface for both teachers and students
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Real-time Updates</strong> - Instant notifications and deadline reminders
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Secure Platform</strong> - Enterprise-grade security for all your data
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>24/7 Accessibility</strong> - Access from anywhere, anytime
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="hero-content" style="background: rgba(255,255,255,0.95);">
                        <h2 class="fw-bold mb-4" style="color: var(--dark);">Key Benefits</h2>
                        <div class="mb-3">
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            <strong>Improved Performance</strong> - Track progress and identify areas for improvement
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-clock text-primary me-2"></i>
                            <strong>Time Saving</strong> - Automate grading and feedback processes
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-comments text-primary me-2"></i>
                            <strong>Better Communication</strong> - Enhanced interaction between teachers and students
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-chart-simple text-primary me-2"></i>
                            <strong>Data-Driven Insights</strong> - Comprehensive analytics for informed decisions
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5">
        <div class="container">
            <div class="cta-section" data-aos="zoom-in">
                <h2 class="cta-title text-white">Ready to Transform Your Learning Experience?</h2>
                <p class="cta-text text-white">
                    Join thousands of educators and students who are already using our platform
                </p>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-cta">
                        <i class="fas fa-rocket me-2"></i> Get Started Now
                    </a>
                @else
                    <a href="{{ url('/dashboard') }}" class="btn btn-cta">
                        <i class="fas fa-tachometer-alt me-2"></i> Go to Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <i class="fas fa-graduation-cap fa-2x mb-3" style="opacity: 0.5;"></i>
                    <p class="mb-2">&copy; {{ date('Y') }} AssignFlow. All rights reserved.</p>
                    <small style="opacity: 0.6;">Empowering Education Through Technology</small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Swiper JS for Slideshow -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Initialize Swiper Slideshow with Right-to-Left effect
        const swiper = new Swiper('.mySwiper', {
            effect: 'slide',
            speed: 800,
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            grabCursor: true,
        });

        function animateNumber(elementId, target) {
            let element = document.getElementById(elementId);
            if (!element) return;
            let current = 0;
            let increment = target / 60;
            let timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.innerText = target.toLocaleString();
                    clearInterval(timer);
                } else {
                    element.innerText = Math.floor(current).toLocaleString();
                }
            }, 20);
        }

        document.addEventListener('DOMContentLoaded', function() {
            animateNumber('userCount', {{ $totalUsers }});
            animateNumber('courseCount', {{ $totalCourses }});
            animateNumber('assignmentCount', {{ $totalAssignments }});
            animateNumber('submissionCount', {{ $totalSubmissions }});
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>
