<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Guru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 text-sm font-medium uppercase">Total Kursus Saya</div>
                    <div class="text-3xl font-bold">{{ $courseCount }}</div>
                </div>
                </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Selamat Datang, Guru!</h3>
                    <p class="mb-4 text-gray-600">Mulai kelola materi pembelajaran Anda melalui tombol di bawah ini.</p>
                    
                    <a href="{{ route('guru.courses.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 transition ease-in-out duration-150">
                        Kelola Mata Pelajaran
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>