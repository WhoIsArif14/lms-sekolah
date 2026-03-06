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
        // Add "ortu" to the enum list for users.role.  Using a raw statement because
        // modifying an enum requires DBAL or raw SQL; this is simple and works on
        // MySQL/MariaDB.  Adjust if you use a different driver.
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin','guru','siswa','ortu') NOT NULL DEFAULT 'siswa';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // revert back to the previous set, dropping the "ortu" option.
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin','guru','siswa') NOT NULL DEFAULT 'siswa';");
    }
};