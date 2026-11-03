<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Jadwal Siswa & Orang Tua - SMA / SMK Negeri 1</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Top Navigation Bar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-teal-600 to-emerald-500 text-white flex items-center justify-center text-lg shadow-md shadow-teal-500/20">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div>
                    <h1 class="font-extrabold text-sm sm:text-base text-slate-900 leading-tight">Portal Jadwal Siswa & Wali Murid</h1>
                    <p class="text-[11px] text-teal-600 font-semibold">SMA / SMK Negeri 1 Akademik</p>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                    <i class="fa-solid fa-gauge mr-1.5 text-slate-500"></i> Panel Admin
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-6xl w-full mx-auto p-4 sm:p-6 space-y-6">

        <!-- Banner & Selector -->
        <div class="bg-gradient-to-r from-teal-700 via-teal-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 opacity-10 text-white text-[200px] pointer-events-none">
                <i class="fa-solid fa-calendar-check"></i>
            </div>

            <div class="relative z-10 max-w-2xl">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-teal-500/20 text-teal-200 border border-teal-400/30 mb-3">
                    <i class="fa-regular fa-calendar mr-1.5"></i> Tahun Ajaran {{ $tahunTerpilih?->tahun }} (Semester {{ $tahunTerpilih?->semester }})
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Selamat Datang di Portal Jadwal</h2>
                <p class="text-teal-100 text-xs sm:text-sm mt-2 leading-relaxed">
                    Siswa dan Orang Tua dapat dengan mudah mengecek jadwal pelajaran kelas, jadwal ujian (UTS/UAS), dan agenda kegiatan sekolah secara langsung dan akurat.
                </p>

                <!-- Class Selector Form -->
                <form action="{{ route('portal.siswa') }}" method="GET" class="mt-6 flex flex-wrap items-center gap-3">
                    <div class="w-full sm:w-72">
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-teal-200 mb-1">Pilih Kelas Siswa:</label>
                        <select name="id_kelas" onchange="this.form.submit()" class="w-full px-4 py-2.5 rounded-xl bg-white text-slate-900 font-bold text-sm shadow-md border-0 focus:outline-none focus:ring-2 focus:ring-teal-400">
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id_kelas }}" {{ $idKelas == $k->id_kelas ? 'selected' : '' }}>
                                    Kelas {{ $k->nama_kelas }} ({{ $k->jurusan }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if($kelasTerpilih)
                        <div class="self-end">
                            <a href="{{ route('portal.siswa.cetak', ['id_kelas' => $idKelas, 'id_tahun_ajaran' => $idTahunAjaran]) }}" target="_blank" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-teal-500/30 hover:bg-teal-500/50 text-white font-semibold text-xs border border-teal-400/30 transition">
                                <i class="fa-solid fa-print mr-2"></i> Cetak Jadwal Kelas
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        @if($kelasTerpilih)
            <!-- Today's Schedule Card (Sorotan Jadwal Hari Ini) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <div class="flex items-center space-x-2">
                        <span class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-sun"></i>
                        </span>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Jadwal Belajar Hari Ini — {{ $hariIni }}</h3>
                            <p class="text-xs text-slate-400">Kelas {{ $kelasTerpilih->nama_kelas }}</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-teal-50 text-teal-700 border border-teal-200">
                        {{ $jadwalHariIni->count() }} Mata Pelajaran
                    </span>
                </div>

                @if($jadwalHariIni->isEmpty())
                    <div class="py-6 text-center text-slate-400">
                        <i class="fa-solid fa-couch text-2xl mb-1 text-slate-300"></i>
                        <p class="text-xs font-medium">Tidak ada kegiatan belajar mengajar pada hari {{ $hariIni }}.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                        @foreach($jadwalHariIni as $jh)
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 hover:border-teal-300 transition">
                                <div class="flex items-center justify-between text-xs mb-1">
                                    <span class="font-bold text-teal-700">Jam Ke-{{ $jh->jamPelajaran?->jam_ke }}</span>
                                    <span class="font-mono text-slate-400 text-[11px]">{{ $jh->jamPelajaran?->format_jam }}</span>
                                </div>
                                <div class="font-bold text-slate-900 text-sm mt-1">{{ $jh->mataPelajaran?->nama_mapel }}</div>
                                <div class="text-xs text-slate-600 mt-1 flex items-center">
                                    <i class="fa-solid fa-chalkboard-user text-slate-400 mr-1.5 text-[11px]"></i>
                                    <span class="truncate">{{ $jh->guru?->nama }}</span>
                                </div>
                                <div class="mt-2 pt-2 border-t border-slate-200/60 text-[11px] font-semibold text-slate-500 flex items-center justify-between">
                                    <span>Ruang: {{ $jh->ruang?->kode_ruang }}</span>
                                    <span class="text-emerald-600"><i class="fa-solid fa-circle-check text-[10px] mr-1"></i> Aktif</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Full Weekly Schedule (Matriks Mingguan Lengkap) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">Jadwal Pelajaran Mingguan Lengkap</h3>
                        <p class="text-xs text-slate-500">Susunan mata pelajaran Kelas {{ $kelasTerpilih->nama_kelas }} dari hari Senin sampai Sabtu.</p>
                    </div>
                    <span class="text-xs font-semibold text-slate-400">
                        Total: {{ $jadwalList->count() }} Jam Pelajaran / Minggu
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse min-w-[750px]">
                        <thead>
                            <tr>
                                <th class="p-3 bg-slate-900 text-white rounded-tl-xl text-xs font-bold uppercase tracking-wider text-center w-28">
                                    Jam / Waktu
                                </th>
                                @foreach($hariList as $h)
                                    <th class="p-3 {{ $h == $hariIni ? 'bg-teal-700' : 'bg-slate-800' }} text-white text-xs font-bold uppercase tracking-wider text-center border-l border-slate-700">
                                        {{ $h }}
                                        @if($h == $hariIni)
                                            <span class="block text-[9px] font-normal text-teal-200">(Hari Ini)</span>
                                        @endif
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 border-b border-slate-200">
                            @foreach($jamList as $jam)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="p-2.5 bg-slate-50 text-center border-r border-slate-200">
                                        <div class="font-extrabold text-slate-800 text-xs">Jam Ke-{{ $jam->jam_ke }}</div>
                                        <div class="font-mono text-[11px] text-slate-400 mt-0.5">{{ $jam->format_jam }}</div>
                                    </td>

                                    @foreach($hariList as $hari)
                                        @php
                                            $slot = $matriksJadwal[$hari][$jam->id_jam] ?? null;
                                        @endphp
                                        <td class="p-2 border-r border-slate-200 align-top h-20 min-w-[140px] {{ $hari == $hariIni ? 'bg-teal-50/20' : '' }}">
                                            @if($slot)
                                                <div class="h-full bg-white border border-teal-200 rounded-xl p-2 shadow-2xs flex flex-col justify-between">
                                                    <div>
                                                        <div class="font-bold text-slate-900 text-xs leading-tight">
                                                            {{ $slot->mataPelajaran?->nama_mapel }}
                                                        </div>
                                                        <div class="text-[11px] text-slate-500 mt-1 truncate">
                                                            {{ $slot->guru?->nama }}
                                                        </div>
                                                    </div>
                                                    <div class="mt-1 text-[10px] font-bold text-teal-700">
                                                        Ruang {{ $slot->ruang?->kode_ruang }}
                                                    </div>
                                                </div>
                                            @else
                                                <div class="h-full rounded-xl flex items-center justify-center text-slate-300 text-xs">
                                                    -
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Two Columns: Jadwal Ujian & Agenda Sekolah -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Jadwal Ujian -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                    <div class="flex items-center space-x-2 pb-3 border-b border-slate-100 mb-4">
                        <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-file-pen"></i>
                        </span>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Jadwal Ujian Mendatang (UTS / UAS)</h3>
                            <p class="text-xs text-slate-400">Kelas {{ $kelasTerpilih->nama_kelas }}</p>
                        </div>
                    </div>

                    @if($ujianList->isEmpty())
                        <div class="py-8 text-center text-slate-400">
                            <i class="fa-solid fa-circle-check text-2xl text-emerald-500 mb-1"></i>
                            <p class="text-xs font-medium">Belum ada agenda ujian terjadwal untuk kelas ini.</p>
                        </div>
                    @else
                        <div class="space-y-2.5">
                            @foreach($ujianList as $u)
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between text-xs">
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 py-0.5 rounded font-extrabold text-[10px] {{ $u->jenis_ujian == 'UTS' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800' }}">
                                                {{ $u->jenis_ujian }}
                                            </span>
                                            <span class="font-bold text-slate-900">{{ $u->mataPelajaran?->nama_mapel }}</span>
                                        </div>
                                        <div class="text-[11px] text-slate-500 mt-1">
                                            <i class="fa-regular fa-clock mr-1"></i> {{ $u->format_jam }} | Ruang: <strong>{{ $u->ruang?->kode_ruang }}</strong>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-bold text-slate-800">{{ $u->tanggal->format('d M Y') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Agenda Kegiatan Sekolah -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                    <div class="flex items-center space-x-2 pb-3 border-b border-slate-100 mb-4">
                        <span class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-flag"></i>
                        </span>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Pengumuman & Agenda Sekolah</h3>
                            <p class="text-xs text-slate-400">Informasi agenda kegiatan terkini</p>
                        </div>
                    </div>

                    @if($kegiatanList->isEmpty())
                        <div class="py-8 text-center text-slate-400">
                            <i class="fa-regular fa-calendar-check text-2xl text-slate-300 mb-1"></i>
                            <p class="text-xs font-medium">Tidak ada kegiatan sekolah dalam waktu dekat.</p>
                        </div>
                    @else
                        <div class="space-y-2.5">
                            @foreach($kegiatanList as $k)
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-bold text-slate-900">{{ $k->nama_kegiatan }}</span>
                                        <span class="text-teal-700 font-semibold">{{ $k->tanggal_mulai->format('d M') }} - {{ $k->tanggal_selesai->format('d M Y') }}</span>
                                    </div>
                                    <div class="text-slate-500 text-[11px] flex items-center justify-between">
                                        <span><i class="fa-regular fa-clock mr-1"></i> {{ $k->format_jam }}</span>
                                        <span>Lokasi: {{ $k->ruang?->nama_ruang ?? 'Area Sekolah' }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-400 mt-8">
        Sistem Informasi Jadwal Sekolah &copy; {{ date('Y') }} SMA / SMK Negeri 1 Akademik
    </footer>
</body>
</html>

