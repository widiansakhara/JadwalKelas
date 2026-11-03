<?php

namespace App\Http\Controllers;

use App\Models\Ruang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RuangController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $jenis = $request->query('jenis');

        $query = Ruang::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_ruang', 'like', "%{$search}%")
                  ->orWhere('kode_ruang', 'like', "%{$search}%");
            });
        }

        if ($jenis) {
            $query->where('jenis', $jenis);
        }

        $ruangList = $query->orderBy('kode_ruang')->paginate(10)->withQueryString();
        $jenisList = Ruang::select('jenis')->distinct()->pluck('jenis');

        return view('ruang.index', compact('ruangList', 'jenisList', 'search', 'jenis'));
    }

    public function create(): View
    {
        return view('ruang.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_ruang' => 'required|string|max:10|unique:ruang,kode_ruang',
            'nama_ruang' => 'required|string|max:50',
            'jenis'      => 'required|string|max:30',
            'kapasitas'  => 'required|integer|min:1',
        ]);

        Ruang::create($validated);

        return redirect()->route('ruang.index')->with('success', 'Master Ruang berhasil ditambahkan.');
    }

    public function edit(Ruang $ruang): View
    {
        return view('ruang.edit', compact('ruang'));
    }

    public function update(Request $request, Ruang $ruang): RedirectResponse
    {
        $validated = $request->validate([
            'kode_ruang' => 'required|string|max:10|unique:ruang,kode_ruang,' . $ruang->id_ruang . ',id_ruang',
            'nama_ruang' => 'required|string|max:50',
            'jenis'      => 'required|string|max:30',
            'kapasitas'  => 'required|integer|min:1',
        ]);

        $ruang->update($validated);

        return redirect()->route('ruang.index')->with('success', 'Master Ruang berhasil diperbarui.');
    }

    public function destroy(Ruang $ruang): RedirectResponse
    {
        // Validasi sesuai Flowchart 04: Cek apakah ruang sedang dipakai di jadwal, ujian, atau kegiatan
        $terpakaiJadwal = $ruang->jadwalPelajaran()->exists();
        $terpakaiUjian = $ruang->jadwalUjian()->exists();
        $terpakaiKegiatan = $ruang->kegiatanSekolah()->exists();

        if ($terpakaiJadwal || $terpakaiUjian || $terpakaiKegiatan) {
            $alasan = [];
            if ($terpakaiJadwal) $alasan[] = 'Jadwal Pelajaran';
            if ($terpakaiUjian) $alasan[] = 'Jadwal Ujian';
            if ($terpakaiKegiatan) $alasan[] = 'Kegiatan Sekolah';

            return redirect()->route('ruang.index')->with('error', 'Ruang tidak dapat dihapus karena masih digunakan pada data: ' . implode(', ', $alasan) . '.');
        }

        $ruang->delete();

        return redirect()->route('ruang.index')->with('success', 'Master Ruang berhasil dihapus.');
    }
}

