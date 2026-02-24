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
        Schema::create('client_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients');
            $table->decimal('total_due', 15, 2);
            $table->decimal('paid_amount', 15, 2);
            $table->decimal('remaining_amount', 15, 2);
            $table->date('payment_date');
            $table->string('payment_method'); // Bank, MoMo, Card, Cheque
            $table->string('currency'); // USD, RWF
            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_payments');
    }
};
