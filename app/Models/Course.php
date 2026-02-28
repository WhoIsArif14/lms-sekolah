<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'day',          // Tambahkan ini
        'time_start',   // Tambahkan ini
        'time_end',     // Tambahkan ini
        'classroom'     // Tambahkan ini
    ];

    // TAMBAHKAN INI: Relasi ke User (Guru)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Pastikan relasi ke materials juga sudah ada
    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function discussions()
    {
        return $this->hasMany(Discussion::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
