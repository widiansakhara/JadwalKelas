<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Modul Jadwal Pelajaran') - Sistem Jadwal Sekolah</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            .print-only {
                display: block !important;
            }
            body {
                background: white !important;
                color: black !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-slate-200 flex-shrink-0 flex flex-col no-print border-r border-slate-800 min-h-screen">
        <!-- Logo / Title -->
        <div class="h-16 flex items-center px-6 bg-slate-950 border-b border-slate-800 space-x-3">
            <div class="w-9 h-9 rounded-lg bg-teal-500 flex items-center justify-center text-white shadow-lg shadow-teal-500/20">
                <i class="fa-solid fa-calendar-days text-lg"></i>
            </div>
            <div>
                <h1 class="font-bold text-sm tracking-wide text-white leading-tight">SISFO JADWAL</h1>
                <p class="text-[10px] text-teal-400 font-medium">Automatic Conflict Detection</p>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto text-sm">
            <div class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Utama</div>
            <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg font-medium transition {{ request()->routeIs('dashboard') ? 'bg-teal-600 text-white shadow' : 'hover:bg-slate-800 text-slate-300' }}">
                <i class="fa-solid fa-gauge w-5 mr-3 text-center"></i> Dashboard
            </a>

            <div class="pt-4 px-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Master Data</div>
            <a href="{{ route('ruang.index') }}" class="flex items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('ruang.*') ? 'bg-teal-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <i class="fa-solid fa-door-open w-5 mr-3 text-center"></i> Master Ruang
            </a>
            <a href="{{ route('guru.index') }}" class="flex items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('guru.*') ? 'bg-teal-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <i class="fa-solid fa-chalkboard-user w-5 mr-3 text-center"></i> Master Guru
            </a>
            <a href="{{ route('mata-pelajaran.index') }}" class="flex items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('mata-pelajaran.*') ? 'bg-teal-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <i class="fa-solid fa-book-open w-5 mr-3 text-center"></i> Master Mata Pelajaran
            </a>
            <a href="{{ route('kelas.index') }}" class="flex items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('kelas.*') ? 'bg-teal-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <i class="fa-solid fa-school w-5 mr-3 text-center"></i> Master Kelas
            </a>
            <a href="{{ route('jam-pelajaran.index') }}" class="flex items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('jam-pelajaran.*') ? 'bg-teal-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <i class="fa-regular fa-clock w-5 mr-3 text-center"></i> Jam Pelajaran
            </a>
            <a href="{{ route('tahun-ajaran.index') }}" class="flex items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('tahun-ajaran.*') ? 'bg-teal-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <i class="fa-regular fa-calendar-check w-5 mr-3 text-center"></i> Tahun Ajaran
            </a>

            <div class="pt-4 px-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Jadwal & Penjadwalan</div>
            <a href="{{ route('portal.siswa') }}" target="_blank" class="flex items-center px-3 py-2 rounded-lg text-emerald-400 hover:bg-slate-800 transition">
                <i class="fa-solid fa-graduation-cap w-5 mr-3 text-center text-emerald-400"></i> Portal Siswa / Wali Murid
            </a>
            <a href="{{ route('jadwal-pelajaran.index') }}" class="flex items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('jadwal-pelajaran.*') ? 'bg-teal-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <i class="fa-solid fa-table-list w-5 mr-3 text-center"></i> Jadwal Kelas
            </a>
            <a href="{{ route('jadwal-guru.index') }}" class="flex items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('jadwal-guru.*') ? 'bg-teal-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <i class="fa-solid fa-user-clock w-5 mr-3 text-center"></i> Jadwal Guru
            </a>
            <a href="{{ route('jadwal-ruang.index') }}" class="flex items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('jadwal-ruang.*') ? 'bg-teal-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <i class="fa-solid fa-building-user w-5 mr-3 text-center"></i> Jadwal Ruang
            </a>
            <a href="{{ route('jadwal-ujian.index') }}" class="flex items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('jadwal-ujian.*') ? 'bg-teal-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <i class="fa-solid fa-file-pen w-5 mr-3 text-center"></i> Jadwal Ujian
            </a>
            <a href="{{ route('kegiatan-sekolah.index') }}" class="flex items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('kegiatan-sekolah.*') ? 'bg-teal-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <i class="fa-solid fa-flag w-5 mr-3 text-center"></i> Kegiatan Sekolah
            </a>

            <div class="pt-4 px-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Monitoring & Konflik</div>
            <a href="{{ route('log-bentrok.index') }}" class="flex items-center justify-between px-3 py-2 rounded-lg transition {{ request()->routeIs('log-bentrok.*') ? 'bg-red-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <span class="flex items-center">
                    <i class="fa-solid fa-triangle-exclamation w-5 mr-3 text-center text-amber-400"></i> Deteksi & Log Bentrok
                </span>
                @php
                    $countBentrok = \App\Models\LogBentrok::count();
                @endphp
                @if($countBentrok > 0)
                    <span class="bg-red-500/20 text-red-400 text-xs px-2 py-0.5 rounded-full font-bold border border-red-500/30">{{ $countBentrok }}</span>
                @endif
            </a>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-3 bg-slate-950 border-t border-slate-800 text-xs text-slate-400 text-center">
            Basis Data Akademik &copy; {{ date('Y') }}
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-h-screen">
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 no-print shadow-sm">
            <div class="flex items-center space-x-3">
                <span class="text-sm font-semibold text-slate-600">
                    <i class="fa-regular fa-calendar-check text-teal-600 mr-1.5"></i>
                    @php
                        $taAktif = \App\Models\TahunAjaran::where('status_aktif', true)->first();
                    @endphp
                    Tahun Ajaran:
                    @if($taAktif)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-teal-100 text-teal-800">
                            {{ $taAktif->tahun }} - Semester {{ $taAktif->semester }} (Aktif)
                        </span>
                    @else
                        <span class="text-amber-600 font-medium">Belum diatur aktif</span>
                    @endif
                </span>
            </div>

            <div class="flex items-center space-x-3">
                <a href="{{ route('portal.siswa') }}" target="_blank" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-300 hover:bg-emerald-100 transition shadow-xs" title="Lihat tampilan khusus Siswa dan Orang Tua">
                    <i class="fa-solid fa-graduation-cap mr-1.5 text-emerald-600"></i> Portal Siswa & Wali Murid
                </a>

                <form action="{{ route('log-bentrok.pindai') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-300 hover:bg-amber-100 transition shadow-sm" title="Pindai seluruh jadwal untuk mengecek bentrok otomatis">
                        <i class="fa-solid fa-radar mr-1.5 text-amber-600"></i> Pindai Bentrok Sistem
                    </button>
                </form>

                <div class="h-6 w-[1px] bg-slate-200"></div>

                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-600 text-xs">
                        AD
                    </div>
                    <span class="text-xs font-semibold text-slate-700">Admin Akademik</span>
                </div>
            </div>
        </header>

        <!-- Main Page Body -->
        <main class="flex-1 p-6 max-w-7xl w-full mx-auto">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-start shadow-sm no-print">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg mr-3 mt-0.5"></i>
                    <div>
                        <h4 class="font-bold">Berhasil!</h4>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm flex items-start shadow-sm no-print">
                    <i class="fa-solid fa-triangle-exclamation text-red-600 text-lg mr-3 mt-0.5"></i>
                    <div>
                        <h4 class="font-bold">Perhatian!</h4>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-5 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-start shadow-sm no-print">
                    <i class="fa-solid fa-bell text-amber-600 text-lg mr-3 mt-0.5"></i>
                    <div>
                        <h4 class="font-bold">Pemberitahuan Sistem</h4>
                        <p>{{ session('warning') }}</p>
                    </div>
                </div>
            @endif

            <!-- Conflict Detection Errors Box (Hasil Deteksi Bentrok Otomatis) -->
            @if(session('conflict_errors'))
                <div class="mb-6 p-5 rounded-2xl bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-300 shadow-md text-red-900 no-print animate-pulse-subtle">
                    <div class="flex items-center space-x-3 mb-2">
                        <span class="w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-ban"></i>
                        </span>
                        <div>
                            <h3 class="font-extrabold text-base text-red-800">DETEKSI BENTROK JADWAL OTOMATIS: DITOLAK!</h3>
                            <p class="text-xs text-red-600 font-medium">Sistem mencegah tabrakan jadwal sesuai aturan integritas jadwal sekolah. Konflik ini telah dicatat ke <strong>Log Bentrok</strong>.</p>
                        </div>
                    </div>
                    <div class="mt-3 bg-white/80 backdrop-blur rounded-xl p-3 border border-red-200">
                        <ul class="space-y-2 text-sm">
                            @foreach(session('conflict_errors') as $err)
                                <li class="flex items-start text-red-700 font-medium">
                                    <i class="fa-solid fa-circle-xmark text-red-500 mr-2 mt-1"></i>
                                    <span>{{ $err }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="mt-3 text-xs text-red-700 flex items-center justify-between">
                        <span><i class="fa-solid fa-lightbulb text-amber-500 mr-1"></i> Silakan pilih jam, hari, guru, atau ruang alternatif agar tidak terjadi bentrok.</span>
                        <a href="{{ route('log-bentrok.index') }}" class="font-bold underline hover:text-red-900">Lihat Riwayat Log Bentrok &rarr;</a>
                    </div>
                </div>
            @endif

            <!-- Validation Errors Standard -->
            @if($errors->any())
                <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm no-print">
                    <div class="font-bold mb-1 flex items-center">
                        <i class="fa-solid fa-circle-exclamation mr-2 text-rose-600"></i>
                        Terdapat kesalahan pada isian form:
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>

