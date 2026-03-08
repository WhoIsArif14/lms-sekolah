<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\User;
use App\Models\Course;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; // Pastikan ini ada

class ClassController extends Controller
{
    // Halaman daftar kelas (Manajemen Kelas)
    public function index()
    {
        $classes = SchoolClass::withCount('students')->get();
        return view('admin.classes.index', compact('classes'));
    }

    public function show($id)
    {
        $class = SchoolClass::findOrFail($id);
        $students = User::where('school_class_id', $id)->get();
        return view('admin.classes.show', compact('class', 'students'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'grade' => ['required', 'string', 'max:10'],
            'name'  => ['required', 'string', 'max:255'],
        ]);

        SchoolClass::create($data);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil dibuat.');
    }

    public function destroy($id)
    {
        $class = SchoolClass::findOrFail($id);
        $class->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }

    // --- FITUR IMPORT SISWA ---
    public function importSiswa(Request $request, $classId)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $data = $spreadsheet->getActiveSheet()->toArray();

        foreach ($data as $index => $row) {
            if ($index == 0) continue; 

            if (!empty($row[0]) && !empty($row[1])) {
                User::create([
                    'name'            => $row[0], 
                    'email'           => $row[1], 
                    'password'        => Hash::make($row[2] ?? 'password123'), 
                    'role'            => 'siswa',
                    'school_class_id' => $classId, 
                ]);
            }
        }

        return back()->with('success', 'Data siswa berhasil diimpor ke kelas!');
    }

    public function downloadStudentTemplate($classId)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Nama');
        $sheet->setCellValue('B1', 'Email');
        $sheet->setCellValue('C1', 'Password');

        $sheet->getStyle('A1:C1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(30);

        // Contoh baris
        $sheet->setCellValue('A2', 'Budi Santoso');
        $sheet->setCellValue('B2', 'budi@example.com');
        $sheet->setCellValue('C2', 'Password123!');

        $writer = new Xlsx($spreadsheet); // Perbaikan Namespace
        $filename = "Template_Import_Siswa_Kelas_{$classId}.xlsx";
        
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename);
    }

    // --- FITUR IMPORT GURU & JADWAL ---
    
    // Tambahkan fungsi ini agar tombol "Download Template Guru" di View berfungsi
    public function downloadTeacherTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header Sesuai Logika Import
        $headers = ['Nama Guru', 'Email', 'Password', 'Mata Pelajaran', 'Nama Kelas', 'Hari', 'Jam Mulai', 'Jam Selesai'];
        $sheet->fromArray($headers, NULL, 'A1');

        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        foreach(range('A','H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Contoh data
        $sheet->setCellValue('A2', 'Andi Wijaya');
        $sheet->setCellValue('B2', 'andi@guru.com');
        $sheet->setCellValue('C2', 'guru123');
        $sheet->setCellValue('D2', 'Matematika');
        $sheet->setCellValue('E2', 'X-A'); // Harus sama dengan nama kelas di DB
        $sheet->setCellValue('F2', 'Senin');
        $sheet->setCellValue('G2', '07:30');
        $sheet->setCellValue('H2', '09:00');

        $writer = new Xlsx($spreadsheet);
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, "Template_Import_Guru_Jadwal.xlsx");
    }

    public function importTeachers(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
        $data = $spreadsheet->getActiveSheet()->toArray();

        foreach ($data as $index => $row) {
            if ($index == 0 || empty($row[1])) continue; 

            // 1. Buat atau Temukan User Guru
            $teacher = User::firstOrCreate(
                ['email' => $row[1]], 
                [
                    'name'     => $row[0], 
                    'password' => Hash::make($row[2] ?? 'guru123'),
                    'role'     => 'teacher',
                ]
            );

            // 2. Cari Kelas berdasarkan Nama (Kolom E)
            $class = SchoolClass::where('name', $row[4])->first();

            if ($class) {
                // 3. Buat Kursus/Mapel (Kolom D)
                $course = Course::create([
                    'title'           => $row[3],
                    'teacher_id'      => $teacher->id,
                    'school_class_id' => $class->id,
                ]);

                // 4. Simpan Jadwal (Kolom F, G, H)
                if (!empty($row[5])) {
                    Schedule::create([
                        'course_id'  => $course->id,
                        'day'        => $row[5],
                        'start_time' => $row[6],
                        'end_time'   => $row[7],
                    ]);
                }
            }
        }

        return back()->with('success', 'Data Guru, Mapel, dan Jadwal berhasil diimpor!');
    }
}