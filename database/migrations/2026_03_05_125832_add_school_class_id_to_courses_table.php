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
        // Update invalid school_class_id values to null
        DB::table('courses')->where('school_class_id', 0)->update(['school_class_id' => null]);

        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedBigInteger('school_class_id')->nullable()->change();
            $table->foreign('school_class_id')->references('id')->on('school_classes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeignIdFor('school_classes');
        });
    }
};
