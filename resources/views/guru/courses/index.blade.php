<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Mata Pelajaran Saya') }}
            </h2>
            <a href="{{ route('guru.courses.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700">
                + Buat Kursus Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($courses as $course)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 flex flex-col">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-indigo-600 mb-2">{{ $course->title }}</h3>
                            <p class="text-gray-600 text-sm line-clamp-3">
                                {{ $course->description }}
                            </p>
                        </div>
                        <div class="mt-auto bg-gray-50 p-4 border-t flex justify-between">
                            <a href="{{ route('guru.courses.show', $course) }}"
                                class="text-indigo-600 hover:text-indigo-900 font-bold text-sm flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Kelola Materi
                            </a>
                            <span class="text-xs text-gray-400 italic">Dibuat:
                                {{ $course->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white p-12 text-center rounded-lg shadow-sm">
                        <p class="text-gray-500 italic">Anda belum memiliki kursus. Silakan buat kursus pertama Anda!
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
