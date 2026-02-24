<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('corporate_folders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['client', 'contract', 'letter']);
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('year')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('corporate_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folder_id')->constrained('corporate_folders')->onDelete('cascade');
            $table->string('name');
            $table->string('file_path');
            $table->string('file_type'); // passport, invoice, ticket, contract, letter, etc.
            $table->foreignId('source_document_id')->nullable(); // For linked docs from Sales/Ops
            $table->string('source_document_type')->nullable(); // For polymorphic-like linking
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('corporate_files');
        Schema::dropIfExists('corporate_folders');
    }
};
