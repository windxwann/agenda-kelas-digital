{{-- resources/views/guru/agenda/index.blade.php --}}
@extends('layouts.guru')

@section('title', 'Jurnal Mengajar')
@section('header', 'Jurnal Mengajar')

@section('content')
<div class="space-y-8 pb-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Riwayat Mengajar</h1>
            <p class="mt-2 text-base text-gray-500 max-w-2xl leading-relaxed">
                Kelola dan pantau seluruh catatan aktivitas belajar mengajar yang telah Anda laksanakan.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('guru.agenda.create') }}" 
               class="inline-flex items-center px-6 py-3.5 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-xl shadow-blue-500/20 hover:shadow-2xl hover:shadow-blue-500/40 hover:-translate-y-0.5 transition-all duration-300">
                <svg class="w-5 h-5 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Jurnal Baru
            </a>
        </div>
    </div>

    <!-- Stats Overview (New Element for Better Layout) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Jurnal</p>
                <p class="text-xl font-black text-gray-900">{{ $agendas->total() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Bulan Ini</p>
                <p class="text-xl font-black text-gray-900">{{ $agendas->where('date', '>=', now()->startOfMonth())->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-violet-50 rounded-2xl flex items-center justify-center text-violet-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status Aktif</p>
                <p class="text-xl font-black text-gray-900">100%</p>
            </div>
        </div>
    </div>

    <!-- Modern List Section -->
    <div class="space-y-4">
        <div class="flex items-center justify-between px-4">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Daftar Aktivitas Terbaru</h3>
            <div class="flex items-center gap-2">
                <button class="p-2 text-gray-400 hover:text-blue-600 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg></button>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4">
            @forelse($agendas as $agenda)
                <div class="group relative bg-white rounded-3xl border border-gray-100 p-6 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300">
                    <div class="flex flex-col lg:flex-row lg:items-center gap-6">
                        <!-- Date Badge -->
                        <div class="flex-shrink-0 flex lg:flex-col items-center lg:items-start gap-3 lg:gap-1 lg:w-32">
                            <span class="text-2xl font-black text-gray-900">{{ \Carbon\Carbon::parse($agenda->date)->format('d') }}</span>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-blue-600 uppercase tracking-tighter">{{ \Carbon\Carbon::parse($agenda->date)->translatedFormat('F Y') }}</span>
                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($agenda->date)->translatedFormat('l') }}</span>
                            </div>
                        </div>

                        <!-- Main Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-3">
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-full border border-blue-100/50">
                                    {{ $agenda->class->name }}
                                </span>
                                <span class="px-3 py-1 bg-gray-50 text-gray-500 text-[10px] font-bold rounded-full border border-gray-100">
                                    {{ $agenda->subject->name }}
                                </span>
                                @if($agenda->room)
                                <span class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-bold rounded-full border border-indigo-100/50">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    {{ $agenda->room }}
                                </span>
                                @endif
                                <span class="ml-auto lg:hidden">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100/50 uppercase tracking-widest">
                                        Published
                                    </span>
                                </span>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors truncate">
                                {{ $agenda->title }}
                            </h4>
                            <p class="mt-1 text-sm text-gray-500 line-clamp-2 leading-relaxed font-medium">
                                {{ strip_tags($agenda->description) }}
                            </p>
                        </div>

                        <!-- Status & Action (Desktop) -->
                        <div class="hidden lg:flex items-center gap-8 pl-8 border-l border-gray-50">
                            <div class="text-right whitespace-nowrap">
                                <span class="inline-flex items-center px-4 py-2 rounded-xl text-[10px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100/50 uppercase tracking-widest">
                                    <span class="w-2 h-2 mr-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Terbit
                                </span>
                                <p class="text-[9px] font-bold text-gray-400 mt-2 uppercase tracking-tighter">Verified Journal</p>
                            </div>
                            
                            <a href="{{ route('guru.agenda.show', $agenda->id) }}" 
                               class="w-12 h-12 text-gray-400 rounded-2xl flex items-center justify-center hover:bg-blue-50 hover:text-blue-600 transition-all duration-300 border border-transparent hover:border-blue-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>

                        <!-- Action (Mobile) -->
                        <div class="lg:hidden mt-4 pt-4 border-t border-gray-50 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-gray-400 uppercase">{{ \Carbon\Carbon::parse($agenda->date)->diffForHumans() }}</span>
                            <a href="{{ route('guru.agenda.show', $agenda->id) }}" class="text-sm font-bold text-blue-600 flex items-center gap-1">
                                Lihat Detail
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white border-2 border-dashed border-gray-100 rounded-[3rem] p-20 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-3xl flex items-center justify-center mx-auto mb-6 text-gray-200">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada jurnal</h3>
                    <p class="text-gray-500 font-medium mb-8">Anda belum memiliki catatan mengajar. Silakan buat jurnal pertama Anda.</p>
                    <a href="{{ route('guru.agenda.create') }}" class="px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-blue-500/20 hover:shadow-xl transition-all">
                        Buat Jurnal Baru
                    </a>
                </div>
            @endforelse
        </div>

        @if($agendas->hasPages())
            <div class="pt-8">
                {{ $agendas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
