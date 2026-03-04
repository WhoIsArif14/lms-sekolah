<div class="bg-white p-6 rounded-2xl shadow-sm border mb-8">
    <h3 class="font-bold mb-4 text-indigo-900">⚙️ Pengaturan Panel Absensi</h3>
    <form action="{{ route('admin.attendance.update') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        @csrf
        <div>
            <label class="block text-xs font-bold text-gray-400 mb-1">Jam Mulai</label>
            <input type="time" name="start_time" value="{{ $setting->start_time }}" class="w-full rounded-xl border-gray-200">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 mb-1">Batas Jam Masuk</label>
            <input type="time" name="end_time" value="{{ $setting->end_time }}" class="w-full rounded-xl border-gray-200">
        </div>
        <div class="flex items-center pb-3">
            <input type="checkbox" name="is_open" {{ $setting->is_open ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600">
            <span class="ml-2 text-sm font-bold text-gray-600">Absen Dibuka</span>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-indigo-700">Simpan</button>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 uppercase text-[10px] font-black text-gray-400">
            <tr>
                <th class="p-4">Siswa</th>
                <th class="p-4">Tanggal</th>
                <th class="p-4">Status</th>
                <th class="p-4">Keterangan</th>
            </tr>
        </thead>
        <tbody class="text-sm">
            @foreach($attendances as $row)
            <tr class="border-t">
                <td class="p-4 font-bold">{{ $row->user->name }}</td>
                <td class="p-4">{{ $row->attendance_date }}</td>
                <td class="p-4">
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $row->status == 'hadir' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $row->status }}
                    </span>
                </td>
                <td class="p-4">
                    @if($row->minutes_late > 0)
                        <span class="text-red-500 font-bold italic text-xs">Terlambat {{ $row->minutes_late }} Menit</span>
                    @else
                        <span class="text-green-500 text-xs">Tepat Waktu</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>