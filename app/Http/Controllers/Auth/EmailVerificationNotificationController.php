<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
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

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
