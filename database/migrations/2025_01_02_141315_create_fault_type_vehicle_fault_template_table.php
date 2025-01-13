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
        Schema::create('fault_type_vehicle_fault_template', function (Blueprint $table) {            
            $table->foreignId('fault_id')->references('id')->on('fault_types')->onDelete('cascade');
            $table->foreignId('template_id')->references('id')->on('vehicle_fault_templates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fault_type_vehicle_fault_template');
    }
};
