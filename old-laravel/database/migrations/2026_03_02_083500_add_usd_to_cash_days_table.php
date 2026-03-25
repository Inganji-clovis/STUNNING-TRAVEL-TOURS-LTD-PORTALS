<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cash_days', function (Blueprint $table) {
            $table->decimal('opening_balance_usd', 15, 2)->default(0)->after('closing_petty_balance');
            $table->decimal('closing_balance_usd', 15, 2)->default(0)->after('opening_balance_usd');
            $table->decimal('opening_petty_balance_usd', 15, 2)->default(0)->after('closing_balance_usd');
            $table->decimal('closing_petty_balance_usd', 15, 2)->default(0)->after('opening_petty_balance_usd');
        });
    }

    public function down(): void
    {
        Schema::table('cash_days', function (Blueprint $table) {
            $table->dropColumn([
                'opening_balance_usd',
                'closing_balance_usd',
                'opening_petty_balance_usd',
                'closing_petty_balance_usd'
            ]);
        });
    }
};
