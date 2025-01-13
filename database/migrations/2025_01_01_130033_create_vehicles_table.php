<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('license_plate', 50)->unique();
            $table->string('vehicle_identification_no', 50)->unique();
            $table->string('engine_serial_no', 50)->unique();
            $table->string('chassis_serial_no', 50)->unique();
            $table->string('vehicle_manufacturer_id');
            $table->string('model', 100);
            $table->date('manufacture_date');
            $table->string('color', 50);
            $table->enum('vehicle_type', ['Car', 'Motorcycle', 'Truck', 'Bus', 'Van', 'SUV', 'Other']);
            $table->enum('fuel_type',["Petrol", "Diesel", "Electric", "Hybrid", "Other"]);
            $table->enum('emission_standard', ["Other", "Euro 1","Euro 2","Euro 3","Euro 4","Euro 5","Euro 6"]);
            $table->integer('weight');
            $table->integer('seats');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
