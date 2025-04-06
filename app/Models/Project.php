<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Project extends Model
{
    use HasFactory, SoftDeletes, HasUuids, LogsActivity;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $casts = [
        'metadata' => 'array',
    ];


    protected $fillable = [
        'name', 'description', 'start_date', 'end_date', 'status'
    ];

    protected static $logAttributes = ['name', 'description', 'start_date', 'end_date', 'status', 'attachment'];

    protected static $logName = 'project';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('project')
            ->logOnlyDirty();
    }
    public function projectUser()
    {
        return $this->hasOne(ProjectUser::class, 'project_id', 'id');
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
                    ->using(ProjectUser::class)
                    ->withPivot('user_id', 'assigned_to')
                    ->withTimestamps();
    }
    
    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject')
        ->where('subject_type', self::class)
        ->where('subject_id', $this->id);
    }
}
