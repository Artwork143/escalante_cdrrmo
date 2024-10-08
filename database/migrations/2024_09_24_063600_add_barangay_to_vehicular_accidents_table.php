<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('vehicular_accidents', function (Blueprint $table) {
            $table->string('barangay')->after('place_of_incident'); // Add barangay column
        });
    }

    public function down()
    {
        Schema::table('vehicular_accidents', function (Blueprint $table) {
            $table->dropColumn('barangay');
        });
    }
};
