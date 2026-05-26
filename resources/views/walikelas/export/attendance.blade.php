{{-- resources/views/walikelas/export/attendance.blade.php --}}
@extends('layouts.walikelas')

@section('title', 'Rekap Presensi')
@section('header', 'Laporan Presensi')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-8">
        <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Rekap Presensi Kelas</h1>
            <p class="mt-2 text-base text-gray-500 max-w-2xl leading-relaxed">
                Unduh laporan kehadiran siswa dalam format Excel atau PDF untuk keperluan administrasi.
            </p>
        </div>
    </div>

    @if($has_class)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Excel Export Card -->
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden group hover:border-blue-200 transition-all duration-300">
            <div class="p-10">
                <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-2">Export Excel</h3>
                <p class="text-sm text-gray-500 font-medium leading-relaxed mb-8">Laporan mendetail dalam format spreadsheet (.xlsx) yang mudah diolah kembali.</p>
                
                <form action="{{ route('wali-kelas.export.attendance') }}" method="GET" class="space-y-4">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Pilih Bulan</label>
                        <select name="month" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all text-sm font-bold text-gray-900">
                            @foreach($months as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full py-4 bg-emerald-600 text-white rounded-xl font-bold text-xs uppercase tracking-widest shadow-lg shadow-emerald-500/20 hover:bg-emerald-700 transition-all">
                        Download Excel
                    </button>
                </form>
            </div>
        </div>

        <!-- PDF Export Card -->
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden group hover:border-blue-200 transition-all duration-300">
            <div class="p-10">
                <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-2">Export PDF</h3>
                <p class="text-sm text-gray-500 font-medium leading-relaxed mb-8">Laporan resmi siap cetak dengan format dokumen (.pdf) yang rapi dan profesional.</p>
                
                <form action="{{ route('wali-kelas.export.attendance.pdf') }}" method="GET" class="space-y-4">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Pilih Bulan</label>
                        <select name="month" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-rose-500 transition-all text-sm font-bold text-gray-900">
                            @foreach($months as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full py-4 bg-rose-600 text-white rounded-xl font-bold text-xs uppercase tracking-widest shadow-lg shadow-rose-500/20 hover:bg-rose-700 transition-all">
                        Download PDF
                    </button>
                </form>
            </div>
        </div>
    </div>
    @else
    <div class="bg-white border border-gray-100 rounded-[2.5rem] p-24 text-center shadow-sm">
        <div class="w-24 h-24 bg-gray-50 rounded-3xl flex items-center justify-center mx-auto mb-8 text-gray-200 border-2 border-dashed border-gray-100">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum Memiliki Kelas</h3>
        <p class="text-gray-500 font-medium max-w-sm mx-auto leading-relaxed">Anda harus ditugaskan sebagai Wali Kelas terlebih dahulu untuk dapat mengakses fitur laporan.</p>
    </div>
    @endif
</div>
@endsection
