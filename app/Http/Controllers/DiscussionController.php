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
}