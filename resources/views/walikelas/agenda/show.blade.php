{{-- resources/views/walikelas/agenda/show.blade.php --}}
@extends('layouts.walikelas')

@section('title', 'Detail Agenda Kelas')
@section('header', 'Detail Agenda')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-12">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('wali-kelas.agenda.index') }}" class="group inline-flex items-center text-sm font-bold text-gray-500 hover:text-blue-600 transition-colors">
            <svg class="w-5 h-5 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Agenda
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
        <!-- Header Information -->
        <div class="px-10 py-10 border-b border-gray-50 bg-gradient-to-r from-gray-50/50 to-white">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 bg-blue-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 leading-tight">{{ $agenda->subject->name }}</h1>
                        <div class="flex items-center gap-3 mt-1.5 text-gray-500">
                            <span class="text-[10px] font-bold uppercase tracking-widest">{{ $agenda->date->translatedFormat('l, d F Y') }}</span>
                            @if($agenda->room)
                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                            <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">Ruangan: {{ $agenda->room }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Grid -->
        <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- Left Column: Teacher & Class -->
            <div class="space-y-8">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-4">Guru Pengajar</label>
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="w-12 h-12 bg-white text-blue-600 rounded-xl flex items-center justify-center font-black text-lg shadow-sm border border-blue-50">
                            {{ strtoupper(substr($agenda->teacher->name, 0, 1)) }}
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-black text-gray-900">{{ $agenda->teacher->name }}</span>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $agenda->teacher->nip ?? 'NIP -' }}</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-4">Judul / Materi Pokok</label>
                    <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm">
                        <p class="text-base text-gray-700 leading-relaxed font-medium">
                            {{ $agenda->title }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Notes & Assignments -->
            <div class="space-y-8">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-4">Detail Aktivitas & Penugasan</label>
                    <div class="p-6 bg-amber-50/50 border border-amber-100 rounded-2xl min-h-[220px]">
                        <div class="prose prose-sm max-w-none text-amber-900 font-medium leading-relaxed">
                            {!! $agenda->description !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer / Meta -->
        <div class="px-10 py-6 bg-gray-50/50 border-t border-gray-50 flex items-center justify-between">
            <div class="flex items-center gap-2 text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-[10px] font-bold uppercase tracking-widest italic">Terisi pada {{ $agenda->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
