<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@lms.com'], // Unsur unik yang dicek
            [
                'name' => 'Admin Sekolah',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Akun Guru
        User::updateOrCreate(
            ['email' => 'guru@lms.com'],
            [
                'name' => 'Ibu Guru Budi',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]
        );

        // Akun Siswa
        User::updateOrCreate(
            ['email' => 'siswa@lms.com'],
            [
                'name' => 'Siswa Teladan',
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]
        );
    }
}
