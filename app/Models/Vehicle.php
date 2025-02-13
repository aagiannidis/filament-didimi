<?php

namespace App\Models;

use App\Models\VehicleModel;
use App\Models\VehicleManufacturer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    const VEHICLE_TYPES = [
        'Car' => 'Car',
        'Motorcycle' => 'Motorcycle',
        'Truck' => 'Truck',
        'Bus' => 'Bus',
        'Van' => 'Van',
        'SUV' => 'SUV',
        'Other' => 'Other',
    ];

    const FUEL_TYPES = [
        'Petrol' => 'Petrol',
        'Diesel' => 'Diesel',
        'Electric' => 'Electric',
        'Hybrid' => 'Hybrid',
        'Other' => 'Other',
    ];


    const EMISSION_STANDARDS = [
        'Other' => 'Other',
        'Euro 1' => 'Euro 1',
        'Euro 2' => 'Euro 2',
        'Euro 3' => 'Euro 3',
        'Euro 4' => 'Euro 4',
        'Euro 5' => 'Euro 5',
        'Euro 6' => 'Euro 6',
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'license_plate',
        'vehicle_identification_no',
        'engine_serial_no',
        'chassis_serial_no',
        'vehicle_manufacturer_id',
        'vehicle_model_id',
        'manufacture_date',
        'color',
        'vehicle_type',
        'fuel_type',
        'emission_standard',
        'weight',
        'seats',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'manufacture_date' => 'date',
    ];

    // public function models(): HasMany
    // {
    //     return $this->hasMany(VehicleModel::class);
    // }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(VehicleManufacturer::class,'vehicle_manufacturer_id','id');
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class,'vehicle_model_id','id');
    }

    public function modelPlusManufacturer(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return new \Illuminate\Database\Eloquent\Casts\Attribute(
            get: fn ($value) => $this->model.' - '.$this->manufacturer->name
        );
    }

    // public function getPrimaryAddressAttribute()
    // {
    //     return $this->addresses()
    //         ->wherePivot('type','is_correspondence')
    //         ->first();
    // }

}
