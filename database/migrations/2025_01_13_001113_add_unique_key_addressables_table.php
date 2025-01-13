<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('addressables', function (Blueprint $table) {
            // Add a new unique index:
            $table->unique(['addressable_id', 'addressable_type', 'address_id'], 'unique_triplet');
        });
    }

    public function down()
    {
        Schema::table('addressables', function (Blueprint $table) {
            // Drop the unique index (use the same index name)
            $table->dropUnique('unique_triplet');
        });
    }
};
