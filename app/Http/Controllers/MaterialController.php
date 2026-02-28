<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function store(Request $request, Course $course)
    {
        // gunakan nama field yang sama dengan formulir guru dan validasi file/link
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:file,video_link',
            'file_content' => 'required_if:type,file|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
            // only validate URL when a video link is provided; make it nullable otherwise
            'link_content' => 'required_if:type,video_link|nullable|url',
        ]);

        if ($request->type === 'file') {
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

        return redirect()->back()->with('success', 'Materi berhasil ditambahkan ke kursus!');
    }

    public function destroy(Material $material)
    {
        $material->delete();
        return redirect()->back()->with('success', 'Materi berhasil dihapus!');
    }
}
