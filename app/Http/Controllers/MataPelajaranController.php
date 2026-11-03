<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\MataPelajaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MataPelajaranController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $query = MataPelajaran::with('guru');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_mapel', 'like', "%{$search}%")
                  ->orWhere('kode_mapel', 'like', "%{$search}%");
            });
        }

        $mapelList = $query->orderBy('kode_mapel')->paginate(10)->withQueryString();

        return view('mata_pelajaran.index', compact('mapelList', 'search'));
    }

    public function create(): View
    {
        $guruList = Guru::orderBy('nama')->get();
        return view('mata_pelajaran.create', compact('guruList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_mapel'     => 'required|string|max:10|unique:mata_pelajaran,kode_mapel',
            'nama_mapel'     => 'required|string|max:100',
            'jam_per_minggu' => 'required|integer|min:1',
            'guru_ids'       => 'nullable|array',
            'guru_ids.*'     => 'exists:guru,id_guru',
        ]);

        $mapel = MataPelajaran::create([
            'kode_mapel'     => $validated['kode_mapel'],
            'nama_mapel'     => $validated['nama_mapel'],
            'jam_per_minggu' => $validated['jam_per_minggu'],
        ]);

        if (!empty($validated['guru_ids'])) {
            $mapel->guru()->sync($validated['guru_ids']);
        }

        return redirect()->route('mata-pelajaran.index')->with('success', 'Master Mata Pelajaran berhasil ditambahkan.');
    }

    public function edit(MataPelajaran $mataPelajaran): View
    {
        $mataPelajaran->load('guru');
        $guruList = Guru::orderBy('nama')->get();
        return view('mata_pelajaran.edit', compact('mataPelajaran', 'guruList'));
    }

    public function update(Request $request, MataPelajaran $mataPelajaran): RedirectResponse
    {
        $validated = $request->validate([
            'kode_mapel'     => 'required|string|max:10|unique:mata_pelajaran,kode_mapel,' . $mataPelajaran->id_mapel . ',id_mapel',
            'nama_mapel'     => 'required|string|max:100',
            'jam_per_minggu' => 'required|integer|min:1',
            'guru_ids'       => 'nullable|array',
            'guru_ids.*'     => 'exists:guru,id_guru',
        ]);

        $mataPelajaran->update([
            'kode_mapel'     => $validated['kode_mapel'],
            'nama_mapel'     => $validated['nama_mapel'],
            'jam_per_minggu' => $validated['jam_per_minggu'],
        ]);

        $mataPelajaran->guru()->sync($validated['guru_ids'] ?? []);

        return redirect()->route('mata-pelajaran.index')->with('success', 'Master Mata Pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mataPelajaran): RedirectResponse
    {
        // Cek pemakaian di Jadwal Pelajaran atau Jadwal Ujian
        $terpakaiJadwal = $mataPelajaran->jadwalPelajaran()->exists();
        $terpakaiUjian = $mataPelajaran->jadwalUjian()->exists();

        if ($terpakaiJadwal || $terpakaiUjian) {
            $alasan = [];
            if ($terpakaiJadwal) $alasan[] = 'Jadwal Pelajaran';
            if ($terpakaiUjian) $alasan[] = 'Jadwal Ujian';

            return redirect()->route('mata-pelajaran.index')->with('error', 'Mata Pelajaran tidak dapat dihapus karena masih terdaftar pada: ' . implode(', ', $alasan) . '.');
        }

        $mataPelajaran->guru()->detach();
        $mataPelajaran->delete();

        return redirect()->route('mata-pelajaran.index')->with('success', 'Master Mata Pelajaran berhasil dihapus.');
    }
}

