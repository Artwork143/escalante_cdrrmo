<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::table('medical_cases', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false);
        });

        Schema::table('medical_cases', function (Blueprint $table) {
            $table->string('barangay')->after('place_of_incident');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_cases', function (Blueprint $table) {
            //
        });
    }
};
