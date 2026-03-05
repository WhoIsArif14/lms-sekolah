<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="sticky top-4 z-50 bg-white p-4 rounded-2xl shadow-lg border border-indigo-100 mb-8 flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-gray-800">{{ $exam->title }}</h2>
                    <p class="text-xs text-gray-400">{{ $exam->course->name }}</p>
                </div>
                <div class="bg-red-50 text-red-600 px-6 py-2 rounded-xl border border-red-100 flex items-center gap-2">
                    <span class="text-xs font-black">SISA WAKTU:</span>
                    <span id="timer" class="font-mono text-xl font-black">--:--</span>
                </div>
            </div>

            <form id="exam-form" action="{{ route('siswa.exams.submit', $exam->id) }}" method="POST">
                @csrf
                <div class="space-y-6">
                    @foreach($exam->questions as $index => $q)
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                        <div class="flex gap-4">
                            <span class="flex-none w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center font-bold text-gray-500 text-sm">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-1">
                                <p class="text-lg text-gray-800 mb-6 leading-relaxed">{{ $q->question_text }}</p>

                                @if($q->type == 'pilihan_ganda')
                                    <div class="grid grid-cols-1 gap-3">
                                        @foreach($q->options as $key => $option)
                                        <label class="relative flex items-center p-4 border rounded-2xl cursor-pointer hover:bg-indigo-50 transition group">
                                            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $key }}" class="w-5 h-5 text-indigo-600 border-gray-300 focus:ring-indigo-500" required>
                                            <span class="ml-4 text-gray-700 font-medium">{{ strtoupper($key) }}. {{ $option }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                @else
                                    <textarea name="answers[{{ $q->id }}]" 
                                              rows="4" 
                                              class="w-full rounded-2xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 p-4" 
                                              placeholder="Tulis jawaban uraian Anda di sini..." required></textarea>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8 mb-20 text-center">
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-12 py-4 rounded-2xl font-black shadow-xl shadow-indigo-100 transition transform hover:-translate-y-1"
                            onclick="return confirm('Apakah Anda yakin ingin mengakhiri ujian?')">
                        KIRIM SEMUA JAWABAN
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let duration = {{ $exam->duration }} * 60; // durasi dalam detik
        const display = document.querySelector('#timer');
        
        const startTimer = (duration, display) => {
            let timer = duration, minutes, seconds;
            const interval = setInterval(() => {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    clearInterval(interval);
                    alert("Waktu habis! Jawaban Anda akan otomatis terkirim.");
                    document.getElementById('exam-form').submit();
                }
            }, 1000);
        };

        window.onload = () => {
            startTimer(duration, display);
        };
    </script>
</x-app-layout>