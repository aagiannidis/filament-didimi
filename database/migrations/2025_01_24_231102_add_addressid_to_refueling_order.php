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
        Schema::table('refueling_orders', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            $table->foreignId('address_id')->constrained()->after('company_id');
            Schema::enableForeignKeyConstraints();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refueling_orders', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            $table->drop('address_id');
            Schema::enableForeignKeyConstraints();
        });
    }
};
