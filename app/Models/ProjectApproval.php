<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectApproval extends Model
{
    protected  $guaraded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}