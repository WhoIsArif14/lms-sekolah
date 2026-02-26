<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Mata Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100 text-left text-xs font-semibold uppercase tracking-wider">
                            <th class="px-4 py-2">Nama Kursus</th>
                            <th class="px-4 py-2">Pengajar (Guru)</th>
                            <th class="px-4 py-2">Dibuat Pada</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                        <tr class="border-b text-sm">
                            <td class="px-4 py-2 font-bold">{{ $course->title }}</td>
                            <td class="px-4 py-2">{{ $course->teacher->name }}</td>
                            <td class="px-4 py-2">{{ $course->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-2">
                                <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Hapus kursus ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>