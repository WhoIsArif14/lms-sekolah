<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">📝 Ujian Tersedia</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($exams as $exam)
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="flex justify-between items-start mb-4">
                        <span class="bg-indigo-100 text-indigo-700 text-[10px] font-black px-3 py-1 rounded-full uppercase">
                            {{ $exam->course->name }}
                        </span>
                        <div class="text-right">
                            <p class="text-xs text-gray-400 font-bold">{{ $exam->duration }} Menit</p>
                        </div>
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-800 mb-4">{{ $exam->title }}</h3>
                    
                    <div class="border-t pt-4 flex items-center justify-between">
                        <p class="text-xs text-gray-500">{{ $exam->questions->count() }} Butir Soal</p>
                        <a href="{{ route('siswa.exams.enter', $exam->id) }}" 
                           class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl text-xs font-bold transition shadow-lg shadow-indigo-100"
                           onclick="return confirm('Apakah Anda yakin ingin memulai ujian sekarang? Waktu akan langsung berjalan.')">
                            MULAI UJIAN
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-3 bg-white p-12 rounded-3xl text-center border border-dashed">
                    <p class="text-gray-400 italic">Belum ada ujian yang aktif untuk kelas Anda saat ini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>