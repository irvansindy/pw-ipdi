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
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile')->nullable()->after('lastname');
            $table->string('number_phone')->nullable()->after('email_verified_at');
            $table->string('last_edication')->nullable()->after('number_phone');
            $table->string('nik')->nullable()->after('last_edication');
            $table->string('nira_ppni')->nullable()->after('nik');
            $table->string('nira_ipdi')->nullable()->after('nira_ppni');
            $table->enum('pw_ipdi_provinsi', ['Jakarta Pusat', 'Jakarta Utara', 'Jakarta Selatan', 'Jakarta Timur', 'Jakarta Barat'])->nullable()->after('nira_ipdi');
            $table->string('nama_perusahaan')->nullable()->after('pw_ipdi_provinsi');
            $table->unsignedInteger('center_dialisis')->nullable()->after('nama_perusahaan');
            $table->longText('alamat_center_dialisis_perusahaan')->nullable()->after('center_dialisis');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile')->after('lastname');
            $table->dropColumn('number_phone')->after('email_verified_at');
            $table->dropColumn('last_edication')->after('number_phone');
            $table->dropColumn('nik')->after('last_edication');
            $table->dropColumn('nira_ppni')->after('nik');
            $table->dropColumn('nira_ipdi')->after('nira_ppni');
            $table->dropColumn('pw_ipdi_provinsi')->after('nira_ipdi');
            $table->dropColumn('nama_perusahaan')->after('pw_ipdi_provinsi');
            $table->dropColumn('center_dialisis')->after('nama_perusahaan');
            $table->dropColumn('alamat_center_dialisis_perusahaan')->after('center_dialisis');
        });
    }
};
