<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        // Ambil ujian yang aktif di kelas siswa tersebut
        $exams = \App\Models\Exam::whereHas('course', function ($q) use ($user) {
            $q->where('school_class_id', $user->school_class_id);
        })->where('is_active', true)->get();

        return view('siswa.exams.index', compact('exams'));
    }

    public function enter($id)
    {
        $exam = \App\Models\Exam::with('questions')->findOrFail($id);

        // Cek apakah siswa sudah pernah mengerjakan
        $alreadyDone = \App\Models\ExamResponse::where('exam_id', $id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($alreadyDone) {
            return back()->with('error', 'Anda sudah mengerjakan ujian ini.');
        }

        return view('siswa.exams.run', compact('exam'));
    }

    public function submit(Request $request, $id)
    {
        $exam = \App\Models\Exam::with('questions')->findOrFail($id);
        $answers = $request->input('answers'); // Mengambil array jawaban dari form

        $score = 0;
        $totalQuestions = $exam->questions->count();

        // Hitung skor otomatis untuk Pilihan Ganda
        foreach ($exam->questions as $q) {
            if ($q->type == 'pilihan_ganda') {
                if (isset($answers[$q->id]) && $answers[$q->id] == $q->correct_answer) {
                    $score++;
                }
            }
        }

        // Hitung nilai akhir skala 100 (hanya untuk PG sementara)
        $finalScore = ($totalQuestions > 0) ? ($score / $totalQuestions) * 100 : 0;

        \App\Models\ExamResponse::create([
            'exam_id' => $id,
            'user_id' => auth()->id(),
            'answers' => $answers,
            'score' => $finalScore,
            'submitted_at' => now(),
        ]);

        return redirect()->route('siswa.exams.index')->with('success', 'Ujian telah selesai dikerjakan!');
    }
}
