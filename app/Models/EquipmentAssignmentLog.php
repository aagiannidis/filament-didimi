<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentAssignmentLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'equipment_id',
        'assigned_to',
        'assigned_by',
        'assigned_date',
        'returned_date',
        'notes',
    ];

    protected $casts = [
        'assigned_date' => 'datetime',
        'returned_date' => 'datetime',
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
