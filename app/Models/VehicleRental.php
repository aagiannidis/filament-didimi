<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Parallax\FilamentComments\Models\Traits\HasFilamentComments;

class VehicleRental extends Model
{
    use HasFactory, LogsActivity, HasFilamentComments;

    const RENTAL_STATUSES = [                
        'rented' => 'Rented',
        'returned' => 'Returned',        
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rental_date',
        'return_date',
        'rental_cost',
        'rental_status',        
        'asset_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'rental_date' => 'date',
        'return_date' => 'date',
        'rental_cost' => 'decimal:2',        
        'asset_id' => 'integer',
    ];

    protected static $recordEvents = ['created'];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            //->logFillable();
            ->logOnly(['rental_date','return_date','rental_cost', 'asset.license_plate']);
        // Chain fluent methods for configuration options
    }
}
