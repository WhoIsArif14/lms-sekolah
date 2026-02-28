<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Assignment;
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

        // ambil tugas dan majukan pengumpulan siswa saat ini
        $assignments = $course->assignments()->latest()->get();

        $user = auth()->user();
        $submissions = $user->submissions()
            ->whereIn('assignment_id', $assignments->pluck('id'))
            ->get()
            ->keyBy('assignment_id');

        return view('siswa.courses.show', compact('course', 'materials', 'assignments', 'submissions'));
    }

    // siswa mengirimkan file untuk tugas tertentu
    public function submitAssignment(Request $request, Course $course, Assignment $assignment)
    {
        // pastikan kursus sesuai agar tidak mengirim ke tugas lain
        if ($assignment->course_id !== $course->id) {
            abort(404);
        }

        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,zip|max:10240',
        ]);

        $path = $request->file('file')->store('submissions', 'public');

        $user = auth()->user();
        // hapus pengumpulan sebelumnya bila ada (overwrite)
        $user->submissions()->updateOrCreate(
            ['assignment_id' => $assignment->id],
            ['file_path' => $path]
        );

        return back()->with('success', 'Tugas berhasil dikirim!');
    }
}