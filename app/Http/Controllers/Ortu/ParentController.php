<?php

namespace App\Http\Controllers\Ortu;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use App\Models\ExamResponse;

class ParentController extends Controller
{
    public function index()
    {
        // Mengambil data anak-anak dari user ortu yang login
        $children = auth()->user()->children()->with('schoolClass')->get();
        return view('ortu.dashboard', compact('children'));
    }

    public function showChildActivity($childId)
    {
        $child = User::where('id', $childId)->where('parent_id', auth()->id())->firstOrFail();
        
        // Ambil riwayat absen
        $attendances = Attendance::where('user_id', $childId)->latest()->take(10)->get();
        
        // Ambil riwayat nilai ujian
        $examResults = ExamResponse::where('user_id', $childId)->with('exam')->latest()->get();

        return view('ortu.child_detail', compact('child', 'attendances', 'examResults'));
    }
}
