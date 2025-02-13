<?php

namespace App\Models;

use App\Models\VehicleModel;
use App\Models\SecureDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VehicleLicence extends Model
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
        'licence_document_id',
        'plate',
        'old_plate',
        'vin',
        'engine_serial_no',
        'engine_cc',
        'engine_hp',
        'chassis_serial_no',
        'vehicle_model_id',
        'vehicle_d2',
        'first_reg_date',
        'color',
        'license_vehicle_type',
        'vehicle_secondary_type',
        'fuel_type',
        'emission_standard',
        'weight',
        'seats',
        'comments',
        'registered_to',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'first_reg_date' => 'date',
    ];

    // public function models(): HasMany
    // {
    //     return $this->hasMany(VehicleModel::class);
    // }

    // $table->string('licence_document_id', 8)->unique();
    // $table->string('plate', 8)->unique();
    // $table->string('old_plate', 8)->nullable()->default(null);
    // $table->string('vin', 20)->unique();
    // $table->string('engine_serial_no', 50)->nullable()->default(null)->description('Engine Serial #. Not always mentioned. Optional.');
    // $table->integer('engine_cc')->default(0);
    // $table->integer('engine_hp')->default(0);
    // $table->string('chassis_serial_no', 50)->nullable()->default(null)->description('Chassis Serial #. Not always mentioned. Optional.');
    // $table->foreignId('vehicle_model_id')->references('id')->on('vehicle_models')->onDelete('restrict')->nullable()->default(null);
    // $table->string('vehicle_d2')->default('')->description('Vehicle D2 field as per licence.');
    // $table->date('first_reg_date')->nullable()->default(null)->description('Registration date. Field B');
    // $table->string('color', 20)->nullable()->default(null)->description('Color of the vehicle.');
    // $table->string('license_vehicle_type')->nullable()->default(null)->description('Vehicle type as per registration licence.');
    // $table->integer('vehicle_secondary_type')->default(VehicleType::OTHER)->description('Additional vehicle type information as user-specified.');
    // $table->enum('fuel_type', ["Petrol", "Diesel", "Electric", "Hybrid", "Other"]);
    // $table->enum('emission_standard', ["Other", "Euro 1", "Euro 2", "Euro 3", "Euro 4", "Euro 5", "Euro 6"]);
    // $table->integer('weight')->default(0);
    // $table->integer('seats')->default(0);
    // $table->string('comments')->default('');
    // $table->string('registered_to')->default('');

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

    public function secureDocuments(): MorphMany
    {
        return $this->morphMany(SecureDocument::class, 'doc_attachable');
    }

    // public function getPrimaryAddressAttribute()
    // {
    //     return $this->addresses()
    //         ->wherePivot('type','is_correspondence')
    //         ->first();
    // }

}
