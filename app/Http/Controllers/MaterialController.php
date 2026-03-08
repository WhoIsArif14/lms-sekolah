<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Material;

class MaterialController extends Controller
{
    public function store(Request $request, $course)
    {
        $course = \App\Models\Course::findOrFail($course);

        // cek apakah guru pemilik course
        if ($course->user_id != auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk menambah materi ke kursus ini.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:file,video_link',
            'file_content' => 'required_if:type,file|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
            'link_content' => 'required_if:type,video_link|nullable|url',
        ]);

        if ($request->type == 'file') {

            $path = $request->file('file_content')->store('materials', 'public');

            $content = $path;
        } else {

            $content = $request->link_content;
        }

        $course->materials()->create([
            'title' => $request->title,
            'type' => $request->type,
            'content' => $content,
        ]);

        return back()->with('success', 'Materi berhasil ditambahkan!');
    }

    public function destroy(Material $material)
    {
        // Sekarang Storage::disk tidak akan error lagi
        if ($material->type === 'file' && $material->content) {
            Storage::disk('public')->delete($material->content);
        }

        $material->delete();
        return back()->with('success', 'Materi berhasil dihapus!');
    }
}
