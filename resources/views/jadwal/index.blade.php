<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h2 class="text-3xl font-black text-indigo-900 uppercase tracking-tight">
                    📅 Jadwal <span class="text-indigo-600">Online</span>
                </h2>
                <p class="text-gray-500">Daftar seluruh jadwal mata pelajaran yang tersedia di sekolah.</p>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-indigo-50">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-indigo-600">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-white uppercase tracking-widest">Hari</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-white uppercase tracking-widest">Mata Pelajaran</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-white uppercase tracking-widest">Waktu</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-white uppercase tracking-widest">Kelas</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-white uppercase tracking-widest">Guru Pengampu</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($schedules as $item)
                                <tr class="hover:bg-indigo-50/50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-black uppercase">
                                            {{ $item->day }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">
                                        {{ $item->title }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                        {{ $item->time_start }} - {{ $item->time_end }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="font-mono font-bold text-indigo-600 bg-gray-50 px-2 py-1 rounded">
                                            {{ $item->classroom ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-semibold">
                                        {{ $item->user->name }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">
                                        Belum ada jadwal pelajaran yang diinput oleh Guru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>