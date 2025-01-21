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
        Schema::create('fault_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('reported_by')->constrained('users');
            $table->timestamp('reported_date');
            $table->foreignId('building_id')->constrained();
            $table->foreignId('floor_id')->constrained();
            $table->foreignId('room_id')->nullable()->constrained();
            $table->enum('category', ['HVAC', 'LIGHTING', 'NETWORK', 'ELEVATOR', 'ELECTRICAL', 'PLUMBING', 'OTHER']);
            $table->enum('priority', ['LOW', 'MEDIUM', 'HIGH', 'CRITICAL']);
            $table->enum('status', ['REPORTED', 'UNDER_REVIEW', 'ASSIGNED', 'IN_PROGRESS', 'RESOLVED', 'CLOSED'])
                  ->default('REPORTED');
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->json('images')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fault_reports');
    }
};
