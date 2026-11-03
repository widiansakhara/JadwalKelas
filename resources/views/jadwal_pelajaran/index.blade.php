@extends('layouts.app')

@section('title', 'Jadwal Pelajaran Kelas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Jadwal Pelajaran Kelas</h2>
            <p class="text-sm text-slate-500 mt-1">Susun dan pantau jadwal pelajaran mingguan per kelas dengan deteksi bentrok otomatis.</p>
        </div>
        <div class="flex items-center space-x-2">
            @if($kelasTerpilih)
                <a href="{{ route('jadwal-pelajaran.cetak', ['id_tahun_ajaran' => $idTahunAjaran, 'id_kelas' => $idKelas]) }}" target="_blank" class="inline-flex items-center px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-semibold text-xs transition shadow-sm">
                    <i class="fa-solid fa-print mr-2 text-slate-500"></i> Cetak Jadwal
                </a>
            @endif
            <a href="{{ route('jadwal-pelajaran.create', ['id_tahun_ajaran' => $idTahunAjaran, 'id_kelas' => $idKelas]) }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm shadow-md shadow-teal-600/20 transition">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Jadwal
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row gap-3 items-center justify-between">
        <form action="{{ route('jadwal-pelajaran.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5 w-full md:w-auto">
            <!-- Tahun Ajaran -->
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5">Tahun Ajaran</label>
                <select name="id_tahun_ajaran" onchange="this.form.submit()" class="text-xs py-2 px-3 rounded-xl border border-slate-200 font-medium focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @foreach($tahunList as $ta)
                        <option value="{{ $ta->id_tahun_ajaran }}" {{ $idTahunAjaran == $ta->id_tahun_ajaran ? 'selected' : '' }}>
                            {{ $ta->tahun }} ({{ $ta->semester }}) {{ $ta->status_aktif ? '★' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Kelas -->
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5">Pilih Kelas</label>
                <select name="id_kelas" onchange="this.form.submit()" class="text-xs py-2 px-3 rounded-xl border border-slate-200 font-semibold text-teal-800 bg-teal-50/50 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id_kelas }}" {{ $idKelas == $k->id_kelas ? 'selected' : '' }}>
                            {{ $k->nama_kelas }} ({{ $k->jurusan }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- View Mode Switch -->
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5">Tampilan</label>
                <div class="flex items-center bg-slate-100 p-0.5 rounded-xl text-xs font-semibold">
                    <button type="submit" name="view" value="grid" class="px-3 py-1.5 rounded-lg transition {{ $viewMode != 'table' ? 'bg-white text-teal-800 shadow-sm' : 'text-slate-600' }}">
                        <i class="fa-solid fa-table-cells mr-1"></i> Matriks
                    </button>
                    <button type="submit" name="view" value="table" class="px-3 py-1.5 rounded-lg transition {{ $viewMode == 'table' ? 'bg-white text-teal-800 shadow-sm' : 'text-slate-600' }}">
                        <i class="fa-solid fa-list mr-1"></i> Tabel
                    </button>
                </div>
            </div>
        </form>

        <div class="text-xs text-slate-500 font-medium">
            Kelas: <span class="font-bold text-slate-800">{{ $kelasTerpilih?->nama_kelas ?? 'Pilih Kelas' }}</span> |
            Total Sesi: <span class="font-bold text-teal-700">{{ $jadwalList->count() }} jam pelajaran</span>
        </div>
    </div>

    @if(!$kelasTerpilih)
        <div class="p-8 text-center bg-white rounded-2xl border border-slate-200 text-slate-400">
            <i class="fa-solid fa-school text-3xl mb-2 text-slate-300"></i>
            <p class="text-sm font-semibold">Belum ada kelas yang dipilih atau terdaftar di sistem.</p>
        </div>
    @elseif($viewMode == 'table')
        <!-- TABLE VIEW -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3">Hari</th>
                            <th class="px-5 py-3">Jam Ke</th>
                            <th class="px-5 py-3">Waktu</th>
                            <th class="px-5 py-3">Mata Pelajaran</th>
                            <th class="px-5 py-3">Guru Pengajar</th>
                            <th class="px-5 py-3">Ruang</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($jadwalList->sortBy(['hari', 'jamPelajaran.jam_ke']) as $j)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-3.5 font-bold text-slate-800">
                                    <span class="px-2.5 py-1 rounded-md text-xs {{ $j->hari == 'Senin' ? 'bg-red-50 text-red-700' : ($j->hari == 'Jumat' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-700') }}">
                                        {{ $j->hari }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 font-semibold text-teal-700">Jam Ke-{{ $j->jamPelajaran?->jam_ke }}</td>
                                <td class="px-5 py-3.5 font-mono text-xs text-slate-500">{{ $j->jamPelajaran?->format_jam }}</td>
                                <td class="px-5 py-3.5 font-bold text-slate-900">{{ $j->mataPelajaran?->nama_mapel }}</td>
                                <td class="px-5 py-3.5 font-medium text-slate-700">{{ $j->guru?->nama }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2 py-0.5 rounded-md bg-teal-50 text-teal-800 border border-teal-200 text-xs font-semibold">
                                        {{ $j->ruang?->kode_ruang }} ({{ $j->ruang?->nama_ruang }})
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right space-x-1">
                                    <a href="{{ route('jadwal-pelajaran.edit', $j) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                                        <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('jadwal-pelajaran.destroy', $j) }}" method="POST" class="inline" onsubmit="return confirm('Hapus jadwal ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 transition">
                                            <i class="fa-solid fa-trash mr-1"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-slate-400 text-sm">
                                    Belum ada jadwal untuk kelas ini. Klik tombol "Tambah Jadwal" di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- INTERACTIVE GRID / MATRIX VIEW (Hari vs Jam Pelajaran) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-4">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse min-w-[700px]">
                    <thead>
                        <tr>
                            <th class="p-3 bg-slate-900 text-white rounded-tl-xl text-xs font-bold uppercase tracking-wider text-center w-28">
                                Jam / Waktu
                            </th>
                            @foreach($hariList as $h)
                                <th class="p-3 bg-slate-800 text-white text-xs font-bold uppercase tracking-wider text-center border-l border-slate-700">
                                    {{ $h }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 border-b border-slate-200">
                        @foreach($jamList as $jam)
                            <tr class="hover:bg-slate-50/50">
                                <!-- Col Waktu -->
                                <td class="p-2.5 bg-slate-50 text-center border-r border-slate-200">
                                    <div class="font-extrabold text-slate-800 text-xs">Jam Ke-{{ $jam->jam_ke }}</div>
                                    <div class="font-mono text-[11px] text-slate-400 mt-0.5">{{ $jam->format_jam }}</div>
                                </td>

                                <!-- Col Hari -->
                                @foreach($hariList as $hari)
                                    @php
                                        $slot = $matriksJadwal[$hari][$jam->id_jam] ?? null;
                                    @endphp
                                    <td class="p-2 border-r border-slate-200 align-top h-24 min-w-[150px]">
                                        @if($slot)
                                            <!-- Ada Jadwal -->
                                            <div class="group relative h-full bg-gradient-to-br from-teal-50 to-emerald-50/60 border border-teal-200 rounded-xl p-2.5 flex flex-col justify-between shadow-xs hover:shadow-md transition">
                                                <div>
                                                    <div class="font-bold text-slate-900 text-xs leading-tight mb-1">
                                                        {{ $slot->mataPelajaran?->nama_mapel }}
                                                    </div>
                                                    <div class="text-[11px] text-slate-600 flex items-center font-medium">
                                                        <i class="fa-solid fa-chalkboard-user text-teal-600 mr-1 text-[10px]"></i>
                                                        <span class="truncate">{{ $slot->guru?->nama }}</span>
                                                    </div>
                                                </div>

                                                <div class="mt-2 pt-1.5 border-t border-teal-200/60 flex items-center justify-between">
                                                    <span class="px-1.5 py-0.5 bg-teal-100 text-teal-800 rounded font-semibold text-[10px]">
                                                        {{ $slot->ruang?->kode_ruang }}
                                                    </span>
                                                    <div class="opacity-0 group-hover:opacity-100 transition space-x-1">
                                                        <a href="{{ route('jadwal-pelajaran.edit', $slot) }}" class="text-slate-600 hover:text-teal-700 text-xs" title="Edit">
                                                            <i class="fa-solid fa-pen"></i>
                                                        </a>
                                                        <form action="{{ route('jadwal-pelajaran.destroy', $slot) }}" method="POST" class="inline" onsubmit="return confirm('Hapus jadwal slot ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-slate-400 hover:text-red-600 text-xs" title="Hapus">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <!-- Slot Kosong: Klik untuk tambah jadwal di slot ini -->
                                            <a href="{{ route('jadwal-pelajaran.create', ['id_tahun_ajaran' => $idTahunAjaran, 'id_kelas' => $idKelas, 'hari' => $hari, 'id_jam' => $jam->id_jam]) }}" class="h-full border-2 border-dashed border-slate-200 hover:border-teal-400 hover:bg-teal-50/30 rounded-xl flex items-center justify-center text-slate-300 hover:text-teal-600 transition group p-2 text-center" title="Klik untuk mengisi jadwal {{ $hari }} Jam Ke-{{ $jam->jam_ke }}">
                                                <span class="text-xs font-semibold flex items-center">
                                                    <i class="fa-solid fa-plus mr-1 text-[10px] group-hover:scale-110 transition"></i> Isi
                                                </span>
                                            </a>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs text-slate-400 pt-2 border-t border-slate-100">
                <span><i class="fa-solid fa-info-circle mr-1 text-teal-600"></i> Klik kotak bertanda <strong>+ Isi</strong> pada jadwal kosong untuk langsung mengisi sesi tersebut.</span>
                <span>Automatic Conflict Detection aktif menjaga jadwal dari bentrok guru, ruang, dan kelas.</span>
            </div>
        </div>
    @endif
</div>
@endsection

