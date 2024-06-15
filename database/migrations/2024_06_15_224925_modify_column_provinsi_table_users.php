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
            DB::statement("ALTER TABLE users MODIFY pw_ipdi_provinsi integer(11) NULL");
            $table->integer('pw_ipdi_kabupaten')->after('pw_ipdi_provinsi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            DB::statement("ALTER TABLE users MODIFY pw_ipdi_provinsi varchar(255) NULL");
            $table->dropColumn("pw_ipdi_kabupaten");
        });
    }
};
