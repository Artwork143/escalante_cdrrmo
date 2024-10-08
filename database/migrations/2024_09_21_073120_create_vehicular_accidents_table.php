<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicularAccidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicular_accidents', function (Blueprint $table) {
            $table->id();
            $table->date('date'); // Date of the accident
            $table->string('rescue_team'); // Rescue team involved
            $table->string('barangay');
            $table->string('place_of_incident'); // Location of the accident
            $table->integer('no_of_patients'); // Number of patients
            $table->string('cause_of_incident')->nullable(); // Cause of the accident
            $table->string('vehicles_involved')->nullable(); // Vehicles involved in the accident
            $table->string('facility_name'); // Facility where patients were brought
            $table->timestamps(); // Adds created_at and updated_at timestamps
            $table->tinyInteger('is_approved')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicular_accidents');
    }
}
