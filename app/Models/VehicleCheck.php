<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleCheck extends Model
{
    use HasFactory;
    
    const CHECK_RESULTS = [                
        'pass' => 'Pass',
        'fail' => 'Fail',        
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'check_date',
        'check_type',
        'check_result',        
        'asset_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'check_date' => 'date',
        'vehicle_id' => 'integer',
        'asset_id' => 'integer',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
    
}
