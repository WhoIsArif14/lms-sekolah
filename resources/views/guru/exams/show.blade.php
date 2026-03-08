<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('guru.exams.index', $exam->course_id) }}" class="text-sm text-indigo-600 font-bold">←
                    Kembali ke Daftar Ujian</a>
                <h2 class="text-2xl font-bold mt-2">Kelola Soal: {{ $exam->title }}</h2>
            </div>

            {{-- NOTIFIKASI SUKSES --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl">
                    <p class="font-bold text-green-700">✅ {{ session('success') }}</p>
                </div>
            @endif

            {{-- NOTIFIKASI WARNING (soal dilewati saat import) --}}
            @if (session('import_errors'))
                <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                    <p class="font-bold text-yellow-700 mb-2">⚠️ Beberapa soal dilewati:</p>
                    <ul class="list-disc list-inside text-yellow-600 text-sm space-y-1">
                        @foreach (session('import_errors') as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- NOTIFIKASI ERROR --}}
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <p class="font-bold text-red-700 mb-1">⚠️ Terjadi kesalahan:</p>
                    <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- IMPORT SOAL VIA EXCEL --}}
            <div class="bg-indigo-50 border border-indigo-200 p-6 rounded-2xl shadow-sm mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-bold text-indigo-800">📥 Import Soal dari Excel</h3>
                        <p class="text-xs text-indigo-500 mt-1">Download template terlebih dahulu untuk melihat format
                            yang benar.</p>
                    </div>
                    <a href="{{ route('guru.exams.questions.template', $exam->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition text-sm font-semibold">
                        ⬇️ Download Template
                    </a>
                </div>
                <form action="{{ route('guru.exams.questions.import', $exam->id) }}" method="POST"
                    enctype="multipart/form-data" class="flex items-center gap-4">
                    @csrf
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                        class="flex-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-indigo-700 transition whitespace-nowrap">
                        Import Soal
                    </button>
                </form>
            </div>

            {{-- FORM TAMBAH SOAL MANUAL --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border mb-8">
                <h3 class="font-bold mb-4">➕ Tambah Soal Manual</h3>
                <form action="{{ route('guru.exams.questions.store', $exam->id) }}" method="POST" class="space-y-4">
                    @csrf

                    <select name="type" id="type_soal" onchange="toggleOptions(this.value)"
                        class="w-full rounded-xl border-gray-200">
                        <option value="uraian">Soal Uraian (Essay)</option>
                        <option value="pilihan_ganda">Pilihan Ganda</option>
                    </select>

                    <textarea name="question_text" placeholder="Tulis soal di sini..." class="w-full rounded-xl border-gray-200"
                        rows="3" required></textarea>

                    <div id="opsi_pg" class="hidden space-y-2 bg-gray-50 p-4 rounded-xl">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <input type="text" name="a" placeholder="Opsi A"
                                class="rounded-lg border-gray-200 text-sm">
                            <input type="text" name="b" placeholder="Opsi B"
                                class="rounded-lg border-gray-200 text-sm">
                            <input type="text" name="c" placeholder="Opsi C"
                                class="rounded-lg border-gray-200 text-sm">
                            <input type="text" name="d" placeholder="Opsi D"
                                class="rounded-lg border-gray-200 text-sm">
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 mt-2 uppercase">Kunci Jawaban:</p>
                        <select name="correct_answer" class="w-full rounded-lg border-gray-200 text-sm">
                            <option value="a">Jawaban A</option>
                            <option value="b">Jawaban B</option>
                            <option value="c">Jawaban C</option>
                            <option value="d">Jawaban D</option>
                        </select>
                    </div>

                    <button type="submit"
                        class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition">
                        Simpan Soal
                    </button>
                </form>
            </div>

            {{-- DAFTAR SOAL --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-gray-700">📋 Daftar Soal</h3>
                    <span class="text-sm text-gray-400">{{ $exam->questions->count() }} soal</span>
                </div>

                @forelse($exam->questions as $index => $q)
                    <div class="bg-white p-6 rounded-2xl border shadow-sm">
                        <div class="flex justify-between items-center mb-2">
                            <span
                                class="text-[10px] font-black uppercase px-2 py-0.5 rounded
                            {{ $q->type == 'pilihan_ganda' ? 'bg-amber-100 text-amber-600' : 'bg-blue-100 text-blue-600' }}">
                                {{ str_replace('_', ' ', $q->type) }}
                            </span>
                            <div class="flex items-center gap-3">
                                <p class="text-gray-300 font-bold">#{{ $index + 1 }}</p>
                                {{-- Tombol hapus soal --}}
                                <form action="{{ route('guru.exams.questions.store', $exam->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus soal ini?')">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>

                        <p class="text-gray-800 font-medium mb-4">{{ $q->question_text }}</p>

                        @if ($q->type == 'pilihan_ganda')
                            <div class="grid grid-cols-2 gap-2">
                                @php
                                    $options = is_array($q->options)
                                        ? $q->options
                                        : json_decode($q->options, true) ?? [];
                                @endphp
                                @foreach ($options as $key => $val)
                                    @if ($val)
                                        <div
                                            class="text-xs p-2 rounded-lg border
                                    {{ $q->correct_answer == $key
                                        ? 'border-green-500 bg-green-50 text-green-700 font-bold'
                                        : 'border-gray-200 text-gray-500' }}">
                                            {{ strtoupper($key) }}. {{ $val }}
                                            @if ($q->correct_answer == $key)
                                                <span class="ml-1 text-green-500">✓</span>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-400 italic">Soal uraian — siswa menjawab secara tertulis.</p>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12 bg-white rounded-2xl border">
                        <p class="text-4xl mb-3">📝</p>
                        <p class="text-gray-400 italic">Belum ada soal. Tambahkan soal manual atau import dari Excel.
                        </p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <script>
        function toggleOptions(val) {
            const opsiDiv = document.getElementById('opsi_pg');
            opsiDiv.classList.toggle('hidden', val !== 'pilihan_ganda');
        }
    </script>
</x-app-layout>
