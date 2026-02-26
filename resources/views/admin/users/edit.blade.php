<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Role</label>
                        <select name="role" class="w-full border-gray-300 rounded-lg">
                            <option value="guru" {{ $user->role == 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="siswa" {{ $user->role == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        </select>
                    </div>

                    <div class="p-4 bg-yellow-50 rounded-lg mb-4 border border-yellow-200">
                        <label class="block text-yellow-800 font-bold mb-1 italic text-sm">Reset Password (Opsional)</label>
                        <p class="text-xs text-yellow-700 mb-2">*Kosongkan jika tidak ingin mengganti password</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="password" name="password" placeholder="Password Baru" class="w-full border-gray-300 rounded-lg text-sm">
                            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" class="w-full border-gray-300 rounded-lg text-sm">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:underline text-sm">Batal</a>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 font-bold">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>