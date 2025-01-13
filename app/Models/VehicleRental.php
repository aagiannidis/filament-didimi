<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleRental extends Model
{
    use HasFactory;

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

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
