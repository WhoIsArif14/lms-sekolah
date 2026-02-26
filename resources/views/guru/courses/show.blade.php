<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Materi: ') }} {{ $course->title }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ showForm: false, type: 'file' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <button @click="showForm = !showForm" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold shadow hover:bg-indigo-700 transition">
                    <span x-text="showForm ? '- Batalkan' : '+ Tambah Materi (File/Video)'"></span>
                </button>
            </div>

            <div x-show="showForm" 
                 x-transition 
                 class="bg-white p-6 rounded-lg shadow-md mb-6 border border-gray-200">
                
                <form action="{{ route('guru.courses.storeMaterial', $course) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Judul Materi</label>
                            <input type="text" name="title" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500" placeholder="Contoh: Modul Aljabar Dasar" required>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tipe Materi</label>
                            <select name="type" x-model="type" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500">
                                <option value="file">File (PDF/Doc/PPT)</option>
                                <option value="video_link">Link Video (YouTube/Drive)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <div x-show="type === 'file'">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih File Modul</label>
                            <input type="file" name="file_content" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-1 text-xs text-gray-500">*Format: PDF, DOCX, PPTX, ZIP (Maks 10MB)</p>
                        </div>

                        <div x-show="type === 'video_link'">
                            <label class="block text-sm font-bold text-gray-700 mb-2">URL Video / Link Drive</label>
                            <input type="url" name="link_content" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500" placeholder="https://www.youtube.com/watch?v=...">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-green-700 shadow-md transition">
                            Simpan Materi
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6 border border-gray-200">
                <h3 class="font-bold text-lg mb-6 text-gray-800">Daftar Materi di Kursus Ini</h3>
                <div class="space-y-3">
                    @forelse($materials as $material)
                        <div class="flex items-center justify-between p-4 border rounded-xl hover:bg-gray-50 transition">
                            <div class="flex items-center">
                                @if($material->type == 'video_link')
                                    <div class="bg-red-100 text-red-600 p-2 rounded-lg mr-4">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-width="2"/></svg>
                                    </div>
                                @else
                                    <div class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-4">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-width="2"/></svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold text-gray-700">{{ $material->title }}</p>
                                    <p class="text-xs text-gray-500 uppercase">{{ $material->type }}</p>
                                </div>
                            </div>
                            <a href="{{ $material->type == 'file' ? asset('storage/'.$material->content) : $material->content }}" 
                               target="_blank" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-200 transition">
                                Buka Materi
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <p class="text-gray-500 italic">Belum ada materi di kursus ini.</p>
                        </div>
                    @endforelse
                </div>
                @include('components.forum-diskusi')
            </div>

        </div>
    </div>
</x-app-layout>