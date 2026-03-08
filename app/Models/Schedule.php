<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    // Kolom yang boleh diisi melalui mass-assignment (seperti saat import)
    protected $fillable = [
        'course_id',
        'day',
        'start_time',
        'end_time'
    ];

    /**
     * Relasi balik ke Course (Jadwal ini miliknya Mapel apa?)
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}