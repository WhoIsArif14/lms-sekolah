<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-2xl font-black text-indigo-900 mb-6 uppercase">📝 Buat Tugas Baru</h2>

                {{-- Tampilkan error validasi --}}
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <p class="font-bold text-red-700 mb-2">⚠️ Gagal menyimpan tugas:</p>
                        <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Tampilkan pesan sukses --}}
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                        <p class="font-bold text-green-700">✅ {{ session('success') }}</p>
                    </div>
                @endif

                <form action="{{ route('guru.assignments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-bold text-gray-700 mb-1">Pilih Mata Pelajaran <span class="text-red-500">*</span></label>
                        <select name="course_id" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('course_id') border-red-400 @enderror" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }} ({{ $course->classroom }})
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold text-gray-700 mb-1">Judul Tugas <span class="text-red-500">*</span></label>
                        <input type="text" name="title"
                            value="{{ old('title') }}"
                            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('title') border-red-400 @enderror"
                            placeholder="Contoh: Tugas Mandiri Pertemuan 1" required>
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold text-gray-700 mb-1">Perintah Tugas (Instruksi) <span class="text-red-500">*</span></label>
                        <textarea name="instruction" rows="4"
                            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('instruction') border-red-400 @enderror"
                            placeholder="Tuliskan detail apa yang harus dikerjakan siswa..." required>{{ old('instruction') }}</textarea>
                        @error('instruction')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Tenggat Waktu (Deadline) <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="deadline"
                                value="{{ old('deadline') }}"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('deadline') border-red-400 @enderror"
                                required>
                            @error('deadline')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Link Tambahan (Opsional)</label>
                            <input type="url" name="link"
                                value="{{ old('link') }}"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('link') border-red-400 @enderror"
                                placeholder="https://example.com">
                            @error('link')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block font-bold text-gray-700 mb-1">Upload Soal/Modul (PDF/Doc, maks 5MB)</label>
                        <input type="file" name="file_soal"
                            accept=".pdf,.doc,.docx,.zip"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 @error('file_soal') border border-red-400 rounded-xl p-2 @enderror">
                        @error('file_soal')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('guru.assignments.index') }}"
                            class="px-6 py-2 text-gray-500 font-bold hover:text-gray-700 transition">Batal</a>
                        <button type="submit"
                            class="bg-indigo-600 text-white px-8 py-2 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                            Publikasikan Tugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>