<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('trigger_before_users_delete', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });
        DB::unprepared('
            CREATE TRIGGER before_users_delete
            BEFORE DELETE ON users
            FOR EACH ROW
            BEGIN
                INSERT INTO users_deleted_log (
                    id,
                    username,
                    firstname,
                    lastname,
                    profile,
                    jenis_kelamin,
                    email,
                    email_verified_at,
                    number_phone,
                    last_edication,
                    nik,
                    nira_ppni,
                    nira_ipdi,
                    pw_ipdi_provinsi,
                    pw_ipdi_kabupaten,
                    nama_perusahaan,
                    center_dialisis,
                    alamat_center_dialisis_perusahaan,
                    address,
                    city,
                    country,
                    postal,
                    about,
                    status,
                    sertifikat_file,
                    sertifikat_start_date,
                    sertifikat_end_date
                ) VALUES (
                    OLD.id,
                    OLD.username,
                    OLD.firstname,
                    OLD.lastname,
                    OLD.profile,
                    OLD.jenis_kelamin,
                    OLD.email,
                    OLD.email_verified_at,
                    OLD.number_phone,
                    OLD.last_edication,
                    OLD.nik,
                    OLD.nira_ppni,
                    OLD.nira_ipdi,
                    OLD.pw_ipdi_provinsi,
                    OLD.pw_ipdi_kabupaten,
                    OLD.nama_perusahaan,
                    OLD.center_dialisis,
                    OLD.alamat_center_dialisis_perusahaan,
                    OLD.address,
                    OLD.city,
                    OLD.country,
                    OLD.postal,
                    OLD.about,
                    OLD.status,
                    OLD.sertifikat_file,
                    OLD.sertifikat_start_date,
                    OLD.sertifikat_end_date
                );
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('trigger_before_users_delete');
        DB::unprepared('DROP TRIGGER IF EXISTS before_users_delete');
    }
};
