<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('cash_days', function (Blueprint $table) {
            $table->decimal('opening_petty_balance', 15, 2)->default(0)->after('opening_balance');
            $table->decimal('closing_petty_balance', 15, 2)->default(0)->after('closing_balance');
        });
    }
    public function down(): void {
        Schema::table('cash_days', function (Blueprint $table) {
            $table->dropColumn(['opening_petty_balance', 'closing_petty_balance']);
        });
    }
};
