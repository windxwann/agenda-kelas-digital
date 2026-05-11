<?php
// app/Imports/StudentsImport.php

namespace App\Imports;

use App\Models\User;
use App\Models\Classes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $class = Classes::where('name', $row['kelas'])->first();
            
            if ($class) {
                $student = User::create([
                    'name' => $row['nama'],
                    'email' => ($row['email'] ?? ($row['nis'] . '@agenda.local')),
                    'nis' => $row['nis'],
                    'gender' => ($row['gender'] ?? ($row['jenis_kelamin'] ?? 'L')), // Default L
                    'class_id' => $class->id,
                    'phone' => $row['telepon'] ?? null,
                    'address' => $row['alamat'] ?? null,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]);
                
                $student->assignRole('siswa');
            }
        }
    }
    
    public function rules(): array
    {
        return [
            'nama' => 'required',
            'nis' => 'required|unique:users,nis',
            'kelas' => 'required',
            'gender' => 'nullable|in:L,P,laki-laki,perempuan',
        ];
    }
}