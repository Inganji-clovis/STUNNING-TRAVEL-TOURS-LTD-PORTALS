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
        Schema::create('cloud_syncs', function (Blueprint $table) {
            $table->id();
            $table->string('year', 4);
            $table->string('month', 2);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->foreignId('synced_by')->constrained('users')->onDelete('cascade');
            $table->text('error_log')->nullable();
            $table->timestamps();
            
            // Ensure we don't accidentally sync the same month twice
            $table->unique(['year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cloud_syncs');
    }
};
