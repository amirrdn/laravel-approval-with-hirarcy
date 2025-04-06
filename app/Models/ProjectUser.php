<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectUser extends Pivot
{
    protected $table = 'project_user';
    protected $fillable = ['project_id', 'user_id', 'assigned_to'];
    
    public function assigned()
    {
        return $this->hasOne(User::class, 'id', 'assigned_to');
    }
    public function creator()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
