@extends('layouts.app')

@section('title', 'Master Tahun Ajaran')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Master Tahun Ajaran</h2>
            <p class="text-sm text-slate-500 mt-1">Pengaturan periode akademik, semester ganjil/genap, dan penetapan tahun ajaran yang sedang aktif.</p>
        </div>
        <div>
            <a href="{{ route('tahun-ajaran.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-sm shadow-md shadow-teal-600/20 transition">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Tahun Ajaran
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3">No</th>
                        <th class="px-5 py-3">Tahun Ajaran</th>
                        <th class="px-5 py-3">Semester</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($tahunList as $index => $t)
                        <tr class="hover:bg-slate-50/80 transition {{ $t->status_aktif ? 'bg-teal-50/40' : '' }}">
                            <td class="px-5 py-3.5 text-xs text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-5 py-3.5 font-bold text-slate-900">{{ $t->tahun }}</td>
                            <td class="px-5 py-3.5 text-xs font-semibold text-slate-700">Semester {{ $t->semester }}</td>
                            <td class="px-5 py-3.5">
                                @if($t->status_aktif)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-teal-100 text-teal-800 border border-teal-200">
                                        <i class="fa-solid fa-circle-check mr-1.5 text-teal-600"></i> Aktif Berjalan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right space-x-1">
                                @if(!$t->status_aktif)
                                    <form action="{{ route('tahun-ajaran.set-aktif', $t) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition" title="Aktifkan Tahun Ajaran Ini">
                                            <i class="fa-solid fa-power-off mr-1"></i> Aktifkan
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('tahun-ajaran.edit', $t) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                </a>
                                <form action="{{ route('tahun-ajaran.destroy', $t) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tahun ajaran ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 transition">
                                        <i class="fa-solid fa-trash mr-1"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400 text-sm">
                                Belum ada data tahun ajaran terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

