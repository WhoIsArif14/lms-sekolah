<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border mb-6">
                <h3 class="font-bold text-lg mb-4 text-gray-800">➕ Tambah Kelas Baru</h3>
                <form action="{{ route('admin.classes.store') }}" method="POST" class="flex flex-col md:flex-row gap-4">
                    @csrf
                    <input type="text" name="grade" placeholder="Tingkat (10/11/12)" class="rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500" required>
                    <input type="text" name="name" placeholder="Nama Kelas (IPA 1 / Merdeka A)" class="rounded-xl border-gray-200 w-full focus:ring-indigo-500 focus:border-indigo-500" required>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl font-bold transition">Simpan</button>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="p-4 font-black text-gray-400 uppercase text-xs">Tingkat</th>
                            <th class="p-4 font-black text-gray-400 uppercase text-xs">Nama Kelas</th>
                            <th class="p-4 font-black text-gray-400 uppercase text-xs text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $class)
                        <tr class="border-b last:border-0 hover:bg-gray-50 transition">
                            <td class="p-4 font-bold text-gray-700">{{ $class->grade }}</td>
                            <td class="p-4 text-gray-600">{{ $class->name }}</td>
                            <td class="p-4">
                                <div class="flex justify-end items-center gap-3">
                                    <a href="{{ route('admin.classes.show', $class->id) }}" 
                                       class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-lg font-bold text-xs border border-emerald-100 hover:bg-emerald-600 hover:text-white transition">
                                        DETAIL & IMPORT
                                    </a>

                                    <form action="{{ route('admin.classes.destroy', $class->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-400 hover:text-red-600 font-bold text-xs uppercase" 
                                                onclick="return confirm('Hapus kelas ini? Semua data siswa di dalamnya mungkin akan terdampak.')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-8 text-center text-gray-400 italic">Belum ada data kelas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>