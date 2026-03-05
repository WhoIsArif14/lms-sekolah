<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResponse extends Model
{
    use HasFactory;

    protected $fillable = ['exam_id', 'user_id', 'answers', 'score', 'submitted_at'];

    // Cast answers agar otomatis menjadi array
    protected $casts = [
        'answers' => 'array',
        'submitted_at' => 'datetime',
    ];

    // Relasi ke Ujian
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    // Relasi ke Siswa (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
