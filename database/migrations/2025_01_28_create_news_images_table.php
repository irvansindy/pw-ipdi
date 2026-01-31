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
        Schema::create('news_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('news_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type')->default('news_image');
            $table->decimal('file_size', 10, 2)->nullable(); // in KB
            $table->string('mime_type')->nullable();
            $table->integer('order')->default(0); // untuk sorting
            $table->timestamps();

            // $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
            $table->index('news_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_images');
    }
};
