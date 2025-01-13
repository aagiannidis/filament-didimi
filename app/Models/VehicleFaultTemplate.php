<?php

namespace App\Models;

use App\Models\Ticket;
use App\Models\FaultType;
use App\Models\VehicleType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VehicleFaultTemplate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [        
        'title',
        'description',
        'description_gr',
        'precautions',
        'precautions_gr',
        'priority',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function vehicleTypes(): BelongsToMany
    {
        return $this->belongsToMany(VehicleType::class, 'vehicle_fault_template_vehicle_type', 'template_id', 'type_id');
        /*
        Schema::create('vehicle_fault_template_vehicle_type', function (Blueprint $table) {            
            $table->foreignId('template_id')->references('id')->on('vehicle_fault_templates')->onDelete('cascade');
            $table->foreignId('type_id')->references('id')->on('vehicle_types')->onDelete('cascade');
        });
        */
    }

    public function faultTypes(): BelongsToMany
    {
        return $this->belongsToMany(FaultType::class, 'fault_type_vehicle_fault_template', 'template_id', 'fault_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }


}
