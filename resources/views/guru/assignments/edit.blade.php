<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-2xl font-black text-indigo-900 mb-6 uppercase">✏️ Edit Tugas</h2>

                <form action="{{ route('guru.assignments.update', $assignment) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-bold text-gray-700">Judul Tugas</label>
                        <input type="text" name="title" value="{{ $assignment->title }}"
                            class="w-full rounded-xl border-gray-300" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold text-gray-700">Perintah Tugas</label>
                        <textarea name="instruction" rows="4" class="w-full rounded-xl border-gray-300" required>{{ $assignment->instruction }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-bold text-gray-700">Deadline</label>
                            <input type="datetime-local" name="deadline"
                                value="{{ date('Y-m-d\TH:i', strtotime($assignment->deadline)) }}"
                                class="w-full rounded-xl border-gray-300" required>
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700">Link Tambahan</label>
                            <input type="url" name="link" value="{{ $assignment->link }}"
                                class="w-full rounded-xl border-gray-300">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block font-bold text-gray-700">Ganti Soal/Modul (Kosongkan jika tidak ingin
                            ganti)</label>
                        @if ($assignment->file_path)
                            <p class="text-xs text-indigo-600 mb-2 font-bold">File saat ini:
                                {{ basename($assignment->file_path) }}</p>
                        @endif
                        <input type="file" name="file_soal" class="w-full text-sm text-gray-500">
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="submit"
                            class="bg-green-600 text-white px-8 py-2 rounded-xl font-bold hover:bg-green-700 transition">
                            Update Tugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
