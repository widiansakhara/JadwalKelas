@extends('layouts.app')

@section('title', 'Tambah Master Guru')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Tambah Master Guru</h2>
            <p class="text-sm text-slate-500 mt-1">Daftarkan tenaga pengajar baru ke dalam sistem.</p>
        </div>
        <a href="{{ route('guru.index') }}" class="inline-flex items-center px-3 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <form action="{{ route('guru.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NIP (Nomor Induk Pegawai) <span class="text-red-500">*</span></label>
                <input type="text" name="nip" value="{{ old('nip') }}" required placeholder="Contoh: 198001012005011001" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                <p class="text-[11px] text-slate-400 mt-1">Harus unik, maksimal 20 digit.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap & Gelar <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Drs. Budi Santoso, M.Pd" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="budi@sekolah.sch.id" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">No. Handphone / WhatsApp <span class="text-red-500">*</span></label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" required placeholder="081234567890" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Mata Pelajaran yang Diampu</label>
                <div class="grid grid-cols-2 gap-2 mt-2 max-h-48 overflow-y-auto p-3 border border-slate-200 rounded-xl bg-slate-50">
                    @forelse($mapelList as $m)
                        <label class="flex items-center space-x-2 text-xs text-slate-700 p-1.5 rounded-lg hover:bg-white transition cursor-pointer">
                            <input type="checkbox" name="mapel_ids[]" value="{{ $m->id_mapel }}" {{ in_array($m->id_mapel, old('mapel_ids', [])) ? 'checked' : '' }} class="rounded text-teal-600 focus:ring-teal-500">
                            <span>{{ $m->nama_mapel }} <span class="text-slate-400">({{ $m->kode_mapel }})</span></span>
                        </label>
                    @empty
                        <p class="text-xs text-slate-400 col-span-2">Belum ada mata pelajaran terdaftar.</p>
                    @endforelse
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('guru.index') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold bg-teal-600 hover:bg-teal-700 text-white rounded-xl shadow-md shadow-teal-600/20 transition">
                    <i class="fa-solid fa-save mr-1.5"></i> Simpan Data Guru
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

