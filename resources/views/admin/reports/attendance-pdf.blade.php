{{-- resources/views/admin/reports/attendance-pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Presensi Siswa</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .summary {
            margin-top: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .badge-present { color: green; }
        .badge-absent { color: red; }
        .badge-late { color: orange; }
        .badge-excused { color: blue; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PRESENSI SISWA</h1>
        <p>Agenda Kelas Digital</p>
        @if(isset($class))
            <p>Kelas: {{ $class->name }}</p>
        @endif
        <p>Periode: {{ request('start_date', date('Y-m-01')) }} s/d {{ request('end_date', date('Y-m-t')) }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $index => $attendance)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                <td>{{ $attendance->student->nis }}</td>
                <td>{{ $attendance->student->name }}</td>
                <td>
                    @php
                        $statusLabels = [
                            'present' => 'Hadir',
                            'absent' => 'Alpha',
                            'late' => 'Terlambat',
                            'excused' => 'Izin'
                        ];
                    @endphp
                    {{ $statusLabels[$attendance->status] }}
                </td>
                <td>{{ $attendance->note ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="summary">
        <strong>Ringkasan:</strong>
        <p>Total Data: {{ $attendances->count() }}</p>
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>
    
    <div class="footer">
        Dicetak oleh: {{ Auth::user()->name }}
    </div>
</body>
</html>