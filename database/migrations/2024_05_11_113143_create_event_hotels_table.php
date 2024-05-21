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
        Schema::create('event_hotels', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('event_id');
            // $table->unsignedInteger('hotel_id');
            $table->string('name')->nullable();
            $table->string('price')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_hotels');
    }
};
