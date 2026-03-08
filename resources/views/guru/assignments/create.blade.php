<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-2xl font-black text-indigo-900 mb-6 uppercase">📝 Buat Tugas Baru</h2>

                <form action="{{ route('guru.assignments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-bold text-gray-700">Pilih Mata Pelajaran</label>
                        <select name="course_id" class="w-full rounded-xl border-gray-300 shadow-sm" required>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }} ({{ $course->classroom }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold text-gray-700">Judul Tugas</label>
                        <input type="text" name="title" class="w-full rounded-xl border-gray-300"
                            placeholder="Contoh: Tugas Mandiri Pertemuan 1" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold text-gray-700">Perintah Tugas (Instruksi)</label>
                        <textarea name="instruction" rows="4" class="w-full rounded-xl border-gray-300"
                            placeholder="Tuliskan detail apa yang harus dikerjakan siswa..." required></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-bold text-gray-700">Tenggat Waktu (Deadline)</label>
                            <input type="datetime-local" name="deadline" class="w-full rounded-xl border-gray-300"
                                required>
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700">Link Tambahan (Opsional)</label>
                            <input type="url" name="link" class="w-full rounded-xl border-gray-300"
                                placeholder="https://example.com">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block font-bold text-gray-700">Upload Soal/Modul (PDF/Doc)</label>
                        <input type="file" name="file_soal"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('guru.assignments.index') }}"
                            class="px-6 py-2 text-gray-500 font-bold">Batal</a>
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
