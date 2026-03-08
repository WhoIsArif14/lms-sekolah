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

    // 6. Download Template Soal Excel
    public function downloadQuestionTemplate($id)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Soal');

        // Header
        $headers = ['Tipe Soal', 'Pertanyaan', 'Opsi A', 'Opsi B', 'Opsi C', 'Opsi D', 'Jawaban Benar'];
        $sheet->fromArray($headers, NULL, 'A1');

        // Style header
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5']
            ],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        // Contoh soal pilihan ganda
        $sheet->setCellValue('A2', 'pilihan_ganda');
        $sheet->setCellValue('B2', 'Ibukota Indonesia adalah?');
        $sheet->setCellValue('C2', 'Jakarta');
        $sheet->setCellValue('D2', 'Bandung');
        $sheet->setCellValue('E2', 'Surabaya');
        $sheet->setCellValue('F2', 'Medan');
        $sheet->setCellValue('G2', 'a');

        // Contoh soal pilihan ganda 2
        $sheet->setCellValue('A3', 'pilihan_ganda');
        $sheet->setCellValue('B3', 'Berapakah hasil dari 5 x 5?');
        $sheet->setCellValue('C3', '20');
        $sheet->setCellValue('D3', '25');
        $sheet->setCellValue('E3', '30');
        $sheet->setCellValue('F3', '35');
        $sheet->setCellValue('G3', 'b');

        // Contoh soal essay
        $sheet->setCellValue('A4', 'uraian');
        $sheet->setCellValue('B4', 'Jelaskan proses fotosintesis pada tumbuhan!');
        $sheet->setCellValue('C4', '(kosongkan)');
        $sheet->setCellValue('D4', '(kosongkan)');
        $sheet->setCellValue('E4', '(kosongkan)');
        $sheet->setCellValue('F4', '(kosongkan)');
        $sheet->setCellValue('G4', '(kosongkan)');

        // Warna baris contoh
        $sheet->getStyle('A2:G3')->applyFromArray([
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEF2FF']],
        ]);
        $sheet->getStyle('A4:G4')->applyFromArray([
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0F9FF']],
        ]);

        // Border
        $sheet->getStyle('A1:G4')->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        ]);

        // Info tambahan
        $sheet->setCellValue('A6', 'CATATAN PENGISIAN:');
        $sheet->getStyle('A6')->applyFromArray(['font' => ['bold' => true, 'color' => ['rgb' => '4F46E5']]]);
        $sheet->setCellValue('A7', '• Kolom "Tipe Soal" diisi: pilihan_ganda atau uraian');
        $sheet->setCellValue('A8', '• Kolom "Jawaban Benar" diisi: a, b, c, atau d (hanya untuk pilihan_ganda)');
        $sheet->setCellValue('A9', '• Untuk soal uraian, kolom Opsi A-D dan Jawaban Benar dikosongkan');
        $sheet->setCellValue('A10', '• Baris pertama adalah header, jangan dihapus');

        $sheet->getStyle('A7:A10')->applyFromArray([
            'font' => ['color' => ['rgb' => '6B7280']],
        ]);

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'Template_Soal_Ujian.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    // 7. Import Soal dari Excel
    public function importQuestions(Request $request, $examId)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ], [
            'file.required' => 'File wajib dipilih.',
            'file.mimes'    => 'File harus berformat xlsx, xls, atau csv.',
            'file.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        $exam = Exam::findOrFail($examId);

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file('file')->getRealPath());
            $data = $spreadsheet->getActiveSheet()->toArray();
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'File tidak dapat dibaca. Pastikan format file benar.']);
        }

        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        foreach ($data as $index => $row) {
            // Skip header
            if ($index == 0) continue;

            // Skip baris kosong
            if (empty($row[0]) || empty($row[1])) continue;

            $tipe       = strtolower(trim($row[0]));
            $pertanyaan = trim($row[1]);

            // Validasi tipe soal
            if (!in_array($tipe, ['pilihan_ganda', 'uraian'])) {
                $errors[] = "Baris " . ($index + 1) . ": Tipe soal '{$tipe}' tidak valid. Gunakan 'pilihan_ganda' atau 'uraian'.";
                $skipped++;
                continue;
            }

            $questionData = [
                'exam_id'       => $examId,
                'type'          => $tipe,
                'question_text' => $pertanyaan,
                'options'       => null,
                'correct_answer' => null,
            ];

            // Jika pilihan ganda, validasi opsi dan jawaban
            if ($tipe == 'pilihan_ganda') {
                $opsiA = trim($row[2] ?? '');
                $opsiB = trim($row[3] ?? '');
                $opsiC = trim($row[4] ?? '');
                $opsiD = trim($row[5] ?? '');
                $jawaban = strtolower(trim($row[6] ?? ''));

                if (empty($opsiA) || empty($opsiB)) {
                    $errors[] = "Baris " . ($index + 1) . ": Soal pilihan ganda minimal harus ada Opsi A dan B.";
                    $skipped++;
                    continue;
                }

                if (!in_array($jawaban, ['a', 'b', 'c', 'd'])) {
                    $errors[] = "Baris " . ($index + 1) . ": Jawaban benar '{$jawaban}' tidak valid. Gunakan a, b, c, atau d.";
                    $skipped++;
                    continue;
                }

                $questionData['options'] = json_encode([
                    'a' => $opsiA,
                    'b' => $opsiB,
                    'c' => $opsiC ?: null,
                    'd' => $opsiD ?: null,
                ]);
                $questionData['correct_answer'] = $jawaban;
            }

            try {
                Question::create($questionData);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Baris " . ($index + 1) . ": Gagal menyimpan soal. " . $e->getMessage();
                $skipped++;
            }
        }

        $message = "{$imported} soal berhasil diimpor.";
        if ($skipped > 0) $message .= " {$skipped} soal dilewati.";
        if (!empty($errors)) session()->flash('import_errors', $errors);

        return back()->with('success', $message);
    }
}
