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
        // Tambah kolom ke tabel users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'parent_phone')) {
                $table->string('parent_phone')->nullable()->after('email');
            }
        });

        // Tambah kolom ke tabel attendances
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'type')) {
                $table->enum('type', ['MASUK', 'PULANG'])->default('MASUK')->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_and_users', function (Blueprint $table) {
            //
        });
    }
};
