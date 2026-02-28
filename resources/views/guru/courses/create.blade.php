<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Kursus Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-sm rounded-lg border border-gray-200">
                <form action="{{ route('guru.courses.store') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Mata Pelajaran</label>
                        <input type="text" name="title"
                            class="w-full border-gray-300 rounded-lg focus:ring-indigo-500"
                            placeholder="Contoh: Matematika Kelas X" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Singkat</label>
                        <textarea name="description" rows="5" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500"
                            placeholder="Jelaskan apa yang akan dipelajari di kursus ini..." required></textarea>
                    </div>

                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('guru.courses.index') }}" class="text-gray-500 hover:underline">Batal</a>
                        <button type="submit"
                            class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700 shadow-lg">
                            Simpan & Publikasikan
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Hari</label>
                            <select name="day"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kelas</label>
                            <input type="text" name="classroom" placeholder="Contoh: X-RPL-A"
                                class="w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                            <input type="time" name="time_start" class="w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                            <input type="time" name="time_end" class="w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
