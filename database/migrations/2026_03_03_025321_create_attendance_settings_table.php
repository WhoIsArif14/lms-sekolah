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
        Schema::create('attendance_settings', function (Blueprint $table) {
            $table->id();
            $table->time('start_time'); // Jam mulai absen (misal 07:00)
            $table->time('end_time');   // Batas jam masuk (misal 08:00)
            $table->boolean('is_open')->default(true); // Status buka/tutup manual
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_settings');
    }
};
