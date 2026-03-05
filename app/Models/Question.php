<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['exam_id', 'type', 'question_text', 'options', 'correct_answer'];

    // Cast kolom options agar otomatis menjadi array saat dipanggil (karena kita simpan sebagai JSON)
    protected $casts = [
        'options' => 'array',
    ];

    // Relasi balik ke Ujian
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
