<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pengguna Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" class="w-full border-gray-200 rounded-2xl focus:ring-indigo-500 shadow-sm" placeholder="Masukkan nama..." required>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Email</label>
                        <input type="email" name="email" class="w-full border-gray-200 rounded-2xl focus:ring-indigo-500 shadow-sm" placeholder="email@sekolah.id" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                            <input type="password" name="password" class="w-full border-gray-200 rounded-2xl focus:ring-indigo-500 shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Role</label>
                            <select name="role" id="role-select" class="w-full border-gray-200 rounded-2xl shadow-sm" onchange="toggleFields()">
                                <option value="guru">Guru</option>
                                <option value="siswa">Siswa</option>
                                <option value="ortu">Orang Tua</option>
                            </select>
                        </div>
                    </div>

                    <div id="wa-field" class="hidden mb-5">
                        <label class="block text-sm font-bold text-green-700 mb-2">Nomor WhatsApp Orang Tua</label>
                        <input type="text" name="parent_phone" class="w-full border-green-200 bg-green-50 rounded-2xl focus:ring-green-500 shadow-sm" placeholder="Contoh: 628123456789">
                        <p class="text-[10px] text-green-600 mt-1 italic">*Gunakan kode negara 62. Nomor ini digunakan untuk bot notifikasi absen.</p>
                    </div>

                    <div id="parent-field" class="hidden mb-6 p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
                        <label class="block text-xs font-black uppercase text-indigo-400 mb-2">Hubungkan ke Akun Orang Tua (Opsional)</label>
                        <select name="parent_id" class="w-full border-transparent bg-white rounded-xl shadow-sm focus:ring-indigo-500">
                            <option value="">-- Cari Nama Orang Tua --</option>
                            @foreach($parents as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-600 text-sm font-medium">Batal</a>
                        <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-2xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition">
                            Simpan User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleFields() {
            const role = document.getElementById('role-select').value;
            const parentField = document.getElementById('parent-field');
            const waField = document.getElementById('wa-field');

            // Logika untuk menampilkan Hubungkan Ortu (Hanya Siswa)
            if (role === 'siswa') {
                parentField.classList.remove('hidden');
            } else {
                parentField.classList.add('hidden');
            }

            // Logika untuk menampilkan Nomor WA (Siswa & Ortu)
            if (role === 'siswa' || role === 'ortu') {
                waField.classList.remove('hidden');
            } else {
                waField.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>