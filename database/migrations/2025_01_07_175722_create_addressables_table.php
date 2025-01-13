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
        Schema::create('addressables', function (Blueprint $table) {            
            $table->foreignId('address_id')->nullable()->default(null)->references('id')->on('addresses')->onDelete('cascade');
            $table->morphs('addressable');
            $table->enum('type', ["home","work","factory","storage","other"])->default("other");
            $table->boolean('is_correspondence');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addressables');
    }
};
