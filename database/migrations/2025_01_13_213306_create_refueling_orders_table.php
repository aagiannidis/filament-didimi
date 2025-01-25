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
        Schema::disableForeignKeyConstraints();

        Schema::create('refueling_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('company_id')->constrained();
            $table->foreignId('asset_id')->constrained();
            $table->date('start_date')->nullable();
            $table->date('end_date');
            $table->enum('fuel_type',["Petrol", "Diesel"]);
            $table->integer('fuel_qty')->default(0);
            $table->enum('state', ["created","submitted","approved_by_officer","approved_by_manager","receipt_attached","closed"]);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refueling_orders');
    }
};
