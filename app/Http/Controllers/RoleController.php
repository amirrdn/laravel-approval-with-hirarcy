<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Permission;
use App\Models\Role;
use App\Services\RoleService;

use Inertia\Inertia;
class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }
    public function index()
    {
        $this->authorizePermission('manage roles');
        $roles = Role::with(['children', 'permissions'])->get();
        return Inertia::render('Roles/Index', [
            'csrf_token' => csrf_token(),
            'roles' => $roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorizePermission('manage roles');
        $permissions = Permission::all();
        $role = Role::with('children')->get();
        $classess = $this->roleService;
        return Inertia::render('Roles/Create', [
            'csrf_token' => csrf_token(),
            'permissions' => $permissions,
            'roles' => $role
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizePermission('manage roles');
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create(['name' => $request->name, 'parent' => $request->parent ?? 0]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Role created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorizePermission('manage roles');
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorizePermission('manage roles');
        $role = Role::with('permissions', 'children')->findOrFail($id);
        $permissions = Permission::all();
        $allRoles = Role::with('children')->get();
        return Inertia::render('Roles/Edit', [
            'csrf_token' => csrf_token(),
            'permissions' => $permissions,
            'roles' => $allRoles,
            'role' => $role
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorizePermission('manage roles');
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'permissions' => 'array'
        ]);

        $role->update(['name' => $request->name, 'parent' => $request->parent]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Role updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorizePermission('manage roles');
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted.');
    }

    private function authorizePermission(string $permission)
    {
        if (!auth()->user()->can($permission)) {
            abort(403, 'Unauthorized');
        }
    }
    private function getRoleHierarchy($roles, $parentId = 0, $prefix = '', $excludeId = null)
    {
        $result = [];

        foreach ($roles as $role) {
            if ($role->parent == $parentId && $role->id != $excludeId) {
                $role->display_name = $prefix . $role->name;
                $result[] = $role;

                $children = $this->getRoleHierarchy($roles, $role->id, $prefix . '-- ', $excludeId);
                $result = array_merge($result, $children);
            }
        }

        return $result;
    }
}
