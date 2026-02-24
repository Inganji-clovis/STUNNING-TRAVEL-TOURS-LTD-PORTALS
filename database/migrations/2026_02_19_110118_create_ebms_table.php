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
        Schema::create('ebms', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('tin_number');
            $table->text('description')->nullable();
            $table->date('date');
            $table->decimal('amount_rwf', 15, 2);
            $table->string('file_path');
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebms');
    }
};
