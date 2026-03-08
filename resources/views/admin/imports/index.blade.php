<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Impor Data Pengguna & Akademik') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-red-800 mb-2">Terjadi kesalahan:</h3>
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-sm text-green-800">✓ {{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-sm text-red-800">✗ {{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                {{-- CARD 1: SISWA --}}
                <div class="bg-white rounded-lg shadow-md overflow-hidden border-t-4 border-indigo-600">
                    <div class="bg-indigo-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">Impor Data Siswa</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-blue-900 mb-2">Format File Excel:</h3>
                            <ul class="text-sm text-blue-800 space-y-1 text-xs">
                                <li>• <strong>Nama</strong>: Nama siswa</li>
                                <li>• <strong>Email</strong>: Email siswa</li>
                                <li>• <strong>Password</strong>: Default: Password123!</li>
                            </ul>
                        </div>
                        <div>
                            <a href="{{ route('admin.imports.template.students') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Template Siswa
                            </a>
                        </div>
                        <form action="{{ route('admin.imports.students') }}" method="POST"
                            enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <button type="submit"
                                class="w-full bg-indigo-600 text-white font-semibold py-2 rounded-lg hover:bg-indigo-700 transition">
                                Impor Siswa
                            </button>
                        </form>
                    </div>
                </div>

                {{-- CARD 2: ORANG TUA --}}
                <div class="bg-white rounded-lg shadow-md overflow-hidden border-t-4 border-green-600">
                    <div class="bg-green-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">Impor Data Orang Tua</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-green-900 mb-2">Format File Excel:</h3>
                            <ul class="text-sm text-green-800 space-y-1 text-xs">
                                <li>• <strong>Nama</strong>: Nama orang tua</li>
                                <li>• <strong>Email</strong>: Email orang tua</li>
                                <li>• <strong>Password</strong>: Default: Password123!</li>
                            </ul>
                        </div>
                        <div>
                            <a href="{{ route('admin.imports.template.parents') }}"
                                class="inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Template Ortu
                            </a>
                        </div>
                        <form action="{{ route('admin.imports.parents') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-4">
                            @csrf
                            <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <button type="submit"
                                class="w-full bg-green-600 text-white font-semibold py-2 rounded-lg hover:bg-green-700 transition">
                                Impor Orang Tua
                            </button>
                        </form>
                    </div>
                </div>

                {{-- CARD 3: GURU & JADWAL --}}
                <div class="bg-white rounded-lg shadow-md overflow-hidden border-t-4 border-red-600 flex flex-col">
                    <div class="bg-red-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">Impor Guru & Jadwal</h2>
                    </div>
                    <div class="p-6 space-y-4 flex flex-col flex-1">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-red-900 mb-2">Format File Excel:</h3>
                            <ul class="text-sm text-red-800 space-y-1 text-xs">
                                <li>• <strong>Nama, Email, Password</strong>: Akun Guru</li>
                                <li>• <strong>Mapel & Nama Kelas</strong>: Sesuai data sistem</li>
                                <li>• <strong>Hari & Jam Pelajaran</strong>: Untuk jadwal</li>
                            </ul>
                        </div>
                        <div>
                            <a href="{{ route('admin.teachers.template') }}"
                                class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Template Guru
                            </a>
                        </div>
                        <form action="{{ route('admin.teachers.import') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-4 flex flex-col flex-1">
                            @csrf
                            <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                                class="w-full px-3 py-2 border border-red-300 rounded-lg text-sm">
                            <button type="submit"
                                class="w-full bg-red-600 text-white font-semibold py-2 rounded-lg hover:bg-red-700 transition mt-auto">
                                Impor Guru & Jadwal
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-yellow-900 mb-3">⚠️ Perhatian Penting</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <ul class="text-sm text-yellow-800 space-y-2">
                        <li>• File harus memiliki header di baris pertama.</li>
                        <li>• Kolom <strong>Nama</strong> dan <strong>Email</strong> wajib diisi.</li>
                        <li>• Email harus unik (tidak boleh ada email ganda).</li>
                        <li>• Password default: <code class="bg-yellow-100 px-1 rounded">Password123!</code></li>
                    </ul>
                    <ul class="text-sm text-yellow-800 space-y-2">
                        <li>• <strong>Guru:</strong> Satu email guru bisa untuk banyak kelas/jadwal sekaligus.</li>
                        <li>• <strong>Nama Kelas:</strong> Harus sama persis dengan yang ada di Manajemen Kelas.</li>
                        <li>• <strong>Format Jam:</strong> Gunakan format 24 jam (Contoh: 07:30).</li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 flex justify-between items-center">
                <a href="{{ route('admin.users.index') }}"
                    class="text-indigo-600 hover:text-indigo-700 font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Manajemen Pengguna
                </a>
                <a href="{{ route('admin.courses.index') }}"
                    class="text-purple-600 hover:text-purple-700 font-medium">
                    Lihat Monitoring Kursus →
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
