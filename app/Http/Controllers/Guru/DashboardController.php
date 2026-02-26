<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung jumlah kursus milik guru yang login
        $courseCount = Course::where('user_id', auth()->id())->count();
        
        return view('guru.dashboard', compact('courseCount'));
    }
}