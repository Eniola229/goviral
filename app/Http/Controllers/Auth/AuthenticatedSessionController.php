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
        
        // 2. Regenerate session
        $request->session()->regenerate();
        
        // 3. Get authenticated user
        $user = Auth::user();
        
        if ($user) {
            try {
                // Send login notification
                $user->notify(new UserLoggedIn($user));
                Log::info('Login notification sent for user: ' . $user->id);
            } catch (\Exception $e) {
                // Log error but don't break the login flow
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