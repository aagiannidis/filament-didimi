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
        Schema::create('maintenance_assets', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['HVAC', 'LIGHTING', 'NETWORK', 'ELEVATOR', 'ELECTRICAL', 'PLUMBING']);
            $table->string('name');
            $table->foreignId('building_id')->constrained('buildings');
            $table->foreignId('floor_id')->constrained('floors');
            $table->foreignId('room_id')->nullable()->constrained('rooms');
            $table->date('installation_date');
            $table->date('last_maintenance_date');
            $table->date('next_maintenance_date');
            $table->enum('status', [
                'OPERATIONAL',
                'NEEDS_ATTENTION',
                'UNDER_MAINTENANCE',
                'OUT_OF_SERVICE'
            ])->default('OPERATIONAL');
            $table->json('specifications');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_assets');
    }
};
