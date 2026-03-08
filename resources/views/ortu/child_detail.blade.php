<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('ortu.dashboard') }}" class="text-gray-400 hover:text-indigo-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                Aktivitas Ananda: <span class="text-indigo-600">{{ $child->name }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 bg-indigo-600">
                            <h3 class="text-white font-bold flex items-center gap-2">
                                📅 10 Absensi Terakhir
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="flow-root">
                                <ul role="list" class="-mb-8">
                                    @foreach($attendances as $index => $at)
                                    <li>
                                        <div class="relative pb-8">
                                            @if($index !== count($attendances) - 1)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full {{ $at->status == 'present' ? 'bg-green-500' : 'bg-red-500' }} flex items-center justify-center ring-8 ring-white">
                                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path></svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-800 font-bold">{{ strtoupper($at->status) }} <span class="font-normal text-gray-500">({{ $at->type }})</span></p>
                                                    </div>
                                                    <div class="text-right text-xs whitespace-nowrap text-gray-500">
                                                        <time>{{ $at->created_at->format('d M Y') }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                        <h3 class="text-xl font-black text-gray-800 mb-6 flex items-center gap-3">
                            <span class="p-2 bg-amber-100 rounded-lg">✍️</span> Nilai Ujian Terbaru
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($exams as $exam)
                            <div class="p-5 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-white hover:shadow-md transition group">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-bold text-gray-700 group-hover:text-indigo-600 transition">{{ $exam->exam->title }}</h4>
                                        <p class="text-xs text-gray-400 mt-1">{{ $exam->created_at->format('d M Y, H:i') }} WIB</p>
                                    </div>
                                    <div class="text-2xl font-black {{ $exam->score >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $exam->score }}
                                    </div>
                                </div>
                                <div class="mt-4 w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $exam->score }}%"></div>
                                </div>
                            </div>
                            @empty
                            <div class="col-span-2 text-center py-12">
                                <p class="text-gray-400 italic">Belum ada riwayat nilai ujian.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-8 p-6 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-3xl text-white shadow-lg shadow-indigo-200">
                        <h4 class="font-bold text-lg mb-1">Catatan untuk Orang Tua 💡</h4>
                        <p class="text-indigo-100 text-sm">Dukung terus proses belajar ananda. Nilai hanyalah angka, yang terpenting adalah konsistensi dan kejujuran.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>