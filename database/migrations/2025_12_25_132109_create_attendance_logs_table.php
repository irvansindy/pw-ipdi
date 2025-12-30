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
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('event_id');
            $table->date('attendance_date');
            $table->time('attendance_time');
            $table->tinyInteger('day_number')->default(1);
            $table->string('scanned_code')->nullable();
            $table->text('notes')->nullable()->comment('Catatan tambahan jika perlu');
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

            // Unique constraint: user hanya bisa 1x absen per hari per event
            $table->unique(['transaction_id', 'user_id', 'attendance_date'], 'unique_daily_attendance');

            $table->index(['transaction_id', 'attendance_date']);
            $table->index(['user_id', 'attendance_date']);
            $table->index(['event_id', 'attendance_date']);
        });

        // Update transaction_details
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->tinyInteger('attendance_count')->default(0)->after('is_attendanced');
            $table->datetime('last_attendance_at')->nullable()->after('attendance_count');
            $table->boolean('attendance_completed')->default(false)->after('last_attendance_at')
                ->comment('Apakah sudah lengkap absen semua hari');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');

        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropColumn(['attendance_count', 'last_attendance_at', 'attendance_completed']);
        });
    }
};