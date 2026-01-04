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
        // Tambahkan field di table events untuk durasi acara
        Schema::table('events', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('date');
            $table->date('end_date')->nullable()->after('start_date');
            $table->tinyInteger('total_days')->default(1)->after('end_date')
                ->comment('Total hari event berlangsung (auto calculated)');
            $table->tinyInteger('max_attendance')->nullable()->after('total_days')
                ->comment('Maksimal absensi yang diizinkan (optional, default = total_days)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date', 'total_days', 'max_attendance']);
        });
    }
};