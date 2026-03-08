<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function index()
    {
        // Mengambil tugas yang dimiliki oleh guru yang sedang login
        $assignments = Assignment::whereHas('course', function ($query) {
            $query->where('user_id', auth()->id());
        })->with('course')->get();

        return view('guru.assignments.index', compact('assignments'));
    }

    public function create()
    {
        // Mengambil daftar kursus milik guru untuk dipilih saat buat tugas
        $courses = Course::where('user_id', auth()->id())->get();
        return view('guru.assignments.create', compact('courses'));
    }

    // Pastikan parameter Course $course ada di sini
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'instruction' => 'required|string',
            'deadline' => 'nullable|date',
        ]);

        $course = Course::find($validated['course_id']);
        unset($validated['course_id']); // Remove course_id from validated data since it's not a fillable field in Assignment

        // Ini akan otomatis mengisi course_id
        $course->assignments()->create($validated);

        return redirect()->back()->with('success', 'Tugas berhasil ditambahkan!');
    }

    public function update(Request $request, Assignment $assignment)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'instruction' => 'required',
            'deadline' => 'required|date',
            'file_soal' => 'nullable|mimes:pdf,doc,docx,zip|max:5120',
        ]);

        $data = $request->all();

        if ($request->hasFile('file_soal')) {
            // Hapus file lama jika ada
            if ($assignment->file_path) {
                Storage::disk('public')->delete($assignment->file_path);
            }
            $data['file_path'] = $request->file('file_soal')->store('assignments/soal', 'public');
        }

        $assignment->update($data);

        return redirect()->route('guru.assignments.index')->with('success', 'Tugas berhasil diperbarui!');
    }

    public function destroy(Assignment $assignment)
    {
        // Menghapus tugas otomatis akan menghapus submission jika relasi di set cascade
        $assignment->delete();

        return back()->with('success', 'Tugas berhasil dihapus!');
    }
}
