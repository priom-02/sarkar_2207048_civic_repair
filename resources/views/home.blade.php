<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CivicReport - Empower Your Neighborhood</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ==========================================================================
           PREMIUM LANDING PAGE CSS
           ========================================================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #0d9488; /* Spruce Teal */
            --primary-glow: rgba(13, 148, 136, 0.15);
            --secondary: #d97706; /* Amber Gold Accent */
            --bg-dark: #f8fafc; /* Clean Light Slate Background */
            --text-light: #0f172a; /* Slate 900 Main Text */
            --text-gray: #475569; /* Slate 600 Muted Text */
            --glass-bg: #ffffff;
            --glass-border: rgba(0, 0, 0, 0.08);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-light);
            line-height: 1.6;
            overflow-x: hidden;
            position: relative;
        }

        /* Abstract Aurora Glows */
        body::before {
            content: '';
            position: absolute;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(13, 148, 136, 0.08) 0%, rgba(248, 250, 252, 0) 70%);
            top: -10%;
            left: -10%;
            pointer-events: none;
            z-index: 0;
        }

        body::after {
            content: '';
            position: absolute;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(217, 119, 6, 0.05) 0%, rgba(248, 250, 252, 0) 70%);
            top: 40%;
            right: -20%;
            pointer-events: none;
            z-index: 0;
        }

        /* Floating Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }

        /* Navigation */
        nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            padding: 1.1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
            transition: all 0.3s;
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
            gap: 0.75rem;
            cursor: pointer;
            text-decoration: none;
        }

        .logo-icon-container {
            width: 36px;
            height: 36px;
        }

        .custom-logo-svg {
            width: 100%;
            height: 100%;
        }

        .logo-text {
            font-size: 1.6rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.04em;
            text-transform: lowercase;
        }

        .logo-text-accent {
            color: var(--primary);
        }

        .nav-links {
            display: flex;
            gap: 2.25rem;
            align-items: center;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-gray);
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn-login {
            padding: 0.65rem 1.4rem;
            border: 1px solid var(--glass-border);
            background: rgba(0,0,0,0.02);
            color: #0f172a;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.92rem;
            transition: all 0.3s;
            text-decoration: none;
        }

        .btn-login:hover {
            background: rgba(0,0,0,0.06);
            border-color: rgba(0,0,0,0.15);
        }

        .btn-signup {
            padding: 0.65rem 1.4rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.92rem;
            transition: all 0.3s;
            text-decoration: none;
            box-shadow: 0 4px 15px var(--primary-glow);
        }

        .btn-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13, 148, 136, 0.3);
        }

        /* Hero Section */
        .hero {
            padding: 7rem 2rem 6rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 4rem;
            align-items: center;
            position: relative;
            z-index: 10;
        }

        .hero-content {
            text-align: left;
        }

         .hero h1 {
            font-size: 3.8rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            letter-spacing: -0.04em;
            background: linear-gradient(to right, #0f172a, #0d9488, #d97706);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.15rem;
            color: var(--text-gray);
            margin-bottom: 2.5rem;
            max-width: 580px;
        }

        .hero-buttons {
            display: flex;
            gap: 1.25rem;
        }

        .btn-hero-primary {
            padding: 0.9rem 2rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 4px 20px var(--primary-glow);
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(13, 148, 136, 0.4);
        }

        .btn-hero-secondary {
            padding: 0.9rem 2rem;
            border: 1px solid var(--glass-border);
            background: rgba(0,0,0,0.02);
            color: #0f172a;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-hero-secondary:hover {
            background: rgba(0,0,0,0.05);
            border-color: rgba(0,0,0,0.15);
        }

        /* Mockup Panel styling */
        .hero-visual {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .monitor-mockup {
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.06);
            width: 100%;
            max-width: 440px;
            animation: float 6s ease-in-out infinite;
        }

        /* Features Section (Bento Grid) */
        .features {
            padding: 7rem 2rem;
            background: #ffffff;
            border-top: 1px solid var(--glass-border);
            position: relative;
            z-index: 10;
        }

        .features-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 5rem;
        }

        .section-title {
            font-size: 2.75rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 1rem;
            background: linear-gradient(to right, #0f172a, #334155);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: var(--text-gray);
            max-width: 600px;
            margin: 0 auto;
        }

        .bento-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.75rem;
        }

        .bento-card {
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 20px;
            padding: 2.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            min-height: 250px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        }

        .bento-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0) 40%, rgba(255, 255, 255, 0.85) 100%);
            z-index: 1;
        }

        .bento-card:hover {
            transform: translateY(-5px);
            border-color: rgba(13, 148, 136, 0.35);
            box-shadow: 0 15px 35px rgba(13, 148, 136, 0.1);
        }

        .bento-card.wide {
            grid-column: span 2;
        }

        .bento-card.tall {
            grid-row: span 2;
            min-height: 520px;
        }

        .bento-content {
            position: relative;
            z-index: 10;
        }

        .bento-card h3 {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #0f172a;
        }

        .bento-card p {
            font-size: 0.95rem;
            color: var(--text-gray);
            line-height: 1.6;
        }

        .bento-badge {
            position: absolute;
            top: 2rem;
            right: 2rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.3rem 0.75rem;
            background: rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.08);
            border-radius: 9999px;
            color: #0f172a;
            z-index: 10;
        }

        /* How It Works Section */
        .how-it-works {
            padding: 7rem 2rem;
            background: var(--bg-dark);
            border-top: 1px solid var(--glass-border);
            position: relative;
            z-index: 10;
        }

        .how-it-works-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .steps-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 3rem;
            margin-top: 4rem;
        }

        .step-item {
            position: relative;
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.08);
            padding: 2.5rem 2rem;
            border-radius: 20px;
            transition: all 0.3s;
        }

        .step-item:hover {
            background: #ffffff;
            border-color: rgba(217, 119, 6, 0.35);
            box-shadow: 0 10px 25px rgba(217, 119, 6, 0.08);
        }

        .step-badge {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1.75rem;
            box-shadow: 0 4px 15px rgba(13, 148, 136, 0.3);
        }

        .step-item h3 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #0f172a;
        }

        .step-item p {
            font-size: 0.95rem;
            color: var(--text-gray);
            line-height: 1.6;
        }

        /* User Roles / For Everyone Section */
        .roles {
            padding: 7rem 2rem;
            background: #ffffff;
            border-top: 1px solid var(--glass-border);
            position: relative;
            z-index: 10;
        }

        .roles-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .roles-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2.25rem;
            margin-top: 4rem;
        }

        .role-card {
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        }

        .role-card:hover {
            transform: scale(1.02);
            background: #ffffff;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
        }

        .role-card.citizen:hover { border-color: rgba(217, 119, 6, 0.35); }
        .role-card.worker:hover { border-color: rgba(13, 148, 136, 0.35); }
        .role-card.admin:hover { border-color: rgba(217, 119, 6, 0.35); }

        .role-icon-placeholder {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            background: rgba(0,0,0,0.02);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            color: #0f172a;
            border: 1px solid rgba(0,0,0,0.08);
        }

        .role-card.citizen .role-icon-placeholder { background: rgba(217, 119, 6, 0.08); border-color: rgba(217, 119, 6, 0.2); color: var(--secondary); }
        .role-card.worker .role-icon-placeholder { background: rgba(13, 148, 136, 0.08); border-color: rgba(13, 148, 136, 0.2); color: var(--primary); }
        .role-card.admin .role-icon-placeholder { background: rgba(217, 119, 6, 0.08); border-color: rgba(217, 119, 6, 0.2); color: var(--secondary); }

        .role-card h3 {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #0f172a;
        }

        .role-card p {
            font-size: 0.95rem;
            color: var(--text-gray);
            line-height: 1.6;
            margin-bottom: 2rem;
            min-height: 75px;
        }

        /* CTA Section */
        .cta {
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
            border-top: 1px solid var(--glass-border);
            padding: 6rem 2rem;
            text-align: center;
            position: relative;
            z-index: 10;
        }

        .cta-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .cta h2 {
            font-size: 3rem;
            font-weight: 800;
            letter-spacing: -0.04em;
            margin-bottom: 1.5rem;
            color: white;
        }

        .cta p {
            font-size: 1.15rem;
            color: var(--text-gray);
            margin-bottom: 2.5rem;
        }

        /* Footer */
        footer {
            background: #05080f;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding: 4rem 2rem;
            text-align: center;
            position: relative;
            z-index: 10;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2.5rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        .footer-links a {
            color: var(--text-gray);
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: white;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 2rem;
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #475569;
        }

        /* Mobile Layouts */
        @media (max-width: 968px) {
            .hero {
                grid-template-columns: 1fr;
                padding-top: 5rem;
                gap: 3rem;
                text-align: center;
            }
            .hero h1 {
                font-size: 2.8rem;
            }
            .hero p {
                margin: 0 auto 2.5rem auto;
            }
            .hero-buttons {
                justify-content: center;
            }
            .bento-grid {
                grid-template-columns: 1fr;
            }
            .bento-card.wide, .bento-card.tall {
                grid-column: span 1;
                grid-row: span 1;
                min-height: 250px;
            }
            .steps-container, .roles-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <a href="#" class="logo">
                <div class="logo-icon-container">
                    <svg class="custom-logo-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <!-- Document sheet -->
                        <path d="M 52,32 L 72,32 C 74,32 76,34 77,35 L 85,43 C 86,44 87,46 87,48 L 87,78 C 87,81 84,84 81,84 L 52,84" fill="none" stroke="#10b981" stroke-width="7" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M 72,32 L 72,44 C 72,46 74,48 76,48 L 87,48" fill="none" stroke="#10b981" stroke-width="7" stroke-linecap="round" />
                        <!-- Lines on document -->
                        <line x1="61" y1="58" x2="76" y2="58" stroke="#ffffff" stroke-width="6" stroke-linecap="round" />
                        <line x1="61" y1="66" x2="76" y2="66" stroke="#ffffff" stroke-width="6" stroke-linecap="round" />
                        <line x1="61" y1="74" x2="76" y2="74" stroke="#ffffff" stroke-width="6" stroke-linecap="round" />
                        <!-- Speech bubble -->
                        <path d="M 45,16 C 24,16 8,30 8,48 C 8,58 13,67 21,73 L 18,85 L 30,79 C 35,81 40,82 45,82 C 66,82 82,68 82,48 C 82,30 66,16 45,16 Z" fill="none" stroke="#ffffff" stroke-width="8" stroke-linecap="round" stroke-linejoin="round" />
                        <!-- Buildings inside speech bubble -->
                        <path d="M 21,72 L 21,53 L 29,43 L 29,72 Z" fill="#34d399" />
                        <path d="M 31,72 L 31,37 L 41,26 L 41,72 Z" fill="#10b981" />
                        <path d="M 43,72 L 43,47 L 51,37 L 51,72 Z" fill="#047857" />
                    </svg>
                </div>
                <span class="logo-text">civic <span class="logo-text-accent">report</span></span>
            </a>
            <ul class="nav-links">
                <li><a href="#how-it-works">How It Works</a></li>
                <li><a href="#user-types">For You</a></li>
            </ul>
            <div class="auth-buttons">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-signup">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-login">Sign In</a>
                    <a href="{{ route('register') }}" class="btn-signup">Get Started</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Empower Your Neighborhood</h1>
            <p>Report local infrastructure issues, upvote resident complaints, track worker order assignments, and build a safer, cleaner community together.</p>
            <div class="hero-buttons">
                <a href="{{ route('register') }}" class="btn-hero-primary">Start Reporting Now</a>
                <a href="#how-it-works" class="btn-hero-secondary">How It Works</a>
            </div>
        </div>

        <div class="hero-visual">
            <!-- Simulated Live Platform Monitor Panel -->
            <div class="monitor-mockup">
                <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(0,0,0,0.08); padding-bottom: 1rem; margin-bottom: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="width: 10px; height: 10px; background: #0d9488; border-radius: 50%; display: inline-block; box-shadow: 0 0 10px rgba(13, 148, 136, 0.5); animation: pulse 2s infinite;"></span>
                        <span style="font-weight: 700; font-size: 0.85rem; color: #0f172a; text-transform: uppercase; letter-spacing: 0.05em;">Live City Monitor</span>
                    </div>
                    <span style="background: rgba(13, 148, 136, 0.1); color: #0d9488; font-size: 0.72rem; font-weight: 700; padding: 0.25rem 0.65rem; border-radius: 9999px; border: 1px solid rgba(13, 148, 136, 0.2);">ACTIVE</span>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem;">
                    <div style="background: rgba(0,0,0,0.01); border: 1px solid rgba(0,0,0,0.08); border-radius: 12px; padding: 1rem;">
                        <span style="display: block; font-size: 1.5rem; margin-bottom: 0.25rem;">📈</span>
                        <span style="font-size: 1.25rem; font-weight: 800; color: #0f172a; display: block;">{{ $resolutionRate }}%</span>
                        <span style="font-size: 0.72rem; color: var(--text-gray); font-weight: 600; text-transform: uppercase; letter-spacing: 0.03em;">Resolution Rate</span>
                    </div>
                    <div style="background: rgba(0,0,0,0.01); border: 1px solid rgba(0,0,0,0.08); border-radius: 12px; padding: 1rem;">
                        <span style="display: block; font-size: 1.5rem; margin-bottom: 0.25rem;">⚡</span>
                        <span style="font-size: 1.25rem; font-weight: 800; color: #0f172a; display: block;">{{ $avgResponseTime }}</span>
                        <span style="font-size: 0.72rem; color: var(--text-gray); font-weight: 600; text-transform: uppercase; letter-spacing: 0.03em;">Avg Response</span>
                    </div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 0.75rem; letter-spacing: 0.05em;">Live Feed Activity</div>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @forelse($recentActivities as $activity)
                            <div style="display: flex; gap: 0.75rem; align-items: flex-start; font-size: 0.82rem;">
                                <span style="font-size: 0.72rem; color: var(--text-gray); font-weight: 500; min-width: 50px;">{{ $activity['time'] }}</span>
                                <span style="color: #334155; font-weight: 600;">{{ $activity['text'] }}</span>
                            </div>
                        @empty
                            <div style="font-size: 0.82rem; color: var(--text-gray); font-style: italic; padding-left: 5px;">No recent activities logged yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works" id="how-it-works">
        <div class="how-it-works-container">
            <div class="section-header">
                <h2 class="section-title">Simple Operations Flow</h2>
                <p class="section-subtitle">How our platform connects municipal boards, workers, and citizens seamlessly.</p>
            </div>

            <div class="steps-container">
                <div class="step-item">
                    <div class="step-badge">1</div>
                    <h3>Report Issue</h3>
                    <p>Citizens file complaints detailing categories, coordinates, and photo evidence.</p>
                </div>
                <div class="step-item">
                    <div class="step-badge">2</div>
                    <h3>Dispatch Workers</h3>
                    <p>Administrators check analytics hotspots and assign work orders to designated operations staff.</p>
                </div>
                <div class="step-item">
                    <div class="step-badge">3</div>
                    <h3>Verify Resolution</h3>
                    <p>Workers resolve the tasks, upload evidence, and update status timelines for community review.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- User Types / For Everyone Section -->
    <section class="roles" id="user-types">
        <div class="roles-container">
            <div class="section-header">
                <h2 class="section-title">Tailored Portals for Everyone</h2>
                <p class="section-subtitle">Custom modules built to empower every stakeholder role in public improvements.</p>
            </div>

            <div class="roles-grid">
                <!-- Role Card 1 -->
                <div class="role-card citizen">
                    <div class="role-icon-placeholder">👤</div>
                    <h3>Citizens Portal</h3>
                    <p>Submit complaints, upvote local reports, discuss resolutions, and track progress timelines from your personalized space.</p>
                    <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="btn-signup" style="width: 100%; text-align: center;">{{ auth()->check() ? 'Go to Citizen Portal' : 'Sign In as Citizen' }}</a>
                </div>

                <!-- Role Card 2 -->
                <div class="role-card worker">
                    <div class="role-icon-placeholder">🔧</div>
                    <h3>Worker Portal</h3>
                    <p>Access assigned city work orders, navigate maps, update progress, and submit photo confirmation logs.</p>
                    <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="btn-signup" style="width: 100%; text-align: center;">{{ auth()->check() ? 'Go to Worker Portal' : 'Sign In as Worker' }}</a>
                </div>

                <!-- Role Card 3 -->
                <div class="role-card admin">
                    <div class="role-icon-placeholder">💼</div>
                    <h3>Admin Console</h3>
                    <p>Monitor platform statistics, manage complaint categories, dispatch workers, and suspend or activate accounts.</p>
                    <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="btn-signup" style="width: 100%; text-align: center;">{{ auth()->check() ? 'Go to Admin Console' : 'Sign In as Admin' }}</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="cta-container">
            <h2>Ready to Improve Your Neighborhood?</h2>
            <p>Join citizens and city workers collaborating in real-time to build better, safer municipalities today.</p>
            <div class="hero-buttons">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-hero-primary" style="width: 100%; max-width: 300px; text-align: center; margin: 0 auto;">Go to Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="btn-hero-primary">Create Your Account</a>
                    <a href="{{ route('login') }}" class="btn-hero-secondary">Sign In Here</a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-links">
            <a href="#features">Features</a>
            <a href="#how-it-works">How It Works</a>
            <a href="#user-types">User Types</a>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 CivicReport. Built to support municipal governance.</p>
        </div>
    </footer>

    <script>
        // Smooth scrolling for anchor links
        document.addEventListener('DOMContentLoaded', function() {
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
