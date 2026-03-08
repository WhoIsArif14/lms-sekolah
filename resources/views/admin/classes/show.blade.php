<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Siswa: ') }} {{ $class->grade }} - {{ $class->name }}
            </h2>
            <a href="{{ route('admin.classes.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800">
                ← Kembali ke Daftar Kelas
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-black text-gray-800 uppercase text-xs mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Import Siswa via Excel/CSV
                    </h3>
                    <form action="{{ route('admin.classes.import.siswa', $class->id) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="flex items-center justify-center w-full">
                            <label
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk
                                            upload</span> atau drag and drop</p>
                                    <p class="text-xs text-gray-400">XLSX, XLS, atau CSV (Max. 2MB)</p>
                                </div>
                                <input type="file" name="file" class="hidden" accept=".xlsx,.xls,.csv" required
                                    onchange="this.form.submit()" />
                            </label>
                        </div>
                        <div class="text-[11px] text-gray-500 italic">
                            * Format kolom: <strong>Nama, Email, Password</strong>. Baris pertama dianggap Header.
                        </div>
                    </form>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-black text-gray-800 uppercase text-xs mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Import Orang Tua via Excel/CSV
                    </h3>
                    <form action="{{ route('admin.classes.import.parents', $class->id) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="flex items-center justify-center w-full">
                            <label
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk
                                            upload</span> atau drag and drop</p>
                                    <p class="text-xs text-gray-400">XLSX, XLS, atau CSV (Max. 2MB)</p>
                                </div>
                                <input type="file" name="file" class="hidden" accept=".xlsx,.xls,.csv" required
                                    onchange="this.form.submit()" />
                            </label>
                        </div>
                        <div class="text-[11px] text-gray-500 italic">
                            * Format kolom: <strong>Nama, Email, Password, No. Telepon</strong>. Baris pertama dianggap
                            Header.
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b bg-gray-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-700">Daftar Siswa di {{ $class->name }}</h3>
                </div>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-400 uppercase text-[10px] font-black tracking-widest border-b">
                            <th class="p-4">No</th>
                            <th class="p-4">Nama Lengkap</th>
                            <th class="p-4">Email</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($students as $index => $student)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4 text-gray-400 text-sm">{{ $index + 1 }}</td>
                                <td class="p-4">
                                    <div class="font-bold text-gray-800">{{ $student->name }}</div>
                                </td>
                                <td class="p-4 text-gray-500 text-sm">{{ $student->email }}</td>
                                <td class="p-4 text-right">
                                    <button class="text-red-400 hover:text-red-600 transition">
                                        <svg class="w-5 h-5 ml-auto" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                            </path>
                                        </svg>
                                        <p class="text-gray-400 italic">Belum ada siswa di kelas ini. Silakan gunakan
                                            fitur import.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
