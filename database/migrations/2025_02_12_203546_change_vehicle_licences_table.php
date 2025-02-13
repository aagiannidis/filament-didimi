<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\VehicleType;
use App\Enums\CheckType;
use App\Enums\CheckResultType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vehicle_licences', function (Blueprint $table) {
            $table->string('vehicle_d2')->nullable()->default(null)->description('Vehicle D2 field as per licence.')->change();
            $table->integer('fuel_type')->change();
            $table->integer('weight')->nullable()->default(null)->change();
            $table->integer('seats')->nullable()->default(null)->change();
            $table->string('comments')->nullable()->default(null)->change();
            $table->string('registered_to')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
