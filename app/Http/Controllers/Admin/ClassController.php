<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;

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

    // Fungsi Proses Import
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
}
