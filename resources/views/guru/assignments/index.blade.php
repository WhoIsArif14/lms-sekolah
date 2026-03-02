<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tight">
                        Manajemen <span class="text-indigo-600">Tugas</span>
                    </h2>
                    <p class="text-sm text-gray-500">Berikan instruksi dan pantau pengumpulan tugas siswa.</p>
                </div>
                <a href="{{ route('guru.assignments.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-indigo-700">
                    + Buat Tugas Baru
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6 text-center">
                @if($assignments->isEmpty())
                    <p class="text-gray-500 italic py-10">Belum ada tugas yang dibuat.</p>
                @else
                    @endif
            </div>
        </div>
    </div>
</x-app-layout>