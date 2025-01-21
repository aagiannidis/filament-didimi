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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('floor_id')->constrained();
            $table->string('number', 20);
            $table->string('name', 100);
            $table->enum('type', ['OFFICE', 'MEETING_ROOM', 'COMMON_AREA', 'UTILITY', 'SERVER_ROOM']);
            $table->integer('capacity')->default(0);
            $table->decimal('area_sqm', 10, 2);
            $table->enum('status', ['ACTIVE', 'MAINTENANCE', 'INACTIVE'])->default('ACTIVE');
            $table->timestamps();
            $table->unique(['floor_id', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
