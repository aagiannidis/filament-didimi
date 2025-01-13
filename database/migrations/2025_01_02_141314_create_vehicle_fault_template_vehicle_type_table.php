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
        Schema::create('vehicle_fault_template_vehicle_type', function (Blueprint $table) {            
            $table->foreignId('template_id')->references('id')->on('vehicle_fault_templates')->onDelete('cascade');
            $table->foreignId('type_id')->references('id')->on('vehicle_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_fault_template_vehicle_type');
    }
};
