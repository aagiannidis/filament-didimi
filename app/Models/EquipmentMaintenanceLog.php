<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentMaintenanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'maintenance_type',
        'description',
        'performed_by',
        'maintenance_date',
        'cost',
        'status',
        'notes',
    ];

    protected $casts = [
        'maintenance_date' => 'datetime',
        'cost' => 'decimal:2',
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
