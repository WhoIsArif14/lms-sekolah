<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">👨‍👩‍👦 Panel Orang Tua</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($children as $child)
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center text-2xl font-bold text-indigo-600">
                            {{ strtoupper(substr($child->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="font-black text-gray-800 text-lg">{{ $child->name }}</h3>
                            <p class="text-sm text-gray-400">Kelas: {{ $child->schoolClass->name ?? 'Belum ada kelas' }}</p>
                        </div>
                    </div>
                    
                    <a href="{{ route('ortu.child.detail', $child->id) }}" class="block text-center bg-indigo-600 text-white py-3 rounded-2xl font-bold text-sm hover:bg-indigo-700 transition">
                        Lihat Absen & Nilai
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>