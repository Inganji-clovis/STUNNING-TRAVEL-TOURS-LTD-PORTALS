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
            $table->decimal('tax_amount', 15, 2)->default(0)->after('amount');
            $table->date('issue_date')->nullable()->after('tax_amount');
            $table->date('due_date')->nullable()->after('issue_date');
        });

        Schema::table('client_payments', function (Blueprint $table) {
            $table->foreignId('billing_document_id')->nullable()->constrained('billing_documents')->onDelete('set null')->after('client_id');
            $table->string('receipt_file')->nullable()->after('currency');
        });

        Schema::table('ebms', function (Blueprint $table) {
            $table->foreignId('billing_document_id')->nullable()->constrained('billing_documents')->onDelete('set null')->after('id');
            $table->decimal('vat_amount', 15, 2)->default(0)->after('amount_rwf');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ebms', function (Blueprint $table) {
            $table->dropForeign(['billing_document_id']);
            $table->dropColumn(['billing_document_id', 'vat_amount']);
        });

        Schema::table('client_payments', function (Blueprint $table) {
            $table->dropForeign(['billing_document_id']);
            $table->dropColumn(['billing_document_id', 'receipt_file']);
        });

        Schema::table('billing_documents', function (Blueprint $table) {
            $table->dropColumn(['tax_amount', 'issue_date', 'due_date']);
        });
    }
};
