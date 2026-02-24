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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('general'); // e.g. 'company', 'security', 'portals'
            $table->string('name')->unique(); // e.g. 'company_name', 'sales_portal_enabled'
            $table->boolean('locked')->default(false); // If true, only Super Admins can change it
            $table->json('payload')->nullable(); // The actual setting value(s), stored as JSON array/object or simple string
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
