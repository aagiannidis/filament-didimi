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
        Schema::disableForeignKeyConstraints();

        Schema::create('vehicle_checks', function (Blueprint $table) {
            $table->id();
            $table->date('check_date');
            $table->string('check_type', 50);
            $table->enum('check_result', ["pass","fail"]);            
            $table->foreignId('asset_id')->constrained('assets', 'id');            
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_checks');
    }
};
