<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass; // Asumsi nama model kelasmu
use App\Models\User;


class AttendanceController extends Controller
{
    // 1. Lihat Daftar Semua Kelas
    public function index()
    {
        $classes = SchoolClass::withCount('students')->get();
        return view('admin.attendance.classes', compact('classes'));
    }

    // 2. Lihat Daftar Siswa di Kelas Tertentu & Status Absen Hari Ini
    public function showClass($classId)
    {
        $class = SchoolClass::findOrFail($classId);
        $today = now()->format('Y-m-d');

        // Ambil semua siswa di kelas ini beserta absennya hari ini
        $students = User::where('class_id', $classId)
            ->where('role', 'siswa')
            ->with(['attendances' => function ($query) use ($today) {
                $query->where('attendance_date', $today);
            }])
            ->get();

        return view('admin.attendance.class_detail', compact('class', 'students', 'today'));
    }
}
