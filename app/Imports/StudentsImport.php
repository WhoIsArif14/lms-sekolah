<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Facades\Hash;

class StudentsImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip jika kolom penting kosong
        if (empty($row['name']) || empty($row['email'])) {
            return null;
        }

        // Cek apakah email sudah terdaftar
        $existingUser = User::where('email', $row['email'])->first();
        if ($existingUser) {
            return null; // Skip jika email sudah ada
        }

        $parent = null;
        if (isset($row['parent_email'])) {
            $parent = User::where('email', $row['parent_email'])->where('role', 'ortu')->first();
        }

        return new User([
            'name'      => $row['name'] ?? null,
            'email'     => $row['email'] ?? null,
            'password'  => Hash::make($row['password'] ?? 'Password123!'),
            'role'      => 'siswa',
            'parent_id' => $parent?->id,
            'parent_phone' => $row['parent_phone'] ?? $parent?->parent_phone,
        ]);
    }
}
