<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Absensi per Kelas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($classes as $class)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                        <h3 class="text-lg font-bold">{{ $class->title }}</h3>
                        <p class="text-sm text-gray-500">{{ $class->students_count ?? 0 }} Siswa</p>
                        <a href="{{ route('admin.attendance.class', $class->id) }}" class="mt-4 inline-block text-indigo-600 font-bold">
                            Lihat Detail →
                        </a>
                    </div>
                @empty
                    <p class="text-gray-500 italic">Belum ada data kelas/kursus.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>