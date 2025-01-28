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
        Schema::create('secure_documents', function (Blueprint $table) {
            $table->id();
            // doc_attachable_type and doc_attachable_id columns
            $table->morphs('doc_attachable');

            // Document metadata
            $table->string('type'); // Type of document (e.g., "passport", "contract")
            $table->string('original_filename'); // Original filename of the document
            $table->string('random_filename'); // Random filename used for storage
            $table->string('path'); // Random filename used for storage
            $table->json('flags')->nullable(); // JSON field for flags like "contains personal data", "is top secret"

            // User tracking
            $table->foreignId('uploaded_by_user_id') // User who uploaded the document
                ->constrained('users')
                ->cascadeOnDelete();
            $table->timestamp('uploaded_at')->nullable(); // When the document was uploaded

            // Approval and verification process
            $table->json('status_history')->nullable(); // JSON field to track user actions (verification, approval)

            // Expiry date
            $table->date('expiry_date')->nullable(); // Expiry date for automatic deletion

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secure_documents');
    }
};
