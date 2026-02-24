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
        Schema::create('tax_compliances', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['pension', 'maternity', 'bhi', 'rra']);
            $table->string('declaration_number');
            $table->decimal('declared_amount', 15, 2);
            $table->string('declaration_file');
            $table->decimal('receipt_amount', 15, 2)->nullable();
            $table->date('payment_date')->nullable();
            $table->string('receipt_file')->nullable();
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_compliances');
    }
};
