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
        Schema::table('travel_documents', function (Blueprint $table) {
            $table->string('passport_number')->nullable()->after('type');
            $table->string('country_code')->nullable()->after('passport_number');
            $table->string('nationality')->nullable()->after('country_code');
            $table->date('dob')->nullable()->after('nationality');
            $table->string('place_of_birth')->nullable()->after('dob');
            $table->string('gender')->nullable()->after('place_of_birth');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_documents', function (Blueprint $table) {
            $table->dropColumn(['passport_number', 'country_code', 'nationality', 'dob', 'place_of_birth', 'gender']);
        });
    }
};
