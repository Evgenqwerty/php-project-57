<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property \App\Models\User $creator
 * @property \App\Models\User $assignedTo
 * @property \App\Models\TaskStatus $status
 */

class Task extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status_id',
        'creator_by_id',
        'assigned_to_id',
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
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class);
    }
}
