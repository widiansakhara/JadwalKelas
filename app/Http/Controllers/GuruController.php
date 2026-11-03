<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\MataPelajaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuruController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $query = Guru::with('mataPelajaran');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $guruList = $query->orderBy('nama')->paginate(10)->withQueryString();

        return view('guru.index', compact('guruList', 'search'));
    }

    public function create(): View
    {
        $mapelList = MataPelajaran::orderBy('nama_mapel')->get();
        return view('guru.create', compact('mapelList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nip'       => 'required|string|max:20|unique:guru,nip',
            'nama'      => 'required|string|max:100',
            'email'     => 'required|email|max:100',
            'no_hp'     => 'required|string|max:20',
            'mapel_ids' => 'nullable|array',
            'mapel_ids.*' => 'exists:mata_pelajaran,id_mapel',
        ]);

        $guru = Guru::create([
            'nip'   => $validated['nip'],
            'nama'  => $validated['nama'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'],
        ]);

        if (!empty($validated['mapel_ids'])) {
            $guru->mataPelajaran()->sync($validated['mapel_ids']);
        }

        return redirect()->route('guru.index')->with('success', 'Master Guru berhasil ditambahkan.');
    }

    public function edit(Guru $guru): View
    {
        $guru->load('mataPelajaran');
        $mapelList = MataPelajaran::orderBy('nama_mapel')->get();
        return view('guru.edit', compact('guru', 'mapelList'));
    }

    public function update(Request $request, Guru $guru): RedirectResponse
    {
        $validated = $request->validate([
            'nip'       => 'required|string|max:20|unique:guru,nip,' . $guru->id_guru . ',id_guru',
            'nama'      => 'required|string|max:100',
            'email'     => 'required|email|max:100',
            'no_hp'     => 'required|string|max:20',
            'mapel_ids' => 'nullable|array',
            'mapel_ids.*' => 'exists:mata_pelajaran,id_mapel',
        ]);

        $guru->update([
            'nip'   => $validated['nip'],
            'nama'  => $validated['nama'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'],
        ]);

        $guru->mataPelajaran()->sync($validated['mapel_ids'] ?? []);

        return redirect()->route('guru.index')->with('success', 'Master Guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru): RedirectResponse
    {
        // Validasi sesuai Flowchart 01: Cek apakah guru masih dipakai di jadwal_pelajaran, jadwal_pengawas, atau kegiatan_sekolah
        $terpakaiJadwal = $guru->jadwalPelajaran()->exists();
        $terpakaiPengawas = $guru->jadwalPengawas()->exists();
        $terpakaiKegiatan = $guru->kegiatanSekolah()->exists();

        if ($terpakaiJadwal || $terpakaiPengawas || $terpakaiKegiatan) {
            $alasan = [];
            if ($terpakaiJadwal) $alasan[] = 'Jadwal Pelajaran';
            if ($terpakaiPengawas) $alasan[] = 'Jadwal Pengawas Ujian';
            if ($terpakaiKegiatan) $alasan[] = 'Penanggung Jawab Kegiatan Sekolah';

            return redirect()->route('guru.index')->with('error', 'Data Guru tidak dapat dihapus karena masih aktif di: ' . implode(', ', $alasan) . '.');
        }

        $guru->mataPelajaran()->detach();
        $guru->delete();

        return redirect()->route('guru.index')->with('success', 'Master Guru berhasil dihapus.');
    }
}

