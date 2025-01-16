<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('vehicle_manufacturer_id');
            $table->dropColumn('model');
            $table->foreignId('vehicle_manufacturer_id')->nullable()->default(null)->references('id')->on('vehicle_manufacturers')->onDelete('cascade');
            $table->foreignId('vehicle_model_id')->nullable()->default(null)->references('id')->on('vehicle_models')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign('vehicle_manufacturer_id');
            $table->dropColumn('vehicle_manufacturer_id');
            $table->dropForeign('vehicle_model_id');
            $table->dropColumn('vehicle_model_id');
        });
    }    
};

