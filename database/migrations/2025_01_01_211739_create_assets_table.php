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

        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_reference', 50)->unique();
            $table->string('license_plate', 50)->unique()->nullable();
            $table->date('date_of_purchase')->nullable();
            $table->decimal('cost_of_purchase', 10, 2)->nullable();
            $table->enum('condition', ["new","used","damaged"]);
            $table->foreignId('vehicle_id')->constrained('vehicles')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        
        Schema::dropIfExists('assets');
    }
};
