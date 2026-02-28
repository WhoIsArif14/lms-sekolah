<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6">Edit Mata Pelajaran</h2>

            <div class="bg-white p-6 rounded-xl shadow-lg">
                <form action="{{ route('guru.courses.update', $course) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-bold">Nama Mata Pelajaran</label>
                        <input type="text" name="title" value="{{ $course->title }}" class="w-full rounded-lg border-gray-300">
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold">Deskripsi</label>
                        <textarea name="description" class="w-full rounded-lg border-gray-300">{{ $course->description }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-bold">Hari</label>
                            <select name="day" class="w-full rounded-lg border-gray-300">
                                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari)
                                    <option value="{{ $hari }}" {{ $course->day == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold">Kelas</label>
                            <input type="text" name="classroom" value="{{ $course->classroom }}" class="w-full rounded-lg border-gray-300">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block font-bold">Jam Mulai</label>
                            <input type="time" name="time_start" value="{{ $course->time_start }}" class="w-full rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block font-bold">Jam Selesai</label>
                            <input type="time" name="time_end" value="{{ $course->time_end }}" class="w-full rounded-lg border-gray-300">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('guru.courses.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg">Batal</a>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold">Update Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>