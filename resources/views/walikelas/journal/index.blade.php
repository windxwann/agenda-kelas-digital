{{-- resources/views/walikelas/journal/index.blade.php --}}
@extends('layouts.walikelas')

@section('title', 'Jurnal Guru')
@section('header', 'Jurnal Guru')

@section('content')
<div class="space-y-8 pb-8">
    <!-- Header Section -->
    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Jurnal Guru Kelas {{ $class->name ?? '-' }}</h1>
                <p class="mt-1 text-sm text-gray-500 font-medium">
                    Rekapitulasi materi pembelajaran dari seluruh guru mata pelajaran.
                </p>
            </div>
        </div>
    </div>

    @if($has_class)
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Waktu & Mapel</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Guru Pengajar</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Ringkasan Materi</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 bg-white">
                    @forelse($journals as $journal)
                        <tr class="group hover:bg-gray-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $journal->subject->name }}</span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">{{ $journal->date->translatedFormat('d M Y') }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-[10px] font-black shadow-sm border border-indigo-100">
                                        {{ strtoupper(substr($journal->teacher->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-bold text-gray-900">{{ $journal->teacher->name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="max-w-md">
                                    <span class="text-sm font-bold text-gray-950 tracking-tight leading-relaxed">
                                        {{ $journal->title }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('wali-kelas.agenda.show', $journal->id) }}" class="inline-flex items-center justify-center px-5 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all border border-transparent hover:border-indigo-100">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center text-gray-400 font-medium italic">Belum ada jurnal guru yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($journals->hasPages())
        <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100">
            {{ $journals->links() }}
        </div>
        @endif
    </div>
    @else
    <div class="bg-white border border-gray-100 rounded-3xl p-24 text-center shadow-sm">
        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-8 text-gray-200 border-2 border-dashed border-gray-100">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Akses Terbatas</h3>
        <p class="text-gray-500 font-medium max-w-sm mx-auto leading-relaxed">Halaman ini hanya tersedia untuk Wali Kelas yang sudah ditugaskan ke kelas tertentu.</p>
    </div>
    @endif
</div>
@endsection
