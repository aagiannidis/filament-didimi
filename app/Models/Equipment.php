<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'model',
        'serial_number',
        'assigned_to',
        'assigned_date',
        'status',
        'manufacturer',
        'purchase_date',
        'warranty_expiry',
        'purchase_cost',
        'notes',
        'specifications',
        'department_id',
        'location_id',
    ];

    protected $casts = [
        'assigned_date' => 'datetime',
        'purchase_date' => 'date',
        'warranty_expiry' => 'date',
        'purchase_cost' => 'decimal:2',
        'specifications' => 'json',
    ];

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'location_id');
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(EquipmentMaintenanceLog::class);
    }

    public function assignmentLogs(): HasMany
    {
        return $this->hasMany(EquipmentAssignmentLog::class);
    }
}
