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
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:150', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'role_id' => ['required', 'integer', 'in:1,2'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'accept_terms' => ['required', 'accepted'],
        ];

        if ($request->role_id == 1) {
            $rules['nid_number'] = ['required', 'string', 'regex:/^(\d{10}|\d{13}|\d{17})$/'];
            $rules['nid_front_photo'] = ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'];
            $rules['nid_back_photo'] = ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'];
        }

        $request->validate($rules, [
            'nid_number.regex' => 'The NID number must be exactly 10, 13, or 17 digits.',
        ]);

        $userData = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ];

        if ($request->role_id == 1) {
            $userData['nid_number'] = $request->nid_number;
            $userData['nid_verified'] = 'pending';

            if ($request->hasFile('nid_front_photo')) {
                $path = $request->file('nid_front_photo')->store('nid_photos', 'public');
                $userData['nid_front_photo'] = asset('storage/' . $path);
            }

            if ($request->hasFile('nid_back_photo')) {
                $path = $request->file('nid_back_photo')->store('nid_photos', 'public');
                $userData['nid_back_photo'] = asset('storage/' . $path);
            }
        }

        $user = User::create($userData);

        event(new Registered($user));

        Auth::login($user);

        if ($user->role_id == 2) {
            return redirect()->route('worker.dashboard');
        }

        return redirect()->route('citizen.index');
    }
}
