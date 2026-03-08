<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex border-b border-gray-200 mb-6 bg-white rounded-t-2xl px-4">
                <a href="{{ route('admin.users.index', ['tab' => 'guru']) }}"
                    class="py-4 px-6 font-bold border-b-2 transition {{ $tab == 'guru' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                    👨‍🏫 Daftar Guru
                </a>
                <a href="{{ route('admin.users.index', ['tab' => 'siswa']) }}"
                    class="py-4 px-6 font-bold border-b-2 transition {{ $tab == 'siswa' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                    🎓 Daftar Siswa
                </a>
                <a href="{{ route('admin.users.index', ['tab' => 'ortu']) }}"
                    class="py-4 px-6 font-bold border-b-2 transition {{ $tab == 'ortu' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                    👨‍👩‍👦 Daftar Orang Tua
                </a>
            </div>

            <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex gap-2">
                    <input type="hidden" name="tab" value="{{ $tab }}">

                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama atau email..."
                            class="border-gray-200 rounded-xl text-sm w-72 focus:ring-indigo-500 focus:border-indigo-500 pl-10 shadow-sm">
                        <div class="absolute left-3 top-2.5 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <button type="submit"
                        class="bg-gray-800 text-white px-6 py-2 rounded-xl hover:bg-black transition font-bold text-sm shadow-sm">Cari</button>
                </form>

                <div class="flex gap-2">
                    <a href="{{ route('admin.imports.index') }}"
                        class="bg-green-600 text-white px-6 py-2 rounded-xl hover:bg-green-700 font-bold shadow-lg shadow-green-100 transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        Impor Data
                    </a>
                    <a href="{{ route('admin.users.create') }}"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-xl hover:bg-indigo-700 font-bold shadow-lg shadow-indigo-100 transition">
                        + Tambah {{ $tab == 'ortu' ? 'Orang Tua' : ucfirst($tab) }}
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
                @if ($users->isEmpty())
                    <div class="text-center py-12">
                        <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                        <p class="text-gray-500">Data {{ $tab }} tidak ditemukan.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-[10px] font-black uppercase text-gray-400 tracking-widest">
                                        Informasi Pengguna</th>
                                    @if ($tab == 'siswa' || $tab == 'ortu')
                                        <th
                                            class="px-6 py-4 text-left text-[10px] font-black uppercase text-gray-400 tracking-widest">
                                            Kelas</th>
                                    @endif
                                    @if ($tab == 'siswa')
                                        <th
                                            class="px-6 py-4 text-left text-[10px] font-black uppercase text-gray-400 tracking-widest">
                                            Orang Tua</th>
                                    @endif
                                    <th
                                        class="px-6 py-4 text-right text-[10px] font-black uppercase text-gray-400 tracking-widest">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($users as $user)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-bold text-gray-900">{{ $user->name }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        @if ($tab == 'siswa' || $tab == 'ortu')
                                            <td class="px-6 py-4">
                                                @php
                                                    $className = '-';
                                                    if ($tab == 'siswa' && $user->schoolClass) {
                                                        $className =
                                                            $user->schoolClass->grade .
                                                            ' - ' .
                                                            $user->schoolClass->name;
                                                    } elseif ($tab == 'ortu') {
                                                        $firstChild = $user->children->first();
                                                        if ($firstChild && $firstChild->schoolClass) {
                                                            $className =
                                                                $firstChild->schoolClass->grade .
                                                                ' - ' .
                                                                $firstChild->schoolClass->name;
                                                        }
                                                    }
                                                @endphp
                                                @if ($className !== '-')
                                                    <a href="{{ route('admin.classes.show', $user->schoolClass?->id ?: $user->children->first()?->school_class_id) }}"
                                                        class="text-sm text-indigo-600 hover:underline">
                                                        {{ $className }}
                                                    </a>
                                                @else
                                                    <span
                                                        class="text-sm text-gray-700 font-medium">{{ $className }}</span>
                                                @endif
                                            </td>
                                        @endif

                                        @if ($tab == 'siswa')
                                            <td class="px-6 py-4">
                                                @if ($user->parent)
                                                    <span
                                                        class="text-sm text-gray-700 font-medium">{{ $user->parent->name }}</span>
                                                @else
                                                    <span class="text-xs text-red-400 italic">Belum dihubungkan</span>
                                                @endif
                                            </td>
                                        @endif

                                        <td class="px-6 py-4 text-right space-x-2">
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                                class="inline-flex items-center justify-center w-9 h-9 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                class="inline">
                                                @csrf @method('DELETE')
                                                <button
                                                    class="inline-flex items-center justify-center w-9 h-9 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition shadow-sm"
                                                    onclick="return confirm('Hapus user ini?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
