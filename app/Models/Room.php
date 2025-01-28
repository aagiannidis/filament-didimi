<?php

namespace App\Models;

use App\Models\SecureDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;


    protected $fillable = [
        'floor_id',
        'number',
        'name',
        'type',
        'capacity',
        'area_sqm',
        'status',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'area_sqm' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function floor(): BelongsTo
    {
        return $this->belongsTo(Floor::class);
    }

    public function wallPorts(): HasMany
    {
        return $this->hasMany(WallPort::class);
    }

    public function secureDocuments(): MorphMany
    {
        return $this->morphMany(SecureDocument::class, 'doc_attachable');
    }

    // public function assets(): HasMany
    // {
    //     return $this->hasMany(BuildingAsset::class);
    // }

    // public function getAssetsAttribute(): array
    // {
    //     return [
    //         'hvac' => $this->assets()->where('type', 'HVAC')->pluck('id')->toArray(),
    //         'lighting' => $this->assets()->where('type', 'LIGHTING')->pluck('id')->toArray(),
    //         'network' => $this->assets()->where('type', 'NETWORK')->pluck('id')->toArray(),
    //         'electrical' => $this->assets()->where('type', 'ELECTRICAL')->pluck('id')->toArray(),
    //     ];
    // }

    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
