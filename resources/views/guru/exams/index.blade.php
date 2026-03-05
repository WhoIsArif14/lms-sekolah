<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Ujian Online: {{ $course->name }}</h2>
                <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="bg-indigo-600 text-white px-4 py-2 rounded-xl font-bold text-sm">
                    + Buat Ujian Baru
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($exams as $exam)
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-start mb-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black {{ $exam->is_active ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                            {{ $exam->is_active ? 'PUBLISHED' : 'DRAFT' }}
                        </span>
                        <p class="text-xs text-gray-400">{{ $exam->duration }} Menit</p>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-4">{{ $exam->title }}</h3>
                    
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('guru.exams.show', $exam->id) }}" class="text-center bg-indigo-50 text-indigo-600 py-2 rounded-xl text-xs font-bold hover:bg-indigo-600 hover:text-white transition">
                            KELOLA SOAL ({{ $exam->questions->count() }})
                        </a>
                        <form action="{{ route('guru.exams.toggle', $exam->id) }}" method="POST">
                            @csrf
                            <button class="w-full border py-2 rounded-xl text-xs font-bold {{ $exam->is_active ? 'text-red-500 border-red-100 bg-red-50' : 'text-emerald-500 border-emerald-100 bg-emerald-50' }}">
                                {{ $exam->is_active ? 'NONAKTIFKAN' : 'AKTIFKAN SEKARANG' }}
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div id="modalTambah" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md">
            <h3 class="font-bold text-lg mb-4">Buat Ujian Baru</h3>
            <form action="{{ route('guru.exams.store', $course->id) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <input type="text" name="title" placeholder="Judul Ujian (Contoh: UTS Semester Ganjil)" class="w-full rounded-xl border-gray-200" required>
                    <input type="number" name="duration" placeholder="Durasi (Menit)" class="w-full rounded-xl border-gray-200" required>
                    <div class="flex gap-2">
                        <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="flex-1 py-2 bg-gray-100 rounded-xl font-bold">Batal</button>
                        <button type="submit" class="flex-1 py-2 bg-indigo-600 text-white rounded-xl font-bold">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>