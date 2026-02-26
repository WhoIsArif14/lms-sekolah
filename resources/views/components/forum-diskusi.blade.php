<div class="mt-12 bg-white shadow-sm rounded-xl p-8 border border-gray-200">
    <h3 class="text-lg font-bold mb-6 flex items-center">
        <span class="bg-indigo-500 w-2 h-6 rounded-full mr-3"></span>
        Forum Diskusi
    </h3>

    <div class="space-y-6 mb-8 max-h-96 overflow-y-auto p-4 bg-gray-50 rounded-lg">
        @forelse($course->discussions()->with('user')->oldest()->get() as $chat)
            <div class="flex {{ $chat->user_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[80%] {{ $chat->user_id == auth()->id() ? 'bg-indigo-600 text-white rounded-l-xl rounded-tr-xl' : 'bg-white text-gray-800 rounded-r-xl rounded-tl-xl shadow-sm border' }} p-4">
                    <div class="flex justify-between items-center mb-1 gap-4">
                        <span class="text-xs font-black uppercase {{ $chat->user_id == auth()->id() ? 'text-indigo-200' : 'text-indigo-600' }}">
                            {{ $chat->user->name }} ({{ $chat->user->role }})
                        </span>
                        <span class="text-[10px] {{ $chat->user_id == auth()->id() ? 'text-indigo-300' : 'text-gray-400' }}">
                            {{ $chat->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <p class="text-sm">{{ $chat->message }}</p>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-400 italic py-4">Belum ada diskusi. Mulai tanyakan sesuatu!</p>
        @endforelse
    </div>

    <form action="{{ route('discussions.store', $course) }}" method="POST">
        @csrf
        <div class="flex gap-2">
            <input type="text" name="message" placeholder="Tulis pesan diskusi..." class="flex-1 rounded-lg border-gray-300 focus:ring-indigo-500" required>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700">
                Kirim
            </button>
        </div>
    </form>
</div>