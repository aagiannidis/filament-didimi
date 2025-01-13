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

        Schema::create('vehicle_rentals', function (Blueprint $table) {
            $table->id();
            $table->date('rental_date');
            $table->date('return_date')->nullable();
            $table->decimal('rental_cost', 10, 2);
            $table->enum('rental_status', ["rented","returned"]);
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
        Schema::dropIfExists('vehicle_rentals');
    }
};
