<?php

namespace App\Models;

use App\Models\VehicleFaultTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FaultType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category',
        'abbreviation',
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
        return $this->belongsToMany(VehicleFaultTemplate::class, 'fault_type_vehicle_fault_template', 'fault_id', 'template_id');
    }
}
