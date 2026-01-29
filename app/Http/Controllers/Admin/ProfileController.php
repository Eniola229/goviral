<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Traits\LogsAdminActivity;

class ProfileController extends Controller
{
    use LogsAdminActivity;

    /**
     * Show the admin profile
     */
    public function show()
    {
        $admin = auth('admin')->user();

        // Log the view
        $this->logActivity(
            'viewed',
            $admin->name . ' viewed their profile',
            'Admin Profile',
            $admin->id
        );

        return view('admin.profile.show', compact('admin'));
    }

    /**
     * Update admin profile information
     */
    public function update(Request $request)
    {
        $admin = auth('admin')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $admin->id,
        ]);

        $oldData = [
            'name' => $admin->name,
            'email' => $admin->email,
        ];

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Log the update
        $this->logUpdated(
            'Admin Profile',
            $admin->id,
            $admin->name . ' updated their profile information',
            [
                'name' => [
                    'old' => $oldData['name'],
                    'new' => $request->name
                ],
                'email' => [
                    'old' => $oldData['email'],
                    'new' => $request->email
                ]
            ]
        );

        return back()->with('success', 'Profile updated successfully');
    }

    /**
     * Update admin password
     */
    public function updatePassword(Request $request)
    {
        $admin = auth('admin')->user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Update password
        $admin->update([
            'password' => Hash::make($request->new_password),
        ]);

        // Log the password change
        $this->logActivity(
            'password_changed',
            $admin->name . ' changed their password',
            'Admin Profile',
            $admin->id
        );

        return back()->with('success', 'Password updated successfully');
    }
}