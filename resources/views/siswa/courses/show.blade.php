<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('siswa.dashboard') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2"/></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $course->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-xl p-8 border border-gray-200">
                <h3 class="text-lg font-bold mb-6 flex items-center">
                    <span class="bg-yellow-400 w-2 h-6 rounded-full mr-3"></span>
                    Materi Pembelajaran
                </h3>

                <div class="space-y-4">
                    @forelse($materials as $material)
                        <div class="flex items-center justify-between p-5 border rounded-2xl hover:border-indigo-300 hover:bg-indigo-50 transition group">
                            <div class="flex items-center">
                                @if($material->type == 'video_link')
                                    <div class="bg-red-50 text-red-600 p-3 rounded-full mr-4 group-hover:bg-red-100">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zm6.707 3.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7a1 1 0 10-2 0v3.586L7.707 9.293z"/></svg>
                                    </div>
                                @else
                                    <div class="bg-blue-50 text-blue-600 p-3 rounded-full mr-4 group-hover:bg-blue-100">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A1 1 0 0111.293 2.707l3 3a1 1 0 01.293.707V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                                    </div>
                                @endif
                                <div>
                                    <h4 class="font-bold text-gray-700">{{ $material->title }}</h4>
                                    <p class="text-xs text-gray-400 uppercase">{{ $material->type }}</p>
                                </div>
                            </div>
                            <a href="{{ $material->type == 'file' ? asset('storage/'.$material->content) : $material->content }}" 
                               target="_blank" class="bg-white border border-gray-300 text-gray-700 px-5 py-2 rounded-xl text-sm font-bold hover:bg-gray-800 hover:text-white transition shadow-sm">
                                Buka
                            </a>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-10">Materi belum diunggah oleh guru.</p>
                    @endforelse
                </div>

                {{-- daftar tugas bagi siswa --}}
                <div class="mt-8">
                    <h3 class="text-lg font-bold mb-6 flex items-center">
                        <span class="bg-green-400 w-2 h-6 rounded-full mr-3"></span>
                        Tugas
                    </h3>
                    @forelse($assignments as $assignment)
                        <div class="mb-6 p-4 border rounded-lg">
                            <p class="font-bold text-gray-700">{{ $assignment->title }}</p>
                            <p class="text-sm text-gray-600 mb-1">{{ $assignment->instruction }}</p>
                            <p class="text-xs text-gray-500 mb-2">Deadline: {{ $assignment->deadline ? $assignment->deadline->format('d M Y H:i') : 'Tidak ditentukan' }}</p>

                            @if(isset($submissions[$assignment->id]))
                                <p class="text-green-600 font-semibold">Sudah dikumpulkan:</p>
                                <a href="{{ asset('storage/'.$submissions[$assignment->id]->file_path) }}" class="text-blue-600 hover:underline" target="_blank">Unduh</a>
                            @else
                                <form action="{{ route('siswa.courses.assignments.submit', [$course, $assignment]) }}" method="POST" enctype="multipart/form-data" class="mt-2">
                                    @csrf
                                    <div class="flex items-center gap-2">
                                        <input type="file" name="file" required class="text-sm text-gray-700">
                                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                                            Kirim
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500 italic">Belum ada tugas yang diberikan guru.</p>
                    @endforelse
                </div>

                @include('components.forum-diskusi')
            </div>
        </div>
    </div>
</x-app-layout>