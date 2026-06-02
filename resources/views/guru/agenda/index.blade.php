{{-- resources/views/guru/agenda/index.blade.php --}}
@extends('layouts.guru')

@section('title', 'Jurnal Mengajar')
@section('header', 'Jurnal Mengajar')

@section('content')
<div class="space-y-6 pb-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 sm:gap-6">
        <div class="flex-1 min-w-0">
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Riwayat Mengajar</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-500 font-medium">
                Kelola dan pantau catatan aktivitas belajar mengajar Anda.
            </p>
        </div>

        <div class="flex items-center">
            <a href="{{ route('guru.agenda.create') }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 text-xs sm:text-sm font-black text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-xl shadow-blue-500/20 hover:shadow-2xl hover:shadow-blue-500/40 transition-all duration-300 uppercase tracking-widest">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Jurnal Baru
            </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6">
        <div class="bg-white p-5 sm:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center sm:items-center gap-3 sm:gap-4 text-center sm:text-left">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 flex-shrink-0">
                <svg class="w-5 h-5 sm:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <div>
                <p class="text-[8px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Total Jurnal</p>
                <p class="text-lg sm:text-xl font-black text-gray-900 leading-none">{{ $agendas->total() }}</p>
            </div>
        </div>
        <div class="bg-white p-5 sm:p-6 rounded-[2rem] border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center sm:items-center gap-3 sm:gap-4 text-center sm:text-left">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 flex-shrink-0">
                <svg class="w-5 h-5 sm:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-[8px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Bulan Ini</p>
                <p class="text-lg sm:text-xl font-black text-gray-900 leading-none">{{ $agendas->where('date', '>=', now()->startOfMonth())->count() }}</p>
            </div>
        </div>
        <div class="hidden lg:flex bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm items-center gap-4">
            <div class="w-12 h-12 bg-violet-50 rounded-2xl flex items-center justify-center text-violet-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Status Aktif</p>
                <p class="text-xl font-black text-gray-900 leading-none">100%</p>
            </div>
        </div>
    </div>

    <!-- Modern List Section -->
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between px-2 gap-4">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Daftar Aktivitas Terbaru</h3>
            <form method="GET" action="{{ route('guru.agenda.index') }}" class="flex items-center gap-2">
                <select name="academic_year_id" onchange="this.form.submit()" 
                        class="block w-full sm:w-48 px-3 py-2 bg-white border-none rounded-xl focus:ring-4 focus:ring-blue-500/10 transition-all text-[10px] font-bold uppercase tracking-wider shadow-sm">
                    <option value="">Tahun Ajaran</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                            {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="grid grid-cols-1 gap-4">
            @forelse($agendas as $agenda)
                <div class="group relative bg-white rounded-[2rem] border border-gray-100 p-5 sm:p-6 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300">
                    <div class="flex flex-col lg:flex-row lg:items-center gap-4 sm:gap-6">
                        <!-- Date & Header (Mobile) -->
                        <div class="flex items-center justify-between lg:hidden">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-600 text-white rounded-2xl flex flex-col items-center justify-center shadow-lg shadow-blue-500/20">
                                    <span class="text-[8px] font-black uppercase leading-none opacity-60 mb-0.5">{{ \Carbon\Carbon::parse($agenda->date)->translatedFormat('M') }}</span>
                                    <span class="text-sm font-black leading-none">{{ \Carbon\Carbon::parse($agenda->date)->format('d') }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-black text-gray-900">{{ $agenda->class->name }}</span>
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $agenda->subject->name }}</span>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[8px] font-black uppercase tracking-widest rounded-lg border border-emerald-100/50">Published</span>
                        </div>

                        <!-- Date Badge (Desktop) -->
                        <div class="hidden lg:flex flex-shrink-0 flex-col items-start w-32">
                            <span class="text-2xl font-black text-gray-900">{{ \Carbon\Carbon::parse($agenda->date)->format('d') }}</span>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-blue-600 uppercase tracking-tighter">{{ \Carbon\Carbon::parse($agenda->date)->translatedFormat('F Y') }}</span>
                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($agenda->date)->translatedFormat('l') }}</span>
                            </div>
                        </div>

                        <!-- Main Content -->
                        <div class="flex-1 min-w-0">
                            <div class="hidden lg:flex flex-wrap items-center gap-2 mb-3">
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-full border border-blue-100/50">
                                    {{ $agenda->class->name }}
                                </span>
                                <span class="px-3 py-1 bg-gray-50 text-gray-500 text-[10px] font-bold rounded-full border border-gray-100">
                                    {{ $agenda->subject->name }}
                                </span>
                                @if($agenda->room)
                                <span class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-bold rounded-full border border-indigo-100/50">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $agenda->room }}
                                </span>
                                @endif
                            </div>
                            <h4 class="text-sm sm:text-lg font-black text-gray-900 group-hover:text-blue-600 transition-colors truncate">
                                {{ $agenda->title }}
                            </h4>
                            <p class="mt-1 text-xs sm:text-sm text-gray-500 line-clamp-2 leading-relaxed font-medium">
                                {{ strip_tags($agenda->description) }}
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex lg:flex items-center gap-4 lg:pl-8 lg:border-l lg:border-gray-50 mt-2 sm:mt-0">
                            @if($agenda->room)
                            <div class="lg:hidden flex items-center gap-1 text-gray-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                <span class="text-[10px] font-bold">{{ $agenda->room }}</span>
                            </div>
                            @endif

                            <div class="hidden lg:block text-right whitespace-nowrap mr-4">
                                <span class="inline-flex items-center px-4 py-2 rounded-xl text-[10px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100/50 uppercase tracking-widest">
                                    Terbit
                                </span>
                            </div>
                            
                            <a href="{{ route('guru.agenda.show', $agenda->id) }}" 
                               class="flex-1 lg:flex-none inline-flex items-center justify-center px-5 py-3 sm:w-12 sm:h-12 bg-gray-50 lg:bg-transparent text-blue-600 lg:text-gray-400 rounded-2xl lg:rounded-2xl border border-blue-100 lg:border-transparent hover:bg-blue-600 hover:text-white lg:hover:bg-blue-50 lg:hover:text-blue-600 transition-all duration-300">
                                <span class="lg:hidden text-[10px] font-black uppercase tracking-widest mr-2">Detail</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white border-2 border-dashed border-gray-100 rounded-[3rem] p-10 sm:p-20 text-center">
                    <div class="w-16 h-16 sm:w-20 h-20 bg-gray-50 rounded-3xl flex items-center justify-center mx-auto mb-6 text-gray-200">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-black text-gray-900 mb-2">Belum ada jurnal</h3>
                    <p class="text-xs sm:text-sm text-gray-500 font-medium mb-8">Anda belum memiliki catatan mengajar.</p>
                    <a href="{{ route('guru.agenda.create') }}" class="px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-blue-500/20 hover:shadow-xl transition-all">
                        Buat Jurnal Pertama
                    </a>
                </div>
            @endforelse
        </div>

        @if($agendas->hasPages())
            <div class="pt-6">
                {{ $agendas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
