<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('model');
            $table->string('serial_number')->unique();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_date')->nullable();
            $table->enum('status', ['ACTIVE', 'MAINTENANCE', 'RETIRED'])->default('ACTIVE');
            $table->string('manufacturer')->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('warranty_expiry')->nullable();
            $table->decimal('purchase_cost', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->json('specifications')->nullable();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('location_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Equipment maintenance history
        Schema::create('equipment_maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained()->cascadeOnDelete();
            $table->string('maintenance_type');
            $table->text('description');
            $table->foreignId('performed_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('maintenance_date');
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('status');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Equipment assignments history
        Schema::create('equipment_assignment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_to')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('assigned_date');
            $table->timestamp('returned_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equipment_assignment_logs');
        Schema::dropIfExists('equipment_maintenance_logs');
        Schema::dropIfExists('equipment');
    }
};

