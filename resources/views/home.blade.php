<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CivicReport - Empower Your Neighborhood</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        /* Navigation */
        nav {
            background: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .logo-icon-container {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .custom-logo-svg {
            width: 100%;
            height: 100%;
        }

        .logo-text {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0f2d59;
            letter-spacing: -0.03em;
            text-transform: lowercase;
        }

        .logo-text-accent {
            color: #3182ce;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #2563eb;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn-login {
            padding: 0.6rem 1.5rem;
            border: 2px solid #2563eb;
            background: transparent;
            color: #2563eb;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-login:hover {
            background: #2563eb;
            color: white;
        }

        .btn-signup {
            padding: 0.6rem 1.5rem;
            background: linear-gradient(135deg, #2563eb 0%, #06b6d4 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .btn-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            color: white;
            padding: 6rem 2rem;
            text-align: center;
            overflow: hidden;
            position: relative;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(6, 182, 212, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
            pointer-events: none;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite reverse;
            pointer-events: none;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-30px); }
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.1;
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary {
            padding: 1rem 2.5rem;
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 6px 20px rgba(6, 182, 212, 0.3);
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(6, 182, 212, 0.4);
        }

        .btn-secondary {
            padding: 1rem 2.5rem;
            background: white;
            color: #0f172a;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.1);
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary:hover {
            background: #f0f9ff;
            transform: translateY(-3px);
        }

        /* Features Section */
        .features {
            padding: 5rem 2rem;
            background: #f8fafc;
        }

        .features-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            color: #0f172a;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.1rem;
            color: #64748b;
            margin-bottom: 4rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            border-left: 4px solid #2563eb;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            font-size: 1.3rem;
            margin-bottom: 0.8rem;
            color: #0f172a;
        }

        .feature-card p {
            color: #64748b;
            line-height: 1.7;
        }

        /* How It Works */
        .how-it-works {
            padding: 5rem 2rem;
            background: white;
        }

        .how-it-works-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .step {
            text-align: center;
        }

        .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #2563eb 0%, #06b6d4 100%);
            color: white;
            border-radius: 50%;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .step h3 {
            font-size: 1.2rem;
            margin-bottom: 0.8rem;
            color: #0f172a;
        }

        .step p {
            color: #64748b;
            line-height: 1.6;
        }

        /* User Types Section */
        .user-types {
            padding: 5rem 2rem;
            background: #f8fafc;
        }

        .user-types-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .types-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .type-card {
            background: white;
            padding: 2.5rem;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
        }

        .type-card:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .type-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .type-card h3 {
            font-size: 1.4rem;
            margin-bottom: 0.8rem;
            color: #0f172a;
        }

        .type-card p {
            color: #64748b;
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, #2563eb 0%, #06b6d4 100%);
            color: white;
            padding: 4rem 2rem;
            text-align: center;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
        }

        .cta-section p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: 0.95;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Footer */
        footer {
            background: #0f172a;
            color: #94a3b8;
            padding: 3rem 2rem;
            text-align: center;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: white;
        }

        .footer-bottom {
            border-top: 1px solid #1e293b;
            padding-top: 2rem;
            margin-top: 2rem;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .nav-links {
                gap: 1rem;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn-primary, .btn-secondary {
                width: 100%;
                max-width: 300px;
            }

            .section-title {
                font-size: 2rem;
            }

            .cta-section h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <div class="logo" onclick="window.location.reload()">
                <div class="logo-icon-container">
                    <svg class="custom-logo-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <!-- Document sheet -->
                        <path d="M 52,32 L 72,32 C 74,32 76,34 77,35 L 85,43 C 86,44 87,46 87,48 L 87,78 C 87,81 84,84 81,84 L 52,84" fill="none" stroke="#3182ce" stroke-width="7" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M 72,32 L 72,44 C 72,46 74,48 76,48 L 87,48" fill="none" stroke="#3182ce" stroke-width="7" stroke-linecap="round" />
                        <!-- Lines on document -->
                        <line x1="61" y1="58" x2="76" y2="58" stroke="#0f2d59" stroke-width="6" stroke-linecap="round" />
                        <line x1="61" y1="66" x2="76" y2="66" stroke="#0f2d59" stroke-width="6" stroke-linecap="round" />
                        <line x1="61" y1="74" x2="76" y2="74" stroke="#0f2d59" stroke-width="6" stroke-linecap="round" />
                        <!-- Speech bubble -->
                        <path d="M 45,16 C 24,16 8,30 8,48 C 8,58 13,67 21,73 L 18,85 L 30,79 C 35,81 40,82 45,82 C 66,82 82,68 82,48 C 82,30 66,16 45,16 Z" fill="none" stroke="#0f2d59" stroke-width="8" stroke-linecap="round" stroke-linejoin="round" />
                        <!-- Buildings inside speech bubble -->
                        <path d="M 21,72 L 21,53 L 29,43 L 29,72 Z" fill="#4299e1" />
                        <path d="M 31,72 L 31,37 L 41,26 L 41,72 Z" fill="#3182ce" />
                        <path d="M 43,72 L 43,47 L 51,37 L 51,72 Z" fill="#2b6cb0" />
                    </svg>
                </div>
                <span class="logo-text">civic <span class="logo-text-accent">report</span></span>
            </div>
            <ul class="nav-links">
                <li><a href="#features">Features</a></li>
                <li><a href="#how-it-works">How It Works</a></li>
                <li><a href="#user-types">For You</a></li>
            </ul>
            <div class="auth-buttons">
                <a href="{{ route('login') }}" class="btn-login">Sign In</a>
                <a href="{{ route('register') }}" class="btn-signup">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Empower Your Neighborhood</h1>
            <p>Report civic issues, track progress, and build a better community. Your voice matters!</p>
            <div class="hero-buttons">
                <a href="{{ route('register') }}" class="btn-primary">📍 Start Reporting Now</a>
                <a href="#how-it-works" class="btn-secondary">Learn More</a>
            </div>
        </div>
    </section>


    <!-- Features Section -->
    <section class="features" id="features">
        <div class="features-container">
            <h2 class="section-title">Why Choose CivicReport?</h2>
            <p class="section-subtitle">A platform designed to make civic engagement simple, transparent, and impactful</p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3>Easy Reporting</h3>
                    <p>Report issues with photos and descriptions. Your neighborhood, your voice.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3>Community Support</h3>
                    <p>Vote on issues and show support for causes that matter to you.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Real-time Updates</h3>
                    <p>Track progress as your reported issues move through the system.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">👷</div>
                    <h3>Direct Assignment</h3>
                    <p>Issues are assigned to qualified workers for swift resolution.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💬</div>
                    <h3>Communication Hub</h3>
                    <p>Discuss issues and collaborate with your community members.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🎯</div>
                    <h3>Impact Tracking</h3>
                    <p>See your contributions and celebrate community achievements.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works" id="how-it-works">
        <div class="how-it-works-container">
            <h2 class="section-title">How It Works</h2>
            <p class="section-subtitle">Simple steps to create positive change in your community</p>
            
            <div class="steps-grid">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Create Account</h3>
                    <p>Sign up as a Citizen, Worker, or Admin. It's quick and easy!</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Report Issues</h3>
                    <p>Describe the problem, add photos, and submit to your area.</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Get Assigned</h3>
                    <p>Issues are prioritized and assigned to qualified workers.</p>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <h3>Track & Support</h3>
                    <p>Follow progress and vote to show community support.</p>
                </div>
                <div class="step">
                    <div class="step-number">5</div>
                    <h3>Resolution</h3>
                    <p>Issues are marked complete with updates and photos.</p>
                </div>
                <div class="step">
                    <div class="step-number">6</div>
                    <h3>Celebrate Impact</h3>
                    <p>Earn recognition as a community champion!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- User Types -->
    <section class="user-types" id="user-types">
        <div class="user-types-container">
            <h2 class="section-title">For Everyone</h2>
            <p class="section-subtitle">Different roles, one mission: building better communities</p>
            
            <div class="types-grid">
                <div class="type-card">
                    <div class="type-icon">👤</div>
                    <h3>Citizens</h3>
                    <p>Report civic issues and participate in your community's improvement. Vote on issues and see real progress.</p>
                    <a href="{{ route('login') }}" class="btn-signup">Sign In as Citizen</a>
                </div>
                <div class="type-card">
                    <div class="type-icon">👷</div>
                    <h3>Workers</h3>
                    <p>Get assigned to civic projects and make a direct impact. Update status and resolve community issues.</p>
                    <a href="{{ route('login') }}" class="btn-signup">Sign In as Worker</a>
                </div>
                <div class="type-card">
                    <div class="type-icon">👨‍💼</div>
                    <h3>Administrators</h3>
                    <p>Manage the platform, assign workers, and oversee operations. (Contact platform admin)</p>
                    <a href="{{ route('login') }}" class="btn-signup">Admin Login</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <h2>Ready to Make a Difference?</h2>
        <p>Join thousands of citizens working together to improve their neighborhoods</p>
        <div class="hero-buttons">
            <a href="{{ route('register') }}" class="btn-primary">Get Started Today</a>
            <a href="{{ route('login') }}" class="btn-secondary">I Already Have an Account</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-links">
                <a href="#features">Features</a>
                <a href="#how-it-works">How It Works</a>
                <a href="#user-types">User Types</a>
                <a href="#privacy">Privacy Policy</a>
                <a href="#contact">Contact</a>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 CivicReport. Empowering communities together.</p>
            </div>
        </div>
    </footer>

    <script>
        // Ensure all navigation links work properly
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    if (this.getAttribute('href').length > 1) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            target.scrollIntoView({ behavior: 'smooth' });
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
