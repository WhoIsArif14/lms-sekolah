<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\StudentsImport;
use App\Imports\ParentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ImportController extends Controller
{
    /**
     * Tampilkan halaman import
     */
    public function index()
    {
        return view('admin.imports.index');
    }

    /**
     * Import data siswa dari file Excel/CSV
     */
    public function importStudents(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120', // Max 5MB
        ], [
            'file.required' => 'File harus dipilih',
            'file.mimes' => 'File harus berformat Excel atau CSV',
            'file.max' => 'Ukuran file maksimal 5MB',
        ]);

        try {
            $file = $request->file('file');
            $import = new StudentsImport();
            Excel::import($import, $file);

            return back()->with('success', 'Data siswa berhasil diimpor!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    /**
     * Import data orang tua dari file Excel/CSV
     */
    public function importParents(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120', // Max 5MB
        ], [
            'file.required' => 'File harus dipilih',
            'file.mimes' => 'File harus berformat Excel atau CSV',
            'file.max' => 'Ukuran file maksimal 5MB',
        ]);

        try {
            $file = $request->file('file');
            $import = new ParentsImport();
            Excel::import($import, $file);

            return back()->with('success', 'Data orang tua berhasil diimpor!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    /**
     * Download template untuk import siswa
     */
    public function downloadStudentTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $sheet->setCellValue('A1', 'Name');
        $sheet->setCellValue('B1', 'Email');
        $sheet->setCellValue('C1', 'Password');
        $sheet->setCellValue('D1', 'Parent Email');

        // Format header
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFB3E5FC');

        // Set column width
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(30);

        // Add example data
        $sheet->setCellValue('A2', 'Budi Santoso');
        $sheet->setCellValue('B2', 'budi@example.com');
        $sheet->setCellValue('C2', 'Password123!');
        $sheet->setCellValue('D2', 'parent@example.com');

        // Save and download
        $writer = new Xlsx($spreadsheet);
        $response = response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'Template_Import_Siswa.xlsx');

        return $response;
    }

    /**
     * Download template untuk import orang tua
     */
    public function downloadParentTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $sheet->setCellValue('A1', 'Name');
        $sheet->setCellValue('B1', 'Email');
        $sheet->setCellValue('C1', 'Password');

        // Format header
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);
        $sheet->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFC8E6C9');

        // Set column width
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(20);

        // Add example data
        $sheet->setCellValue('A2', 'Siti Nurjanah');
        $sheet->setCellValue('B2', 'siti@example.com');
        $sheet->setCellValue('C2', 'Password123!');

        // Save and download
        $writer = new Xlsx($spreadsheet);
        $response = response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'Template_Import_Orang_Tua.xlsx');

        return $response;
    }
}
