<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\VehicleType;
use App\Enums\CheckType;
use App\Enums\CheckResultType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicle_licences', function (Blueprint $table) {

            $table->id();
            $table->string('licence_document_id', 8)->unique();
            $table->string('plate', 8)->unique();
            $table->string('old_plate', 8)->nullable()->default(null);
            $table->string('vin', 20)->unique();
            $table->string('engine_serial_no', 50)->nullable()->default(null)->description('Engine Serial #. Not always mentioned. Optional.');
            $table->integer('engine_cc')->default(0);
            $table->integer('engine_hp')->default(0);
            $table->string('chassis_serial_no', 50)->nullable()->default(null)->description('Chassis Serial #. Not always mentioned. Optional.');
            $table->foreignId('vehicle_model_id')->references('id')->on('vehicle_models')->onDelete('restrict')->nullable()->default(null);
            $table->string('vehicle_d2')->default('')->description('Vehicle D2 field as per licence.');
            $table->date('first_reg_date')->nullable()->default(null)->description('Registration date. Field B');
            $table->string('color', 20)->nullable()->default(null)->description('Color of the vehicle.');
            $table->string('license_vehicle_type')->nullable()->default(null)->description('Vehicle type as per registration licence.');
            $table->integer('vehicle_secondary_type')->default(VehicleType::OTHER)->description('Additional vehicle type information as user-specified.');
            $table->enum('fuel_type', ["Petrol", "Diesel", "Electric", "Hybrid", "Other"]);
            $table->enum('emission_standard', ["Other", "Euro 1", "Euro 2", "Euro 3", "Euro 4", "Euro 5", "Euro 6"]);
            $table->integer('weight')->default(0);
            $table->integer('seats')->default(0);
            $table->string('comments')->default('');
            $table->string('registered_to')->default('');

            //$table->index(['plate', 'vehicle_model_id']);

            $table->softDeletes();
            $table->timestamps();
        });


        Schema::table('vehicle_checks', function (Blueprint $table) {

            Schema::disableForeignKeyConstraints();

            // $table->dropColumn('check_date');
            // $table->dropColumn('check_type');
            // $table->dropColumn('check_result');
            // $table->dropConstrainedForeignIdFor('asset');
            // $table->dropColumn('asset_id');

            // $table->id();

            $table->string('slip_code')->default(0)->description('Slip serial number');
            $table->string('branch_code')->default(0)->description('Branch code');

            $table->foreignId('asset_id')->references('id')->on('assets')->onDelete('restrict')->nullable()->default(null);
            $table->dateTime('check_date')->nullable()->description('Date of check');
            $table->dateTime('valid_to')->nullable()->description('Validity period of the check');
            $table->string('issues_found')->nullable();
            $table->string('comments')->nullable();
            $table->string('driver_fullname')->nullable();
            $table->json('flags')->nullable();

            $table->integer('check_result')->description('Additional vehicle type information as user-specified.');
            $table->integer('check_type')->description('Additional vehicle type information as user-specified.');

            $table->softDeletes();
            // $table->timestamps();

            Schema::enableForeignKeyConstraints();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_licences');

        Schema::table('vehicle_checks', function (Blueprint $table) {
            $table->dropColumn(['check_date','slip_code','branch_code','check_date','valid_to','issues_found','comments','driver_fullname','flags','check_result','check_type']);
        });

    }
};
