<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_day_id')->constrained('cash_days')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('cash_categories');
            $table->string('type'); // income, expense
            $table->decimal('amount', 15, 2);
            $table->string('source')->default('main'); // main, petty
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('cash_transactions');
    }
};
