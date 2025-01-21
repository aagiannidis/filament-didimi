<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WallPort extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'port_number',
        'type',
        'location',
        'status',
        'speed',
        'extension',
        'last_tested_date',
    ];

    protected $casts = [
        'last_tested_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'INACTIVE');
    }

    public function scopeFaulty($query)
    {
        return $query->where('status', 'FAULTY');
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeDataPorts($query)
    {
        return $query->where('type', 'DATA');
    }

    public function scopeVoicePorts($query)
    {
        return $query->where('type', 'VOICE');
    }

    public function toggleStatus(): void
    {
        if ($this->status === 'FAULTY') {
            return;
        }

        $this->status = $this->status === 'ACTIVE' ? 'INACTIVE' : 'ACTIVE';
        $this->save();
    }

    public function markAsFaulty(): void
    {
        $this->status = 'FAULTY';
        $this->save();
    }
}
