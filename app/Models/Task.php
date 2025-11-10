<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $status_id
 * @property int $creator_by_id
 * @property int $assigned_by_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\Models\User $creator
 * @property \App\Models\User $assignedTo
 * @property \App\Models\TaskStatus $status
 * @property \Illuminate\Database\Eloquent\Collection<\App\Models\Label> $labels
 */

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
