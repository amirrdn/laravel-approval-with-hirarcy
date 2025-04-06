<?php
namespace App\Services;

use App\Models\Project;
use App\Models\User;
class ProjectService{

    public function getAll($paginate)
    {
        if (auth()->user()->hasRole('Administrator')) {
            return Project::with('users')->paginate($paginate);
        }else{
            return Project::whereHas('projectUser.assigned', function ($query) {
                $query->where(function ($query) {
                    $query->where('user_id', (string) auth()->id())
                    ->orWhere('assigned_to', (string) auth()->id());
                });
            })
            ->with(['users', 'projectUser.assigned'])
            ->paginate($paginate);
        }
    }
    
    public function usersHirarcy()
    {
        $currentUser = auth()->user();
        $currentRole = auth()->user()->roles->first();
        if (!$currentRole || !$currentRole->parentRole) {
            $users = collect(); 
        } else {
            $parentRole = $currentRole->parentRole;
            $users = User::whereHas('role', function ($query) use ($parentRole) {
                $query->where('id', $parentRole->id);
            })->get();
           
            if(str_contains(strtolower($currentRole->name), 'manager')){
                $users = User::whereHas('role', function ($query) use ($parentRole) {
                    $query->where('id', $parentRole->parent);
                })->get();
            }else{
                $users = User::whereHas('role', function ($query) use ($parentRole) {
                    $query->where('id', $parentRole->parent);
                })->get();

                if(count($users) === 0){
                    $users = User::whereHas('role', function ($query) use ($parentRole) {
                        $query->where('parent', $parentRole->parent);
                    })->get();
                }
            }
        }

        return $users;
    }
    
    public function projectByID($id)
    {
        return Project::with(['projectUser' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->with('users')->findOrFail($id);
    }
}