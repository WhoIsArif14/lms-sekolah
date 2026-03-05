<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Course;
use App\Models\Question;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    // 1. Menampilkan daftar ujian berdasarkan Mata Pelajaran
    public function index($courseId)
    {
        $course = Course::findOrFail($courseId);
        $exams = Exam::where('course_id', $courseId)->get();
        return view('guru.exams.index', compact('course', 'exams'));
    }

    // 2. Menyimpan Identitas Ujian Baru (Judul & Durasi)
    public function store(Request $request, $courseId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
        ]);

        Exam::create([
            'course_id' => $courseId,
            'title' => $request->title,
            'duration' => $request->duration,
            'is_active' => false, // Default mati sampai Guru mempublish
        ]);

        return back()->with('success', 'Ujian berhasil dibuat! Silakan tambah soal.');
    }

    // 3. Halaman Kelola Soal (Bank Soal)
    public function show($id)
    {
        $exam = Exam::with('questions')->findOrFail($id);
        return view('guru.exams.show', compact('exam'));
    }

    // 4. Menyimpan Soal (PG atau Uraian)
    public function storeQuestion(Request $request, $examId)
    {
        $request->validate([
            'type' => 'required|in:pilihan_ganda,uraian',
            'question_text' => 'required',
        ]);

        $data = [
            'exam_id' => $examId,
            'type' => $request->type,
            'question_text' => $request->question_text,
        ];

        // Jika Pilihan Ganda, simpan opsi dan kunci jawaban
        if ($request->type == 'pilihan_ganda') {
            $data['options'] = json_encode([
                'a' => $request->a,
                'b' => $request->b,
                'c' => $request->c,
                'd' => $request->d,
            ]);
            $data['correct_answer'] = $request->correct_answer;
        }

        Question::create($data);
        return back()->with('success', 'Soal berhasil ditambahkan!');
    }

    // 5. Aktifkan/Nonaktifkan Ujian
    public function toggleStatus($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->is_active = !$exam->is_active;
        $exam->save();

        $status = $exam->is_active ? 'diaktifkan' : 'dimatikan';
        return back()->with('success', "Ujian berhasil $status!");
    }
}
