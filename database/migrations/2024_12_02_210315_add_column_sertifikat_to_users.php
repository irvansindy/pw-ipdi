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
            $table->string('sertifikat_file')->nullable()->after('status');
            $table->date('sertifikat_start_date')->nullable()->after('sertifikat_file');
            $table->date('sertifikat_end_date')->nullable()->after('sertifikat_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('sertifikat_file')->after('status');
            $table->dropColumn('sertifikat_start_date')->after('sertifikat_file');
            $table->dropColumn('sertifikat_end_date')->after('sertifikat_start_date');
        });
    }
};
