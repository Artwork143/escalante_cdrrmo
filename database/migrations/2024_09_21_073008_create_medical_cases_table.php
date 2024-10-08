<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_cases', function (Blueprint $table) {
            $table->id();
            $table->date('date'); // Date of the medical case
            $table->string('rescue_team'); // Rescue team involved
            $table->string('place_of_incident'); // Location of the incident
            $table->integer('no_of_patients'); // Number of patients involved
            $table->text('chief_complaints')->nullable(); // Chief complaints of the patients
            $table->string('facility_name'); // Name of the medical facility
            $table->timestamps(); // Adds created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medical_cases');
    }
}
