<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Modul Jadwal Pelajaran') - MTs Al-Ghozaly Miftahul Huda Siwatu</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        teal: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#16a34a',
                            600: '#15803d',
                            700: '#166534',
                            800: '#14532d',
                            900: '#064e3b',
                            950: '#052e16',
                        },
                        emerald: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#16a34a',
                            600: '#15803d',
                            700: '#166534',
                            800: '#14532d',
                            900: '#064e3b',
                            950: '#052e16',
                        },
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#16a34a',
                            600: '#15803d',
                            700: '#166534',
                            800: '#14532d',
                            900: '#064e3b',
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
    <aside class="w-64 bg-gradient-to-b from-[#073b1d] via-[#052e16] to-[#031d0e] text-emerald-50 flex-shrink-0 flex flex-col no-print border-r border-emerald-900/80 min-h-screen shadow-xl">
        <!-- Logo / Title -->
        <div class="h-20 flex items-center px-5 bg-[#031f0f]/80 border-b border-emerald-800/60 space-x-3.5">
            <div class="w-12 h-12 rounded-xl bg-white/10 p-1.5 flex items-center justify-center border border-amber-400/30 shadow-inner flex-shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Miftahul Huda Siwatu" class="w-full h-full object-contain drop-shadow">
            </div>
            <div class="min-w-0">
                <h1 class="font-extrabold text-sm tracking-wide text-white leading-tight truncate">MIFTAHUL HUDA</h1>
                <p class="text-[11px] text-amber-300 font-semibold truncate">MTs Al-Ghozaly Siwatu</p>
                <p class="text-[9px] text-emerald-300/80 font-medium">Sistem Informasi Jadwal</p>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto text-sm">
            <div class="px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-emerald-300/60">Utama</div>
            <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-emerald-600 to-green-600 text-white shadow-md shadow-black/25 border-l-4 border-amber-400' : 'hover:bg-white/10 text-emerald-100/80 hover:text-white' }}">
                <i class="fa-solid fa-gauge w-5 mr-3 text-center"></i> Dashboard
            </a>

            <div class="pt-4 px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-emerald-300/60">Master Data</div>
            <a href="{{ route('ruang.index') }}" class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('ruang.*') ? 'bg-gradient-to-r from-emerald-600 to-green-600 text-white font-semibold shadow-md shadow-black/25 border-l-4 border-amber-400' : 'hover:bg-white/10 text-emerald-100/80 hover:text-white' }}">
                <i class="fa-solid fa-door-open w-5 mr-3 text-center"></i> Master Ruang
            </a>
            <a href="{{ route('guru.index') }}" class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('guru.*') ? 'bg-gradient-to-r from-emerald-600 to-green-600 text-white font-semibold shadow-md shadow-black/25 border-l-4 border-amber-400' : 'hover:bg-white/10 text-emerald-100/80 hover:text-white' }}">
                <i class="fa-solid fa-chalkboard-user w-5 mr-3 text-center"></i> Master Guru
            </a>
            <a href="{{ route('mata-pelajaran.index') }}" class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('mata-pelajaran.*') ? 'bg-gradient-to-r from-emerald-600 to-green-600 text-white font-semibold shadow-md shadow-black/25 border-l-4 border-amber-400' : 'hover:bg-white/10 text-emerald-100/80 hover:text-white' }}">
                <i class="fa-solid fa-book-open w-5 mr-3 text-center"></i> Master Mata Pelajaran
            </a>
            <a href="{{ route('kelas.index') }}" class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('kelas.*') ? 'bg-gradient-to-r from-emerald-600 to-green-600 text-white font-semibold shadow-md shadow-black/25 border-l-4 border-amber-400' : 'hover:bg-white/10 text-emerald-100/80 hover:text-white' }}">
                <i class="fa-solid fa-school w-5 mr-3 text-center"></i> Master Kelas
            </a>
            <a href="{{ route('jam-pelajaran.index') }}" class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('jam-pelajaran.*') ? 'bg-gradient-to-r from-emerald-600 to-green-600 text-white font-semibold shadow-md shadow-black/25 border-l-4 border-amber-400' : 'hover:bg-white/10 text-emerald-100/80 hover:text-white' }}">
                <i class="fa-regular fa-clock w-5 mr-3 text-center"></i> Jam Pelajaran
            </a>
            <a href="{{ route('tahun-ajaran.index') }}" class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('tahun-ajaran.*') ? 'bg-gradient-to-r from-emerald-600 to-green-600 text-white font-semibold shadow-md shadow-black/25 border-l-4 border-amber-400' : 'hover:bg-white/10 text-emerald-100/80 hover:text-white' }}">
                <i class="fa-regular fa-calendar-check w-5 mr-3 text-center"></i> Tahun Ajaran
            </a>

            <div class="pt-4 px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-emerald-300/60">Jadwal & Penjadwalan</div>
            <a href="{{ route('portal.siswa') }}" target="_blank" class="flex items-center px-3 py-2 rounded-xl text-amber-300 font-semibold hover:bg-white/10 transition">
                <i class="fa-solid fa-graduation-cap w-5 mr-3 text-center text-amber-300"></i> Portal Siswa / Wali Murid
            </a>
            <a href="{{ route('jadwal-pelajaran.index') }}" class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('jadwal-pelajaran.*') ? 'bg-gradient-to-r from-emerald-600 to-green-600 text-white font-semibold shadow-md shadow-black/25 border-l-4 border-amber-400' : 'hover:bg-white/10 text-emerald-100/80 hover:text-white' }}">
                <i class="fa-solid fa-table-list w-5 mr-3 text-center"></i> Jadwal Kelas
            </a>
            <a href="{{ route('jadwal-guru.index') }}" class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('jadwal-guru.*') ? 'bg-gradient-to-r from-emerald-600 to-green-600 text-white font-semibold shadow-md shadow-black/25 border-l-4 border-amber-400' : 'hover:bg-white/10 text-emerald-100/80 hover:text-white' }}">
                <i class="fa-solid fa-user-clock w-5 mr-3 text-center"></i> Jadwal Guru
            </a>
            <a href="{{ route('jadwal-ruang.index') }}" class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('jadwal-ruang.*') ? 'bg-gradient-to-r from-emerald-600 to-green-600 text-white font-semibold shadow-md shadow-black/25 border-l-4 border-amber-400' : 'hover:bg-white/10 text-emerald-100/80 hover:text-white' }}">
                <i class="fa-solid fa-building-user w-5 mr-3 text-center"></i> Jadwal Ruang
            </a>
            <a href="{{ route('jadwal-ujian.index') }}" class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('jadwal-ujian.*') ? 'bg-gradient-to-r from-emerald-600 to-green-600 text-white font-semibold shadow-md shadow-black/25 border-l-4 border-amber-400' : 'hover:bg-white/10 text-emerald-100/80 hover:text-white' }}">
                <i class="fa-solid fa-file-pen w-5 mr-3 text-center"></i> Jadwal Ujian
            </a>
            <a href="{{ route('kegiatan-sekolah.index') }}" class="flex items-center px-3 py-2 rounded-xl transition {{ request()->routeIs('kegiatan-sekolah.*') ? 'bg-gradient-to-r from-emerald-600 to-green-600 text-white font-semibold shadow-md shadow-black/25 border-l-4 border-amber-400' : 'hover:bg-white/10 text-emerald-100/80 hover:text-white' }}">
                <i class="fa-solid fa-flag w-5 mr-3 text-center"></i> Kegiatan Sekolah
            </a>

            <div class="pt-4 px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-emerald-300/60">Monitoring & Konflik</div>
            <a href="{{ route('log-bentrok.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl transition {{ request()->routeIs('log-bentrok.*') ? 'bg-red-600 text-white font-semibold shadow-md' : 'hover:bg-white/10 text-emerald-100/80 hover:text-white' }}">
                <span class="flex items-center">
                    <i class="fa-solid fa-triangle-exclamation w-5 mr-3 text-center text-amber-400"></i> Deteksi & Log Bentrok
                </span>
                @php
                    $countBentrok = \App\Models\LogBentrok::count();
                @endphp
                @if($countBentrok > 0)
                    <span class="bg-red-500/20 text-red-300 text-xs px-2 py-0.5 rounded-full font-bold border border-red-400/30">{{ $countBentrok }}</span>
                @endif
            </a>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-3 bg-[#02170b] border-t border-emerald-900/60 text-[11px] text-emerald-300/70 text-center">
            Yayasan Miftahul Huda Siwatu &copy; {{ date('Y') }}
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-h-screen">
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-emerald-100 flex items-center justify-between px-6 no-print shadow-xs">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 object-contain hidden sm:block">
                <span class="text-sm font-semibold text-slate-700">
                    <i class="fa-regular fa-calendar-check text-teal-600 mr-1"></i>
                    @php
                        $taAktif = \App\Models\TahunAjaran::where('status_aktif', true)->first();
                    @endphp
                    Tahun Ajaran:
                    @if($taAktif)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-teal-100 text-teal-800 border border-teal-200">
                            {{ $taAktif->tahun }} - Semester {{ $taAktif->semester }} (Aktif)
                        </span>
                    @else
                        <span class="text-amber-600 font-medium">Belum diatur aktif</span>
                    @endif
                </span>
            </div>

            <div class="flex items-center space-x-3">
                <a href="{{ route('portal.siswa') }}" target="_blank" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-teal-50 text-teal-700 border border-teal-300 hover:bg-teal-100 transition shadow-xs" title="Lihat tampilan khusus Siswa dan Orang Tua">
                    <i class="fa-solid fa-graduation-cap mr-1.5 text-teal-600"></i> Portal Siswa & Wali Murid
                </a>

                <form action="{{ route('log-bentrok.pindai') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-300 hover:bg-amber-100 transition shadow-xs" title="Pindai seluruh jadwal untuk mengecek bentrok otomatis">
                        <i class="fa-solid fa-radar mr-1.5 text-amber-600"></i> Pindai Bentrok Sistem
                    </button>
                </form>

                <div class="h-6 w-[1px] bg-slate-200"></div>

                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full bg-teal-700 flex items-center justify-center font-bold text-amber-300 text-xs shadow-xs">
                        MH
                    </div>
                    <div class="leading-tight">
                        <span class="block text-xs font-bold text-slate-800">Admin Akademik</span>
                        <span class="block text-[10px] text-teal-700 font-medium">Siwatu Wonosobo</span>
                    </div>
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

