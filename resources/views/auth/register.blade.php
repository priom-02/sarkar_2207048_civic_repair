<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Civic Reporting Platform</title>
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

            <!-- Right Side - Register Form -->
            <div class="auth-form-container">
                <div class="form-card">
                    <h2>Create Account</h2>
                    <p class="form-subtitle">Join our civic reporting community</p>

                    @if ($errors->any())
                        <div class="alert alert-error">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST" class="register-form">
                        @csrf

                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input 
                                type="text" 
                                id="full_name" 
                                name="full_name" 
                                placeholder="John Doe"
                                value="{{ old('full_name') }}"
                                required
                                autofocus
                            >
                            @error('full_name')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                placeholder="you@example.com"
                                value="{{ old('email') }}"
                                required
                            >
                            @error('email')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number (Optional)</label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                placeholder="+1 (555) 000-0000"
                                value="{{ old('phone') }}"
                            >
                            @error('phone')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="role_id">Account Type</label>
                            <select id="role_id" name="role_id" required>
                                <option value="">Select account type</option>
                                <option value="1">Citizen - Report and support issues</option>
                                <option value="2">Worker - Work on civic projects</option>
                            </select>
                            @error('role_id')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                            <small>Note: Admin accounts are created by administrators only</small>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                placeholder="Minimum 8 characters"
                                required
                            >
                            @error('password')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                placeholder="Confirm your password"
                                required
                            >
                        </div>

                        <label class="checkbox-group accept-terms">
                            <input type="checkbox" name="accept_terms" required>
                            <span>I agree to the <a href="#">Terms & Conditions</a></span>
                        </label>

                        <button type="submit" class="btn btn-login">Create Account</button>
                    </form>

                    <p class="signin-prompt">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="signin-link">Sign in here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
