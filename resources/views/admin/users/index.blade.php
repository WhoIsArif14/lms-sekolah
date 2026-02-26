<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex border-b border-gray-200 mb-6">
                <a href="{{ route('admin.users.index', ['tab' => 'guru']) }}" 
                   class="py-2 px-6 font-bold border-b-2 transition {{ $tab == 'guru' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    Daftar Guru
                </a>
                <a href="{{ route('admin.users.index', ['tab' => 'siswa']) }}" 
                   class="py-2 px-6 font-bold border-b-2 transition {{ $tab == 'siswa' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    Daftar Siswa
                </a>
            </div>

            <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex gap-2">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari {{ $tab }}..." 
                           class="border-gray-300 rounded-lg text-sm w-64 focus:ring-indigo-500">
                    
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-black">Cari</button>
                </form>

                <a href="{{ route('admin.users.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-bold shadow-md">
                    + Tambah {{ ucfirst($tab) }} Baru
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                @if($users->isEmpty())
                    <p class="text-center text-gray-500 py-4">Data {{ $tab }} tidak ditemukan.</p>
                @else
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase text-gray-600">Nama</th>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase text-gray-600">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($users as $user)
                            <tr>
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-4 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="px-4 py-4 text-sm space-x-3">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Edit</a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:text-red-900 font-bold" onclick="return confirm('Hapus user ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>