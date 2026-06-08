<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Civic Reporting Platform</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-container">
        <div class="auth-wrapper">
            <!-- Left Side - Branding -->
            <div class="auth-branding">
                <div class="brand-logo">🏛️</div>
                <h1>CivicReport</h1>
                <p>Empowering Communities Together</p>
                <div class="brand-features">
                    <div class="feature">✓ Report Civic Issues</div>
                    <div class="feature">✓ Track Progress</div>
                    <div class="feature">✓ Build Better Communities</div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="auth-form-container">
                <div class="form-card">
                    <h2>Sign In</h2>
                    <p class="form-subtitle">Access your civic reporting account</p>

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

                    <form action="{{ route('login') }}" method="POST" class="login-form">
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
                            @error('email')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
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
                            @error('password')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group remember-forgot">
                            <label class="checkbox-group">
                                <input type="checkbox" name="remember" id="remember">
                                <span>Remember me</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-login">Sign In</button>
                    </form>

                    <div class="divider">Or</div>

                    <p class="signup-prompt">
                        New to CivicReport? 
                        <a href="{{ route('register') }}" class="signup-link">Create an account</a>
                    </p>
                </div>

                <!-- Role Info -->
                <div class="role-info">
                    <h3>Account Types</h3>
                    <div class="role-card">
                        <span class="role-icon">👤</span>
                        <div>
                            <strong>Citizen</strong>
                            <p>Report issues and support solutions</p>
                        </div>
                    </div>
                    <div class="role-card">
                        <span class="role-icon">👷</span>
                        <div>
                            <strong>Worker</strong>
                            <p>Work on assigned issues</p>
                        </div>
                    </div>
                    <div class="role-card">
                        <span class="role-icon">👨‍💼</span>
                        <div>
                            <strong>Admin</strong>
                            <p>Manage platform & assignments</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
