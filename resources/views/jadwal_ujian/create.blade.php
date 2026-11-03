@extends('layouts.app')

@section('title', 'Tambah Jadwal Ujian')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Input Jadwal Ujian</h2>
            <p class="text-sm text-slate-500 mt-1">Sistem otomatis mendeteksi bentrok ruang, kelas, dan pengawas pada rentang jam ujian.</p>
        </div>
        <a href="{{ route('jadwal-ujian.index') }}" class="inline-flex items-center px-3 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali
        </a>
    </div>

    <!-- Info Box Deteksi Bentrok Ujian -->
    <div class="p-4 rounded-xl bg-amber-50/80 border border-amber-200 text-amber-900 text-xs flex items-start space-x-3">
        <i class="fa-solid fa-shield-halved text-amber-600 text-base mt-0.5"></i>
        <div>
            <span class="font-bold">Deteksi Bentrok Otomatis Ujian & Pengawas:</span>
            <ul class="list-disc list-inside mt-1 space-y-0.5 text-amber-800">
                <li>Ruang tidak boleh dipakai bersamaan untuk dua ujian atau kegiatan lain pada tanggal & jam yang overlap.</li>
                <li>Kelas tidak boleh memiliki dua ujian di jam yang bersamaan.</li>
                <li>Guru pengawas tidak dapat ditugaskan di dua ruang ujian pada jam yang sama.</li>
            </ul>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <form action="{{ route('jadwal-ujian.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Tahun Ajaran & Jenis Ujian -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tahun Ajaran <span class="text-red-500">*</span></label>
                    <select name="id_tahun_ajaran" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach($tahunList as $ta)
                            <option value="{{ $ta->id_tahun_ajaran }}" {{ old('id_tahun_ajaran') == $ta->id_tahun_ajaran ? 'selected' : '' }}>
                                {{ $ta->tahun }} ({{ $ta->semester }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jenis Ujian <span class="text-red-500">*</span></label>
                    <select name="jenis_ujian" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach(['UTS', 'UAS', 'Ulangan Harian', 'Try Out'] as $ju)
                            <option value="{{ $ju }}" {{ old('jenis_ujian') == $ju ? 'selected' : '' }}>{{ $ju }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Kelas & Mapel -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kelas <span class="text-red-500">*</span></label>
                    <select name="id_kelas" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <select name="id_mapel" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($mapelList as $m)
                            <option value="{{ $m->id_mapel }}" {{ old('id_mapel') == $m->id_mapel ? 'selected' : '' }}>
                                {{ $m->kode_mapel }} - {{ $m->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Ruang Ujian -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ruangan Ujian <span class="text-red-500">*</span></label>
                <select name="id_ruang" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">-- Pilih Ruangan --</option>
                    @foreach($ruangList as $r)
                        <option value="{{ $r->id_ruang }}" {{ old('id_ruang') == $r->id_ruang ? 'selected' : '' }}>
                            {{ $r->kode_ruang }} - {{ $r->nama_ruang }} (Kapasitas: {{ $r->kapasitas }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tanggal & Jam Waktu -->
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Ujian <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jam Mulai <span class="text-red-500">*</span></label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai', '08:00') }}" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jam Selesai <span class="text-red-500">*</span></label>
                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai', '09:30') }}" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <!-- Pengawas Ujian -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tugaskan Guru Pengawas Ujian (jadwal_pengawas)</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2 max-h-48 overflow-y-auto p-3 border border-slate-200 rounded-xl bg-slate-50">
                    @foreach($guruList as $g)
                        <label class="flex items-center space-x-2 text-xs text-slate-700 p-1.5 rounded-lg hover:bg-white transition cursor-pointer">
                            <input type="checkbox" name="pengawas_ids[]" value="{{ $g->id_guru }}" {{ in_array($g->id_guru, old('pengawas_ids', [])) ? 'checked' : '' }} class="rounded text-teal-600 focus:ring-teal-500">
                            <span class="truncate">{{ $g->nama }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('jadwal-ujian.index') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold bg-teal-600 hover:bg-teal-700 text-white rounded-xl shadow-md shadow-teal-600/20 transition">
                    <i class="fa-solid fa-save mr-1.5"></i> Simpan & Cek Bentrok Ujian
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

