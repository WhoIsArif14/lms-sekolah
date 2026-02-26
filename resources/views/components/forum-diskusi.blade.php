<div class="mt-12 bg-white shadow-sm rounded-xl p-8 border border-gray-200">
    <h3 class="text-lg font-bold mb-6 flex items-center text-gray-800">
        <span class="bg-indigo-500 w-2 h-6 rounded-full mr-3"></span>
        Forum Diskusi
    </h3>

    <div class="space-y-6 mb-8 max-h-[500px] overflow-y-auto p-4 bg-gray-50 rounded-xl border">
        @forelse($course->discussions()->with('user')->oldest()->get() as $chat)
            <div class="flex {{ $chat->user_id == auth()->id() ? 'justify-end' : 'justify-start' }} items-start gap-2">
                
                @if(auth()->user()->role === 'admin' || auth()->id() === $course->user_id || auth()->id() === $chat->user_id)
                    @if($chat->user_id == auth()->id()) <form action="{{ route('discussions.destroy', $chat) }}" method="POST" onsubmit="return confirm('Hapus pesan?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-300 hover:text-red-500 mt-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </form>
                    @endif
                @endif

                <div class="max-w-[80%] {{ $chat->user_id == auth()->id() ? 'bg-indigo-600 text-white rounded-l-2xl rounded-tr-2xl' : 'bg-white text-gray-800 rounded-r-2xl rounded-tl-2xl shadow-sm border border-gray-100' }} p-4">
                    <div class="flex justify-between items-center mb-1 gap-4">
                        <span class="text-[10px] font-black uppercase {{ $chat->user_id == auth()->id() ? 'text-indigo-200' : 'text-indigo-600' }}">
                            {{ $chat->user->name }} ({{ ucfirst($chat->user->role) }})
                        </span>
                        <span class="text-[9px] {{ $chat->user_id == auth()->id() ? 'text-indigo-300' : 'text-gray-400' }}">
                            {{ $chat->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <p class="text-sm leading-relaxed">{{ $chat->message }}</p>
                </div>

                @if((auth()->user()->role === 'admin' || auth()->id() === $course->user_id) && $chat->user_id != auth()->id())
                    <form action="{{ route('discussions.destroy', $chat) }}" method="POST" onsubmit="return confirm('Hapus pesan?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-gray-300 hover:text-red-500 mt-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <p class="text-center text-gray-400 italic py-10">Belum ada diskusi di sini.</p>
        @endforelse
    </div>

    <form action="{{ route('discussions.store', $course) }}" method="POST" class="flex gap-3">
        @csrf
        <input type="text" name="message" placeholder="Ketik pesan..." class="flex-1 rounded-xl border-gray-300 focus:ring-indigo-500 shadow-sm" required>
        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-indigo-700 shadow-md transition">Kirim</button>
    </form>
</div>