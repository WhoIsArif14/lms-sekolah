<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Katalog Kursus Tersedia') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($courses as $course)
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200 hover:shadow-lg transition">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-width="2"/></svg>
                                </div>
                                <span class="ml-2 text-xs font-bold text-gray-400 uppercase tracking-widest">Kursus</span>
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $course->title }}</h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $course->description }}</p>
                            
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                                <span>Guru: {{ $course->user->name }}</span>
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 border-t">
                            <a href="{{ route('siswa.courses.show', $course) }}" class="block text-center bg-indigo-600 text-white py-2 rounded-lg font-bold hover:bg-indigo-700 transition">
                                Mulai Belajar
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 bg-white rounded-lg shadow">
                        <p class="text-gray-500">Belum ada kursus yang diterbitkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>