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
        Schema::create('fault_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fault_id')->constrained('fault_reports');
            $table->foreignId('user_id')->constrained();
            $table->text('message');
            $table->enum('type', ['COMMENT', 'STATUS_UPDATE', 'RESOLUTION'])->default('COMMENT');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fault_feedback');
    }
};
