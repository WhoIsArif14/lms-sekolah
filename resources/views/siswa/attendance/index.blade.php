<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-black text-gray-800 mb-6 uppercase">📅 Presensi <span
                    class="text-indigo-600">Siswa</span></h2>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-4 text-lg">Input Kehadiran</h3>

                        @if ($alreadyAbsen)
                            <div class="bg-green-50 text-green-700 p-4 rounded-xl border border-green-100 text-center">
                                <p class="font-bold">✅ Kamu sudah absen hari ini.</p>
                                <p class="text-xs">Terima kasih atas kedisiplinannya!</p>
                            </div>
                        @else
                            <form action="{{ route('siswa.attendance.store') }}" method="POST"
                                enctype="multipart/form-data" id="attendanceForm">
                                @csrf
                                <input type="hidden" name="lat_siswa" id="lat_siswa">
                                <input type="hidden" name="lng_siswa" id="lng_siswa">
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Status</label>
                                    <select name="status" id="status"
                                        class="w-full rounded-xl border-gray-200 focus:ring-indigo-500"
                                        onchange="toggleIzin()">
                                        <option value="hadir">Hadir</option>
                                        <option value="izin">Izin</option>
                                        <option value="sakit">Sakit</option>
                                    </select>
                                </div>

                                <div id="form-izin" class="hidden">
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Alasan /
                                            Catatan</label>
                                        <textarea name="note" class="w-full rounded-xl border-gray-200 text-sm" rows="3"
                                            placeholder="Tulis alasan singkat..."></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Lampiran
                                            Surat (PDF/JPG)</label>
                                        <input type="file" name="attachment" class="w-full text-xs text-gray-500">
                                    </div>
                                </div>

                                <button type="submit"
                                    class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition"
                                    id="submitBtn">
                                    Kirim Presensi
                                </button>

                            </form>
                        @endif
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="p-4 text-xs font-black uppercase text-gray-400">Tanggal</th>
                                    <th class="p-4 text-xs font-black uppercase text-gray-400">Tipe</th>
                                    <th class="p-4 text-xs font-black uppercase text-gray-400">Status</th>
                                    <th class="p-4 text-xs font-black uppercase text-gray-400">Lampiran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $row)
                                    <tr>
                                        <td class="p-4 text-sm text-gray-700 font-medium">
                                            {{ date('d M Y', strtotime($row->attendance_date)) }}
                                        </td>
                                        <td class="p-4">
                                            @if ($row->type == 'MASUK')
                                                <span
                                                    class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase">Masuk</span>
                                            @else
                                                <span
                                                    class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase">Pulang</span>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            @if ($row->status == 'hadir')
                                                <span
                                                    class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase">Hadir</span>
                                            @elseif($row->status == 'izin')
                                                <span
                                                    class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase">Izin</span>
                                            @else
                                                <span
                                                    class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase">Sakit</span>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            @if ($row->attachment)
                                                <a href="{{ asset('storage/' . $row->attachment) }}" target="_blank"
                                                    class="text-indigo-600 text-xs font-bold underline">Lihat Surat</a>
                                            @else
                                                <span class="text-gray-300 text-xs">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-8 text-center text-gray-400 italic">Belum ada
                                            riwayat absensi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleIzin() {
            const status = document.getElementById('status').value;
            const formIzin = document.getElementById('form-izin');
            if (status === 'izin' || status === 'sakit') {
                formIzin.classList.remove('hidden');
            } else {
                formIzin.classList.add('hidden');
            }
        }

        // Get user location on page load
        window.addEventListener('load', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('lat_siswa').value = position.coords.latitude;
                    document.getElementById('lng_siswa').value = position.coords.longitude;
                }, function(error) {
                    console.log('Error getting location:', error);
                    // If location not available, set to 0 (will be handled in controller)
                    document.getElementById('lat_siswa').value = '0';
                    document.getElementById('lng_siswa').value = '0';
                });
            } else {
                document.getElementById('lat_siswa').value = '0';
                document.getElementById('lng_siswa').value = '0';
            }
        });

        // Handle form submission
        document.getElementById('attendanceForm').addEventListener('submit', function(e) {
            const status = document.getElementById('status').value;
            const submitBtn = document.getElementById('submitBtn');

            if (status === 'hadir') {
                const lat = document.getElementById('lat_siswa').value;
                const lng = document.getElementById('lng_siswa').value;

                if (lat === '0' || lng === '0') {
                    e.preventDefault();
                    alert('Tidak dapat mendapatkan lokasi Anda. Pastikan GPS aktif dan izinkan akses lokasi.');
                    return;
                }
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Mengirim...';
        });
    </script>
</x-app-layout>
