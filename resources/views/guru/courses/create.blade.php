<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Kursus Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-xl">
                    <ul class="list-disc ml-5 text-sm font-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white p-8 shadow-sm rounded-2xl border border-gray-200">
                <form action="{{ route('guru.courses.store') }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Mata Pelajaran</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 shadow-sm"
                            placeholder="Contoh: Matematika" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Singkat</label>
                        <textarea name="description" rows="4" class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 shadow-sm"
                            placeholder="Deskripsi kursus..." required>{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Hari</label>
                            <select name="day" class="w-full rounded-xl border-gray-300 shadow-sm">
                                @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                                    <option value="{{ $h }}" {{ old('day') == $h ? 'selected' : '' }}>
                                        {{ $h }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Kelas</label>
                            <select name="school_class_id" class="w-full rounded-xl border-gray-300 shadow-sm" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ old('school_class_id') == $class->id ? 'selected' : '' }}>
                                        Kelas {{ $class->grade }} - {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jam Mulai</label>
                            <input type="time" name="time_start" value="{{ old('time_start') }}"
                                class="w-full rounded-xl border-gray-300 shadow-sm" required>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jam Selesai</label>
                            <input type="time" name="time_end" value="{{ old('time_end') }}"
                                class="w-full rounded-xl border-gray-300 shadow-sm" required>
                        </div>
                    </div>

                    <input type="hidden" name="classroom" value="-">

                    <div class="flex items-center justify-end space-x-4 mt-10 pt-6 border-t border-gray-100">
                        <a href="{{ route('guru.courses.index') }}"
                            class="text-gray-500 hover:text-gray-800 text-sm font-medium">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition transform active:scale-95">
                            Simpan & Publikasikan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
