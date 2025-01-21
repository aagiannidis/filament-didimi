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
        Schema::create('wall_ports', function (Blueprint $table) {
            $table->id();            
            $table->foreignId('room_id')->constrained();
            $table->string('port_number', 20);
            $table->enum('type', ['DATA', 'VOICE']);
            $table->enum('location', ['NORTH', 'SOUTH', 'EAST', 'WEST']);
            $table->enum('status', ['ACTIVE', 'INACTIVE', 'FAULTY'])->default('ACTIVE');
            $table->string('speed', 20)->nullable();
            $table->string('extension', 20)->nullable();
            $table->timestamp('last_tested_date')->nullable();
            $table->timestamps();
            $table->unique(['room_id', 'port_number']); 
            $table->softDeletes();           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wall_ports');
    }
};
