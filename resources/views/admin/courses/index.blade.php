<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Mata Pelajaran & Jadwal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-end">
                <a href="{{ route('admin.imports.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    + Impor Guru & Jadwal Baru
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                <th class="px-4 py-3">Mata Pelajaran</th>
                                <th class="px-4 py-3">Kelas</th>
                                <th class="px-4 py-3">Pengajar & Jadwal</th>
                                <th class="px-4 py-3">Dibuat Pada</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($courses as $course)
                            <tr class="hover:bg-gray-50 transition text-sm">
                                <td class="px-4 py-4 font-bold text-gray-800">
                                    {{ $course->title }}
                                </td>

                                <td class="px-4 py-4 text-gray-600">
                                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-semibold">
                                        {{ $course->schoolClass->name ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="px-4 py-4">
                                    <div class="font-medium text-gray-900">
                                        {{ $course->teacher->name ?? '⚠️ Guru Belum Ditentukan' }}
                                    </div>
                                    <div class="text-xs text-indigo-600 mt-1 flex flex-wrap gap-1">
                                        @forelse($course->schedules as $schedule)
                                            <span class="bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded">
                                                {{ $schedule->day }}: {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                            </span>
                                        @empty
                                            <span class="text-gray-400 italic">Belum ada jadwal set</span>
                                        @endforelse
                                    </div>
                                </td>

                                <td class="px-4 py-4 text-gray-500">
                                    {{ $course->created_at->format('d M Y') }}
                                </td>

                                <td class="px-4 py-4 text-center">
                                    <div class="flex justify-center space-x-3">
                                        <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Hapus kursus ini? Semua data jadwal terkait juga akan terhapus.')">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 font-medium">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(method_exists($courses, 'links'))
                <div class="mt-6">
                    {{ $courses->links() }}
                </div>
                @endif
                
                @if($courses->isEmpty())
                    <div class="text-center py-12">
                        <p class="text-gray-500 italic">Belum ada data mata pelajaran. Silakan lakukan impor data guru dan jadwal.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>