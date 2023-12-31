<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectWatcher extends Model
{
    public $table = 'project_watcher';

    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}