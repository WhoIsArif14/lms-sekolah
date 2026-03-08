<?php

namespace App\Http\Controllers\Ortu;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use App\Models\ExamResponse;
use App\Models\Notification;

class ParentController extends Controller
{
    public function index()
    {
        // Mengambil data anak-anak dari user ortu yang login
        $children = auth()->user()->children()->with('schoolClass')->get();

        // Mengambil notifikasi untuk orang tua
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->take(10)
            ->get();

        // Hitung notifikasi unread
        $unreadCount = Notification::where('user_id', auth()->id())
            ->unread()
            ->count();

        return view('ortu.dashboard', compact('children', 'notifications', 'unreadCount'));
    }

    public function showChildActivity($id)
    {
        $child = User::findOrFail($id);

        // Ambil 10 absensi terakhir
        $attendances = \App\Models\Attendance::where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Ambil nilai ujian terbaru
        $exams = \App\Models\ExamResponse::where('user_id', $id)
            ->with('exam')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('ortu.child_detail', compact('child', 'attendances', 'exams'));
    }

    public function notifications()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('ortu.notifications', compact('notifications'));
    }

    public function markNotificationAsRead($notificationId)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->where('id', $notificationId)
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllNotificationsAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->unread()
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
