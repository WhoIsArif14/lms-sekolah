<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('siswa.dashboard') }}" class="text-indigo-600 font-bold text-sm hover:underline">←
                    Kembali ke Daftar Kursus</a>
            </div>

            <h2 class="text-3xl font-black text-indigo-900 mb-6 uppercase tracking-tight">{{ $course->title }}</h2>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h3 class="text-xl font-bold mb-4 flex items-center">
                            <span class="mr-2">📚</span> Materi Pembelajaran
                        </h3>

                        <div class="space-y-3">
                            @forelse($course->materials as $material)
                                <div
                                    class="flex justify-between items-center p-4 bg-gray-50 rounded-xl hover:bg-indigo-50 transition border border-transparent hover:border-indigo-100 group">
                                    <div class="flex items-center">
                                        @if ($material->type == 'video')
                                            <span class="p-2 bg-red-100 text-red-600 rounded-lg mr-3">🎥</span>
                                        @else
                                            <span class="p-2 bg-blue-100 text-blue-600 rounded-lg mr-3">📄</span>
                                        @endif
                                        <div>
                                            <h4
                                                class="font-bold text-gray-800 text-sm group-hover:text-indigo-700 transition">
                                                {{ $material->title }}</h4>
                                            <span
                                                class="text-[10px] uppercase font-bold text-gray-400">{{ $material->type }}</span>
                                        </div>
                                    </div>

                                    @if ($material->type == 'video')
                                        <a href="{{ $material->content }}" target="_blank"
                                            class="text-xs font-black text-indigo-600 uppercase tracking-widest">Tonton
                                            Video</a>
                                    @else
                                        <a href="{{ asset('storage/' . $material->content) }}" download
                                            class="text-xs font-black text-indigo-600 uppercase tracking-widest">Download
                                            PDF</a>
                                    @endif
                                </div>
                            @empty
                                <p class="text-gray-400 italic text-sm text-center py-4">Belum ada materi yang diunggah.
                                </p>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h3 class="text-xl font-bold mb-4 flex items-center">
                            <span class="mr-2">📝</span> Tugas & Latihan
                        </h3>

                        @forelse($course->assignments as $assignment)
                            <div
                                class="border border-gray-100 rounded-xl p-5 mb-4 hover:border-indigo-200 transition bg-white shadow-sm">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-bold text-lg text-gray-800">{{ $assignment->title }}</h4>
                                        <p class="text-sm text-gray-500 mb-3">{{ $assignment->instruction }}</p>

                                        @if ($assignment->file_path)
                                            <a href="{{ asset('storage/' . $assignment->file_path) }}"
                                                class="inline-flex items-center text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-lg hover:bg-indigo-100 transition">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                    </path>
                                                </svg>
                                                Unduh Modul Soal
                                            </a>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <span
                                            class="text-[10px] font-black uppercase text-red-500 block">Deadline:</span>
                                        <span
                                            class="text-xs font-bold text-gray-600">{{ date('d M Y, H:i', strtotime($assignment->deadline)) }}</span>
                                    </div>
                                </div>

                                <div class="mt-6 pt-4 border-t border-dashed border-gray-100">
                                    @php $submission = $assignment->submissions->first(); @endphp

                                    @if ($submission)
                                        <div
                                            class="flex items-center justify-between bg-green-50 p-3 rounded-xl border border-green-100">
                                            <div class="flex items-center text-green-700 font-bold text-sm">
                                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                                    </path>
                                                </svg>
                                                Tugas Terkirim
                                            </div>
                                            <span
                                                class="text-xs font-black text-green-800 bg-green-200 px-2 py-1 rounded">SKOR:
                                                {{ $submission->score ?? 'Waiting' }}</span>
                                        </div>
                                    @else
                                        <form action="{{ route('siswa.assignments.submit', $assignment->id) }}"
                                            method="POST" enctype="multipart/form-data"
                                            class="flex items-center gap-3">
                                            @csrf
                                            <div class="flex-1">
                                                <input type="file" name="file_jawaban"
                                                    class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer"
                                                    required>
                                            </div>
                                            <button type="submit"
                                                class="bg-indigo-600 text-white px-5 py-2 rounded-xl text-xs font-bold hover:bg-indigo-700 shadow-md shadow-indigo-100 transition">
                                                Kirim Jawaban
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-400 italic text-sm text-center py-4">Tidak ada tugas saat ini.</p>
                        @endforelse
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Guru Pengampu</h4>
                        <div class="flex items-center">
                            <div
                                class="w-12 h-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-black text-lg shadow-lg">
                                {{ substr($course->user->name, 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <p class="font-bold text-gray-800">{{ $course->user->name }}</p>
                                <p class="text-xs text-gray-500 italic">Pendidik Profesional</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-indigo-900 p-6 rounded-2xl shadow-xl text-white">
                        <h4 class="text-[10px] font-black text-indigo-300 uppercase tracking-widest mb-4">Informasi
                            Kelas</h4>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center border-b border-indigo-800 pb-2">
                                <span class="text-xs text-indigo-200">Kelas</span>
                                <span class="text-sm font-bold">{{ $course->classroom ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-indigo-800 pb-2">
                                <span class="text-xs text-indigo-200">Hari</span>
                                <span class="text-sm font-bold">{{ $course->day ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-indigo-200">Waktu</span>
                                <span class="text-sm font-bold">{{ $course->time_start }} -
                                    {{ $course->time_end }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-amber-50 p-6 rounded-2xl border border-amber-100">
                        <p class="text-amber-800 text-xs leading-relaxed italic">
                            <strong>Tips:</strong> Pastikan mengunggah file jawaban dalam format PDF jika diminta oleh
                            guru agar mudah dibaca.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
