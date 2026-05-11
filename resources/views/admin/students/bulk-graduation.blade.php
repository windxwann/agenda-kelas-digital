{{-- resources/views/admin/students/bulk-graduation.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelulusan Massal')
@section('header', 'Kelulusan Massal Siswa')

@section('content')
<div class="space-y-6">
    <!-- Header dan Filter -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Proses Kelulusan</h3>
                <p class="text-sm text-gray-500">Pilih kelas untuk menampilkan daftar siswa yang akan diluluskan.</p>
            </div>
            <form action="{{ route('admin.students.bulk-graduation') }}" method="GET" class="flex gap-2">
                <select name="class_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent min-w-[200px]" onchange="this.form.submit()">
                    <option value="">-- Pilih Kelas XII --</option>
                    @foreach($classes as $classroom)
                        <option value="{{ $classroom->id }}" {{ $selectedClassId == $classroom->id ? 'selected' : '' }}>
                            {{ $classroom->name }} ({{ $classroom->grade_level }})
                        </option>
                    @endforeach
                </select>
                <noscript>
                    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Pilih</button>
                </noscript>
            </form>
        </div>
    </div>

    @if($selectedClassId)
        <form action="{{ route('admin.students.process-bulk-graduation') }}" method="POST">
            @csrf
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div class="flex items-center">
                        <input type="checkbox" id="selectAll" class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500" checked>
                        <label for="selectAll" class="ml-3 text-sm font-medium text-gray-700 cursor-pointer">Pilih Semua ({{ count($students) }} siswa)</label>
                    </div>
                    <div class="text-sm text-gray-500 italic">
                        Hapus centang untuk siswa yang tidak lulus
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Pilih</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">NIS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Saat Ini</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($students as $student)
                                <tr class="hover:bg-blue-50/30 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" 
                                               class="student-checkbox w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500" checked>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-mono text-gray-600">{{ $student->nis }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Aktif</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                        <p>Tidak ada siswa aktif di kelas ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(count($students) > 0)
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                        <a href="{{ route('admin.students.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-white transition-colors">
                            Batal
                        </a>
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin meluluskan siswa yang dipilih? Siswa yang lulus tidak akan bisa login kembali.')"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-md transition-all">
                            Proses Kelulusan
                        </button>
                    </div>
                @endif
            </div>
        </form>
    @else
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Pilih Kelas Terlebih Dahulu</h3>
            <p class="text-gray-500 mt-1">Gunakan dropdown di atas untuk memilih kelas yang akan diproses kelulusannya.</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.student-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = this.checked;
        });
    });

    // Handle single checkbox change to update selectAll state
    document.querySelectorAll('.student-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const selectAll = document.getElementById('selectAll');
            const allCheckboxes = document.querySelectorAll('.student-checkbox');
            const checkedCheckboxes = document.querySelectorAll('.student-checkbox:checked');
            
            if (selectAll) {
                selectAll.checked = allCheckboxes.length === checkedCheckboxes.length;
                selectAll.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < allCheckboxes.length;
            }
        });
    });
</script>
@endpush
@endsection
