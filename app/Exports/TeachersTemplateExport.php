<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TeachersTemplateExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize
{
    public function array(): array
    {
        return [
            [
                'Budi Santoso',
                'budi@guru.com',
                '198501012010011001',
                '08123456789',
                'Jl. Pendidikan No. 123',
                'password123'
            ],
            [
                'Siti Aminah',
                'siti@guru.com',
                '199005052015022002',
                '08987654321',
                'Perum Indah Blok B-4',
                'guruoke123'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'NIP',
            'Telepon',
            'Alamat',
            'Password'
        ];
    }

    public function title(): string
    {
        return 'Template Import Guru';
    }
}
