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
                <a href="{{ route('guru.assignments.create') }}"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-indigo-700">
                    + Buat Tugas Baru
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-6">
                @if ($assignments->isEmpty())
                    <p class="text-gray-500 italic py-10 text-center">Belum ada tugas yang dibuat.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($assignments as $assignment)
                            <div
                                class="p-4 border border-gray-100 rounded-2xl shadow-sm hover:bg-gray-50 transition flex justify-between items-center">
                                <div class="flex-1">
                                    <p class="font-bold text-gray-700 truncate">{{ $assignment->title }}</p>
                                    <p class="text-[11px] text-gray-500 mt-1 flex items-center">
                                        <svg class="w-3 h-3 mr-1 text-red-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Deadline:
                                        {{ $assignment->deadline ? $assignment->deadline->format('d M, H:i') : 'Terbuka' }}
                                    </p>
                                </div>
                                <form action="{{ route('guru.assignments.destroy', $assignment->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus tugas?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
