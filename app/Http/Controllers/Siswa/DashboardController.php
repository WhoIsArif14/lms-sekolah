<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Siswa bisa melihat semua kursus yang tersedia di sekolah
        $courses = Course::with('user')->latest()->get();
        return view('siswa.dashboard', compact('courses'));
    }

    public function show(Course $course)
    {
        // Melihat detail materi di dalam kursus
        $materials = $course->materials()->latest()->get();
        return view('siswa.courses.show', compact('course', 'materials'));
    }
}