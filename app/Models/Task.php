<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status_id',
        'creator_by_id',
        'assigned_by_id',
    ];

    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'creator_by_id');
    }

    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'status_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_by_id');
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class);
    }
}
