<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Civic Reporting Platform</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-container" style="max-width: 1100px;">
        <div class="auth-wrapper">
            <!-- Left Side - Branding -->
            <div class="auth-branding">
                <div class="logo-icon-container-auth">
                    <svg class="custom-logo-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <!-- Document sheet -->
                        <path d="M 52,32 L 72,32 C 74,32 76,34 77,35 L 85,43 C 86,44 87,46 87,48 L 87,78 C 87,81 84,84 81,84 L 52,84" fill="none" stroke="#e0f2fe" stroke-width="7" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M 72,32 L 72,44 C 72,46 74,48 76,48 L 87,48" fill="none" stroke="#e0f2fe" stroke-width="7" stroke-linecap="round" />
                        <!-- Lines on document -->
                        <line x1="61" y1="58" x2="76" y2="58" stroke="#ffffff" stroke-width="6" stroke-linecap="round" />
                        <line x1="61" y1="66" x2="76" y2="66" stroke="#ffffff" stroke-width="6" stroke-linecap="round" />
                        <line x1="61" y1="74" x2="76" y2="74" stroke="#ffffff" stroke-width="6" stroke-linecap="round" />
                        <!-- Speech bubble -->
                        <path d="M 45,16 C 24,16 8,30 8,48 C 8,58 13,67 21,73 L 18,85 L 30,79 C 35,81 40,82 45,82 C 66,82 82,68 82,48 C 82,30 66,16 45,16 Z" fill="none" stroke="#ffffff" stroke-width="8" stroke-linecap="round" stroke-linejoin="round" />
                        <!-- Buildings inside speech bubble -->
                        <path d="M 21,72 L 21,53 L 29,43 L 29,72 Z" fill="#93c5fd" />
                        <path d="M 31,72 L 31,37 L 41,26 L 41,72 Z" fill="#e0f2fe" />
                        <path d="M 43,72 L 43,47 L 51,37 L 51,72 Z" fill="#60a5fa" />
                    </svg>
                </div>
                <h1 class="logo-text-auth">civic <span style="color: #06b6d4;">report</span></h1>
                <p>Empowering Communities Together</p>
                
                <div class="brand-features">
                    <div class="feature">
                        <span style="color: #22d3ee; font-weight: bold;">✓</span> Sign Up as a Citizen or Worker
                    </div>
                    <div class="feature">
                        <span style="color: #22d3ee; font-weight: bold;">✓</span> Pinpoint Issues with Area Maps
                    </div>
                    <div class="feature">
                        <span style="color: #22d3ee; font-weight: bold;">✓</span> Help Improve Municipal Services
                    </div>
                </div>
            </div>

            <!-- Right Side - Registration Form -->
            <div class="auth-form-container">
                <div class="form-card">
                    <h2>Create Account</h2>
                    <p class="form-subtitle">Join our civic reporting community today</p>

                    @if ($errors->any())
                        <div class="alert alert-error">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST">
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
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number (Optional)</label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                placeholder="+8801XXXXXXXXX"
                                value="{{ old('phone') }}"
                            >
                        </div>

                        <div class="form-group">
                            <label for="role_id">Account Type</label>
                            <select id="role_id" name="role_id" required>
                                <option value="">Select account type</option>
                                <option value="1" {{ old('role_id') == 1 ? 'selected' : '' }}>Citizen - Report and vote on issues</option>
                                <option value="2" {{ old('role_id') == 2 ? 'selected' : '' }}>Worker - Complete public works assignments</option>
                            </select>
                            <small>Note: Admin accounts can only be created by existing administrators.</small>
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

                        <button type="submit" class="btn-login">Create Account</button>
                    </form>

                    <p class="signin-prompt" style="margin-top: 1.5rem;">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="signin-link">Sign in here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
