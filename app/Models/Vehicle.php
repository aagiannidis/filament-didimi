<?php

namespace App\Models;

use App\Models\VehicleModel;
use Illuminate\Database\Eloquent\Model;
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
        'model',
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

    public function models(): HasMany
    {
        return $this->hasMany(VehicleModel::class);
    }

}
