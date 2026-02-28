<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
{
    // Mengambil semua kursus yang punya jadwal, urutkan berdasarkan hari
    $schedules = \App\Models\Course::whereNotNull('day')
                ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
                ->orderBy('time_start')
                ->get();

    return view('jadwal.index', compact('schedules'));
}
}
