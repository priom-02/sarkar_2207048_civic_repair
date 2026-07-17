<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Civic Reporting Platform</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ time() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-wrapper">
            <!-- Left Side - Branding -->
            <div class="auth-branding">
                <div class="logo-icon-container-auth">
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
                <h1 class="logo-text-auth">civic <span style="color: #f59e0b;">report</span></h1>
                <p>Empowering Communities Together</p>
                
                <div class="brand-features">
                    <div class="feature">
                        <span style="color: #f59e0b; font-weight: bold;">✓</span> Report Civic Issues Easily
                    </div>
                    <div class="feature">
                        <span style="color: #f59e0b; font-weight: bold;">✓</span> Track Resolutions Live
                    </div>
                    <div class="feature">
                        <span style="color: #f59e0b; font-weight: bold;">✓</span> Mobilize Community Action
                    </div>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="auth-form-container">
                <div class="form-card">
                    <h2>Sign In</h2>
                    <p class="form-subtitle">Welcome back! Please enter your details.</p>

                    @if ($errors->any())
                        <div class="alert alert-error">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                placeholder="you@example.com"
                                value="{{ old('email') }}"
                                required
                                autofocus
                            >
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                placeholder="••••••••"
                                required
                            >
                        </div>

                        <div class="remember-forgot">
                            <label class="checkbox-group">
                                <input type="checkbox" name="remember" id="remember">
                                <span>Remember me</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a class="forgot-password-link" href="{{ route('password.request') }}" style="color: var(--secondary-color); text-decoration: none;">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="btn-login">Sign In</button>
                    </form>

                    <div class="divider">Or</div>

                    <p class="signup-prompt">
                        New to CivicReport? 
                        <a href="{{ route('register') }}" class="signup-link">Create an account</a>
                    </p>

                    <!-- Autofill Helper Box -->
                    <div class="autofill-helper-box">
                        <div class="autofill-helper-title">Demo Accounts Autofill</div>
                        <div class="autofill-buttons">
                            <button type="button" class="autofill-btn" onclick="autofill('admin@civicplatform.bd', 'Admin@1234')">Admin</button>
                            <button type="button" class="autofill-btn" onclick="autofill('fatema@gmail.com', 'Password@123')">Citizen</button>
                            <button type="button" class="autofill-btn" onclick="autofill('rahim.worker@civicplatform.bd', 'Worker@1234')">Worker</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function autofill(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
