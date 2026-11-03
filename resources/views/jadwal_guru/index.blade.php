@extends('layouts.app')

@section('title', 'Jadwal Mengajar Guru')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Jadwal Mengajar Guru</h2>
            <p class="text-sm text-slate-500 mt-1">Pantau distribusi beban mengajar dan jadwal mingguan per guru.</p>
        </div>
        @if($guruTerpilih)
            <div>
                <a href="{{ route('jadwal-guru.cetak', ['id_guru' => $idGuru, 'id_tahun_ajaran' => $idTahunAjaran]) }}" target="_blank" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-semibold text-sm shadow-sm transition">
                    <i class="fa-solid fa-print mr-2 text-slate-500"></i> Cetak Jadwal Guru
                </a>
            </div>
        @endif
    </div>

    <!-- Filter Bar -->
    <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row gap-3 items-center justify-between">
        <form action="{{ route('jadwal-guru.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5 w-full md:w-auto">
            <!-- Tahun Ajaran -->
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5">Tahun Ajaran</label>
                <select name="id_tahun_ajaran" onchange="this.form.submit()" class="text-xs py-2 px-3 rounded-xl border border-slate-200 font-medium focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @foreach($tahunList as $ta)
                        <option value="{{ $ta->id_tahun_ajaran }}" {{ $idTahunAjaran == $ta->id_tahun_ajaran ? 'selected' : '' }}>
                            {{ $ta->tahun }} ({{ $ta->semester }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Pilih Guru -->
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-0.5">Pilih Guru</label>
                <select name="id_guru" onchange="this.form.submit()" class="text-xs py-2 px-3 rounded-xl border border-slate-200 font-semibold text-teal-800 bg-teal-50/50 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @foreach($guruList as $g)
                        <option value="{{ $g->id_guru }}" {{ $idGuru == $g->id_guru ? 'selected' : '' }}>
                            {{ $g->nama }} (NIP: {{ $g->nip }})
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        @if($guruTerpilih)
            <div class="text-xs text-slate-600 font-medium flex items-center space-x-3">
                <span class="px-3 py-1 rounded-xl bg-blue-50 text-blue-800 font-bold border border-blue-200">
                    <i class="fa-solid fa-clock mr-1"></i> Beban: {{ $totalJamMengajar }} Jam / Minggu
                </span>
            </div>
        @endif
    </div>

    @if($guruTerpilih)
        <!-- Teacher Profile Card -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-teal-600 text-white flex items-center justify-center text-xl font-bold shadow-md shadow-teal-600/20">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900 leading-tight">{{ $guruTerpilih->nama }}</h3>
                    <p class="text-xs text-slate-500 font-mono mt-0.5">NIP: {{ $guruTerpilih->nip }} | <i class="fa-regular fa-envelope text-slate-400"></i> {{ $guruTerpilih->email }} | <i class="fa-solid fa-phone text-slate-400"></i> {{ $guruTerpilih->no_hp }}</p>
                    <div class="flex flex-wrap gap-1 mt-2">
                        @foreach($guruTerpilih->mataPelajaran as $m)
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded text-[11px] font-semibold">
                                {{ $m->nama_mapel }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="text-right">
                <span class="text-xs text-slate-400">Status Validasi Bentrok:</span>
                <div class="inline-flex items-center text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200 mt-1">
                    <i class="fa-solid fa-circle-check mr-1.5"></i> Terverifikasi Bebas Bentrok
                </div>
            </div>
        </div>

        <!-- Weekly Timetable Matrix for Guru -->
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
                                <td class="p-2.5 bg-slate-50 text-center border-r border-slate-200">
                                    <div class="font-extrabold text-slate-800 text-xs">Jam Ke-{{ $jam->jam_ke }}</div>
                                    <div class="font-mono text-[11px] text-slate-400 mt-0.5">{{ $jam->format_jam }}</div>
                                </td>

                                @foreach($hariList as $hari)
                                    @php
                                        $slot = $matriksJadwal[$hari][$jam->id_jam] ?? null;
                                    @endphp
                                    <td class="p-2 border-r border-slate-200 align-top h-24 min-w-[150px]">
                                        @if($slot)
                                            <div class="h-full bg-gradient-to-br from-blue-50 to-indigo-50/60 border border-blue-200 rounded-xl p-2.5 flex flex-col justify-between shadow-xs">
                                                <div>
                                                    <div class="font-bold text-slate-900 text-xs leading-tight mb-1">
                                                        {{ $slot->mataPelajaran?->nama_mapel }}
                                                    </div>
                                                    <div class="text-[11px] font-bold text-blue-700 flex items-center">
                                                        <i class="fa-solid fa-school text-[10px] mr-1"></i>
                                                        {{ $slot->kelas?->nama_kelas }}
                                                    </div>
                                                </div>

                                                <div class="mt-2 pt-1.5 border-t border-blue-200/60 flex items-center justify-between text-[10px]">
                                                    <span class="px-1.5 py-0.5 bg-blue-100 text-blue-800 rounded font-semibold">
                                                        Ruang {{ $slot->ruang?->kode_ruang }}
                                                    </span>
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
    @endif
</div>
@endsection

