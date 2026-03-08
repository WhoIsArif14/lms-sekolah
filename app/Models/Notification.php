<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'is_read',
        'data',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array',
    ];

    /**
     * Relationship with User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Create attendance notification for parent
     */
    public static function createAttendanceNotification($student, $type, $note = null)
    {
        if (!$student->parent_id) {
            return null; // No parent linked
        }

        $parent = User::find($student->parent_id);
        if (!$parent) {
            return null;
        }

        $title = "Notifikasi Absensi Anak";
        $message = self::generateAttendanceMessage($student->name, $type, $note);

        return self::create([
            'user_id' => $parent->id,
            'title' => $title,
            'message' => $message,
            'type' => 'attendance',
            'data' => [
                'student_id' => $student->id,
                'student_name' => $student->name,
                'attendance_type' => $type,
                'note' => $note,
            ],
        ]);
    }

    /**
     * Generate attendance message
     */
    private static function generateAttendanceMessage($studentName, $type, $note = null)
    {
        $baseMessage = "Ananda {$studentName} telah melakukan absensi.";

        switch ($type) {
            case 'MASUK':
                return $baseMessage . " Status: HADIR (Masuk sekolah)";
            case 'PULANG':
                return $baseMessage . " Status: HADIR (Pulang sekolah)";
            case 'IZIN':
                return $baseMessage . " Status: IZIN" . ($note ? ". Alasan: {$note}" : "");
            case 'SAKIT':
                return $baseMessage . " Status: SAKIT" . ($note ? ". Catatan: {$note}" : "");
            default:
                return $baseMessage . " Status: {$type}";
        }
    }
}
