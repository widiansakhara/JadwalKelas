@extends('layouts.app')

@section('title', 'Tambah Tahun Ajaran')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Tambah Tahun Ajaran</h2>
            <p class="text-sm text-slate-500 mt-1">Daftarkan periode kalender akademik baru.</p>
        </div>
        <a href="{{ route('tahun-ajaran.index') }}" class="inline-flex items-center px-3 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <form action="{{ route('tahun-ajaran.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tahun Ajaran <span class="text-red-500">*</span></label>
                <input type="text" name="tahun" value="{{ old('tahun', date('Y') . '/' . (date('Y') + 1)) }}" required placeholder="Contoh: 2024/2025" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                <p class="text-[11px] text-slate-400 mt-1">Format: YYYY/YYYY (contoh: 2024/2025).</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Semester <span class="text-red-500">*</span></label>
                <select name="semester" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="Ganjil" {{ old('semester') == 'Ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
                    <option value="Genap" {{ old('semester') == 'Genap' ? 'selected' : '' }}>Semester Genap</option>
                </select>
            </div>

            <div class="pt-2">
                <label class="flex items-center space-x-2.5 cursor-pointer">
                    <input type="checkbox" name="status_aktif" value="1" {{ old('status_aktif') ? 'checked' : '' }} class="rounded text-teal-600 focus:ring-teal-500">
                    <span class="text-xs font-semibold text-slate-700">Tetapkan langsung sebagai Tahun Ajaran Aktif</span>
                </label>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('tahun-ajaran.index') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold bg-teal-600 hover:bg-teal-700 text-white rounded-xl shadow-md shadow-teal-600/20 transition">
                    <i class="fa-solid fa-save mr-1.5"></i> Simpan Tahun Ajaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

