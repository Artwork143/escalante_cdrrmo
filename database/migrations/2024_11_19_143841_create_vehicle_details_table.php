<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicular_accident_id')->constrained()->onDelete('cascade'); // Foreign key to vehicular_accidents
            $table->string('vehicle_type'); // Type of the vehicle (e.g., Car, Motorcycle)
            $table->string('vehicle_detail'); // Specific vehicle details (e.g., Mustang, Harley)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_details');
    }
}
