<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'assigned_to',
        'due_date',
        'status',
        'priority',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'status' => \App\Enums\TaskStatus::class,
        'priority' => \App\Enums\TaskPriority::class,
    ];

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
