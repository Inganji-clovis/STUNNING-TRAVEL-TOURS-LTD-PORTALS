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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('tin')->nullable()->after('name');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->renameColumn('service_description', 'destination');
            $table->string('airline')->nullable()->after('passenger_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('tin');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->renameColumn('destination', 'service_description');
            $table->dropColumn('airline');
        });
    }
};
