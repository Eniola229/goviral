<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Notifications\UserLoggedIn;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Authenticate user
        $request->authenticate();

        // 2. Check if user is blocked BEFORE regenerating session
        $user = Auth::user();

        if ($user && $user->status === 'BLOCK') {
            // Log them back out immediately
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Your account has been blocked. Please contact support.',
            ])->onlyInput('email');
        }

        // 3. Regenerate session
        $request->session()->regenerate();

        // 4. Send login notification
        if ($user) {
            try {
                $user->notify(new UserLoggedIn($user));
            } catch (\Exception $e) {
                Log::error('Failed to send login notification: ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());
            }
        }

        return redirect()->intended(route('dashboard'));
    }
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}