{{-- resources/views/admin/students/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manajemen Siswa')
@section('header', 'Manajemen Siswa')

@section('content')
<div class="space-y-6 pb-8">
    <!-- Header Title Section -->
    <div class="flex flex-col gap-1">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-3">
            Data Siswa
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                {{ $students->total() }} Total
            </span>
        </h1>
        <p class="text-sm text-gray-500">
            Kelola data siswa, import/export, dan pantau statistik kehadiran secara real-time.
        </p>
    </div>

    <!-- Action Toolbar Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <!-- Left Side: Descriptive Icon & Title -->
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-sm border border-blue-100">
                <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-800">Menu Tindakan & Operasi Data</h3>
                <p class="text-xs text-gray-400 mt-0.5">Impor, ekspor, kelola kelulusan, atau tambah data baru di sini</p>
            </div>
        </div>
        
        <!-- Right Side: Unified Action Buttons -->
        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto lg:justify-end">
            <!-- Import & Template Group -->
            <div class="flex items-center bg-gray-50 border border-gray-200 rounded-xl p-1 shadow-sm hover:border-gray-300 transition-all duration-200">
                <button onclick="openImportModal()" 
                        class="inline-flex items-center px-4 py-2 text-xs font-bold text-gray-600 rounded-lg hover:bg-white hover:text-gray-900 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    Import
                </button>
                <div class="w-px h-5 bg-gray-200 mx-0.5"></div>
                <a href="{{ route('admin.students.export.template') }}" 
                   class="inline-flex items-center px-4 py-2 text-xs font-bold text-gray-600 rounded-lg hover:bg-white hover:text-gray-900 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                    </svg>
                    Template
                </a>
            </div>

            <!-- Pilih Massal Button -->
            <button id="btn-toggle-select" onclick="toggleSelectMode()" 
               class="inline-flex items-center px-4 py-2.5 text-xs font-bold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-red-50 hover:text-red-600 hover:border-red-100 transition-all duration-200 shadow-sm">
                <svg class="w-4 h-4 mr-2 text-gray-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <span id="toggle-select-text">Pilih Massal</span>
            </button>

            <!-- Kelulusan Massal Button -->
            <a href="{{ route(request()->segment(1) . '.students.bulk-graduation') }}" 
               class="inline-flex items-center px-4 py-2.5 text-xs font-bold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-100 transition-all duration-200 shadow-sm">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Kelulusan Massal
            </a>

            <!-- Tambah Siswa Button -->
            <a href="{{ route('admin.students.create') }}" 
               class="inline-flex items-center px-5 py-2.5 text-xs font-black text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-md shadow-blue-500/10 hover:from-blue-700 hover:to-indigo-700 hover:shadow-lg hover:shadow-blue-500/20 transition-all duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Siswa
            </a>
        </div>
    </div>
    
    <!-- Statistik Cepat -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-2">
        <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100 group hover:border-blue-500 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Siswa</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">{{ $students->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100 group hover:border-green-500 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Kelas</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">{{ is_countable($classList) ? count($classList) : 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100 group hover:border-amber-500 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Laki-laki</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">{{ $maleCount ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100 group hover:border-purple-500 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Perempuan</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">{{ $femaleCount ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-2">
        <form method="GET" action="{{ route('admin.students.index') }}" class="flex flex-col lg:flex-row gap-4">
            <!-- Search Input -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari nama, NIS, atau NISN siswa..." 
                       class="block w-full pl-12 pr-4 py-3.5 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm">
            </div>

            <!-- Dropdown Filters Group -->
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-3">
                <!-- Filter Kelas -->
                <div class="relative w-full sm:w-48">
                    <select name="class_id" onchange="this.form.submit()" 
                            class="appearance-none block w-full pl-4 pr-10 py-3 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm">
                        <option value="">Semua Kelas</option>
                        @foreach($classList as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }} (TA {{ $class->academic_year }})
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <!-- Filter Gender -->
                <div class="relative w-full sm:w-40">
                    <select name="gender" onchange="this.form.submit()" 
                            class="appearance-none block w-full pl-4 pr-10 py-3 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm">
                        <option value="">Gender</option>
                        <option value="L" {{ request('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ request('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <!-- Filter Status -->
                <div class="relative w-full sm:w-40">
                    <select name="status" onchange="this.form.submit()" 
                            class="appearance-none block w-full pl-4 pr-10 py-3 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm">
                        <option value="">Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Lulus</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <!-- Per Page Selector -->
                <div class="relative w-full sm:w-32">
                    <select name="per_page" onchange="this.form.submit()" 
                            class="appearance-none block w-full pl-4 pr-10 py-3 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10/hal</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25/hal</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50/hal</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100/hal</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <!-- Reset Button -->
                @if(request()->anyFilled(['search', 'class_id', 'gender', 'status']))
                    <a href="{{ route('admin.students.index') }}" 
                       class="inline-flex items-center justify-center p-3 text-red-500 hover:bg-red-50 rounded-xl transition-all" 
                       title="Reset Filter">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                @endif
            </div>
        </form>
        
        <!-- Info Hasil Pencarian -->
        @if(request('search') || request('class_id') || request('gender'))
            <div class="mt-3 text-sm text-gray-600">
                Menampilkan {{ $students->count() }} dari {{ $students->total() }} siswa
                @if(request('search'))
                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-100 text-blue-800 ml-2">
                        Pencarian: {{ request('search') }}
                    </span>
                @endif
                @if(request('class_id'))
                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-100 text-blue-800 ml-2">
                        Kelas terpilih
                    </span>
                @endif
            </div>
        @endif
    </div>
    
    <!-- Tabel Siswa dengan Scroll - Desktop View -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hidden md:block">
        <div class="overflow-x-auto" style="max-height: 60vh; overflow-y: auto;">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 sticky top-0 z-10 border-b border-gray-200 shadow-sm">
                    <tr>
                        <th class="px-4 py-4 w-12 checkbox-column hidden">
                            <input type="checkbox" id="select-all" title="Pilih Semua"
                                   class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500 cursor-pointer">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">NIS</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider min-w-[200px]">Nama Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Gender</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-32">NISN</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider min-w-[150px]">Tempat/Tgl Lahir</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider min-w-[250px]">Alamat Lengkap</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-40">No Telepon</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider min-w-[180px]">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Kelas</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($students as $student)
                    <tr class="hover:bg-gray-50/50 transition-colors student-row">
                        <td class="px-4 py-4 whitespace-nowrap checkbox-column hidden">
                            <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                   class="student-checkbox w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500 cursor-pointer">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-mono font-bold bg-gray-100 text-gray-700 rounded-lg border border-gray-200">
                                {{ $student->nis }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center text-gray-500 text-xs font-bold mr-3">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div class="text-sm font-bold text-gray-900">{{ $student->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($student->gender == 'L')
                                <span class="px-3 py-1 text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100 rounded-lg">Laki-laki</span>
                            @elseif($student->gender == 'P')
                                <span class="px-3 py-1 text-xs font-bold bg-pink-50 text-pink-700 border border-pink-100 rounded-lg">Perempuan</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-mono text-sm">
                            {{ $student->nisn ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($student->tempat_lahir || $student->tanggal_lahir)
                                <div class="font-medium text-gray-900">{{ $student->tempat_lahir ?? '-' }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    {{ $student->tanggal_lahir ? \Carbon\Carbon::parse($student->tanggal_lahir)->translatedFormat('d M Y') : '-' }}
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm max-w-xs truncate">
                            <div>{{ $student->address ?? '-' }}</div>
                            @if($student->rt || $student->rw || $student->kelurahan || $student->kecamatan)
                                <div class="text-xs text-gray-400 mt-0.5 font-normal leading-relaxed">
                                    @if($student->rt || $student->rw) RT {{ $student->rt ?? '-' }}/RW {{ $student->rw ?? '-' }}, @endif
                                    @if($student->kelurahan || $student->kecamatan) {{ $student->kelurahan ?? '-' }}, {{ $student->kecamatan ?? '-' }} @endif
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            {{ $student->phone ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 truncate max-w-[150px]">
                            {{ $student->email ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100 rounded-lg">
                                {{ $student->class->name ?? 'Belum ada kelas' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($student->status == 'active')
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-green-50 text-green-700 border border-green-100">
                                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-500"></span>
                                    Aktif
                                </span>
                            @elseif($student->status == 'graduated')
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-indigo-500"></span>
                                    Lulus
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-red-50 text-red-700 border border-red-100">
                                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-red-500"></span>
                                    Non-Aktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.students.show', $student) }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.students.edit', $student) }}" class="text-gray-400 hover:text-green-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus siswa {{ $student->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center text-gray-400">
                                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <p class="text-lg font-medium">Belum ada data siswa</p>
                                <a href="{{ route('admin.students.create') }}" class="mt-4 text-blue-600 hover:underline">Tambah siswa baru</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination dengan Info -->
        @if($students->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="text-sm text-gray-500 font-medium">
                Menampilkan {{ $students->firstItem() }} - {{ $students->lastItem() }} dari {{ $students->total() }} siswa
            </div>
            <div>
                {{ $students->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
    
    <!-- Mobile Card View -->
    <div class="md:hidden space-y-3">
        @forelse($students as $student)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 hover:border-blue-200 transition-all duration-200">
            <!-- Header Card -->
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl flex items-center justify-center text-blue-600 font-bold text-lg border border-blue-100">
                        {{ strtoupper(substr($student->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-base">{{ $student->name }}</h3>
                        <p class="text-xs text-gray-500 font-mono">NIS: {{ $student->nis }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.students.show', $student) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </a>
                    <a href="{{ route('admin.students.edit', $student) }}" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Info Grid -->
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div class="bg-gray-50 rounded-xl p-2.5">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Kelas</p>
                    <p class="text-sm font-bold text-gray-900">{{ $student->class->name ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-2.5">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status</p>
                    @if($student->status == 'active')
                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-[10px] font-bold bg-green-50 text-green-700 border border-green-100">
                            <span class="w-1.5 h-1.5 mr-1 rounded-full bg-green-500"></span>
                            Aktif
                        </span>
                    @elseif($student->status == 'graduated')
                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                            <span class="w-1.5 h-1.5 mr-1 rounded-full bg-indigo-500"></span>
                            Lulus
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-[10px] font-bold bg-red-50 text-red-700 border border-red-100">
                            <span class="w-1.5 h-1.5 mr-1 rounded-full bg-red-500"></span>
                            Non-Aktif
                        </span>
                    @endif
                </div>
                <div class="bg-gray-50 rounded-xl p-2.5">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Gender</p>
                    @if($student->gender == 'L')
                        <span class="text-sm font-bold text-blue-700">Laki-laki</span>
                    @elseif($student->gender == 'P')
                        <span class="text-sm font-bold text-pink-700">Perempuan</span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </div>
                <div class="bg-gray-50 rounded-xl p-2.5">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">NISN</p>
                    <p class="text-sm font-bold font-mono text-gray-700">{{ $student->nisn ?? '-' }}</p>
                </div>
            </div>
            
            <!-- Additional Info -->
            <div class="border-t border-gray-100 pt-3 space-y-2">
                @if($student->tempat_lahir || $student->tanggal_lahir)
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div class="text-xs">
                        <p class="font-medium text-gray-900">{{ $student->tempat_lahir ?? '-' }}</p>
                        <p class="text-gray-500">{{ $student->tanggal_lahir ? \Carbon\Carbon::parse($student->tanggal_lahir)->translatedFormat('d M Y') : '-' }}</p>
                    </div>
                </div>
                @endif
                
                @if($student->phone)
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <p class="text-xs text-gray-700">{{ $student->phone }}</p>
                </div>
                @endif
                
                @if($student->address)
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <p class="text-xs text-gray-700 line-clamp-2">{{ $student->address }}</p>
                </div>
                @endif
            </div>
            
            <!-- Delete Action for Mobile -->
            <div class="border-t border-gray-100 mt-3 pt-3">
                <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="w-full" onsubmit="return confirm('Yakin ingin menghapus siswa {{ $student->name }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus Siswa
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
            <div class="flex flex-col items-center text-gray-400">
                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <p class="text-lg font-medium">Belum ada data siswa</p>
                <a href="{{ route('admin.students.create') }}" class="mt-4 text-blue-600 hover:underline">Tambah siswa baru</a>
            </div>
        </div>
        @endforelse
        
        <!-- Mobile Pagination -->
        @if($students->hasPages())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <div class="text-sm text-gray-500 font-medium text-center mb-3">
                Menampilkan {{ $students->firstItem() }} - {{ $students->lastItem() }} dari {{ $students->total() }} siswa
            </div>
            {{ $students->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Floating Bulk Action Toolbar -->
<div id="bulk-toolbar"
     class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 hidden"
     style="min-width: 340px;">
    <div class="flex items-center gap-4 bg-gray-900 text-white px-6 py-3.5 rounded-2xl shadow-2xl shadow-black/30 border border-white/10 backdrop-blur-md">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                <span id="selected-count-badge" class="text-xs font-black">0</span>
            </div>
            <span class="text-sm font-semibold" id="selected-count-label">siswa dipilih</span>
        </div>
        <div class="h-5 w-px bg-white/20"></div>
        <button type="button" id="btn-bulk-delete"
                class="flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-500 rounded-xl text-sm font-bold transition-all duration-200 hover:scale-105">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            Hapus Terpilih
        </button>
        <button type="button" id="btn-cancel-select"
                class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl text-sm font-semibold transition-all duration-200">
            Batal
        </button>
    </div>
</div>

<!-- Hidden Bulk Delete Form (uses DELETE method spoofing) -->
<form id="bulk-delete-form" action="{{ route(request()->segment(1) . '.students.bulk-delete') }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
    <div id="bulk-delete-inputs"></div>
</form>

<!-- Modal Import Excel -->
<div id="importModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="closeImportModal()"></div>
        
        <!-- Modal Panel -->
        <div class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all">
            <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900" id="modal-title">
                            Import Data Siswa
                        </h3>
                        <button type="button" onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-lg p-1.5 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="mt-4">
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-500 transition-all hover:bg-blue-50 cursor-pointer group"
                             onclick="document.getElementById('file').click()">
                            <input type="file" name="file" id="file" class="hidden" accept=".xlsx,.xls,.csv" required>
                            <div class="w-16 h-16 mx-auto bg-blue-50 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <p class="text-gray-700 font-medium">Klik untuk pilih file atau drag and drop</p>
                            <p class="text-sm text-gray-500 mt-2">Format: .xlsx, .xls, .csv (Max 2MB)</p>
                            <div id="fileName" class="mt-4 text-sm font-semibold text-blue-600 bg-blue-50 py-2 px-4 rounded-lg hidden"></div>
                        </div>

                        <!-- Dropdown Pilihan Kelas Tujuan -->
                        <div class="mt-4">
                            <label for="import_class_id" class="block text-sm font-bold text-gray-700 mb-2">Kelas Tujuan (Opsional)</label>
                            <div class="relative">
                                <select name="class_id" id="import_class_id" 
                                        class="appearance-none block w-full pl-4 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm font-semibold text-gray-700">
                                    <option value="">-- Pilih Kelas Tujuan -- (Kosongkan jika ada kolom KELAS di Excel)</option>
                                    @foreach($classList as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }} (TA {{ $class->academic_year }})</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Pilih kelas di atas jika file Excel Anda tidak memiliki judul kolom standar/kategori kelas.</p>
                        </div>
                        
                        <div class="mt-6 p-4 bg-amber-50 border border-amber-100 rounded-xl">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-amber-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-amber-800 leading-relaxed">
                                    <strong>Petunjuk:</strong> Pastikan file Excel memiliki kolom: NIS, NAMA, GENDER, KELAS, ALAMAT. (Email akan digenerate otomatis berdasarkan NIS)
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3 border-t border-gray-100">
                    <button type="button" onclick="closeImportModal()" 
                            class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-sm transition-colors">
                        Import Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // ─── Bulk Select / Delete Logic ───────────────────────────────────────────
    const selectAll       = document.getElementById('select-all');
    const bulkToolbar     = document.getElementById('bulk-toolbar');
    const selectedBadge   = document.getElementById('selected-count-badge');
    const selectedLabel   = document.getElementById('selected-count-label');
    const btnBulkDelete   = document.getElementById('btn-bulk-delete');
    const btnCancelSelect = document.getElementById('btn-cancel-select');
    const bulkForm        = document.getElementById('bulk-delete-form');
    const bulkInputs      = document.getElementById('bulk-delete-inputs');
    
    let selectModeActive = false;

    function toggleSelectMode() {
        selectModeActive = !selectModeActive;
        const cols = document.querySelectorAll('.checkbox-column');
        const btn = document.getElementById('btn-toggle-select');
        const text = document.getElementById('toggle-select-text');
        
        if (selectModeActive) {
            cols.forEach(el => el.classList.remove('hidden'));
            btn.classList.remove('text-gray-600', 'bg-white', 'border-gray-200', 'hover:bg-red-50', 'hover:text-red-600', 'hover:border-red-100');
            btn.classList.add('text-white', 'bg-red-600', 'border-transparent', 'hover:bg-red-700');
            text.textContent = 'Batal Pilih';
        } else {
            cols.forEach(el => el.classList.add('hidden'));
            btn.classList.add('text-gray-600', 'bg-white', 'border-gray-200', 'hover:bg-red-50', 'hover:text-red-600', 'hover:border-red-100');
            btn.classList.remove('text-white', 'bg-red-600', 'border-transparent', 'hover:bg-red-700');
            text.textContent = 'Pilih Massal';
            
            // Clear selections when closing
            document.querySelectorAll('.student-checkbox').forEach(cb => {
                cb.checked = false;
                cb.closest('tr').classList.remove('bg-red-50/40');
            });
            if (selectAll) {
                selectAll.checked = false;
                selectAll.indeterminate = false;
            }
            updateToolbar();
        }
    }

    function getChecked() {
        return document.querySelectorAll('.student-checkbox:checked');
    }

    function updateToolbar() {
        const checked = getChecked();
        const n = checked.length;
        if (n > 0) {
            selectedBadge.textContent  = n;
            selectedLabel.textContent  = `siswa dipilih`;
            bulkToolbar.classList.remove('hidden');
        } else {
            bulkToolbar.classList.add('hidden');
        }
        // Sync select-all state
        const all = document.querySelectorAll('.student-checkbox');
        if (selectAll) {
            selectAll.checked       = all.length > 0 && n === all.length;
            selectAll.indeterminate = n > 0 && n < all.length;
        }
    }

    // Select-All toggle
    selectAll?.addEventListener('change', function () {
        document.querySelectorAll('.student-checkbox').forEach(cb => {
            cb.checked = this.checked;
            cb.closest('tr').classList.toggle('bg-red-50/40', this.checked);
        });
        updateToolbar();
    });

    // Individual checkbox
    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('student-checkbox')) {
            e.target.closest('tr').classList.toggle('bg-red-50/40', e.target.checked);
            updateToolbar();
        }
    });

    // Hapus Terpilih
    btnBulkDelete?.addEventListener('click', function () {
        const checked = getChecked();
        if (checked.length === 0) return;

        if (!confirm(`Yakin ingin menghapus ${checked.length} siswa yang dipilih? Tindakan ini tidak dapat dibatalkan.`)) return;

        // Build hidden inputs
        bulkInputs.innerHTML = '';
        checked.forEach(cb => {
            const inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = 'student_ids[]';
            inp.value = cb.value;
            bulkInputs.appendChild(inp);
        });
        bulkForm.submit();
    });

    // Batal seleksi
    btnCancelSelect?.addEventListener('click', function () {
        if (selectModeActive) {
            toggleSelectMode();
        } else {
            document.querySelectorAll('.student-checkbox').forEach(cb => {
                cb.checked = false;
                cb.closest('tr').classList.remove('bg-red-50/40');
            });
            if (selectAll) { selectAll.checked = false; selectAll.indeterminate = false; }
            updateToolbar();
        }
    });
    // ─────────────────────────────────────────────────────────────────────────

    function openImportModal() {
        document.getElementById('importModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeImportModal() {
        document.getElementById('importModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        // Reset file input
        document.getElementById('file').value = '';
        document.getElementById('fileName').classList.add('hidden');
    }
    
    // Tampilkan nama file yang dipilih
    document.getElementById('file')?.addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        const fileNameSpan = document.getElementById('fileName');
        if (fileName) {
            fileNameSpan.textContent = `✓ ${fileName}`;
            fileNameSpan.classList.remove('hidden');
        } else {
            fileNameSpan.classList.add('hidden');
        }
    });
    
    // Tutup modal dengan ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImportModal();
        }
    });
    
    // Drag and drop untuk modal import
    const dropZone = document.querySelector('#importModal .border-2');
    if (dropZone) {
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('border-blue-500', 'bg-blue-50');
        });
        
        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('border-blue-500', 'bg-blue-50');
        });
        
        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('border-blue-500', 'bg-blue-50');
            const file = e.dataTransfer.files[0];
            const fileInput = document.getElementById('file');
            fileInput.files = e.dataTransfer.files;
            
            // Trigger change event
            const event = new Event('change', { bubbles: true });
            fileInput.dispatchEvent(event);
        });
    }
</script>
@endpush
@push('scripts')
<script>
    // Real-time Polling Logic
    // Kita akan melakukan "Smart Refresh" hanya jika ada perubahan di halaman lain
    let currentDataState = "";

    function calculateState() {
        // Ambil representasi singkat dari baris tabel saat ini
        const rows = document.querySelectorAll('tbody tr');
        return Array.from(rows).map(row => row.innerText.trim()).join('|');
    }

    async function pollData() {
        try {
            // Kita fetch halaman yang sama secara silent
            const response = await fetch(window.location.href, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const html = await response.text();
            
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Update Statistik
            const newStats = doc.querySelector('.grid.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-4');
            const oldStats = document.querySelector('.grid.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-4');
            if (newStats && oldStats && newStats.innerHTML !== oldStats.innerHTML) {
                oldStats.innerHTML = newStats.innerHTML;
            }

            // Update Tabel
            const newTable = doc.querySelector('table');
            const oldTable = document.querySelector('table');
            if (newTable && oldTable && newTable.innerHTML !== oldTable.innerHTML) {
                oldTable.innerHTML = newTable.innerHTML;
                // Re-apply checkbox column visibility based on selectModeActive
                const cols = oldTable.querySelectorAll('.checkbox-column');
                if (selectModeActive) {
                    cols.forEach(el => el.classList.remove('hidden'));
                } else {
                    cols.forEach(el => el.classList.add('hidden'));
                }
                oldTable.closest('.overflow-x-auto').classList.add('bg-blue-50');
                setTimeout(() => oldTable.closest('.overflow-x-auto').classList.remove('bg-blue-50'), 1000);
            }
        } catch (e) {
            // Silent error
        }
    }

    // Jalankan polling setiap 60 detik agar server lokal tidak terbebani secara berlebihan
    setInterval(pollData, 60000);
</script>
@endpush
@endsection