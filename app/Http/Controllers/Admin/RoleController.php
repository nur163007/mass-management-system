<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        // Only Super Admin can manage roles
        if (session('role') != 1) {
            return redirect()->route('dashboard')->with('error', 'Only Super Admin can access Role Management.');
        }

        $roles = Role::withCount('members')->orderBy('id', 'asc')->get();
        return view('admin.role.index', compact('roles'));
    }

    public function create()
    {
        if (session('role') != 1) {
            return redirect()->route('dashboard')->with('error', 'Only Super Admin can access Role Management.');
        }

        return view('admin.role.create');
    }

    public function store(Request $request)
    {
        if (session('role') != 1) {
            return redirect()->route('dashboard')->with('error', 'Only Super Admin can create roles.');
        }

        $this->validate($request, [
            'name' => 'required|string|max:100|unique:roles,name',
            'slug' => 'nullable|string|max:100|unique:roles,slug',
            'description' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        $slug = $request->slug ? Str::slug($request->slug, '_') : Str::slug($request->name, '_');

        Role::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.role.index')->with('success', 'Role created successfully.');
    }

    public function edit($id)
    {
        if (session('role') != 1) {
            return redirect()->route('dashboard')->with('error', 'Only Super Admin can edit roles.');
        }

        $role = Role::findOrFail($id);
        return view('admin.role.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        if (session('role') != 1) {
            return redirect()->route('dashboard')->with('error', 'Only Super Admin can update roles.');
        }

        $role = Role::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:100|unique:roles,name,' . $id,
            'slug' => 'nullable|string|max:100|unique:roles,slug,' . $id,
            'description' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        // Prevent modifying slug of default core roles
        $slug = in_array($role->id, [1, 2, 3]) 
            ? $role->slug 
            : ($request->slug ? Str::slug($request->slug, '_') : Str::slug($request->name, '_'));

        $role->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.role.index')->with('success', 'Role updated successfully.');
    }

    public function destroy($id)
    {
        if (session('role') != 1) {
            return redirect()->route('dashboard')->with('error', 'Only Super Admin can delete roles.');
        }

        // Protect default core system roles (1: Super Admin, 2: Manager, 3: User)
        if (in_array($id, [1, 2, 3])) {
            return redirect()->route('admin.role.index')->with('error', 'Core system roles (Super Admin, Manager, User) cannot be deleted.');
        }

        $role = Role::findOrFail($id);

        // Check if members exist with this role
        if ($role->members()->count() > 0) {
            return redirect()->route('admin.role.index')->with('error', 'Cannot delete role because members are currently assigned to it.');
        }

        $role->delete();

        return redirect()->route('admin.role.index')->with('success', 'Role deleted successfully.');
    }
}
