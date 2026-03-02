<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-black text-gray-800 mb-6 uppercase">📋 Daftar <span class="text-indigo-600">Tugas
                    Saya</span></h2>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="p-4 text-xs font-black uppercase text-gray-400">Mata Pelajaran</th>
                            <th class="p-4 text-xs font-black uppercase text-gray-400">Judul Tugas</th>
                            <th class="p-4 text-xs font-black uppercase text-gray-400 text-center">Deadline</th>
                            <th class="p-4 text-xs font-black uppercase text-gray-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignments as $assignment)
                            <tr class="border-b border-gray-50 hover:bg-indigo-50/30 transition">
                                <td class="p-4 text-sm font-bold text-gray-700">{{ $assignment->course->title }}</td>
                                <td class="p-4 text-sm text-gray-600">{{ $assignment->title }}</td>
                                <td
                                    class="p-4 text-xs font-bold text-center {{ now() > $assignment->deadline ? 'text-red-500' : 'text-gray-500' }}">
                                    {{ date('d M Y, H:i', strtotime($assignment->deadline)) }}
                                </td>
                                <td class="p-4 text-right">
                                    <a href="{{ route('siswa.courses.show', $assignment->course_id) }}"
                                        class="text-xs font-black text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full uppercase hover:bg-indigo-100 transition">
                                        Buka Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-10 text-center text-gray-400 italic">Belum ada tugas yang
                                    tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
