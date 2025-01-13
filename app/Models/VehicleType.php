<?php

namespace App\Models;

use App\Models\VehicleFaultTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VehicleType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'classification',
        'category',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function vehicleFaultTemplates(): BelongsToMany
    {
        return $this->belongsToMany(VehicleFaultTemplate::class, 'vehicle_fault_template_vehicle_type', 'type_id', 'template_id');
    }
}
