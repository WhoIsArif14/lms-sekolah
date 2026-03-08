<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::withCount('students')->get();
        return view('admin.attendance.classes', compact('classes'));
    }

    public function showClass($id)
    {
        $class = SchoolClass::findOrFail($id);
        $today = now()->format('Y-m-d');

        $students = User::where('school_class_id', $id)
            ->where('role', 'siswa')
            ->with(['attendances' => function ($query) use ($today) {
                $query->where('attendance_date', $today);
            }])
            ->get();

        return view('admin.attendance.class_detail', compact('class', 'students', 'today'));
    }

    /**
     * LOGIKA PERBAIKAN: Simpan Absen & Kirim Notifikasi WA
     */
    public function store(Request $request)
    {
        // 1. Validasi Input sesuai kolom di database kamu
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status'  => 'required|in:Hadir,Sakit,Izin,Alpa',
            'note'    => 'nullable|string',
        ]);

        try {
            // 2. Simpan atau Update data absen (mencegah duplikat di hari yang sama)
            $absen = Attendance::updateOrCreate(
                [
                    'user_id'         => $request->user_id,
                    'attendance_date' => now()->format('Y-m-d'),
                ],
                [
                    'status' => $request->status,
                    'type'   => now()->hour < 12 ? 'MASUK' : 'PULANG',
                    'note'   => $request->note,
                    // Tambahkan kolom lain jika dikirim dari form, misal: 'lat_siswa' => $request->lat
                ]
            );

            // 3. Ambil data Siswa & Nomor WA Ortu
            $siswa = User::findOrFail($request->user_id);

            // 4. Kirim WA jika nomor parent_phone tersedia
            if (!empty($siswa->parent_phone)) {
                $this->sendWhatsAppNotification($siswa, $absen);
            }

            return back()->with('success', 'Absensi ' . $siswa->name . ' berhasil disimpan dan notifikasi telah dikirim.');
        } catch (\Exception $e) {
            Log::error("Gagal Simpan/Kirim WA: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }

    /**
     * Private Function: Integrasi API Fonnte
     */
    private function sendWhatsAppNotification($siswa, $absen)
    {
        // 1. Tentukan Header dan Emoji berdasarkan Status Absensi
        $header = ($absen->status == 'Alpa') ? "⚠️ *PERINGATAN KETIDAKHADIRAN*" : "🔔 *NOTIFIKASI KEHADIRAN*";

        // 2. Susun Template Pesan
        $pesan = "{$header}\n\n" .
            "Halo Bapak/Ibu, menginfokan kehadiran putra/putri Anda:\n\n" .
            "Nama: *{$siswa->name}*\n" .
            "Status: *{$absen->status}*\n" .
            "Waktu: *{$absen->type}* (" . now()->format('H:i') . " WIB)\n" .
            "Tanggal: " . now()->format('d-m-Y') . "\n";

        // Tambahkan catatan jika ada (misal: alasan sakit/izin)
        if (!empty($absen->note)) {
            $pesan .= "Keterangan: _{$absen->note}_\n";
        }

        $pesan .= "\nTerima kasih telah menggunakan layanan StudiFy.";

        // 3. Format Nomor HP Orang Tua ke Standar Internasional (62)
        $target = $this->formatNumber($siswa->parent_phone);

        try {
            // 4. Eksekusi Pengiriman ke API Fonnte
            $response = Http::withHeaders([
                'Authorization' => config('services.fonnte.token'), // Mengambil token dari config/services.php
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target'  => $target,
                'message' => $pesan,
                'delay'   => '2', // Jeda antrean pesan dalam detik
            ]);

            // DEBUGGING: Hapus tanda komentar (//) di bawah ini jika ingin melihat respon error di layar
            // dd($response->json()); 

            // 5. Catat Log jika Gagal
            if ($response->failed()) {
                Log::error("Fonnte API Gagal mengirim ke {$target}. Respon: " . $response->body());
            }
        } catch (\Exception $e) {
            // Mencegah aplikasi crash jika server Fonnte down
            Log::error("Koneksi Fonnte Error: " . $e->getMessage());
        }
    }

    /**
     * Fungsi Pembantu untuk Merapikan Format Nomor HP
     */
    private function formatNumber($number)
    {
        // Menghapus karakter selain angka
        $number = preg_replace('/[^0-9]/', '', $number);

        // Mengganti awalan 0 menjadi 62
        if (substr($number, 0, 1) === '0') {
            return '62' . substr($number, 1);
        }

        return $number;
    }
}
