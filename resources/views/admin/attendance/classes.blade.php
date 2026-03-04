<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Absensi per Kelas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($classes as $class)
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h3 class="text-lg font-black text-gray-800">
            Kelas {{ $class->grade }} - {{ $class->name }}
        </h3>
        <p class="text-sm text-gray-400 mb-4">{{ $class->students_count }} Siswa</p>
        
        <a href="{{ route('admin.attendance.class', $class->id) }}" class="text-indigo-600 font-bold text-sm">
            Lihat Detail →
        </a>
    </div>
@endforeach
            </div>
        </div>
    </div>
</x-app-layout>