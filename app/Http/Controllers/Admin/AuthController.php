<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\LogsAdminActivity;

class AuthController extends Controller
{
    use LogsAdminActivity;

    /**
     * Show admin login form
     */
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $admin = Auth::guard('admin')->user();
            
            // Check if admin is active
            if (!$admin->isActive()) {
                Auth::guard('admin')->logout();
                return back()->with('error', 'Your account has been deactivated. Please contact the administrator.');
            }
            
            // Update last login
            $admin->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            // Log the login
            $this->logLogin();

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome back, ' . $admin->name);
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Invalid email or password');
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        // Log the logout before actually logging out
        $this->logLogout();

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully');
    }
}