<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Impor Data Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- Alert Messages -->
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

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Import Siswa -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-indigo-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">Impor Data Siswa</h2>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-blue-900 mb-2">Format File Excel:</h3>
                            <ul class="text-sm text-blue-800 space-y-1">
                                <li>• <strong>Name</strong>: Nama siswa</li>
                                <li>• <strong>Email</strong>: Email siswa</li>
                                <li>• <strong>Password</strong>: Password (opsional, default: Password123!)</li>
                                <li>• <strong>Parent Email</strong>: Email orang tua (opsional)</li>
                            </ul>
                        </div>

                        <div>
                            <a href="{{ route('admin.imports.template.students') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download Template
                            </a>
                        </div>

                        <form action="{{ route('admin.imports.students') }}" method="POST"
                            enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            <div>
                                <label for="student_file" class="block text-sm font-medium text-gray-700 mb-2">Pilih
                                    File Excel/CSV</label>
                                <input type="file" id="student_file" name="file" accept=".xlsx,.xls,.csv" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <p class="text-xs text-gray-500 mt-1">Format: .xlsx, .xls, atau .csv - Maksimal 5MB</p>
                            </div>

                            <button type="submit"
                                class="w-full bg-indigo-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-indigo-700 transition flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Impor Data Siswa
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Import Orang Tua -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-green-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">Impor Data Orang Tua</h2>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-green-900 mb-2">Format File Excel:</h3>
                            <ul class="text-sm text-green-800 space-y-1">
                                <li>• <strong>Name</strong>: Nama orang tua</li>
                                <li>• <strong>Email</strong>: Email orang tua</li>
                                <li>• <strong>Password</strong>: Password (opsional, default: Password123!)</li>
                            </ul>
                        </div>

                        <div>
                            <a href="{{ route('admin.imports.template.parents') }}"
                                class="inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download Template
                            </a>
                        </div>

                        <form action="{{ route('admin.imports.parents') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-4">
                            @csrf

                            <div>
                                <label for="parent_file" class="block text-sm font-medium text-gray-700 mb-2">Pilih File
                                    Excel/CSV</label>
                                <input type="file" id="parent_file" name="file" accept=".xlsx,.xls,.csv" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <p class="text-xs text-gray-500 mt-1">Format: .xlsx, .xls, atau .csv - Maksimal 5MB</p>
                            </div>

                            <button type="submit"
                                class="w-full bg-green-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Impor Data Orang Tua
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Informasi Tambahan -->
            <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-yellow-900 mb-3">⚠️ Perhatian Penting</h3>
                <ul class="text-sm text-yellow-800 space-y-2">
                    <li>• File harus memiliki header (nama kolom) di baris pertama</li>
                    <li>• Kolom <strong>Name</strong> dan <strong>Email</strong> wajib diisi</li>
                    <li>• Email harus unik (tidak boleh sama dengan data yang sudah ada)</li>
                    <li>• Jika password tidak diisi, default password adalah <code
                            class="bg-yellow-100 px-2 py-1 rounded">Password123!</code></li>
                    <li>• Untuk siswa, opsional bisa mencantumkan Email orang tua di kolom <strong>Parent Email</strong>
                    </li>
                    <li>• Data yang sudah ada akan di-skip, tidak akan ditimpa</li>
                </ul>
            </div>

            <!-- Back Button -->
            <div class="mt-8">
                <a href="{{ route('admin.users.index') }}"
                    class="text-indigo-600 hover:text-indigo-700 font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Manajemen Pengguna
                </a>
            </div>
        </div>
    </div>

    <style>
        code {
            font-family: 'Courier New', monospace;
        }
    </style>
</x-app-layout>
