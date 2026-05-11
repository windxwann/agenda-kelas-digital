<?php
// app/Exports/AttendanceReportExport.php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceReportExport implements FromQuery, WithHeadings, WithMapping
{
    protected $filters;
    
    public function __construct($filters)
    {
        $this->filters = $filters;
    }
    
    public function query()
    {
        $query = Attendance::with(['student', 'student.class']);
        
        if (isset($this->filters['class_id']) && $this->filters['class_id']) {
            $query->whereHas('student', function($q) {
                $q->where('class_id', $this->filters['class_id']);
            });
        }
        
        if (isset($this->filters['start_date']) && $this->filters['start_date']) {
            $query->whereDate('date', '>=', $this->filters['start_date']);
        }
        
        if (isset($this->filters['end_date']) && $this->filters['end_date']) {
            $query->whereDate('date', '<=', $this->filters['end_date']);
        }
        
        return $query;
    }
    
    public function headings(): array
    {
        return [
            'Tanggal',
            'NIS',
            'Nama Siswa',
            'Kelas',
            'Status',
            'Jam Masuk',
            'Keterangan'
        ];
    }
    
    public function map($attendance): array
    {
        $statusMap = [
            'present' => 'Hadir',
            'absent' => 'Alpha',
            'late' => 'Terlambat',
            'excused' => 'Izin'
        ];
        
        return [
            $attendance->date->format('d/m/Y'),
            $attendance->student->nis,
            $attendance->student->name,
            $attendance->student->class->name ?? '-',
            $statusMap[$attendance->status] ?? $attendance->status,
            $attendance->check_in_time ?? '-',
            $attendance->note ?? '-'
        ];
    }
}