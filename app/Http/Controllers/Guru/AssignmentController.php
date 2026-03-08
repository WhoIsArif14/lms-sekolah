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
        $assignments = Assignment::whereHas('course', function ($query) {
            $query->where('user_id', auth()->id());
        })->with('course')->latest()->get();

        return view('guru.assignments.index', compact('assignments'));
    }

    public function create()
    {
        $courses = Course::where('user_id', auth()->id())->get();
        return view('guru.assignments.create', compact('courses'));
    }

    // Accept an optional Course when the form is posted from a course page
    public function store(Request $request, Course $course = null)
    {
        // DEBUG: cek apakah request masuk
        \Log::info('=== ASSIGNMENT STORE DIPANGGIL ===');
        \Log::info('Route course param:', ['course' => $course]);
        \Log::info('Request data:', $request->all());
        \Log::info('User ID:', ['id' => auth()->id()]);

        // build rules dynamically depending on whether we already know the course
        $rules = [
            'title'       => 'required|string|max:255',
            'instruction' => 'required|string',
            'deadline'    => 'required|date',
            'link'        => 'nullable|url|max:500',
            'file_soal'   => 'nullable|mimes:pdf,doc,docx,zip|max:5120',
        ];

        if (! $course) {
            $rules['course_id'] = 'required|exists:courses,id';
        }

        $messages = [
            'title.required'       => 'Judul tugas wajib diisi.',
            'instruction.required' => 'Instruksi tugas wajib diisi.',
            'deadline.required'    => 'Tenggat waktu wajib diisi.',
            'deadline.date'        => 'Format tenggat waktu tidak valid.',
            'link.url'             => 'Format link tidak valid.',
            'file_soal.mimes'      => 'File harus PDF, DOC, DOCX, atau ZIP.',
            'file_soal.max'        => 'Ukuran file maksimal 5MB.',
        ];

        if (! $course) {
            $messages = array_merge($messages, [
                'course_id.required' => 'Mata pelajaran wajib dipilih.',
                'course_id.exists'   => 'Mata pelajaran tidak valid.',
            ]);
        }

        $validated = $request->validate($rules, $messages);

        \Log::info('Validasi lolos:', $validated);

        // Resolve course either from route binding or request payload
        if ($course) {
            // ensure this teacher owns the course
            if ($course->user_id !== auth()->id()) {
                \Log::error('Course parameter tidak milik guru ini', ['course_id' => $course->id]);
                abort(403);
            }
        } else {
            // find course from validated input and verify ownership
            $course = Course::where('id', $validated['course_id'])
                ->where('user_id', auth()->id())
                ->first();

            if (! $course) {
                \Log::error('Course tidak ditemukan atau bukan milik guru ini', [
                    'course_id' => $validated['course_id'],
                    'user_id'   => auth()->id(),
                ]);
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['course_id' => 'Mata pelajaran tidak ditemukan atau bukan milik Anda.']);
            }
        }

        \Log::info('Course ditemukan:', ['course' => $course->title]);

        $data = [
            'title'       => $validated['title'],
            'instruction' => $validated['instruction'],
            'deadline'    => $validated['deadline'],
            'link'        => $validated['link'] ?? null,
            'file_path'   => null,
        ];

        // Handle upload file
        if ($request->hasFile('file_soal')) {
            try {
                $data['file_path'] = $request->file('file_soal')
                    ->store('assignments/soal', 'public');
                \Log::info('File berhasil diupload:', ['path' => $data['file_path']]);
            } catch (\Exception $e) {
                \Log::error('Gagal upload file:', ['error' => $e->getMessage()]);
            }
        }

        // Simpan ke database
        try {
            $assignment = $course->assignments()->create($data);
            \Log::info('Tugas berhasil disimpan:', ['assignment_id' => $assignment->id]);
        } catch (\Exception $e) {
            \Log::error('Gagal simpan tugas:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menyimpan tugas: ' . $e->getMessage()]);
        }

        return redirect()->route('guru.assignments.index')
            ->with('success', 'Tugas berhasil dipublikasikan!');
    }

    public function edit(Assignment $assignment)
    {
        $courses = Course::where('user_id', auth()->id())->get();
        return view('guru.assignments.edit', compact('assignment', 'courses'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'instruction' => 'required|string',
            'deadline'    => 'required|date',
            'link'        => 'nullable|url|max:500',
            'file_soal'   => 'nullable|mimes:pdf,doc,docx,zip|max:5120',
        ], [
            'title.required'       => 'Judul tugas wajib diisi.',
            'instruction.required' => 'Instruksi tugas wajib diisi.',
            'deadline.required'    => 'Tenggat waktu wajib diisi.',
            'deadline.date'        => 'Format tenggat waktu tidak valid.',
            'link.url'             => 'Format link tidak valid.',
            'file_soal.mimes'      => 'File harus berformat PDF, DOC, DOCX, atau ZIP.',
            'file_soal.max'        => 'Ukuran file maksimal 5MB.',
        ]);

        $data = $request->only(['title', 'instruction', 'deadline', 'link']);

        if ($request->hasFile('file_soal')) {
            if ($assignment->file_path) {
                Storage::disk('public')->delete($assignment->file_path);
            }
            $data['file_path'] = $request->file('file_soal')
                ->store('assignments/soal', 'public');
        }

        $assignment->update($data);

        return redirect()->route('guru.assignments.index')
            ->with('success', 'Tugas berhasil diperbarui!');
    }

    public function destroy(Assignment $assignment)
    {
        if ($assignment->file_path) {
            Storage::disk('public')->delete($assignment->file_path);
        }

        $assignment->delete();

        return back()->with('success', 'Tugas berhasil dihapus!');
    }
}
