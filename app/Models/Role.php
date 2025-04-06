<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $table = 'roles';

    public function children()
    {
        return $this->hasMany(self::class, 'parent');
    }

    public function parentRole()
    {
        return $this->belongsTo(self::class, 'parent');
    }
}
