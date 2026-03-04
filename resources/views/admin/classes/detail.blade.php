@foreach($classes as $class)
<tr class="border-b">
    <td class="p-4 font-bold">{{ $class->grade }} - {{ $class->name }}</td>
    <td class="p-4">{{ $class->students_count }} Siswa</td>
    <td class="p-4 text-right flex justify-end gap-2">
        
        <form action="{{ route('admin.classes.import', $class->id) }}" method="POST" enctype="multipart/form-data" class="flex gap-2">
            @csrf
            <input type="file" name="file" class="text-xs w-40" required>
            <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded-lg text-xs font-bold">
                IMPORT SISWA
            </button>
        </form>

        <form action="{{ route('admin.classes.destroy', $class->id) }}" method="POST">
            @csrf @method('DELETE')
            <button class="text-red-500 text-xs font-bold" onclick="return confirm('Hapus kelas?')">HAPUS</button>
        </form>
    </td>
</tr>
@endforeach