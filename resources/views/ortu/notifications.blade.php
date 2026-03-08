<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">🔔 Semua Notifikasi</h2>
                <button onclick="markAllAsRead()"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                    Tandai Semua Dibaca
                </button>
            </div>

            <div class="space-y-4">
                @forelse($notifications as $notification)
                    <div
                        class="bg-white p-6 rounded-lg shadow-sm border {{ $notification->is_read ? 'border-gray-200' : 'border-indigo-300 bg-indigo-50' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4
                                    class="font-semibold text-gray-900 {{ $notification->is_read ? '' : 'text-indigo-900' }} mb-2">
                                    {{ $notification->title }}
                                </h4>
                                <p class="text-gray-700 mb-3">{{ $notification->message }}</p>
                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                    <span>{{ $notification->created_at->format('d M Y H:i') }}</span>
                                    <span
                                        class="px-2 py-1 bg-gray-100 rounded text-xs">{{ ucfirst($notification->type) }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if (!$notification->is_read)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        Baru
                                    </span>
                                @endif
                                @if (!$notification->is_read)
                                    <button onclick="markAsRead({{ $notification->id }})"
                                        class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                        Tandai Dibaca
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-12 rounded-lg shadow-sm border border-gray-200 text-center">
                        <div class="text-6xl mb-4">🔔</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada notifikasi</h3>
                        <p class="text-gray-500">Notifikasi akan muncul di sini ketika anak Anda melakukan aktivitas di
                            sekolah.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($notifications->hasPages())
                <div class="mt-8">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        function markAsRead(notificationId) {
            fetch(`{{ url('ortu/notifications') }}/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function markAllAsRead() {
            fetch(`{{ route('ortu.notifications.mark-all-read') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
</x-app-layout>
