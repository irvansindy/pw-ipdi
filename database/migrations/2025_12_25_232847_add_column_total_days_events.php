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
        Schema::table('events', function (Blueprint $table) {
            // Tambah field untuk total days dan max attendance
            $table->tinyInteger('total_days')->default(1)->after('date_end')
                ->comment('Total hari event berlangsung (auto calculated dari date sampai date_end)');

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
            $table->dropColumn(['total_days', 'max_attendance']);
        });
    }
};
