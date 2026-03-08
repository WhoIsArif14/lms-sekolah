<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Materi: ') }} {{ $course->title }}
        </h2>
    </x-slot>

    {{-- Logika Alpine.js: Pastikan salah satu form tertutup jika yang lain dibuka --}}
    <div class="py-12" x-data="{ 
        showForm: {{ $errors->has('type') || $errors->has('file_content') || $errors->has('link_content') ? 'true' : 'false' }}, 
        type: 'file', 
        showAssignForm: {{ $errors->has('title') && !$errors->has('type') ? 'true' : 'false' }} 
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert Notifikasi Sukses --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-500 text-white rounded-xl shadow-lg flex items-center animate-bounce">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Tombol Navigasi --}}
            <div class="mb-8 flex flex-wrap gap-4">
                <button @click="showForm = !showForm; showAssignForm = false"
                    class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition transform active:scale-95 flex items-center justify-center text-sm uppercase tracking-wider">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    TAMBAH MATERI
                </button>

                <a href="{{ route('guru.assignments.index', $course->id) }}"
                    class="bg-yellow-500 text-gray-900 px-6 py-3 rounded-xl font-bold shadow-lg hover:bg-yellow-600 transition transform active:scale-95 flex items-center justify-center text-sm uppercase tracking-wider">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    TAMBAH TUGAS
                </button>

                <a href="{{ route('guru.exams.index', $course->id) }}"
                    class="bg-amber-500 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:bg-amber-600 transition transform active:scale-95 flex items-center justify-center text-sm uppercase tracking-wider">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    KELOLA UJIAN
                </a>
            </div>

            <div x-show="showForm" x-transition class="bg-white p-8 rounded-2xl shadow-md mb-8 border border-gray-200">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <span class="p-2 bg-indigo-100 text-indigo-600 rounded-lg mr-3">📂</span> Upload Materi Baru
                </h3>

                <form action="{{ route('guru.materials.store', $course) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Judul Materi</label>
                            <input type="text" name="title" value="{{ old('title') }}" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 shadow-sm @error('title') border-red-500 @enderror" placeholder="Contoh: Pertemuan 1 - Intro">
                            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tipe Materi</label>
                            <select name="type" x-model="type" class="w-full rounded-xl border-gray-300">
                                <option value="file">File (PDF / Modul)</option>
                                <option value="video_link">Link Video (YouTube)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 p-6 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300">
                        <div x-show="type === 'file'">
                            <input type="file" name="file_content" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-indigo-600 file:text-white">
                            @error('file_content') <p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p> @enderror
                        </div>
                        <div x-show="type === 'video_link'">
                            <input type="url" name="link_content" value="{{ old('link_content') }}" class="w-full rounded-xl border-gray-300" placeholder="https://www.youtube.com/watch?v=...">
                            @error('link_content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white px-10 py-3 rounded-xl font-bold hover:bg-indigo-700 shadow-lg transition">SIMPAN MATERI</button>
                    </div>
                </form>
            </div>

            {{-- <div x-show="showAssignForm" x-transition class="bg-white p-8 rounded-2xl shadow-md mb-8 border border-gray-200">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <span class="p-2 bg-yellow-100 text-yellow-600 rounded-lg mr-3">📝</span> Buat Tugas Baru
                </h3>

                <form action="{{ route('guru.assignments.store', $course->id) }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Judul Tugas</label>
                            <input type="text" name="title" value="{{ old('title') }}" 
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:ring-yellow-500 @error('title') border-red-500 @enderror" 
                                placeholder="Contoh: Latihan Bab 1" required>
                            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Instruksi Pengerjaan</label>
                            <textarea name="instruction" rows="4" 
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:ring-yellow-500 @error('instruction') border-red-500 @enderror" 
                                placeholder="Jelaskan instruksi tugas..." required>{{ old('instruction') }}</textarea>
                            @error('instruction') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="max-w-xs">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Batas Pengumpulan (Deadline)</label>
                            <input type="datetime-local" name="deadline" value="{{ old('deadline') }}"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:ring-yellow-500 @error('deadline') border-red-500 @enderror">
                            @error('deadline') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end border-t pt-6">
                        <button type="submit" class="bg-yellow-500 text-gray-900 px-10 py-3 rounded-xl font-bold hover:bg-yellow-600 shadow-lg transition uppercase tracking-wider text-sm">
                            POSTING TUGAS
                        </button>
                    </div>
                </form>
            </div> --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Daftar Materi --}}
                <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-200">
                    <h3 class="font-bold text-lg mb-6 text-gray-800 flex items-center justify-between border-b pb-4">
                        <span class="flex items-center">📚 Daftar Materi</span>
                        <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-500">{{ $materials->count() }} Item</span>
                    </h3>
                    <div class="space-y-4">
                        @forelse($materials as $material)
                            <div class="p-4 border border-gray-100 rounded-2xl hover:bg-gray-50 transition shadow-sm flex justify-between items-center">
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-gray-700 truncate">{{ $material->title }}</p>
                                    <span class="text-[10px] px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-full font-bold uppercase tracking-tighter">
                                        {{ $material->type }}
                                    </span>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ $material->type == 'file' ? asset('storage/' . $material->content) : $material->content }}" target="_blank" class="p-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-indigo-600 hover:text-white transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                    <form action="{{ route('guru.materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-center py-10 text-gray-400 italic text-sm">Belum ada materi.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Daftar Tugas --}}
                <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-200">
                    <h3 class="font-bold text-lg mb-6 text-gray-800 flex items-center justify-between border-b pb-4">
                        <span class="flex items-center">📝 Daftar Tugas</span>
                        <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-500">{{ $assignments->count() }} Item</span>
                    </h3>
                    <div class="space-y-4">
                        @forelse($assignments as $assignment)
                            <div class="p-4 border border-gray-100 rounded-2xl shadow-sm hover:bg-gray-50 transition flex justify-between items-center">
                                <div class="flex-1">
                                    <p class="font-bold text-gray-700 truncate">{{ $assignment->title }}</p>
                                    <p class="text-[11px] text-gray-500 mt-1 flex items-center">
                                        <svg class="w-3 h-3 mr-1 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Deadline: {{ $assignment->deadline ? $assignment->deadline->format('d M, H:i') : 'Terbuka' }}
                                    </p>
                                </div>
                                <form action="{{ route('guru.assignments.destroy', $assignment->id) }}" method="POST" onsubmit="return confirm('Hapus tugas?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="text-center py-10 text-gray-400 italic text-sm">Belum ada tugas.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="mt-12">
                @include('components.forum-diskusi')
            </div>

        </div>
    </div>
</x-app-layout>