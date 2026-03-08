<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; // Tambahkan \Writer\


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

    // Simpan kelas baru ke database
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

    // Hapus kelas tertentu
    public function destroy($id)
    {
        $class = SchoolClass::findOrFail($id);
        $class->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }

    // Fungsi Proses Import Siswa per kelas
    public function importSiswa(Request $request, $classId)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $data = $spreadsheet->getActiveSheet()->toArray();

        foreach ($data as $index => $row) {
            if ($index == 0) continue; // Lewati baris header (Nama, Email, dll)

            if (!empty($row[0]) && !empty($row[1])) {
                User::create([
                    'name'            => $row[0], // Kolom A: Nama
                    'email'           => $row[1], // Kolom B: Email
                    'password'        => Hash::make($row[2] ?? 'password123'), // Kolom C: Pass
                    'role'            => 'siswa',
                    'school_class_id' => $classId, // Otomatis masuk ke kelas ini
                ]);
            }
        }

        return back()->with('success', 'Data siswa berhasil diimpor ke kelas!');
    }

    /**
     * Download template import siswa khusus per kelas
     */
    public function downloadStudentTemplate($classId)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // header sederhana
        $sheet->setCellValue('A1', 'Nama');
        $sheet->setCellValue('B1', 'Email');
        $sheet->setCellValue('C1', 'Password');

        // styling
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);
        $sheet->getStyle('A1:C1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFDEBD0');

        // column widths
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(20);

        // example row
        $sheet->setCellValue('A2', 'Budi Santoso');
        $sheet->setCellValue('B2', 'budi@example.com');
        $sheet->setCellValue('C2', 'Password123!');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = "Template_Import_Siswa_Kelas_{$classId}.xlsx";
        $response = response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename);

        return $response;
    }

    // Fungsi Proses Import Orang Tua per kelas
    public function importParents(Request $request, $classId)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $data = $spreadsheet->getActiveSheet()->toArray();

        foreach ($data as $index => $row) {
            if ($index == 0) continue; // Lewati header

            if (!empty($row[0]) && !empty($row[1])) {
                User::create([
                    'name'            => $row[0],
                    'email'           => $row[1],
                    'password'        => Hash::make($row[2] ?? 'password123'),
                    'role'            => 'ortu',
                    'parent_phone'    => $row[3] ?? null,
                    'school_class_id' => $classId, // tandai file dengan kelas yang sama
                ]);
            }
        }

        return back()->with('success', 'Data orang tua berhasil diimpor ke kelas!');
    }
}
