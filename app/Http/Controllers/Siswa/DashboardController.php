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
        // Mengambil materi dan tugas yang terkait dengan kursus ini
        $course->load(['materials', 'assignments.submissions' => function ($query) {
            $query->where('user_id', auth()->id());
        }]);

        return view('siswa.courses.show', compact('course'));
    }

    public function submitAssignment(Request $request, Assignment $assignment)
    {
        $request->validate([
            'file_jawaban' => 'required|mimes:pdf,doc,docx,zip,jpg,png|max:5120',
        ]);

        $path = $request->file('file_jawaban')->store('submissions', 'public');

        \App\Models\Submission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'user_id' => auth()->id()],
            ['file_path' => $path]
        );

        return back()->with('success', 'Tugas berhasil dikirim!');
    }

    public function indexAssignments()
    {
        // Mengambil semua tugas yang tersedia untuk siswa
        $assignments = \App\Models\Assignment::with('course')->get();

        return view('siswa.assignments.index', compact('assignments'));
    }

    public function storeAttendance()
    {
        $today = now()->format('Y-m-d');
        $userId = auth()->id();

        // Cek apakah siswa sudah absen hari ini (Global)
        $alreadyPresent = \App\Models\Attendance::where('user_id', $userId)
            ->where('attendance_date', $today)
            ->exists();

        if ($alreadyPresent) {
            return back()->with('info', 'Anda sudah melakukan absensi harian.');
        }

        \App\Models\Attendance::create([
            'user_id' => $userId,
            'attendance_date' => $today,
        ]);

        return back()->with('success', 'Berhasil mencatat kehadiran hari ini!');
    }
}
