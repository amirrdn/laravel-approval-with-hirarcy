<?php
namespace App\Actions;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Str;

use App\Services\UserService;

class UserActions{

    protected $userservice;

    public function __construct(UserService $userservice)
    {
        $this->userservice = $userservice;
    }

    public function storeUser(array $data)
    {
        $user = User::create([
            'id'       => (string) Str::uuid(),
            'name' => $data->name,
            'email' => $data->email,
            'password' => bcrypt($data->password),
            'position' => $data->position,
            'hire_date' => $data->hire_date,
            'is_active' => $data->is_active,
            'metadata' => json_encode(['created_by' => auth()->id()]),
            'role_id' => $data->roleId
        ]);
        $user->syncRoles($data->role ?? []);

        return $user;
    }
    public function UpdateUser(object $data)
    {
        $user = $this->userservice->UserById($data->id);

        $user->update([
            'name' => $data->name,
            'email' => $data->email,
            'position' => $data->position,
            'hire_date' => $data->hire_date,
            'is_active' => $data->is_active,
            'role_id' => $data->roleId
        ]);
    
        if ($data->password) {
            $user->update(['password' => bcrypt($data->password)]);
        }
    
        $user->syncRoles($data->role ?: []);

        return $user;
    }
    
    public function delete(string $id)
    {
        $user = $this->userservice->UserById($id);
        if($user){
            $user->delete();
            return 'success delete';
        }else{
            return 'no data';
        }

    }
}