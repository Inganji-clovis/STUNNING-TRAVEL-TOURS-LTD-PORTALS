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
        Schema::table('billing_documents', function (Blueprint $table) {
            $table->decimal('rate', 15, 2)->nullable()->after('currency');
            $table->string('amount_in_words')->nullable()->after('rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billing_documents', function (Blueprint $table) {
            $table->dropColumn(['rate', 'amount_in_words']);
        });
    }
};
