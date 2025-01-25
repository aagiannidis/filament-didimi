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
        Schema::table('addresses', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->change();
            $table->decimal('longitude', 11, 8)->nullable()->change();
            $table->text('additional_info')->nullable()->change();
            $table->string('unit_number', 20)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            // $table->decimal('latitude', 10, 8)->nullable(false)->change();
            // $table->decimal('longitude', 11, 8)->nullable(false)->change();
            // $table->text('additional_info')->nullable(false)->change();
            // $table->string('unit_number', 20)->nullable(false)->change();
        });

    }
};
