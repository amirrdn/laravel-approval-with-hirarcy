<?php
namespace App\Services;

use App\Models\User;

class UserService{

    public function AllUsers($paginate = NULL)
    {
        if($paginate){
            return User::with('roles')->paginate($paginate);
        }else{
            return User::with('roles')->get();
        }
    }
    public function UserById(string $id)
    {
        return User::findOrFail($id);
    }

}