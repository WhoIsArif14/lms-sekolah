<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        // Tambahkan 'ortu' ke dalam default tab agar bisa diakses
        $tab = $request->input('tab', 'guru');

        $query = User::where('id', '!=', auth()->id())->where('role', $tab);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->latest()->get();

        return view('admin.users.index', compact('users', 'tab'));
    }

    public function create()
    {
        // Ambil data orang tua untuk pilihan jika nanti ingin langsung dihubungkan
        $parents = User::where('role', 'ortu')->get();
        return view('admin.users.create', compact('parents'));
    }

    public function store(Request $request)
    {
        // 1. Validasi data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:guru,siswa,ortu', // Pastikan ada 'ortu' di sini
        ]);

        // 2. Simpan ke database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            // Tambahkan parent_id jika role-nya siswa
            'parent_id' => ($request->role == 'siswa') ? $request->parent_id : null,
        ]);

        // 3. Redirect kembali ke index dengan tab yang sesuai
        return redirect()->route('admin.users.index', ['tab' => $request->role])
            ->with('success', 'User berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        // Pilihan orang tua hanya muncul jika user yang diedit adalah 'siswa'
        $parents = User::where('role', 'ortu')->get();
        return view('admin.users.edit', compact('user', 'parents'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:guru,siswa,ortu'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'parent_id' => ['nullable', 'exists:users,id'], // Tambahkan validasi parent_id
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // Simpan parent_id jika user tersebut adalah siswa
        if ($request->role == 'siswa') {
            $user->parent_id = $request->parent_id;
        } else {
            $user->parent_id = null; // Reset jika role diubah ke guru/ortu
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index', ['tab' => $user->role])->with('success', 'Data user berhasil diperbarui!');
    }
}
