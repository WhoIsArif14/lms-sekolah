<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'title', 'duration', 'is_active'];

    // Relasi ke Mata Pelajaran (Satu ujian milik satu mapel)
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relasi ke Soal (Satu ujian punya banyak soal)
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    // Relasi ke Jawaban Siswa
    public function responses()
    {
        return $this->hasMany(ExamResponse::class);
    }
}
