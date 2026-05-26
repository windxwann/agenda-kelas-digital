{{-- resources/views/guru/agenda/show.blade.php --}}
@extends('layouts.guru')

@section('title', 'Detail Jurnal')
@section('header', 'Detail Jurnal')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-8">
    <!-- Header & Action Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-2">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('guru.agenda.index') }}" class="p-2 bg-white border border-gray-100 rounded-xl text-gray-400 hover:text-blue-600 hover:border-blue-100 transition-all shadow-sm group">
                    <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Detail Jurnal</h1>
            </div>
            <p class="text-base text-gray-500 leading-relaxed ml-12">
                Informasi lengkap mengenai sesi pembelajaran yang telah dilaksanakan.
            </p>
        </div>

        <div class="flex items-center gap-3 ml-12 md:ml-0">
            <button onclick="window.print()" class="inline-flex items-center px-6 py-3 text-sm font-semibold text-gray-700 bg-white border border-gray-200 rounded-2xl shadow-sm hover:bg-gray-50 transition-all duration-200">
                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Cetak
            </button>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/40 overflow-hidden print:shadow-none print:border-0">
        <!-- Badge Header -->
        <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/50 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-blue-600 shadow-sm border border-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Tanggal Pelaksanaan</p>
                    <p class="text-sm font-black text-gray-900">{{ \Carbon\Carbon::parse($agenda->date)->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-4 py-2 rounded-xl text-[10px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100/50 uppercase tracking-widest">
                    <span class="w-2 h-2 mr-2 rounded-full bg-emerald-500"></span>
                    Terbit (Published)
                </span>
            </div>
        </div>

        <div class="p-8 md:p-12">
            <!-- Subject & Class Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <div class="space-y-2">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Mata Pelajaran</p>
                    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100/50">
                        <p class="text-lg font-bold text-gray-900">{{ $agenda->subject->name }}</p>
                        <p class="text-xs text-gray-500 mt-1 font-medium">Kurikulum Merdeka / 2013</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Kelas / Rombel</p>
                    <div class="p-5 bg-blue-50/50 rounded-2xl border border-blue-100/50 flex items-center justify-between">
                        <div>
                            <p class="text-lg font-bold text-blue-900">{{ $agenda->class->name }}</p>
                            <p class="text-xs text-blue-600 mt-1 font-medium">Semester Ganjil 2024/2025</p>
                        </div>
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-600 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                @if($agenda->room)
                <div class="space-y-2">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Ruangan</p>
                    <div class="p-5 bg-indigo-50/50 rounded-2xl border border-indigo-100/50 flex items-center justify-between">
                        <div>
                            <p class="text-lg font-bold text-indigo-900">{{ $agenda->room }}</p>
                            <p class="text-xs text-indigo-600 mt-1 font-medium">Ruang Belajar</p>
                        </div>
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-600 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Content Section -->
            <div class="space-y-10">
                <div class="space-y-4">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Judul / Materi Pokok</p>
                    <h2 class="text-2xl font-black text-gray-900 leading-tight">
                        {{ $agenda->title }}
                    </h2>
                    <div class="h-1 w-20 bg-blue-600 rounded-full"></div>
                </div>

                <div class="space-y-4">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Detail Aktivitas & Pembahasan</p>
                    <div class="bg-gray-50 rounded-[2rem] p-8 md:p-10 border border-gray-100/50">
                        <div class="prose prose-blue max-w-none text-gray-700 font-medium leading-relaxed">
                            {!! $agenda->description !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer / Signature -->
        <div class="px-8 py-10 bg-gray-50/50 border-t border-gray-100 mt-4 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-gray-400 border border-gray-200 overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff" alt="Teacher">
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Guru Pengampu</p>
                    <p class="text-sm font-black text-gray-900">{{ Auth::user()->name }}</p>
                </div>
            </div>
            
            <div class="text-center md:text-right">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">E-Signature / Verifikasi</p>
                <div class="inline-block p-4 bg-white border border-gray-200 rounded-2xl shadow-sm italic text-xs text-gray-400">
                    Sistem Agenda Digital <br>
                    Verified at {{ $agenda->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body { background: white !important; }
        .no-print { display: none !important; }
        @page { margin: 2cm; }
    }
</style>
@endsection
