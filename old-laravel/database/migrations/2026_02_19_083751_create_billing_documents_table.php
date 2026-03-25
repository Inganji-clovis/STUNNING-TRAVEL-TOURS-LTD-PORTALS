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
        Schema::create('billing_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('set null');
            $table->string('type'); // invoice, proforma, receipt
            $table->string('document_number')->unique();
            $table->decimal('amount', 15, 2);
            $table->string('currency')->default('RWF');
            $table->string('status')->default('pending'); // pending, paid, cancelled
            $table->string('file_path');
            $table->foreignId('generated_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_documents');
    }
};
