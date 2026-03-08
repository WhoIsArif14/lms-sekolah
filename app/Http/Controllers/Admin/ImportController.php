<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ImportController extends Controller
{
    public function index()
    {
        return view('admin.imports.index');
    }

    // =============================================
    // IMPORT SISWA
    // =============================================
    public function importStudents(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ], [
            'file.required' => 'File wajib dipilih.',
            'file.mimes'    => 'File harus berformat xlsx, xls, atau csv.',
            'file.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
            $data = $spreadsheet->getActiveSheet()->toArray();
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'File tidak dapat dibaca. Pastikan format file benar.']);
        }

        $imported     = 0;
        $skipped      = 0;
        $ortuDibuat   = 0;
        $errors       = [];

        foreach ($data as $index => $row) {
            if ($index == 0) continue;
            if (empty($row[0]) || empty($row[1])) continue;

            $namaSiswa  = trim($row[0]);
            $emailSiswa = trim($row[1]);
            $password   = $row[2] ?? 'Password123!';
            $namaOrtu   = trim($row[3] ?? '');
            $emailOrtu  = trim($row[4] ?? '');
            $passwordOrtu = $row[5] ?? 'Password123!';

            // Validasi email siswa
            if (!filter_var($emailSiswa, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Baris " . ($index + 1) . ": Email siswa '{$emailSiswa}' tidak valid.";
                $skipped++;
                continue;
            }

            // Skip jika email siswa sudah ada
            if (User::where('email', $emailSiswa)->exists()) {
                $errors[] = "Baris " . ($index + 1) . ": Email siswa '{$emailSiswa}' sudah terdaftar.";
                $skipped++;
                continue;
            }

            // Handle akun orang tua
            $parentId = null;
            if (!empty($emailOrtu) && filter_var($emailOrtu, FILTER_VALIDATE_EMAIL)) {
                $ortu = User::where('email', $emailOrtu)->first();

                if (!$ortu) {
                    // Buat akun orang tua baru
                    $ortu = User::create([
                        'name'     => !empty($namaOrtu) ? $namaOrtu : 'Orang Tua ' . $namaSiswa,
                        'email'    => $emailOrtu,
                        'password' => Hash::make($passwordOrtu),
                        'role'     => 'ortu',
                    ]);
                    $ortuDibuat++;
                } elseif ($ortu->role !== 'ortu') {
                    // Jika akun sudah ada tapi bukan ortu, update rolenya
                    $ortu->update(['role' => 'ortu']);
                }

                $parentId = $ortu->id;
            }

            // Buat akun siswa
            User::create([
                'name'      => $namaSiswa,
                'email'     => $emailSiswa,
                'password'  => Hash::make($password),
                'role'      => 'siswa',
                'parent_id' => $parentId,
            ]);

            $imported++;
        }

        $message = "{$imported} siswa berhasil diimpor.";
        if ($ortuDibuat > 0) $message .= " {$ortuDibuat} akun orang tua baru dibuat.";
        if ($skipped > 0)    $message .= " {$skipped} baris dilewati.";
        if (!empty($errors)) session()->flash('import_errors', $errors);

        return back()->with('success', $message);
    }

    // =============================================
    // IMPORT ORANG TUA
    // =============================================
    public function importParents(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ], [
            'file.required' => 'File wajib dipilih.',
            'file.mimes'    => 'File harus berformat xlsx, xls, atau csv.',
            'file.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
            $data = $spreadsheet->getActiveSheet()->toArray();
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'File tidak dapat dibaca. Pastikan format file benar.']);
        }

        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        foreach ($data as $index => $row) {
            if ($index == 0) continue;
            if (empty($row[0]) || empty($row[1])) continue;

            $nama  = trim($row[0]);
            $email = trim($row[1]);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Baris " . ($index + 1) . ": Email '{$email}' tidak valid.";
                $skipped++;
                continue;
            }

            if (User::where('email', $email)->exists()) {
                $errors[] = "Baris " . ($index + 1) . ": Email '{$email}' sudah terdaftar.";
                $skipped++;
                continue;
            }

            User::create([
                'name'     => $nama,
                'email'    => $email,
                'password' => Hash::make($row[2] ?? 'Password123!'),
                'role'     => 'ortu',
            ]);
            $imported++;
        }

        $message = "{$imported} orang tua berhasil diimpor.";
        if ($skipped > 0) $message .= " {$skipped} baris dilewati.";
        if (!empty($errors)) session()->flash('import_errors', $errors);

        return back()->with('success', $message);
    }

    // =============================================
    // IMPORT GURU & JADWAL
    // =============================================
    public function importTeachers(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ], [
            'file.required' => 'File wajib dipilih.',
            'file.mimes'    => 'File harus berformat xlsx, xls, atau csv.',
            'file.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
            $data = $spreadsheet->getActiveSheet()->toArray();
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'File tidak dapat dibaca. Pastikan format file benar.']);
        }

        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        foreach ($data as $index => $row) {
            if ($index == 0) continue;
            if (empty($row[0]) || empty($row[1])) continue;

            $namaGuru   = trim($row[0]);
            $email      = trim($row[1]);
            $password   = $row[2] ?? 'guru123';
            $namaMapel  = trim($row[3] ?? '');
            $namaKelas  = trim($row[4] ?? '');
            $hari       = trim($row[5] ?? '');
            $jamMulai   = trim($row[6] ?? '');
            $jamSelesai = trim($row[7] ?? '');

            // Validasi email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Baris " . ($index + 1) . ": Email '{$email}' tidak valid.";
                $skipped++;
                continue;
            }

            // Validasi nama mapel
            if (empty($namaMapel)) {
                $errors[] = "Baris " . ($index + 1) . ": Nama mata pelajaran kosong.";
                $skipped++;
                continue;
            }

            // 1. Buat atau temukan user guru
            $guru = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'     => $namaGuru,
                    'password' => Hash::make($password),
                    'role'     => 'guru',
                ]
            );

            // Pastikan role-nya guru
            if ($guru->role !== 'guru') {
                $guru->update(['role' => 'guru']);
            }

            // 2. Cari kelas berdasarkan nama - support format "10-AK 1" atau "AK 1"
            $class = null;
            if (!empty($namaKelas)) {
                // Coba cari langsung by name dulu
                $class = SchoolClass::where('name', $namaKelas)->first();

                // Jika tidak ketemu, coba parse format "grade-name" seperti "10-AK 1"
                if (!$class && str_contains($namaKelas, '-')) {
                    $parts = explode('-', $namaKelas, 2);
                    $grade = trim($parts[0]);
                    $name  = trim($parts[1]);
                    $class = SchoolClass::where('grade', $grade)
                        ->where('name', $name)
                        ->first();
                }

                if (!$class) {
                    $errors[] = "Baris " . ($index + 1) . ": Kelas '{$namaKelas}' tidak ditemukan. Guru '{$namaGuru}' tetap dibuat tanpa kelas.";
                } else {
                    $classroom = $class->grade . ' - ' . $class->name;
                }
            }

            // 3. Buat kursus — sesuai kolom yang dipakai GuruCourseController
            try {
                $course = Course::create([
                    'title'           => $namaMapel,
                    'description'     => 'Mata pelajaran ' . $namaMapel,
                    'user_id'         => $guru->id,
                    'school_class_id' => $class ? $class->id : null,
                    'classroom'       => $classroom,
                    'day'             => !empty($hari) ? $hari : null,
                    'time_start'      => !empty($jamMulai) ? $jamMulai : null,
                    'time_end'        => !empty($jamSelesai) ? $jamSelesai : null,
                ]);

                Log::info("Course '{$namaMapel}' berhasil dibuat untuk guru '{$namaGuru}'", [
                    'course_id' => $course->id,
                    'user_id'   => $guru->id,
                ]);

                $imported++;
            } catch (\Exception $e) {
                Log::error("Gagal buat course baris " . ($index + 1) . ": " . $e->getMessage());
                $errors[] = "Baris " . ($index + 1) . ": Gagal menyimpan mata pelajaran. " . $e->getMessage();
                $skipped++;
            }
        }

        $message = "{$imported} data guru & mata pelajaran berhasil diimpor.";
        if ($skipped > 0) $message .= " {$skipped} baris dilewati.";
        if (!empty($errors)) session()->flash('import_errors', $errors);

        return back()->with('success', $message);
    }

    // =============================================
    // DOWNLOAD TEMPLATE SISWA
    // =============================================
    public function downloadStudentTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Siswa');

        // Header
        $headers = [
            'Nama Siswa',
            'Email Siswa',
            'Password Siswa',
            'Nama Orang Tua',
            'Email Orang Tua',
            'Password Orang Tua',
        ];
        $sheet->fromArray($headers, NULL, 'A1');

        // Style header
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Contoh data baris 1
        $sheet->setCellValue('A2', 'Budi Santoso');
        $sheet->setCellValue('B2', 'budi@example.com');
        $sheet->setCellValue('C2', 'Password123!');
        $sheet->setCellValue('D2', 'Ahmad Subagyo');
        $sheet->setCellValue('E2', 'ahmad@example.com');
        $sheet->setCellValue('F2', 'Password123!');

        // Contoh data baris 2
        $sheet->setCellValue('A3', 'Siti Aminah');
        $sheet->setCellValue('B3', 'siti@example.com');
        $sheet->setCellValue('C3', 'Password123!');
        $sheet->setCellValue('D3', 'Dewi Rahayu');
        $sheet->setCellValue('E3', 'dewi@example.com');
        $sheet->setCellValue('F3', 'Password123!');

        // Contoh data baris 3 - siswa tanpa ortu (kolom ortu dikosongkan)
        $sheet->setCellValue('A4', 'Rafi Pratama');
        $sheet->setCellValue('B4', 'rafi@example.com');
        $sheet->setCellValue('C4', 'Password123!');
        $sheet->setCellValue('D4', '(opsional)');
        $sheet->setCellValue('E4', '(opsional)');
        $sheet->setCellValue('F4', '(opsional)');

        // Warna baris contoh
        $sheet->getStyle('A2:F3')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEF2FF']],
        ]);
        $sheet->getStyle('A4:F4')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F9FAFB']],
            'font' => ['italic' => true, 'color' => ['rgb' => '9CA3AF']],
        ]);

        // Border
        $sheet->getStyle('A1:F4')->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // Info tambahan
        $sheet->setCellValue('A6', 'CATATAN:');
        $sheet->getStyle('A6')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '4F46E5']],
        ]);
        $sheet->setCellValue('A7', '• Kolom Nama & Email Siswa wajib diisi.');
        $sheet->setCellValue('A8', '• Kolom Orang Tua bersifat opsional. Jika email ortu sudah terdaftar, akun ortu tidak akan dibuat ulang.');
        $sheet->setCellValue('A9', '• Jika email ortu belum ada, sistem akan otomatis membuat akun ortu baru.');
        $sheet->setCellValue('A10', '• Password default jika dikosongkan: Password123!');

        $sheet->getStyle('A7:A10')->applyFromArray([
            'font' => ['color' => ['rgb' => '6B7280']],
        ]);

        // Auto size
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'Template_Import_Siswa.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    // =============================================
    // DOWNLOAD TEMPLATE ORANG TUA
    // =============================================
    public function downloadParentTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Orang Tua');

        $headers = ['Nama', 'Email', 'Password'];
        $sheet->fromArray($headers, NULL, 'A1');

        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '16A34A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->setCellValue('A2', 'Ahmad Subagyo');
        $sheet->setCellValue('B2', 'ahmad@example.com');
        $sheet->setCellValue('C2', 'Password123!');
        $sheet->setCellValue('A3', 'Dewi Rahayu');
        $sheet->setCellValue('B3', 'dewi@example.com');
        $sheet->setCellValue('C3', 'Password123!');

        $sheet->getStyle('A2:C3')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0FDF4']],
        ]);

        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getStyle('A1:C3')->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        $writer = new Xlsx($spreadsheet);
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'Template_Import_OrangTua.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    // =============================================
    // DOWNLOAD TEMPLATE GURU & JADWAL
    // =============================================
    public function downloadTeacherTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Guru');

        $headers = [
            'Nama Guru',
            'Email',
            'Password',
            'Mata Pelajaran',
            'Nama Kelas',
            'Hari',
            'Jam Mulai',
            'Jam Selesai'
        ];
        $sheet->fromArray($headers, NULL, 'A1');

        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '7C3AED']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Ambil nama kelas dari database
        $kelasList = SchoolClass::orderBy('grade')->orderBy('name')->get();
        $namaKelasContoh  = $kelasList->isNotEmpty() ? $kelasList->first()->name : 'Fisika';
        $namaKelasContoh2 = $kelasList->count() > 1 ? $kelasList->get(1)->name : $namaKelasContoh;

        // Contoh baris 1
        $sheet->setCellValue('A2', 'Andi Wijaya');
        $sheet->setCellValue('B2', 'andi@guru.com');
        $sheet->setCellValue('C2', 'guru123');
        $sheet->setCellValue('D2', 'Matematika');
        $sheet->setCellValue('E2', $namaKelasContoh);
        $sheet->setCellValue('F2', 'Senin');
        $sheet->setCellValue('G2', '07:30');
        $sheet->setCellValue('H2', '09:00');

        // Contoh baris 2 (guru sama, kelas/hari berbeda)
        $sheet->setCellValue('A3', 'Andi Wijaya');
        $sheet->setCellValue('B3', 'andi@guru.com');
        $sheet->setCellValue('C3', 'guru123');
        $sheet->setCellValue('D3', 'Matematika');
        $sheet->setCellValue('E3', $namaKelasContoh2);
        $sheet->setCellValue('F3', 'Rabu');
        $sheet->setCellValue('G3', '10:00');
        $sheet->setCellValue('H3', '11:30');

        $sheet->getStyle('A2:H3')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F5F3FF']],
        ]);

        $sheet->getStyle('A1:H3')->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // Baris kosong pemisah
        $sheet->setCellValue('A5', 'DAFTAR NAMA KELAS YANG TERSEDIA DI SISTEM:');
        $sheet->getStyle('A5')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '7C3AED']],
        ]);

        $row = 6;
        foreach ($kelasList as $kelas) {
            $sheet->setCellValue('A' . $row, 'Tingkat ' . $kelas->grade . ' - ' . $kelas->name);
            $sheet->setCellValue('B' . $row, '← isi kolom "Nama Kelas" dengan: ' . $kelas->name);
            $sheet->getStyle('B' . $row)->applyFromArray([
                'font' => ['italic' => true, 'color' => ['rgb' => '6B7280']],
            ]);
            $row++;
        }

        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'Template_Import_Guru_Jadwal.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
