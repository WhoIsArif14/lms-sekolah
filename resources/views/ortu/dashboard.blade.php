<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">👨‍👩‍👦 Panel Orang Tua</h2>

            <!-- Notifikasi Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800">🔔 Notifikasi Terbaru</h3>
                    <a href="{{ route('ortu.notifications') }}"
                        class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                        Lihat Semua ({{ $unreadCount }} belum dibaca)
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($notifications as $notification)
                        <div
                            class="bg-white p-4 rounded-lg shadow-sm border {{ $notification->is_read ? 'border-gray-200' : 'border-indigo-300 bg-indigo-50' }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4
                                        class="font-semibold text-gray-900 {{ $notification->is_read ? '' : 'text-indigo-900' }}">
                                        {{ $notification->title }}
                                    </h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                                    <p class="text-xs text-gray-400 mt-2">
                                        {{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                                @if (!$notification->is_read)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        Baru
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200 text-center">
                            <p class="text-gray-500">Belum ada notifikasi</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Children Section -->
            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">👨‍🎓 Anak-anak Saya</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($children as $child)
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                        <div class="flex items-center gap-4 mb-4">
                            <div
                                class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center text-2xl font-bold text-indigo-600">
                                {{ strtoupper(substr($child->name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="font-black text-gray-800 text-lg">{{ $child->name }}</h3>
                                <p class="text-sm text-gray-400">Kelas:
                                    {{ $child->schoolClass->name ?? 'Belum ada kelas' }}</p>
                            </div>
                        </div>

                        <a href="{{ route('ortu.child.detail', $child->id) }}"
                            class="block text-center bg-indigo-600 text-white py-3 rounded-2xl font-bold text-sm hover:bg-indigo-700 transition">
                            Lihat Absen & Nilai
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
