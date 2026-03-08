<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Role</label>
                        <select name="role" id="role-select" class="w-full border-gray-300 rounded-xl shadow-sm" onchange="toggleFields()">
                            <option value="guru" {{ $user->role == 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="siswa" {{ $user->role == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="ortu" {{ $user->role == 'ortu' ? 'selected' : '' }}>Orang Tua</option>
                        </select>
                    </div>

                    <div id="wa-section" class="mb-4 {{ in_array($user->role, ['siswa', 'ortu']) ? '' : 'hidden' }}">
                        <label class="block text-green-700 font-bold mb-2 uppercase text-xs tracking-wider">Nomor WhatsApp Orang Tua</label>
                        <input type="text" name="parent_phone" value="{{ $user->parent_phone }}" 
                               class="w-full border-green-200 bg-green-50 rounded-xl shadow-sm focus:ring-green-500" 
                               placeholder="Contoh: 628123456789">
                        <p class="text-[10px] text-green-600 mt-1 italic">*Gunakan awalan 62 (Kode Negara) agar Bot WA bisa mengirim notifikasi.</p>
                    </div>

                    <div id="parent-selection" class="mb-4 {{ $user->role == 'siswa' ? '' : 'hidden' }}">
                        <label class="block text-indigo-700 font-bold mb-2 uppercase text-xs tracking-wider">Hubungkan ke Orang Tua</label>
                        <select name="parent_id" class="w-full border-indigo-200 bg-indigo-50 rounded-xl shadow-sm focus:ring-indigo-500">
                            <option value="">-- Tanpa Orang Tua --</option>
                            @foreach($parents as $p)
                                <option value="{{ $p->id }}" {{ $user->parent_id == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }} ({{ $p->email }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-indigo-400 mt-1 italic">*Data ini digunakan agar Orang Tua bisa melihat absen & nilai anak melalui akun mereka.</p>
                    </div>

                    <div class="p-4 bg-amber-50 rounded-2xl mb-6 border border-amber-100">
                        <label class="block text-amber-800 font-bold mb-1 italic text-sm">Ganti Password (Opsional)</label>
                        <p class="text-[11px] text-amber-600 mb-3">*Kosongkan jika tidak ingin mengganti password</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="password" name="password" placeholder="Password Baru" class="w-full border-gray-300 rounded-xl text-sm shadow-sm">
                            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" class="w-full border-gray-300 rounded-xl text-sm shadow-sm">
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t">
                        <a href="{{ route('admin.users.index', ['tab' => $user->role]) }}" class="text-gray-500 hover:text-gray-800 font-medium text-sm transition">
                            Batal
                        </a>
                        <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl hover:bg-indigo-700 font-bold shadow-lg shadow-indigo-100 transition transform active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleFields() {
            const role = document.getElementById('role-select').value;
            const parentDiv = document.getElementById('parent-selection');
            const waSection = document.getElementById('wa-section');
            
            // Tampilkan pilihan hubungkan ortu hanya untuk siswa
            if (role === 'siswa') {
                parentDiv.classList.remove('hidden');
            } else {
                parentDiv.classList.add('hidden');
            }

            // Tampilkan input nomor WA hanya untuk siswa dan ortu
            if (role === 'siswa' || role === 'ortu') {
                waSection.classList.remove('hidden');
            } else {
                waSection.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>