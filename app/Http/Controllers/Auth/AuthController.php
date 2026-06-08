<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Check if user account is active
        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->is_active) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Account not found or inactive.']);
        }

        // Attempt authentication
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Redirect based on role
            $user = Auth::user();
            return $this->redirectBasedOnRole($user);
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['password' => 'Invalid credentials.']);
    }

    /**
     * Show the registration form.
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role_id' => ['required', 'integer', 'in:1,2'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'accept_terms' => ['required', 'accepted'],
        ]);

        // Create new user
        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => $request->role_id, // 1 = Citizen, 2 = Worker
            'password' => Hash::make($request->password),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        event(new Registered($user));

        // Log the user in
        Auth::login($user);

        // Redirect based on role - use to() instead of intended()
        return redirect()->to($this->redirectPath($user));
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Redirect user based on their role.
     */
    private function redirectBasedOnRole(User $user): RedirectResponse
    {
        if ($user->role_id == 4) { // Admin
            return redirect()->route('admin.dashboard');
        } elseif ($user->role_id == 2) { // Worker
            return redirect()->route('worker.dashboard');
        } else { // Citizen (role_id == 1)
            return redirect()->route('citizen.index');
        }
    }

    /**
     * Get the path for redirecting after registration.
     */
    private function redirectPath(User $user): string
    {
        if ($user->role_id == 4) {
            return route('admin.dashboard');
        } elseif ($user->role_id == 2) {
            return route('worker.dashboard');
        }

        return route('citizen.index');
    }
}
