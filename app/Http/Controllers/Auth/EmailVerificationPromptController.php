<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        if ($request->user()->hasVerifiedEmail()) {
            $user = $request->user();
            $targetRoute = 'citizen.index';
            if ($user->role_id == 4) {
                $targetRoute = 'admin.dashboard';
            } elseif ($user->role_id == 2) {
                $targetRoute = 'worker.dashboard';
            }
            return redirect()->intended(route($targetRoute, absolute: false));
        }

        return view('auth.verify-email');
    }
}
