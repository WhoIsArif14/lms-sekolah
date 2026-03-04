<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Hanya tampilkan mapel yang school_class_id nya cocok dengan milik siswa
        $courses = \App\Models\Course::where('school_class_id', $user->school_class_id)
            ->with('guru')
            ->get();

        return view('siswa.dashboard', compact('courses'));
    }
    public function show(Course $course)
    {
        // Mengambil materi dan tugas yang terkait dengan kursus ini
        $course->load(['materials', 'assignments.submissions' => function ($query) {
            $query->where('user_id', Auth::id());
        }]);

        return view('siswa.courses.show', compact('course'));
    }

    public function submitAssignment(Request $request, Assignment $assignment)
    {
        $request->validate([
            'file_jawaban' => 'required|mimes:pdf,doc,docx,zip,jpg,png|max:5120',
        ]);

        $path = $request->file('file_jawaban')->store('submissions', 'public');

        \App\Models\Submission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'user_id' => Auth::id()],
            ['file_path' => $path]
        );

        return back()->with('success', 'Tugas berhasil dikirim!');
    }

    public function indexAssignments()
    {
        // Mengambil semua tugas yang tersedia untuk siswa
        $assignments = \App\Models\Assignment::with('course')->get();

        return view('siswa.assignments.index', compact('assignments'));
    }

    public function attendanceIndex()
    {
        $attendances = \App\Models\Attendance::where('user_id', Auth::id())
            ->orderBy('attendance_date', 'desc')
            ->get();

        $alreadyAbsen = \App\Models\Attendance::where('user_id', Auth::id())
            ->where('attendance_date', now()->format('Y-m-d'))
            ->exists();

        return view('siswa.attendance.index', compact('attendances', 'alreadyAbsen'));
    }

    public function storeAttendance(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit',
            'note' => 'nullable|string|max:255',
            'attachment' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'lat_siswa' => 'required_if:status,hadir', // Lat wajib jika status 'hadir'
            'lng_siswa' => 'required_if:status,hadir', // Lng wajib jika status 'hadir'
        ]);

        // 2. Ambil Pengaturan dari Admin
        $setting = \App\Models\AttendanceSetting::first();
        if (!$setting) {
            return back()->with('error', 'Pengaturan absensi belum dikonfigurasi oleh Admin.');
        }

        // 3. Cek apakah Absen sedang dibuka oleh Admin
        if (!$setting->is_open) {
            return back()->with('error', 'Maaf, absensi hari ini sedang ditutup oleh Admin.');
        }

        $now = Carbon::now();
        $today = $now->format('Y-m-d');
        $currentTime = $now->format('H:i:s');

        // 4. Cek apakah Siswa sudah absen hari ini (Cegah Double Absen)
        $alreadyAbsen = \App\Models\Attendance::where('user_id', Auth::id())
            ->where('attendance_date', $today)
            ->exists();

        if ($alreadyAbsen) {
            return back()->with('info', 'Anda sudah melakukan absensi untuk hari ini.');
        }

        $minutesLate = 0;
        $attachmentPath = null;

        // 5. LOGIKA KHUSUS STATUS 'HADIR' (Cek Lokasi & Terlambat)
        if ($request->status == 'hadir') {
            // A. Validasi Radius Lokasi
            $distance = $this->calculateDistance(
                $setting->latitude,
                $setting->longitude,
                $request->lat_siswa,
                $request->lng_siswa
            );

            if ($distance > $setting->radius_meters) {
                return back()->with('error', "Gagal! Anda berada di luar radius sekolah (" . round($distance) . " meter).");
            }

            // B. Hitung Keterlambatan (Hanya jika lewat dari end_time)
            if ($currentTime > $setting->end_time) {
                $limit = Carbon::parse($setting->end_time);
                $minutesLate = $limit->diffInMinutes($now);
            }
        }

        // 6. LOGIKA KHUSUS STATUS 'IZIN/SAKIT' (Upload File)
        else {
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('attendance_letters', 'public');
            }
        }

        // 7. Simpan ke Database
        \App\Models\Attendance::create([
            'user_id' => Auth::id(),
            'attendance_date' => $today,
            'status' => $request->status,
            'note' => $request->note,
            'attachment' => $attachmentPath,
            'lat_siswa' => $request->lat_siswa,
            'lng_siswa' => $request->lng_siswa,
            'minutes_late' => $minutesLate,
        ]);

        // 8. Berikan Feedback ke Siswa
        $statusMsg = ($request->status == 'hadir') ? "Hadir" : ucfirst($request->status);
        $lateMsg = ($minutesLate > 0) ? " Namun Anda terlambat $minutesLate menit." : "";

        return back()->with('success', "Berhasil mencatat status $statusMsg.$lateMsg");
    }

    /**
     * Fungsi Pembantu: Menghitung Jarak Antara Dua Titik Koordinat (Haversine Formula)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Dalam satuan Meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
