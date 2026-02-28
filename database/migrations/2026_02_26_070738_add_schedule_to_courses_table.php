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
        Schema::table('courses', function (Blueprint $table) {
            $table->string('day')->nullable()->after('description');
            $table->time('time_start')->nullable()->after('day');
            $table->time('time_end')->nullable()->after('time_start');
            $table->string('classroom')->nullable()->after('time_end');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['day', 'time_start', 'time_end', 'classroom']);
        });
    }
};
