@extends('layouts.app')

@section('title', 'Tambah Jadwal Pelajaran')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Input Jadwal Pelajaran</h2>
            <p class="text-sm text-slate-500 mt-1">Sistem otomatis mendeteksi bentrok jadwal guru, ruang, dan kelas saat Anda menyimpan.</p>
        </div>
        <a href="{{ route('jadwal-pelajaran.index', ['id_tahun_ajaran' => $prefill['id_tahun_ajaran'], 'id_kelas' => $prefill['id_kelas']]) }}" class="inline-flex items-center px-3 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Matriks
        </a>
    </div>

    <!-- Info Box Aturan Integritas -->
    <div class="p-4 rounded-xl bg-teal-50/70 border border-teal-200 text-teal-900 text-xs flex items-start space-x-3">
        <i class="fa-solid fa-shield-halved text-teal-600 text-base mt-0.5"></i>
        <div>
            <span class="font-bold">Deteksi Bentrok Otomatis (Automatic Conflict Detection):</span>
            <ul class="list-disc list-inside mt-1 space-y-0.5 text-teal-800">
                <li>Guru A <strong>tidak boleh</strong> mendapatkan dua kelas pada jam & hari yang sama.</li>
                <li>Ruang tidak boleh dipakai oleh dua kelas berbeda pada jam & hari yang sama.</li>
                <li>Kelas tidak boleh menerima dua pelajaran berbeda pada jam yang sama.</li>
            </ul>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <form action="{{ route('jadwal-pelajaran.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Tahun Ajaran & Kelas -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tahun Ajaran <span class="text-red-500">*</span></label>
                    <select name="id_tahun_ajaran" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach($tahunList as $ta)
                            <option value="{{ $ta->id_tahun_ajaran }}" {{ old('id_tahun_ajaran', $prefill['id_tahun_ajaran']) == $ta->id_tahun_ajaran ? 'selected' : '' }}>
                                {{ $ta->tahun }} ({{ $ta->semester }}) {{ $ta->status_aktif ? '★ Aktif' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kelas <span class="text-red-500">*</span></label>
                    <select name="id_kelas" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id_kelas }}" {{ old('id_kelas', $prefill['id_kelas']) == $k->id_kelas ? 'selected' : '' }}>
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
                            <option value="{{ $h }}" {{ old('hari', $prefill['hari']) == $h ? 'selected' : '' }}>
                                {{ $h }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jam Pelajaran <span class="text-red-500">*</span></label>
                    <select name="id_jam" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach($jamList as $j)
                            <option value="{{ $j->id_jam }}" {{ old('id_jam', $prefill['id_jam']) == $j->id_jam ? 'selected' : '' }}>
                                Jam Ke-{{ $j->jam_ke }} ({{ $j->format_jam }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Mata Pelajaran -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Mata Pelajaran <span class="text-red-500">*</span></label>
                <select name="id_mapel" id="select_mapel" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach($mapelList as $m)
                        <option value="{{ $m->id_mapel }}" {{ old('id_mapel') == $m->id_mapel ? 'selected' : '' }}>
                            {{ $m->kode_mapel }} - {{ $m->nama_mapel }} ({{ $m->jam_per_minggu }} jam/minggu)
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Guru Pengajar -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Guru Pengajar <span class="text-red-500">*</span></label>
                <select name="id_guru" id="select_guru" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">-- Pilih Guru Pengajar --</option>
                    @foreach($guruList as $g)
                        <option value="{{ $g->id_guru }}" {{ old('id_guru') == $g->id_guru ? 'selected' : '' }}>
                            {{ $g->nama }} (NIP: {{ $g->nip }})
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400 mt-1">Sistem otomatis menolak jika guru sudah memiliki jadwal di kelas lain pada jam yang sama.</p>
            </div>

            <!-- Ruang Kelas / Lab -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ruangan Kelas / Lab <span class="text-red-500">*</span></label>
                <select name="id_ruang" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">-- Pilih Ruangan --</option>
                    @foreach($ruangList as $r)
                        <option value="{{ $r->id_ruang }}" {{ old('id_ruang') == $r->id_ruang ? 'selected' : '' }}>
                            {{ $r->kode_ruang }} - {{ $r->nama_ruang }} ({{ $r->jenis }}, Kapasitas: {{ $r->kapasitas }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('jadwal-pelajaran.index', ['id_tahun_ajaran' => $prefill['id_tahun_ajaran'], 'id_kelas' => $prefill['id_kelas']]) }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold bg-teal-600 hover:bg-teal-700 text-white rounded-xl shadow-md shadow-teal-600/20 transition">
                    <i class="fa-solid fa-save mr-1.5"></i> Simpan & Cek Bentrok
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

