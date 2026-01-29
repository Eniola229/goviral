<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminLogged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Traits\LogsAdminActivity;

class AdminController extends Controller
{
    use LogsAdminActivity;

    /**
     * Display all admins (Super Admin & HR only)
     */
    public function index(Request $request)
    {
        if (!auth('admin')->user()->canManageAdmins()) {
            abort(403, 'Unauthorized action.');
        }

        $query = Admin::query();

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Pagination
        $admins = $query->latest()->paginate(20)->withQueryString();

        // Statistics
        $totalAdmins = Admin::count();
        $activeAdmins = Admin::where('status', 'active')->count();
        $inactiveAdmins = Admin::where('status', 'inactive')->count();
        $superAdmins = Admin::where('role', 'super_admin')->count();

        // Log the view
        $this->logActivity(
            'viewed',
            auth('admin')->user()->name . ' viewed admins list',
            'Admin',
            null
        );

        return view('admin.admins.index', compact(
            'admins',
            'totalAdmins',
            'activeAdmins',
            'inactiveAdmins',
            'superAdmins'
        ));
    }

    /**
     * Show create admin form (Super Admin & HR only)
     */
    public function create()
    {
        if (!auth('admin')->user()->canManageAdmins()) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.admins.create');
    }

    /**
     * Store new admin (Super Admin & HR only)
     */
    public function store(Request $request)
    {
        if (!auth('admin')->user()->canManageAdmins()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:super_admin,accountant,hr,admin,manager,support',
            'status' => 'required|in:active,inactive',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
        ]);

        // Log the creation
        $this->logCreated(
            'Admin',
            $admin->id,
            auth('admin')->user()->name . ' created new admin: ' . $admin->name . ' with role: ' . $admin->role
        );

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin created successfully');
    }

    /**
     * Show admin details (Super Admin & HR only)
     */
    public function show($id)
    {
        if (!auth('admin')->user()->canManageAdmins()) {
            abort(403, 'Unauthorized action.');
        }

        $admin = Admin::findOrFail($id);
        
        // Get admin's activity logs (Super Admin only)
        $logs = null;
        if (auth('admin')->user()->canViewAdminLogs()) {
            $logs = AdminLogged::where('admin_id', $admin->id)
                ->latest()
                ->paginate(20);
        }

        // Get admin statistics
        $totalLogins = AdminLogged::where('admin_id', $admin->id)
            ->where('action', 'login')
            ->count();

        $lastLogin = $admin->last_login_at;
        $totalActions = AdminLogged::where('admin_id', $admin->id)->count();

        // Log the view
        $this->logViewed(
            'Admin',
            $admin->id,
            auth('admin')->user()->name . ' viewed admin profile: ' . $admin->name
        );

        return view('admin.admins.show', compact('admin', 'logs', 'totalLogins', 'lastLogin', 'totalActions'));
    }

    /**
     * Show edit admin form (Super Admin & HR only)
     */
    public function edit($id)
    {
        if (!auth('admin')->user()->canManageAdmins()) {
            abort(403, 'Unauthorized action.');
        }

        $admin = Admin::findOrFail($id);
        
        // Prevent editing yourself
        if ($admin->id === auth('admin')->id()) {
            return redirect()->route('admin.admins.show', $id)
                ->with('error', 'You cannot edit your own account from here.');
        }

        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Update admin (Super Admin & HR only)
     */
    public function update(Request $request, $id)
    {
        if (!auth('admin')->user()->canManageAdmins()) {
            abort(403, 'Unauthorized action.');
        }

        $admin = Admin::findOrFail($id);

        // Prevent editing yourself
        if ($admin->id === auth('admin')->id()) {
            return back()->with('error', 'You cannot edit your own account from here.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $id,
            'password' => 'nullable|min:6|confirmed',
            'role' => 'required|in:super_admin,accountant,hr,admin,manager,support',
            'status' => 'required|in:active,inactive',
        ]);

        $oldData = [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => $admin->role,
            'status' => $admin->status,
        ];

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        // Log the update
        $this->logUpdated(
            'Admin',
            $admin->id,
            auth('admin')->user()->name . ' updated admin: ' . $admin->name,
            [
                'name' => ['old' => $oldData['name'], 'new' => $admin->name],
                'email' => ['old' => $oldData['email'], 'new' => $admin->email],
                'role' => ['old' => $oldData['role'], 'new' => $admin->role],
                'status' => ['old' => $oldData['status'], 'new' => $admin->status],
            ]
        );

        return redirect()->route('admin.admins.show', $id)
            ->with('success', 'Admin updated successfully');
    }

    /**
     * Delete admin (Super Admin only)
     */
    public function destroy($id)
    {
        if (!auth('admin')->user()->isSuperAdmin()) {
            abort(403, 'Only Super Admin can delete admins.');
        }

        $admin = Admin::findOrFail($id);

        // Prevent deleting yourself
        if ($admin->id === auth('admin')->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Prevent deleting the last super admin
        if ($admin->isSuperAdmin() && Admin::where('role', 'super_admin')->count() <= 1) {
            return back()->with('error', 'Cannot delete the last Super Admin.');
        }

        $adminData = [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => $admin->role,
        ];

        // Log the deletion
        $this->logDeleted(
            'Admin',
            $admin->id,
            auth('admin')->user()->name . ' deleted admin: ' . $admin->name . ' (' . $admin->role . ')'
        );

        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin deleted successfully');
    }

    /**
     * View admin logs (Super Admin only)
     */
    public function logs(Request $request)
    {
        if (!auth('admin')->user()->canViewAdminLogs()) {
            abort(403, 'Unauthorized action.');
        }

        $query = AdminLogged::with('admin');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('admin', function($adminQuery) use ($search) {
                      $adminQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by action
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        // Filter by admin
        if ($request->has('admin_id') && $request->admin_id) {
            $query->where('admin_id', $request->admin_id);
        }

        // Filter by date
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->latest()->paginate(50)->withQueryString();

        // Get all admins for filter
        $allAdmins = Admin::orderBy('name')->get();

        return view('admin.admins.logs', compact('logs', 'allAdmins'));
    }
}