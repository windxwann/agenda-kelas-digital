{{-- resources/views/wakasek/evaluation/report.blade.php --}}
@extends('layouts.wakasek')

@section('title', 'Laporan Evaluasi')
@section('header', 'Laporan Evaluasi')

@section('content')
<div class="space-y-8 pb-8">
    <!-- Header Section -->
    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-4 mb-2">
                    <a href="{{ route('wakasek-kurikulum.evaluation.index') }}" class="w-10 h-10 bg-gray-50 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">Laporan Evaluasi Kurikulum</h1>
                </div>
                <p class="text-sm text-gray-500 font-medium ml-14">
                    Rekapitulasi hasil evaluasi belajar dan laporan efektivitas pengajaran.
                </p>
            </div>
        </div>
    </div>

    <!-- Empty State / Coming Soon -->
    <div class="bg-white border border-gray-100 rounded-3xl p-24 text-center shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-blue-50 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-64 h-64 bg-indigo-50 rounded-full blur-3xl"></div>
        
        <div class="relative z-10">
            <div class="w-24 h-24 bg-blue-50 rounded-3xl flex items-center justify-center mx-auto mb-8 text-blue-300 border-2 border-dashed border-blue-200">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v16a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <h3 class="text-2xl font-black text-gray-900 mb-2">Laporan Sedang Disiapkan</h3>
            <p class="text-gray-500 font-medium max-w-sm mx-auto leading-relaxed italic">Fitur pembuatan laporan evaluasi otomatis akan segera diimplementasikan.</p>
            
            <div class="mt-10">
                <div class="inline-flex items-center px-4 py-2 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="w-2 h-2 bg-blue-400 rounded-full mr-3 animate-pulse"></span>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tahap Pengembangan</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
