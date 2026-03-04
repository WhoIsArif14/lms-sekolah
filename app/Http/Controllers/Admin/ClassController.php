<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::orderBy('grade', 'asc')->get();
        return view('admin.classes.index', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'grade' => 'required', // Contoh: 10, 11, 12
            'name' => 'required',  // Contoh: IPA 1, Merdeka A
        ]);

        SchoolClass::create($request->all());
        return back()->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function destroy(SchoolClass $class)
    {
        $class->delete();
        return back()->with('success', 'Kelas berhasil dihapus!');
    }
}
