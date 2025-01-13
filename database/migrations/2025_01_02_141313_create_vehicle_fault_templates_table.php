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
        Schema::create('vehicle_fault_templates', function (Blueprint $table) {
            $table->id();            
            $table->string('title', 50);
            $table->text('description')->nullable();
            $table->text('description_gr')->nullable();
            $table->text('precautions')->nullable();
            $table->text('precautions_gr')->nullable();
            $table->enum('priority', ["low","medium","high"])->default('low');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_fault_templates');
    }
};
