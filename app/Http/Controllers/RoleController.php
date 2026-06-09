<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Constructor - Apply auth and admin middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
        // Remove ->only() or use it correctly - in Laravel 12, this syntax works
        // If you still get error, use the alternative below
        $this->middleware('admin');
    }

    /**
     * Display a listing of roles.
     */
    public function index()
    {
        $roles = Role::withCount('users')->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|max:50|unique:roles,role_name'
        ]);

        Role::create([
            'role_name' => strtolower($request->role_name)
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified role.
     */
    public function show($id)
    {
        $role = Role::withCount('users')->findOrFail($id);
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        // Prevent editing system roles
        if (in_array($role->role_name, ['admin', 'teacher', 'student'])) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'System roles cannot be edited.');
        }

        $request->validate([
            'role_name' => 'required|string|max:50|unique:roles,role_name,' . $id . ',role_id'
        ]);

        $role->update([
            'role_name' => strtolower($request->role_name)
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Prevent deleting system roles
        if (in_array($role->role_name, ['admin', 'teacher', 'student'])) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'System roles cannot be deleted.');
        }

        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot delete role with associated users.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
