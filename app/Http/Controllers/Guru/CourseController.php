<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Material;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
        // Guru hanya melihat kursus yang mereka buat sendiri
        $courses = Course::where('user_id', Auth::id())->latest()->get();
        return view('guru.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('guru.courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'day' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
            'classroom' => 'required',
        ]);

        // Menambahkan user_id otomatis dari guru yang login
        $validated['user_id'] = auth()->id();

        Course::create($validated);

        return redirect()->route('guru.courses.index')->with('success', 'Kursus dan Jadwal berhasil dibuat!');
    }

    public function show(Course $course)
    {
        // Pastikan ini kursus milik guru yang login
        if ($course->user_id !== auth()->id()) {
            abort(403);
        }

        // Gunakan get() setelah memanggil relasi
        $materials = $course->materials()->latest()->get();

        // ambil juga tugas dengan pengumpulan siswa untuk ditampilkan di halaman guru
        $assignments = $course->assignments()
            ->with('submissions.user')
            ->latest()
            ->get();

        return view('guru.courses.show', compact('course', 'materials', 'assignments'));
    }
    public function edit(Course $course)
    {
        // Pastikan hanya pemilik kursus yang bisa edit
        if ($course->user_id !== auth()->id()) {
            abort(403);
        }
        return view('guru.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        if ($course->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'day' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
            'classroom' => 'required',
        ]);

        $course->update($validated);

        return redirect()->route('guru.courses.index')->with('success', 'Mata pelajaran berhasil diperbarui!');
    }

    public function destroy(Course $course)
    {
        if ($course->user_id !== auth()->id()) {
            abort(403);
        }

        $course->delete();

        return redirect()->route('guru.courses.index')->with('success', 'Mata pelajaran berhasil dihapus!');
    }

    // guru menambahkan tugas ke kursus
    public function storeAssignment(Request $request, Course $course)
    {
        if ($course->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'instruction' => 'required|string',
            'deadline' => 'nullable|date',
        ]);

        $course->assignments()->create($validated);

        return back()->with('success', 'Tugas berhasil ditambahkan!');
    }

    public function storeMaterial(Request $request, Course $course)
    {
        // validasi input sama dengan MaterialController supaya keduanya konsisten
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:file,video_link',
            'file_content' => 'required_if:type,file|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240', // Max 10MB
            // link should be checked only when type is video_link
            'link_content' => 'required_if:type,video_link|nullable|url',
        ]);

        // simpan konten sesuai tipe
        if ($request->type === 'file') {
            $path = $request->file('file_content')->store('materials', 'public');
            $content = $path;
        } else {
            $content = $request->link_content;
        }

        $course->materials()->create([
            'title' => $request->title,
            'type' => $request->type,
            'content' => $content,
        ]);

        return back()->with('success', 'Materi berhasil ditambahkan!');
    }
}
