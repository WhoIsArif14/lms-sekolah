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
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
        ]);

        Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(), // ID Guru yang sedang login
        ]);

        return redirect()->route('guru.courses.index')->with('success', 'Kursus berhasil dibuat!');
    }

    public function show(Course $course)
    {
        // Pastikan ini kursus milik guru yang login
        if ($course->user_id !== auth()->id()) {
            abort(403);
        }

        // Gunakan get() setelah memanggil relasi
        $materials = $course->materials()->latest()->get();

        return view('guru.courses.show', compact('course', 'materials'));
    }

    public function storeMaterial(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:file,video_link',
            'file_content' => 'required_if:type,file|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240', // Max 10MB
            'link_content' => 'required_if:type,video_link|url',
        ]);

        $content = '';

        if ($request->type === 'file') {
            $path = $request->file('file_content')->store('materials', 'public');
            $content = $path;
        } else {
            $content = $request->link_content;
        }

        Material::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'type' => $request->type,
            'content' => $content,
        ]);

        return back()->with('success', 'Materi berhasil ditambahkan!');
    }
}
