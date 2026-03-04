<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'grade'];

    // Relasi ke User (Siswa)
    public function students()
    {
        return $this->hasMany(User::class, 'school_class_id');
    }

    // Relasi ke Course (Mata Pelajaran)
    public function courses()
    {
        return $this->hasMany(Course::class, 'school_class_id');
    }
}
