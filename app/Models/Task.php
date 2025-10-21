<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function creator()
    {
        return $this->belongsTo('App\Models\User');
    }
}
