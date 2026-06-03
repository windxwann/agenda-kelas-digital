<!DOCTYPE html>
<html>
<head>
    <title>Laporan Presensi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Laporan Presensi Siswa - {{ $class->name }}</h2>
    <p>Periode: {{ $startDate ?? 'Semua' }} s/d {{ $endDate ?? 'Semua' }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>Hadir</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Telat</th>
                <th>Alpha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $student->nis }}</td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->attendances->where('status', 'present')->count() }}</td>
                <td>{{ $student->attendances->where('status', 'sick')->count() }}</td>
                <td>{{ $student->attendances->where('status', 'excused')->count() }}</td>
                <td>{{ $student->attendances->where('status', 'late')->count() }}</td>
                <td>{{ $student->attendances->where('status', 'absent')->count() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
