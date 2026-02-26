<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        // Mengambil semua kursus beserta relasi gurunya
        $courses = Course::with('teacher')->latest()->get();
        return view('admin.courses.index', compact('courses'));
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return back()->with('success', 'Mata pelajaran berhasil dihapus oleh Admin.');
    }
}
