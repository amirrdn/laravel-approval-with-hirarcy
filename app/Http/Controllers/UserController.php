<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Str;

use App\Services\RoleService;
use App\Services\UserService;
use App\Actions\UserActions;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use Yajra\DataTables\Facades\DataTables;


class UserController extends Controller
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
        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = $this->roleService->AllRoles();
        $classess = $this->roleService;
        return view('users.create', compact('roles','classess'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, UserActions $createUser)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'roles' => 'array',
            'position' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'is_active' => 'required|boolean',
        ]);

        $roleId = $this->roleService->RoleByName($request->role);
        $request->merge([
            'roleId' => $roleId->id
        ]);

        $createUser->storeUser($request);

        return redirect()->route('users.index')->with('success', 'User created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, UserService $userservice)
    {
        $roles = $this->roleService->AllRoles();
        $user = $userservice->UserById($id);
        $classess = $this->roleService;
        return view('users.edit', compact('user', 'roles', 'classess'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, UserActions $updateUser)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'roles' => 'array',
            'position' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'is_active' => 'required|boolean',
        ]);

        $roleId = $this->roleService->RoleByName($request->role);

        $request->merge([
            'roleId' => $roleId->id,
            'id' => $id
        ]);
        $updateUser->UpdateUser($request);
        return redirect()->route('users.index')->with('success', 'User updated.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, UserActions $deleteuser)
    {
        $deleteuser->delete($id);
        
        return redirect()->route('users.index')->with('success', 'User deleted.');
    }

    public function export(UserService $userService)
    {
        
        return Excel::download(new UsersExport($userService), 'users.xlsx');
    }
    public function import(Request $request, UserActions $createUser)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);
    
        Excel::import(new UsersImport($createUser), $request->file('file'));
    
        return redirect()->back()->with('success', 'Data users berhasil diimport!');
    }
    public function datatable(UserService $userservice)
    {
        $users = $userservice->AllUsers();
        if(request()->ajax()){
            return DataTables::of($users)
            ->addColumn('roles', function ($user) {
                return $user->roles->pluck('name')->map(function($role) {
                    return '<span class="inline-block bg-gray-200 text-xs text-gray-800 px-2 py-1 rounded">' . $role . '</span>';
                })->implode(' ');
            })
            ->addColumn('action', function ($user) {
                $edit = '<a href="' . route('users.edit', $user->id) . '" class="text-indigo-600 hover:text-indigo-900">Edit</a>';
                $delete = '<form action="' . route('users.destroy', $user->id) . '" method="POST" class="inline ml-2" onsubmit="return confirm(\'Yakin hapus user?\')">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button class="text-red-600 hover:text-red-800">Hapus</button>
                        </form>';
                return $edit . $delete;
            })
            ->rawColumns(['roles', 'action'])
            ->make(true);
        }
    }
}
