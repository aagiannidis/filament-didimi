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
        Schema::create('fault_types', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ["engine","electrical","transmision","kinetics","mechanics","tooling","hydraulics","bodywork","other"]);
            $table->enum('abbreviation', ["ENG","ELE","TSN","KIN","MEC","TOO","HYD","BOD","OTH"]);
            $table->timestamps();
            $table->unique(['category', 'abbreviation']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fault_types');
    }
};
