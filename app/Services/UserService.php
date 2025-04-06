<?php
namespace App\Services;

use App\Models\User;

class UserService{

    public function AllUsers()
    {
        return User::with('roles')->get();
    }
    public function UserById(string $id)
    {
        return User::findOrFail($id);
    }

}