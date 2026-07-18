<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Civic Reporting Platform</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ time() }}">
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
                        <span style="color: #f59e0b; font-weight: bold;">✓</span> Sign Up as a Citizen or Worker
                    </div>
                    <div class="feature">
                        <span style="color: #f59e0b; font-weight: bold;">✓</span> Pinpoint Issues with Area Maps
                    </div>
                    <div class="feature">
                        <span style="color: #f59e0b; font-weight: bold;">✓</span> Help Improve Municipal Services
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

                    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
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

                        <!-- NID Details for Citizens -->
                        <div id="nid-fields-container" style="display: none; background: rgba(13, 148, 136, 0.03); padding: 1.25rem; border-radius: 12px; border: 1px dashed rgba(13, 148, 136, 0.2); margin-bottom: 1.5rem; flex-direction: column; gap: 1.25rem;">
                            <h4 style="font-size: 0.95rem; font-weight: 700; color: #0d9488; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                                🛡️ National Identity (NID) Verification
                            </h4>
                            
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label for="nid_number" style="font-weight: 600;">NID Number *</label>
                                <input 
                                    type="text" 
                                    id="nid_number" 
                                    name="nid_number" 
                                    placeholder="Enter your 10, 13 or 17 digit NID"
                                >
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label style="font-weight: 600; display: block; margin-bottom: 0.5rem;">NID Front Side *</label>
                                    <div style="position: relative; border: 2px dashed rgba(13, 148, 136, 0.3); border-radius: 10px; padding: 0.75rem; text-align: center; cursor: pointer; background: white; transition: all 0.2s;" onclick="document.getElementById('nid_front_photo').click()">
                                        <input type="file" id="nid_front_photo" name="nid_front_photo" accept="image/*" style="display: none;" onchange="previewImage(this, 'nid-front-preview', 'nid-front-preview-placeholder')">
                                        <div id="nid-front-preview-placeholder">
                                            <span style="font-size: 1.5rem;">📸</span>
                                            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">Upload Front</div>
                                        </div>
                                        <img id="nid-front-preview" style="max-width: 100%; max-height: 80px; border-radius: 6px; display: none; object-fit: contain; margin: 0 auto;">
                                    </div>
                                </div>

                                <div class="form-group" style="margin-bottom: 0;">
                                    <label style="font-weight: 600; display: block; margin-bottom: 0.5rem;">NID Back Side *</label>
                                    <div style="position: relative; border: 2px dashed rgba(13, 148, 136, 0.3); border-radius: 10px; padding: 0.75rem; text-align: center; cursor: pointer; background: white; transition: all 0.2s;" onclick="document.getElementById('nid_back_photo').click()">
                                        <input type="file" id="nid_back_photo" name="nid_back_photo" accept="image/*" style="display: none;" onchange="previewImage(this, 'nid-back-preview', 'nid-back-preview-placeholder')">
                                        <div id="nid-back-preview-placeholder">
                                            <span style="font-size: 1.5rem;">📸</span>
                                            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">Upload Back</div>
                                        </div>
                                        <img id="nid-back-preview" style="max-width: 100%; max-height: 80px; border-radius: 6px; display: none; object-fit: contain; margin: 0 auto;">
                                    </div>
                                </div>
                            </div>
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
    <script>
        function previewImage(input, previewId, placeholderId) {
            const preview = document.getElementById(previewId);
            const placeholder = document.getElementById(placeholderId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    placeholder.style.display = 'none';
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '';
                preview.style.display = 'none';
                placeholder.style.display = 'block';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role_id');
            const nidFieldsContainer = document.getElementById('nid-fields-container');
            const nidNumber = document.getElementById('nid_number');
            const nidFront = document.getElementById('nid_front_photo');
            const nidBack = document.getElementById('nid_back_photo');

            function toggleNidFields() {
                // If Citizen role is selected
                if (roleSelect.value === '1') {
                    nidFieldsContainer.style.display = 'flex';
                    nidNumber.required = true;
                    nidFront.required = true;
                    nidBack.required = true;
                } else {
                    nidFieldsContainer.style.display = 'none';
                    nidNumber.required = false;
                    nidFront.required = false;
                    nidBack.required = false;
                }
            }

            roleSelect.addEventListener('change', toggleNidFields);
            // Run on initial load in case of validation errors/old inputs
            toggleNidFields();
        });
    </script>
</body>
</html>
