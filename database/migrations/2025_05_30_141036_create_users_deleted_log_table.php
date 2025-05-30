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
        Schema::create('users_deleted_log', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->nullable();
            $table->string('username');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('profile')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('number_phone')->nullable();
            $table->string('last_edication')->nullable();
            $table->string('nik')->nullable();
            $table->string('nira_ppni')->nullable();
            $table->string('nira_ipdi')->nullable();
            $table->string('pw_ipdi_provinsi')->nullable();
            $table->integer('pw_ipdi_kabupaten')->nullable();
            $table->string('nama_perusahaan')->nullable();
            $table->string('center_dialisis')->nullable();
            $table->longText('alamat_center_dialisis_perusahaan')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('postal')->nullable();
            $table->text('about')->nullable();
            $table->integer('status')->nullable();
            $table->string('sertifikat_file')->nullable();
            $table->date('sertifikat_start_date')->nullable();
            $table->date('sertifikat_end_date')->nullable();
            $table->timestamp('deleted_at_real')->useCurrent();
            $table->boolean('deleted_by_trigger')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_deleted_log');
    }
};
