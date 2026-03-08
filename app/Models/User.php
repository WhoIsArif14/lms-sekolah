<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'parent_phone',
        'parent_id',
        'school_class_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // Relasi ke courses (sebagai guru)
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    // Relasi ke submissions (pengumpulan tugas siswa)
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    // Relasi ke kelas
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    // Relasi ortu ke anak
    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    // Relasi anak ke ortu
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // Relasi ke attendances ← TAMBAHAN
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Relasi ke exam responses ← TAMBAHAN
    public function examResponses()
    {
        return $this->hasMany(ExamResponse::class);
    }
}