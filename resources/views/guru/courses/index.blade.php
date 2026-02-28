<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tight">
                        Mata Pelajaran <span class="text-indigo-600">Saya</span>
                    </h2>
                    <p class="text-sm text-gray-500">Kelola kurikulum dan materi pembelajaran Anda.</p>
                </div>

                <a href="{{ route('guru.courses.create') }}"
                    class="inline-flex items-center bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Kursus Baru
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm rounded-r-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($courses as $course)
                    <div
                        class="bg-white overflow-hidden shadow-sm hover:shadow-xl rounded-2xl border border-gray-100 transition-all duration-300 flex flex-col group">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <span
                                    class="bg-indigo-50 text-indigo-600 text-[10px] font-black px-2 py-1 rounded-md uppercase tracking-widest">
                                    {{ $course->classroom ?? 'Umum' }}
                                </span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-indigo-600 transition">
                                {{ $course->title }}</h3>
                            <p class="text-gray-500 text-sm line-clamp-2 leading-relaxed">
                                {{ $course->description }}
                            </p>
                        </div>

                        <div
                            class="mt-auto bg-gray-50/50 p-5 border-t border-gray-100 flex justify-between items-center">
                            <a href="{{ route('guru.courses.show', $course) }}"
                                class="text-indigo-600 hover:text-indigo-800 font-bold text-sm flex items-center group">
                                <span>Kelola Materi</span>
                                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                            <div class="text-right">
                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-tighter">Jadwal</p>
                                <p class="text-xs font-bold text-gray-600">{{ $course->day ?? '-' }},
                                    {{ $course->time_start ?? '--:--' }}</p>
                            </div>
                        </div>
                        <div class="flex justify-end gap-2 mt-4 p-4 border-t bg-gray-50">
                            <a href="{{ route('guru.courses.edit', $course) }}"
                                class="text-blue-600 hover:text-blue-800 font-bold text-xs uppercase">
                                Edit
                            </a>

                            <form action="{{ route('guru.courses.destroy', $course) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus mata pelajaran ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-600 hover:text-red-800 font-bold text-xs uppercase">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-3 bg-white p-16 text-center rounded-3xl border-2 border-dashed border-gray-200">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4 text-gray-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium">Anda belum memiliki kursus. Mulai dengan membuat kursus
                            pertama Anda!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
