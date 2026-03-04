<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Hapus course_id karena absen sekarang bersifat harian (global)
            $table->date('attendance_date');

            // Tambahkan status untuk membedakan kehadiran
            $table->enum('status', ['hadir', 'izin', 'sakit'])->default('hadir');

            // Tambahkan kolom untuk alasan dan file surat
            $table->text('note')->nullable(); // Alasan jika izin/sakit
            $table->string('attachment')->nullable(); // Nama file surat izin

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
