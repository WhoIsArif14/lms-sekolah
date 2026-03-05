<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-50">
        <h4 class="font-bold mb-4 flex items-center gap-2">📅 10 Absensi Terakhir</h4>
        <table class="w-full text-sm">
            @foreach ($attendances as $a)
                <tr class="border-b last:border-0">
                    <td class="py-3 text-gray-600">{{ $a->created_at->format('d M Y') }}</td>
                    <td class="py-3 text-right">
                        <span
                            class="px-3 py-1 rounded-full text-[10px] font-black {{ $a->status == 'hadir' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                            {{ strtoupper($a->status) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-50">
        <h4 class="font-bold mb-4 flex items-center gap-2">✍️ Nilai Ujian Terbaru</h4>
        @foreach ($examResults as $res)
            <div class="flex justify-between items-center mb-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                <div>
                    <p class="font-bold text-gray-800">{{ $res->exam->title }}</p>
                    <p class="text-[10px] text-gray-400 uppercase font-black">{{ $res->exam->course->name }}</p>
                </div>
                <div class="text-right">
                    @if ($res->score !== null)
                        <div class="text-2xl font-black text-indigo-600">{{ round($res->score) }}</div>
                        <p class="text-[9px] text-green-500 font-bold uppercase">Selesai Dinilai</p>
                    @else
                        <div class="text-sm font-bold text-amber-500 italic">Menunggu Penilaian Guru</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
