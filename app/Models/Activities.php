<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Models\Activity;
use App\Models\User;

class Activities extends Model
{
    public function causer()
    {
        return $this->belongsTo(User::class, 'causer_id');
    }
}
