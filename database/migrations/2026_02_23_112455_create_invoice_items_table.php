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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('billing_document_id')->constrained('billing_documents')->onDelete('cascade');
            $table->string('passenger_name')->nullable();
            $table->string('service_description')->nullable();
            $table->decimal('price_rwf', 15, 2)->nullable();
            $table->decimal('price_usd', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
