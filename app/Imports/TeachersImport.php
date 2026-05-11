<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TeachersImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $teacher = User::create([
            'name'     => $row['nama'],
            'email'    => $row['email'],
            'nip'      => $row['nip'],
            'phone'    => $row['telepon'] ?? null,
            'address'  => $row['alamat'] ?? null,
            'password' => Hash::make($row['password'] ?? 'password123'),
            'email_verified_at' => now(),
        ]);

        $teacher->assignRole('teacher');

        return $teacher;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nip' => 'required|string|unique:users,nip',
            'password' => 'nullable|string|min:6',
        ];
    }
}
