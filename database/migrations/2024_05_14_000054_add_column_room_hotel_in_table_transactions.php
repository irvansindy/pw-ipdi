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
        Schema::table('transactions', function (Blueprint $table) {
            $table->integer('harga_hotel')->nullable()->after('with_hotel');
            $table->integer('total_room')->nullable()->after('harga_hotel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('harga_hotel')->aft('with_hotel');
            $table->dropColumn('total_room')->aft('harga_hotel');
        });
    }
};
