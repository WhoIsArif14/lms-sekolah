<div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="p-4 text-xs font-black uppercase text-gray-400">Nama Siswa</th>
                <th class="p-4 text-xs font-black uppercase text-gray-400 text-center">Status Hari Ini</th>
                <th class="p-4 text-xs font-black uppercase text-gray-400 text-center">Jam / Terlambat</th>
                <th class="p-4 text-xs font-black uppercase text-gray-400 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $siswa)
            @php $absen = $siswa->attendances->first(); @endphp
            <tr class="border-b last:border-0 hover:bg-gray-50">
                <td class="p-4 font-bold text-gray-700">{{ $siswa->name }}</td>
                <td class="p-4 text-center">
                    @if($absen)
                        @if($absen->status == 'hadir')
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-bold">HADIR</span>
                        @else
                            <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-bold">{{ strtoupper($absen->status) }}</span>
                        @endif
                    @else
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[10px] font-bold">BELUM ABSEN</span>
                    @endif
                </td>
                <td class="p-4 text-center">
                    @if($absen && $absen->status == 'hadir')
                        <span class="text-xs text-gray-500">{{ $absen->created_at->format('H:i') }}</span>
                        @if($absen->minutes_late > 0)
                            <p class="text-[10px] text-red-500 font-bold">Terlambat {{ $absen->minutes_late }}m</p>
                        @endif
                    @else - @endif
                </td>
                <td class="p-4 text-right">
                    @if($absen && $absen->attachment)
                        <a href="{{ asset('storage/'.$absen->attachment) }}" target="_blank" class="text-indigo-600 font-bold text-xs underline text-uppercase">Lihat Surat</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>