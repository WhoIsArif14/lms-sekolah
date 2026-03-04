<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Monitoring Presensi: ') }} {{ $class->name }}
            </h2>
            <div class="text-sm font-bold bg-indigo-100 text-indigo-700 px-4 py-1 rounded-full">
                📅 {{ \Carbon\Carbon::parse($today)->format('d M Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-2xl border shadow-sm">
                    <p class="text-gray-400 text-xs font-bold uppercase">Total Siswa</p>
                    <p class="text-2xl font-black">{{ $students->count() }}</p>
                </div>
                <div class="bg-emerald-50 p-4 rounded-2xl border border-emerald-100">
                    <p class="text-emerald-600 text-xs font-bold uppercase">Hadir</p>
                    <p class="text-2xl font-black text-emerald-700">{{ $students->where('attendances', '!=', null)->count() }}</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b text-gray-400 text-[10px] font-black uppercase tracking-widest">
                            <th class="p-4">Siswa</th>
                            <th class="p-4 text-center">Status Hari Ini</th>
                            <th class="p-4 text-center">Jam Masuk</th>
                            <th class="p-4 text-center">Keterangan</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($students as $student)
                        @php $attendance = $student->attendances->first(); @endphp
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $student->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $student->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-center">
                                @if($attendance)
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-100 text-emerald-600">
                                        HADIR
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-red-100 text-red-600">
                                        TANPA KETERANGAN
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-center font-mono text-sm text-gray-600">
                                {{ $attendance ? \Carbon\Carbon::parse($attendance->created_at)->format('H:i') : '--:--' }}
                            </td>
                            <td class="p-4 text-center">
                                @if($attendance && $attendance->late_minutes > 0)
                                    <span class="text-amber-600 text-xs font-bold">Terlambat {{ $attendance->late_minutes }} mnt</span>
                                @elseif($attendance)
                                    <span class="text-emerald-500 text-xs font-bold">Tepat Waktu</span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <button class="text-gray-400 hover:text-indigo-600 transition">
                                    <svg class="w-5 h-5 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>