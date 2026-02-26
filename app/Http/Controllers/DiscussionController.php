<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\Course;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Discussion::create([
            'course_id' => $course->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return back()->with('success', 'Pesan diskusi terkirim!');
    }

    public function destroy(Discussion $discussion)
    {
        // Hanya Admin atau Guru pemilik kursus yang boleh menghapus
        // Atau siswa yang menulis pesan itu sendiri
        if (
            auth()->user()->role === 'admin' ||
            auth()->id() === $discussion->course->user_id ||
            auth()->id() === $discussion->user_id
        ) {

            $discussion->delete();
            return back()->with('success', 'Pesan berhasil dihapus.');
        }

        abort(403, 'Anda tidak memiliki izin menghapus pesan ini.');
    }
}
