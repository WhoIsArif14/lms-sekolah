<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    // Tambahkan baris ini
    protected $fillable = [
        'user_id',
        'attendance_date',
        'status',
        'type',
        'note',
        'attachment',
        'minutes_late',
        'lat_siswa',
        'lng_siswa',
    ];
}
