<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Facades\Hash;

class ParentsImport implements ToModel, WithHeadingRow, SkipsEmptyRows
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

        return new User([
            'name'     => $row['name'] ?? null,
            'email'    => $row['email'] ?? null,
            'password' => Hash::make($row['password'] ?? 'Password123!'),
            'role'     => 'ortu',
        ]);
    }
}
