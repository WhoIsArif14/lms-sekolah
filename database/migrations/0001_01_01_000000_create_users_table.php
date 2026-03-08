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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->enum('role', ['admin', 'guru', 'siswa', 'orang_tua'])->default('siswa'); // Tambah role orang_tua jika perlu
            $table->string('parent_phone')->nullable(); // Pindahkan dari file migrasi "add" ke sini
            $table->foreignId('school_class_id')->nullable()->constrained('school_classes')->onDelete('cascade'); // Jika siswa butuh kelas
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
