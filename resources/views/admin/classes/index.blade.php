<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border mb-6">
                <h3 class="font-bold text-lg mb-4">➕ Tambah Kelas Baru</h3>
                <form action="{{ route('admin.classes.store') }}" method="POST" class="flex gap-4">
                    @csrf
                    <input type="text" name="grade" placeholder="Tingkat (10/11/12)" class="rounded-xl border-gray-200" required>
                    <input type="text" name="name" placeholder="Nama Kelas (IPA 1 / Merdeka A)" class="rounded-xl border-gray-200 w-full" required>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold">Simpan</button>
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
                        @foreach($classes as $class)
                        <tr class="border-b last:border-0">
                            <td class="p-4 font-bold">{{ $class->grade }}</td>
                            <td class="p-4">{{ $class->name }}</td>
                            <td class="p-4 text-right">
                                <form action="{{ route('admin.classes.destroy', $class->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 font-bold text-xs" onclick="return confirm('Hapus kelas ini?')">HAPUS</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>