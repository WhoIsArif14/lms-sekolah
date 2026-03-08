<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SiswaAttendanceController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Cek waktu: Sebelum jam 12 dianggap MASUK, setelahnya PULANG
        $type = now()->hour < 12 ? 'MASUK' : 'PULANG';

        // Simpan data absen ke database
        Attendance::create([
            'user_id' => $user->id,
            'status' => 'hadir',
            'type' => $type,
        ]);

        // Pemicu Bot WA ke Orang Tua
        if ($user->parent_phone) {
            $this->kirimNotifikasiWA($user->parent_phone, $user->name, $type);
        }

        return back()->with('success', "Absen $type Berhasil dicatat!");
    }

    private function kirimNotifikasiWA($nomor, $nama, $type)
    {
        $token = "TOKEN_DARI_DASHBOARD_FONNTE"; // Ganti dengan token dari akun Fonnte kamu
        
        $pesan = ($type == 'MASUK') 
            ? "🔔 *NOTIFIKASI ABSEN MASUK*\n\nHalo Bapak/Ibu, Ananda *$nama* telah melakukan absen *MASUK* di SMKN 1 Jatirejo."
            : "🏠 *NOTIFIKASI ABSEN PULANG*\n\nHalo Bapak/Ibu, Ananda *$nama* telah melakukan absen *PULANG* dan segera menuju ke rumah.";

        Http::withHeaders(['Authorization' => $token])->asForm()->post('https://api.fonnte.com/send', [
            'target' => $nomor,
            'message' => $pesan,
        ]);
    }
}