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
        Schema::create('transaction_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('description')->nullable();
            $table->enum('file_type', ['payment_proof', 'supporting_document', 'other'])->default('payment_proof');
            $table->integer('file_size')->nullable(); // in KB
            $table->string('mime_type')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_files');
    }
};
