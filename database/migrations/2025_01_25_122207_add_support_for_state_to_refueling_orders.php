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
            $table->enum('state', ["draft","pending_approval","approved","printed","receipt_attached","closed","canceled"])->nullable()->default('draft');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refueling_orders', function (Blueprint $table) {
            $table->dropColumn('state');
        });
    }
};
