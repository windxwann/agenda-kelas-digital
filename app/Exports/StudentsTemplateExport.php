<?php
// app/Exports/StudentsTemplateExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsTemplateExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return collect([
            ['Ahmad Wijaya', 'ahmad@student.com', '2024001', 'XII IPA 1', '08123456789', 'Jl. Pendidikan No. 1'],
        ]);
    }
    
    public function headings(): array
    {
        return [
            'NAMA',
            'EMAIL',
            'NIS',
            'KELAS',
            'TELEPON',
            'ALAMAT'
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}