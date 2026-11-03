@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Sistem Jadwal Pelajaran & Deteksi Bentrok</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola data master, jadwal kegiatan belajar mengajar, ujian, dan pemantauan bentrok jadwal secara otomatis.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('jadwal-pelajaran.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm shadow-md shadow-teal-600/20 transition">
                <i class="fa-solid fa-plus mr-2"></i> Input Jadwal Baru
            </a>
            <a href="{{ route('log-bentrok.index') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-sm border border-rose-200 transition">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i> Log Bentrok ({{ $stats['log_bentrok'] }})
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Master Ruang -->
        <a href="{{ route('ruang.index') }}" class="block p-5 bg-white rounded-2xl border border-slate-200 hover:border-teal-400 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Master Ruang</span>
                <span class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-door-open"></i>
                </span>
            </div>
            <div class="mt-3">
                <span class="text-3xl font-extrabold text-slate-800">{{ $stats['ruang'] }}</span>
                <span class="text-xs text-slate-400 ml-1">ruangan</span>
            </div>
        </a>

        <!-- Master Guru -->
        <a href="{{ route('guru.index') }}" class="block p-5 bg-white rounded-2xl border border-slate-200 hover:border-teal-400 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Master Guru</span>
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </span>
            </div>
            <div class="mt-3">
                <span class="text-3xl font-extrabold text-slate-800">{{ $stats['guru'] }}</span>
                <span class="text-xs text-slate-400 ml-1">pengajar</span>
            </div>
        </a>

        <!-- Master Mapel -->
        <a href="{{ route('mata-pelajaran.index') }}" class="block p-5 bg-white rounded-2xl border border-slate-200 hover:border-teal-400 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Mata Pelajaran</span>
                <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-book-open"></i>
                </span>
            </div>
            <div class="mt-3">
                <span class="text-3xl font-extrabold text-slate-800">{{ $stats['mata_pelajaran'] }}</span>
                <span class="text-xs text-slate-400 ml-1">mapel</span>
            </div>
        </a>

        <!-- Master Kelas -->
        <a href="{{ route('kelas.index') }}" class="block p-5 bg-white rounded-2xl border border-slate-200 hover:border-teal-400 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Rombongan Kelas</span>
                <span class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-school"></i>
                </span>
            </div>
            <div class="mt-3">
                <span class="text-3xl font-extrabold text-slate-800">{{ $stats['kelas'] }}</span>
                <span class="text-xs text-slate-400 ml-1">kelas</span>
            </div>
        </a>

        <!-- Jadwal Pelajaran -->
        <a href="{{ route('jadwal-pelajaran.index') }}" class="block p-5 bg-white rounded-2xl border border-slate-200 hover:border-teal-400 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Jadwal Kelas</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-table-list"></i>
                </span>
            </div>
            <div class="mt-3">
                <span class="text-3xl font-extrabold text-emerald-700">{{ $stats['jadwal_pelajaran'] }}</span>
                <span class="text-xs text-slate-400 ml-1">slot aktif</span>
            </div>
        </a>

        <!-- Jadwal Ujian -->
        <a href="{{ route('jadwal-ujian.index') }}" class="block p-5 bg-white rounded-2xl border border-slate-200 hover:border-teal-400 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Jadwal Ujian</span>
                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-file-pen"></i>
                </span>
            </div>
            <div class="mt-3">
                <span class="text-3xl font-extrabold text-slate-800">{{ $stats['jadwal_ujian'] }}</span>
                <span class="text-xs text-slate-400 ml-1">sesi ujian</span>
            </div>
        </a>

        <!-- Kegiatan Sekolah -->
        <a href="{{ route('kegiatan-sekolah.index') }}" class="block p-5 bg-white rounded-2xl border border-slate-200 hover:border-teal-400 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Kegiatan Sekolah</span>
                <span class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-flag"></i>
                </span>
            </div>
            <div class="mt-3">
                <span class="text-3xl font-extrabold text-slate-800">{{ $stats['kegiatan_sekolah'] }}</span>
                <span class="text-xs text-slate-400 ml-1">agenda</span>
            </div>
        </a>

        <!-- Log Bentrok -->
        <a href="{{ route('log-bentrok.index') }}" class="block p-5 bg-white rounded-2xl border border-slate-200 hover:border-red-400 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Deteksi Bentrok</span>
                <span class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-shield-halved"></i>
                </span>
            </div>
            <div class="mt-3">
                <span class="text-3xl font-extrabold {{ $stats['log_bentrok'] > 0 ? 'text-rose-600' : 'text-emerald-600' }}">{{ $stats['log_bentrok'] }}</span>
                <span class="text-xs text-slate-400 ml-1">tercatat</span>
            </div>
        </a>
    </div>

    <!-- Two Columns: Recent Conflicts & Upcoming School Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Conflicts -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        <span class="w-7 h-7 rounded-lg bg-red-100 text-red-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </span>
                        <h3 class="font-bold text-slate-800 text-sm">Deteksi Bentrok Terkini (Automatic Log)</h3>
                    </div>
                    <a href="{{ route('log-bentrok.index') }}" class="text-xs text-teal-600 font-semibold hover:underline">Lihat Semua &rarr;</a>
                </div>

                @if($bentrokTerbaru->isEmpty())
                    <div class="text-center py-8">
                        <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-2 text-xl">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">Tidak ada riwayat bentrok jadwal</p>
                        <p class="text-xs text-slate-400 mt-0.5">Sistem memvalidasi setiap jadwal bebas dari tabrakan jam guru & ruang.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($bentrokTerbaru as $b)
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="px-2 py-0.5 rounded-full font-bold text-[10px] {{ $b->jenis_bentrok == 'BENTROK GURU' ? 'bg-red-100 text-red-700' : ($b->jenis_bentrok == 'BENTROK RUANG' ? 'bg-amber-100 text-amber-700' : 'bg-purple-100 text-purple-700') }}">
                                        {{ $b->jenis_bentrok }}
                                    </span>
                                    <span class="text-slate-400">{{ $b->waktu_deteksi->diffForHumans() }}</span>
                                </div>
                                <p class="text-slate-700 font-medium">{{ $b->deskripsi }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="pt-4 border-t border-slate-100 mt-4 flex items-center justify-between text-xs text-slate-500">
                <span>Aturan: Guru A tidak boleh mendapatkan dua kelas pada jam yang sama.</span>
                <form action="{{ route('log-bentrok.pindai') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-teal-600 font-semibold hover:underline">Pindai Database</button>
                </form>
            </div>
        </div>

        <!-- Upcoming School Events -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        <span class="w-7 h-7 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-flag"></i>
                        </span>
                        <h3 class="font-bold text-slate-800 text-sm">Agenda & Kegiatan Sekolah Mendatang</h3>
                    </div>
                    <a href="{{ route('kegiatan-sekolah.index') }}" class="text-xs text-teal-600 font-semibold hover:underline">Kelola &rarr;</a>
                </div>

                @if($kegiatanMendatang->isEmpty())
                    <div class="text-center py-8">
                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2 text-xl">
                            <i class="fa-regular fa-calendar"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">Belum ada agenda sekolah terdaftar</p>
                        <p class="text-xs text-slate-400 mt-0.5">Tambahkan kegiatan sekolah agar ruangan terpesan dan tidak bentrok dengan jadwal lain.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($kegiatanMendatang as $k)
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-bold text-slate-800 text-sm">{{ $k->nama_kegiatan }}</span>
                                    <span class="text-slate-500 font-medium">
                                        {{ $k->tanggal_mulai->format('d M') }} - {{ $k->tanggal_selesai->format('d M Y') }}
                                    </span>
                                </div>
                                <div class="flex items-center space-x-3 text-slate-600 mt-1">
                                    <span><i class="fa-regular fa-clock text-slate-400 mr-1"></i> {{ $k->format_jam }}</span>
                                    <span><i class="fa-solid fa-door-open text-slate-400 mr-1"></i> {{ $k->ruang?->nama_ruang ?? 'Tanpa Ruangan' }}</span>
                                    <span><i class="fa-solid fa-user text-slate-400 mr-1"></i> {{ $k->penanggungJawab?->nama }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="pt-4 border-t border-slate-100 mt-4 flex items-center justify-between text-xs text-slate-500">
                <span>Deteksi ruang bentrok otomatis berlaku pada agenda kegiatan.</span>
                <a href="{{ route('kegiatan-sekolah.create') }}" class="text-teal-600 font-semibold hover:underline">+ Tambah Kegiatan</a>
            </div>
        </div>
    </div>
</div>
@endsection

