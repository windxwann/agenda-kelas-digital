{{-- resources/views/siswa/schedule/index.blade.php --}}
@extends('layouts.siswa')

@section('title', 'Jadwal Pelajaran')
@section('header', 'Jadwal Pelajaran')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6">
        <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Jadwal Pelajaran</h1>
            <p class="mt-2 text-base text-gray-500 max-w-2xl leading-relaxed">
                Lihat jadwal pelajaran mingguan Anda. Klik pada hari untuk melihat detail mata pelajaran.
            </p>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('siswa.schedule.index', ['date' => $currentDate, 'delta' => -1]) }}"
               class="p-2.5 rounded-xl hover:bg-gray-100 transition-colors text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>

            <div class="text-center">
                <p class="text-sm font-bold text-gray-800">{{ $weekRange }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Jadwal Mingguan</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('siswa.schedule.index') }}"
                   class="px-3 py-1.5 text-xs font-semibold bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                    Hari Ini
                </a>
                <a href="{{ route('siswa.schedule.index', ['date' => $currentDate, 'delta' => 1]) }}"
                   class="p-2.5 rounded-xl hover:bg-gray-100 transition-colors text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    {{-- Daily Accordion Schedule --}}
    <div class="space-y-3" x-data="{ openDay: {{ $todayIndex }} }">

        @foreach($days as $index => $day)
        @php
            $daySchedules = $schedules[$index] ?? collect();
        @endphp

        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden
                    {{ $day['is_today'] ? 'border-blue-200 ring-1 ring-blue-200' : 'border-gray-100' }}">

            {{-- Day Header --}}
            <button @click="openDay = (openDay === {{ $index }}) ? null : {{ $index }}"
                    class="w-full flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                                {{ $day['is_today'] ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600' }}">
                        <span class="text-sm font-bold">{{ $day['date'] }}</span>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-bold {{ $day['is_today'] ? 'text-blue-600' : 'text-gray-800' }}">
                            {{ $day['name'] }}
                            @if($day['is_today'])
                                <span class="ml-2 text-[10px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full font-semibold uppercase tracking-wide">Hari Ini</span>
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ $daySchedules->count() > 0 ? $daySchedules->count() . ' mata pelajaran' : 'Tidak ada jadwal' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    @if($daySchedules->count() > 0)
                        <span class="text-xs bg-blue-50 text-blue-600 px-2.5 py-1 rounded-full font-medium">
                            {{ \Carbon\Carbon::parse($daySchedules->sortBy('start_time')->first()->start_time)->format('H:i') }}
                            –
                            {{ \Carbon\Carbon::parse($daySchedules->sortBy('start_time')->last()->end_time)->format('H:i') }}
                        </span>
                    @endif
                    <svg class="w-5 h-5 text-gray-400 transition-transform duration-200"
                         :class="{ 'rotate-180': openDay === {{ $index }} }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </button>

            {{-- Day Content --}}
            <div x-show="openDay === {{ $index }}"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="border-t border-gray-100">

                @if($daySchedules->count() > 0)
                    <div class="divide-y divide-gray-50">
                        @foreach($daySchedules->sortBy('start_time') as $schedule)
                        <div class="flex items-center gap-5 px-6 py-4 hover:bg-gray-50 transition-colors">
                            {{-- Time --}}
                            <div class="flex-shrink-0 w-20 text-center">
                                <p class="text-sm font-bold text-blue-600">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</p>
                                <div class="w-px h-3 bg-gray-200 mx-auto my-1"></div>
                                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
                            </div>

                            {{-- Color Bar --}}
                            <div class="w-1 h-12 rounded-full flex-shrink-0 bg-gradient-to-b from-blue-400 to-indigo-500"></div>

                            {{-- Subject Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 truncate">{{ $schedule->subject->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $schedule->teacher->name ?? '-' }}</p>
                            </div>

                            {{-- Room Badge --}}
                            @if($schedule->room)
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center gap-1 text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg font-medium">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $schedule->room }}
                                </span>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-8 text-center">
                        <svg class="w-10 h-10 mx-auto text-gray-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-sm text-gray-400 italic">Tidak ada jadwal pelajaran</p>
                    </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection