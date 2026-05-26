{{-- resources/views/wakasek/teaching/show.blade.php --}}
@extends('layouts.wakasek')

@section('title', 'Detail Jurnal Mengajar - ' . $teacher->name)
@section('header', 'Detail Jurnal Mengajar')

@section('content')
<div class="space-y-8 pb-8">
    <!-- Header Section -->
    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-4 mb-2">
                    <a href="{{ route('wakasek-kurikulum.teaching.index') }}" class="w-10 h-10 bg-gray-50 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">{{ $teacher->name }}</h1>
                </div>
                <p class="text-sm text-gray-500 font-medium ml-14">
                    NIP: {{ $teacher->nip ?? '-' }} | Email: {{ $teacher->email }}
                </p>
            </div>
            <div class="flex items-center gap-4">
                <div class="px-6 py-3 bg-blue-50 text-blue-600 rounded-2xl flex items-center gap-3 border border-blue-100 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-widest opacity-80">Total Sesi</div>
                        <div class="text-xl font-black leading-none mt-1">{{ $agendas->total() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest w-48">Tanggal & Waktu</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest w-48">Kelas & Mata Pelajaran</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest min-w-[300px]">Materi & Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 bg-white">
                    @forelse($agendas as $agenda)
                        <tr class="group hover:bg-gray-50/50 transition-colors">
                            <td class="px-8 py-6 align-top">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                                        {{ \Carbon\Carbon::parse($agenda->date)->translatedFormat('l, d M Y') }}
                                    </span>
                                    <span class="text-xs text-gray-500 font-medium mt-1">
                                        Jam ke-{{ $agenda->time_slot }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6 align-top">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-900">
                                        {{ $agenda->class->name ?? '-' }}
                                    </span>
                                    <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider mt-1">
                                        {{ $agenda->subject->name ?? '-' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col gap-3">
                                    <div>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Materi / Kegiatan</div>
                                        <p class="text-sm text-gray-700 leading-relaxed">{{ $agenda->activity ?? '-' }}</p>
                                    </div>
                                    @if($agenda->notes)
                                    <div>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Catatan Khusus</div>
                                        <p class="text-sm text-gray-500 leading-relaxed italic">{{ $agenda->notes }}</p>
                                    </div>
                                    @endif
                                    @if($agenda->assignment)
                                    <div class="inline-flex items-center px-3 py-1 bg-amber-50 text-amber-600 rounded-lg text-xs font-medium border border-amber-100 self-start">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                        Tugas Diberikan
                                    </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-8 py-12 text-center text-gray-400 font-medium italic">Belum ada jurnal mengajar yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($agendas->hasPages())
        <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100">
            {{ $agendas->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
