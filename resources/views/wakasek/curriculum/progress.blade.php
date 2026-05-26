{{-- resources/views/wakasek/curriculum/progress.blade.php --}}
@extends('layouts.wakasek')

@section('title', 'Detail Progres Kurikulum')
@section('header', 'Progres Kurikulum')

@section('content')
<div class="space-y-8 pb-8">
    <!-- Header Section -->
    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-4 mb-2">
                    <a href="{{ route('wakasek-kurikulum.curriculum.index') }}" class="w-10 h-10 bg-gray-50 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">Detail Progres Mata Pelajaran</h1>
                </div>
                <p class="text-sm text-gray-500 font-medium ml-14">
                    Rincian total sesi mengajar per kelas dan mata pelajaran berdasarkan pencatatan agenda harian.
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
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest w-48">Kelas</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mata Pelajaran</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center w-40">Total Sesi</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest w-64">Indikator Ketuntasan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 bg-white">
                    @forelse($progress->sortBy('class.name') as $item)
                        <tr class="group hover:bg-gray-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <span class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                                    {{ $item->class->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-[10px] font-black group-hover:scale-110 transition-transform">
                                        {{ strtoupper(substr($item->subject->name ?? '-', 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">{{ $item->subject->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="px-4 py-1.5 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-xl border border-emerald-100">
                                    {{ $item->total }} Sesi
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col gap-2">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-[10px] text-gray-400 font-bold uppercase">Estimasi</span>
                                        <span class="text-[10px] font-black text-blue-600">{{ min(100, $item->total * 5) }}%</span>
                                    </div>
                                    <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden shadow-inner">
                                        <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full" style="width: {{ min(100, $item->total * 5) }}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center text-gray-400 font-medium italic">Belum ada data progres mata pelajaran yang tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
