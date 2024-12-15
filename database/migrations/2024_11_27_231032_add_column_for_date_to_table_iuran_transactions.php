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
        Schema::table('iuran_transactions', function (Blueprint $table) {
            $table->string('for_date')->nullable()->after('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iuran_transactions', function (Blueprint $table) {
            $table->dropColumn('for_date')->after('date');
        });
    }
};
