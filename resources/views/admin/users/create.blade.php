<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah User Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" class="w-full border-gray-300 rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Email</label>
                        <input type="email" name="email" class="w-full border-gray-300 rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Password</label>
                        <input type="password" name="password" class="w-full border-gray-300 rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Role</label>
                        <select name="role" class="w-full border-gray-300 rounded-lg">
                            <option value="guru">Guru</option>
                            <option value="siswa">Siswa</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        Simpan User
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>