@extends('layouts.app')

@section('title', 'Edit Jadwal Pelajaran')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Edit Jadwal Pelajaran</h2>
            <p class="text-sm text-slate-500 mt-1">Ubah jadwal: {{ $jadwalPelajaran->kelas?->nama_kelas }} - {{ $jadwalPelajaran->mataPelajaran?->nama_mapel }}.</p>
        </div>
        <a href="{{ route('jadwal-pelajaran.index', ['id_tahun_ajaran' => $jadwalPelajaran->id_tahun_ajaran, 'id_kelas' => $jadwalPelajaran->id_kelas]) }}" class="inline-flex items-center px-3 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Matriks
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <form action="{{ route('jadwal-pelajaran.update', $jadwalPelajaran) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Tahun Ajaran & Kelas -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tahun Ajaran <span class="text-red-500">*</span></label>
                    <select name="id_tahun_ajaran" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach($tahunList as $ta)
                            <option value="{{ $ta->id_tahun_ajaran }}" {{ old('id_tahun_ajaran', $jadwalPelajaran->id_tahun_ajaran) == $ta->id_tahun_ajaran ? 'selected' : '' }}>
                                {{ $ta->tahun }} ({{ $ta->semester }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kelas <span class="text-red-500">*</span></label>
                    <select name="id_kelas" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id_kelas }}" {{ old('id_kelas', $jadwalPelajaran->id_kelas) == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->nama_kelas }} ({{ $k->jurusan }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Hari & Jam Pelajaran -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Hari Pelajaran <span class="text-red-500">*</span></label>
                    <select name="hari" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach($hariList as $h)
                            <option value="{{ $h }}" {{ old('hari', $jadwalPelajaran->hari) == $h ? 'selected' : '' }}>
                                {{ $h }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jam Pelajaran <span class="text-red-500">*</span></label>
                    <select name="id_jam" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach($jamList as $j)
                            <option value="{{ $j->id_jam }}" {{ old('id_jam', $jadwalPelajaran->id_jam) == $j->id_jam ? 'selected' : '' }}>
                                Jam Ke-{{ $j->jam_ke }} ({{ $j->format_jam }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Mata Pelajaran -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Mata Pelajaran <span class="text-red-500">*</span></label>
                <select name="id_mapel" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @foreach($mapelList as $m)
                        <option value="{{ $m->id_mapel }}" {{ old('id_mapel', $jadwalPelajaran->id_mapel) == $m->id_mapel ? 'selected' : '' }}>
                            {{ $m->kode_mapel }} - {{ $m->nama_mapel }} ({{ $m->jam_per_minggu }} jam/minggu)
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Guru Pengajar -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Guru Pengajar <span class="text-red-500">*</span></label>
                <select name="id_guru" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @foreach($guruList as $g)
                        <option value="{{ $g->id_guru }}" {{ old('id_guru', $jadwalPelajaran->id_guru) == $g->id_guru ? 'selected' : '' }}>
                            {{ $g->nama }} (NIP: {{ $g->nip }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Ruang Kelas / Lab -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ruangan <span class="text-red-500">*</span></label>
                <select name="id_ruang" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @foreach($ruangList as $r)
                        <option value="{{ $r->id_ruang }}" {{ old('id_ruang', $jadwalPelajaran->id_ruang) == $r->id_ruang ? 'selected' : '' }}>
                            {{ $r->kode_ruang }} - {{ $r->nama_ruang }} ({{ $r->jenis }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('jadwal-pelajaran.index', ['id_tahun_ajaran' => $jadwalPelajaran->id_tahun_ajaran, 'id_kelas' => $jadwalPelajaran->id_kelas]) }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold bg-teal-600 hover:bg-teal-700 text-white rounded-xl shadow-md shadow-teal-600/20 transition">
                    <i class="fa-solid fa-save mr-1.5"></i> Perbarui Jadwal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

