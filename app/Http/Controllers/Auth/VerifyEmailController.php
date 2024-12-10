<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\MerchantRegistered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $user = User::find($user->id);
        if ($request->user()->hasVerifiedEmail()) {
            $user->notify(new MerchantRegistered($user));
            return redirect()->intended(route('admin_or_merchant.dashboard', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        $user->notify(new MerchantRegistered($user));
        return redirect()->intended(route('admin_or_merchant.dashboard', absolute: false).'?verified=1');
    }
}
