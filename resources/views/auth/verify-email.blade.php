<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Civic Reporting Platform</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-container">
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
                    <div class="feature">✓ Report Civic Issues</div>
                    <div class="feature">✓ Track Progress</div>
                    <div class="feature">✓ Build Better Communities</div>
                </div>
            </div>

            <!-- Right Side - Verification Notice Form -->
            <div class="auth-form-container">
                <div class="form-card">
                    <h2>Verify Your Email</h2>
                    <p class="form-subtitle" style="margin-bottom: 1.5rem; line-height: 1.5;">
                        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                    </p>

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success" style="margin-bottom: 1.5rem;">
                            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                        </div>
                    @endif

                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-login" style="width: 100%;">
                                {{ __('Resend Verification Email') }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}" style="text-align: center;">
                            @csrf
                            <button type="submit" style="background: none; border: none; color: #ef4444; text-decoration: underline; cursor: pointer; font-size: 0.875rem; font-weight: 500;">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
