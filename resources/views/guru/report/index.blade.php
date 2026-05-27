{{-- resources/views/guru/report/index.blade.php --}}
@extends('layouts.guru')

@section('title', 'Laporan Presensi')
@section('header', 'Laporan Presensi')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-6">
        <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Export Laporan</h1>
            <p class="mt-2 text-base text-gray-500 max-w-2xl leading-relaxed">
                Unduh rekapitulasi presensi siswa Anda dalam format PDF atau Excel untuk keperluan administrasi.
            </p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/40 overflow-hidden">
        <form action="{{ route('guru.report.export') }}" method="GET" class="p-8 md:p-12 space-y-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label for="class_id" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Pilih Kelas</label>
                    <div class="relative">
                        <select name="class_id" id="class_id" required class="appearance-none block w-full pl-4 pr-10 py-4 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm font-bold text-gray-900">
                            <option value="">Pilih Kelas...</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <label for="month_year" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Periode Laporan</label>
                    <div class="relative">
                        <select id="month_year" required class="appearance-none block w-full pl-4 pr-10 py-4 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm font-bold text-gray-900" onchange="updatePeriod(this)">
                            <option value="">Pilih Periode...</option>
                            @foreach($months as $period)
                                <option value="{{ $period['value'] }}" data-month="{{ $period['month'] }}" data-year="{{ $period['year'] }}">
                                    {{ $period['label'] }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <input type="hidden" name="month" id="hidden_month">
                    <input type="hidden" name="year" id="hidden_year">
                </div>
            </div>

            <div class="pt-6">
                <input type="hidden" name="format" value="excel">
                <button type="submit" class="w-full py-5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-2xl font-bold text-sm shadow-xl shadow-emerald-500/20 hover:scale-[1.01] transition-all flex items-center justify-center gap-3 tracking-widest uppercase">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Generate Laporan Excel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updatePeriod(select) {
        const option = select.options[select.selectedIndex];
        document.getElementById('hidden_month').value = option.dataset.month;
        document.getElementById('hidden_year').value = option.dataset.year;
    }
</script>
@endsection