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
        Schema::table('buildings', function (Blueprint $table) {
            $table->string('code',10)->unique()->after('name');
            $table->renameColumn('number_of_floors','total_floors');            
            $table->integer('total_capacity')->default(0);
            $table->integer('current_occupancy')->default(0);
            $table->enum('status', ['ACTIVE', 'MAINTENANCE', 'INACTIVE'])->default('ACTIVE');
            $table->foreignId('manager_id')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->renameColumn('total_floors','number_of_floors');
            $table->dropColumn('total_capacity');
            $table->dropColumn('current_occupancy');
            $table->dropColumn('status');
            $table->dropColumn('manager_id');            
        });
    }
};
