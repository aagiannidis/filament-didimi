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
        Schema::create('accounts', function (Blueprint $table) {            
            $table->id();
            $table->string('first_name', 50)->default('No Name');
            $table->string('last_name', 50)->default('No LastName');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ["male","female","other"])->default("male");
            $table->string('photo', 255)->nullable()->default(null);
            $table->string('mobile_phone', 20)->nullable();
            $table->string('home_phone', 20)->nullable();
            $table->string('work_phone', 20)->nullable();                        
            $table->foreignId('user_id')->unique()->nullable()->default(null)->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
