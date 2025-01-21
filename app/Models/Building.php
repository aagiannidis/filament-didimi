<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Building extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string, mixed>
     */
    protected $fillable = [
        'code',
        'name',
        'address',
        'city',
        'country',
        'total_floors',
        'total_capacity',
        'current_occupancy',
        'status',
        'manager_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'total_floors' => 'integer',
        'total_capacity' => 'integer',
        'current_occupancy' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the manager of the building.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get the floors of the building.
     */
    public function floors(): HasMany
    {
        return $this->hasMany(Floor::class);
    }

    /**
     * Get all rooms in the building through floors.
     */
    public function rooms(): HasManyThrough
    {
        return $this->hasManyThrough(Room::class, Floor::class);
    }

    /**
     * Get the building's assets.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(BuildingAsset::class);
    }

    /**
     * Get the building's staff members.
     */
    // public function staff(): BelongsToMany
    // {
    //     return $this->belongsToMany(User::class, 'building_staff')
    //         ->withPivot(['role', 'start_date', 'end_date'])
    //         ->withTimestamps();
    // }

    /**
     * Get the building's addresses.
     */
    // public function addresses(): BelongsToMany
    // {
    //     return $this->belongsToMany(Address::class, 'building_addresses')
    //         ->withPivot(['address_type', 'is_primary'])
    //         ->withTimestamps();
    // }

    public function addresses(): MorphToMany
    {
        return $this->morphToMany(Address::class, 'addressable')
            ->withPivot('type','is_correspondence');
    }

    /**
     * Scope a query to only include active buildings.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }

    /**
     * Scope a query to only include buildings under maintenance.
     */
    public function scopeUnderMaintenance($query)
    {
        return $query->where('status', 'MAINTENANCE');
    }

    /**
     * Scope a query to only include inactive buildings.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'INACTIVE');
    }

    /**
     * Get the building's occupancy rate as a percentage.
     */
    public function getOccupancyRateAttribute(): float
    {
        if ($this->total_capacity === 0) {
            return 0;
        }
        
        return round(($this->current_occupancy / $this->total_capacity) * 100, 2);
    }

    /**
     * Check if the building is at full capacity.
     */
    public function isAtFullCapacity(): bool
    {
        return $this->current_occupancy >= $this->total_capacity;
    }

    /**
     * Get the primary address of the building.
     */
    public function getPrimaryAddressAttribute()
    {
        return $this->addresses()
            ->wherePivot('type','is_correspondence')
            ->first();
    } 
}
