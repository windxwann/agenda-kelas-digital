{{-- resources/views/wakasek/teaching/index.blade.php --}}
@extends('layouts.wakasek')

@section('title', 'Kinerja Pengajaran Guru')
@section('header', 'Kinerja Guru')

@section('content')
<div class="space-y-8 pb-8">
    <!-- Header Section -->
    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Kinerja Pengajaran Guru</h1>
                <p class="mt-1 text-sm text-gray-500 font-medium">
                    Pantau tingkat keaktifan guru dalam mengisi jurnal mengajar dan agenda kelas.
                </p>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Identitas Guru</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Email & Kontak</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Total Agenda</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 bg-white">
                    @forelse($teachers as $teacher)
                        <tr class="group hover:bg-gray-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-sm font-black border border-blue-100 group-hover:scale-110 transition-transform shadow-sm">
                                        {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $teacher->name }}</span>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $teacher->nip ?? 'NIP -' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-600">{{ $teacher->email }}</span>
                                    <span class="text-xs text-gray-400 font-medium">{{ $teacher->phone ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="px-4 py-1.5 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-xl border border-emerald-100">
                                    {{ $teacher->agendas_count }} Sesi
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('wakasek-kurikulum.teaching.show', $teacher->id) }}" class="inline-flex items-center justify-center px-5 py-2 bg-blue-50 text-blue-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all border border-transparent hover:border-blue-100 group">
                                    <span>Detail Jurnal</span>
                                    <svg class="w-3.5 h-3.5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center text-gray-400 font-medium italic">Data guru tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($teachers->hasPages())
        <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100">
            {{ $teachers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
