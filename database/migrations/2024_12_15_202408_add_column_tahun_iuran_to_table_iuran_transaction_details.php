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
        Schema::table('iuran_transaction_details', function (Blueprint $table) {
            $table->string('tahun_iuran')->nullable()->after('user_id');
            $table->string('biaya_regis_anggota_baru')->nullable()->after('tahun_iuran');
            $table->string('biaya_iuran_tahunan')->nullable()->after('biaya_regis_anggota_baru');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iuran_transaction_details', function (Blueprint $table) {
            $table->dropColumn('tahun_iuran')->after('user_id');
            $table->dropColumn('biaya_regis_anggota_baru')->after('tahun_iuran');
            $table->dropColumn('biaya_iuran_tahunan')->after('biaya_regis_anggota_baru');
        });
    }
};
