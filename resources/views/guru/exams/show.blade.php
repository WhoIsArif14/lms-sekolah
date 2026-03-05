<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('guru.exams.index', $exam->course_id) }}" class="text-sm text-indigo-600 font-bold">← Kembali ke Daftar Ujian</a>
                <h2 class="text-2xl font-bold mt-2">Kelola Soal: {{ $exam->title }}</h2>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border mb-8">
                <h3 class="font-bold mb-4">➕ Tambah Soal Baru</h3>
                <form action="{{ route('guru.exams.questions.store', $exam->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <select name="type" id="type_soal" onchange="toggleOptions(this.value)" class="w-full rounded-xl border-gray-200">
                        <option value="uraian">Soal Uraian (Essay)</option>
                        <option value="pilihan_ganda">Pilihan Ganda</option>
                    </select>

                    <textarea name="question_text" placeholder="Tulis soal di sini..." class="w-full rounded-xl border-gray-200" rows="3" required></textarea>

                    <div id="opsi_pg" class="hidden space-y-2 bg-gray-50 p-4 rounded-xl">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <input type="text" name="a" placeholder="Opsi A" class="rounded-lg border-gray-200 text-sm">
                            <input type="text" name="b" placeholder="Opsi B" class="rounded-lg border-gray-200 text-sm">
                            <input type="text" name="c" placeholder="Opsi C" class="rounded-lg border-gray-200 text-sm">
                            <input type="text" name="d" placeholder="Opsi D" class="rounded-lg border-gray-200 text-sm">
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 mt-2 uppercase">Kunci Jawaban:</p>
                        <select name="correct_answer" class="w-full rounded-lg border-gray-200 text-sm">
                            <option value="a">Jawaban A</option>
                            <option value="b">Jawaban B</option>
                            <option value="c">Jawaban C</option>
                            <option value="d">Jawaban D</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold">Simpan Soal</button>
                </form>
            </div>

            <div class="space-y-4">
                @foreach($exam->questions as $index => $q)
                <div class="bg-white p-6 rounded-2xl border shadow-sm">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded {{ $q->type == 'pilihan_ganda' ? 'bg-amber-100 text-amber-600' : 'bg-blue-100 text-blue-600' }}">
                            {{ str_replace('_', ' ', $q->type) }}
                        </span>
                        <p class="text-gray-300 font-bold">#{{ $index + 1 }}</p>
                    </div>
                    <p class="text-gray-800 font-medium mb-4">{{ $q->question_text }}</p>
                    
                    @if($q->type == 'pilihan_ganda')
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($q->options as $key => $val)
                                <div class="text-xs p-2 rounded-lg border {{ $q->correct_answer == $key ? 'border-green-500 bg-green-50 text-green-700 font-bold' : 'text-gray-500' }}">
                                    {{ strtoupper($key) }}. {{ $val }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        function toggleOptions(val) {
            const opsiDiv = document.getElementById('opsi_pg');
            if(val === 'pilihan_ganda') {
                opsiDiv.classList.remove('hidden');
            } else {
                opsiDiv.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>