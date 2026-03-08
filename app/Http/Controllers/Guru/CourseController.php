<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('user_id', auth()->id())->get();

        return view('guru.courses.index', compact('courses'));
    }

    public function create()
    {
        $classes = \App\Models\SchoolClass::all(); // Ambil semua kelas buatan Admin
        return view('guru.courses.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'school_class_id' => 'required|exists:school_classes,id',
            'day' => 'required|string',
            'time_start' => 'required',
            'time_end' => 'required',
            'classroom' => 'nullable|string'
        ]);

        Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'school_class_id' => $request->school_class_id,
            'day' => $request->day,
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
            'classroom' => $request->classroom,
            'user_id' => auth()->id()
        ]);

        return redirect()
            ->route('guru.courses.index')
            ->with('success', 'Kursus berhasil dibuat');
    }

    public function show($id)
    {
        $course = Course::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $materials = $course->materials;
        $assignments = $course->assignments;

        return view('guru.courses.show', compact('course', 'materials', 'assignments'));
    }

    public function edit($id)
    {
        $course = Course::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('guru.courses.edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $course->update([
            'title' => $request->title,
            'description' => $request->description
        ]);

        return redirect()
            ->route('guru.courses.index')
            ->with('success', 'Course berhasil diupdate');
    }

    public function destroy($id)
    {
        $course = Course::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $course->delete();

        return redirect()
            ->route('guru.courses.index')
            ->with('success', 'Course berhasil dihapus');
    }
}
