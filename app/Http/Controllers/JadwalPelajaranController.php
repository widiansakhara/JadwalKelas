<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Ruang;
use App\Models\TahunAjaran;
use App\Services\ConflictDetectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalPelajaranController extends Controller
{
    public function __construct(
        protected ConflictDetectionService $conflictService
    ) {}

    public function index(Request $request): View
    {
        $tahunList = TahunAjaran::orderByDesc('tahun')->get();
        $tahunAktif = TahunAjaran::where('status_aktif', true)->first() ?? $tahunList->first();
        $idTahunAjaran = $request->query('id_tahun_ajaran', $tahunAktif?->id_tahun_ajaran);

        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $idKelas = $request->query('id_kelas', $kelasList->first()?->id_kelas);
        $hariFilter = $request->query('hari');
        $viewMode = $request->query('view', 'grid'); // 'grid' atau 'table'

        $jamList = JamPelajaran::orderBy('jam_ke')->get();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        $query = JadwalPelajaran::with(['tahunAjaran', 'kelas', 'mataPelajaran', 'guru', 'ruang', 'jamPelajaran'])
            ->where('id_tahun_ajaran', $idTahunAjaran);

        if ($idKelas) {
            $query->where('id_kelas', $idKelas);
        }

        if ($hariFilter) {
            $query->where('hari', $hariFilter);
        }

        $jadwalList = $query->get();

        // Siapkan struktur matriks (hari => [id_jam => jadwal]) untuk Grid View
        $matriksJadwal = [];
        foreach ($hariList as $h) {
            $matriksJadwal[$h] = [];
            foreach ($jamList as $j) {
                $matriksJadwal[$h][$j->id_jam] = null;
            }
        }

        foreach ($jadwalList as $jadwal) {
            if (isset($matriksJadwal[$jadwal->hari])) {
                $matriksJadwal[$jadwal->hari][$jadwal->id_jam] = $jadwal;
            }
        }

        $kelasTerpilih = $kelasList->firstWhere('id_kelas', $idKelas);
        $tahunTerpilih = $tahunList->firstWhere('id_tahun_ajaran', $idTahunAjaran);

        return view('jadwal_pelajaran.index', compact(
            'jadwalList',
            'tahunList',
            'tahunTerpilih',
            'idTahunAjaran',
            'kelasList',
            'kelasTerpilih',
            'idKelas',
            'jamList',
            'hariList',
            'hariFilter',
            'matriksJadwal',
            'viewMode'
        ));
    }

    public function create(Request $request): View
    {
        $tahunList = TahunAjaran::orderByDesc('tahun')->get();
        $tahunAktif = TahunAjaran::where('status_aktif', true)->first();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $mapelList = MataPelajaran::orderBy('nama_mapel')->get();
        $guruList = Guru::with('mataPelajaran')->orderBy('nama')->get();
        $ruangList = Ruang::orderBy('kode_ruang')->get();
        $jamList = JamPelajaran::orderBy('jam_ke')->get();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        $prefill = [
            'id_tahun_ajaran' => $request->query('id_tahun_ajaran', $tahunAktif?->id_tahun_ajaran),
            'id_kelas'        => $request->query('id_kelas'),
            'hari'            => $request->query('hari'),
            'id_jam'          => $request->query('id_jam'),
        ];

        return view('jadwal_pelajaran.create', compact(
            'tahunList',
            'kelasList',
            'mapelList',
            'guruList',
            'ruangList',
            'jamList',
            'hariList',
            'prefill'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id_tahun_ajaran',
            'id_kelas'        => 'required|exists:kelas,id_kelas',
            'id_mapel'        => 'required|exists:mata_pelajaran,id_mapel',
            'id_guru'         => 'required|exists:guru,id_guru',
            'id_ruang'        => 'required|exists:ruang,id_ruang',
            'id_jam'          => 'required|exists:jam_pelajaran,id_jam',
            'hari'            => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
        ]);

        // AUTOMATIC CONFLICT DETECTION (Deteksi Bentrok Otomatis)
        $konflik = $this->conflictService->cekSemuaJadwalPelajaran($validated);

        if (!empty($konflik)) {
            return back()->withInput()->with('conflict_errors', $konflik);
        }

        JadwalPelajaran::create($validated);

        return redirect()->route('jadwal-pelajaran.index', [
            'id_tahun_ajaran' => $validated['id_tahun_ajaran'],
            'id_kelas'        => $validated['id_kelas'],
        ])->with('success', 'Jadwal pelajaran berhasil disimpan.');
    }

    public function edit(JadwalPelajaran $jadwalPelajaran): View
    {
        $tahunList = TahunAjaran::orderByDesc('tahun')->get();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $mapelList = MataPelajaran::orderBy('nama_mapel')->get();
        $guruList = Guru::with('mataPelajaran')->orderBy('nama')->get();
        $ruangList = Ruang::orderBy('kode_ruang')->get();
        $jamList = JamPelajaran::orderBy('jam_ke')->get();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('jadwal_pelajaran.edit', compact(
            'jadwalPelajaran',
            'tahunList',
            'kelasList',
            'mapelList',
            'guruList',
            'ruangList',
            'jamList',
            'hariList'
        ));
    }

    public function update(Request $request, JadwalPelajaran $jadwalPelajaran): RedirectResponse
    {
        $validated = $request->validate([
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id_tahun_ajaran',
            'id_kelas'        => 'required|exists:kelas,id_kelas',
            'id_mapel'        => 'required|exists:mata_pelajaran,id_mapel',
            'id_guru'         => 'required|exists:guru,id_guru',
            'id_ruang'        => 'required|exists:ruang,id_ruang',
            'id_jam'          => 'required|exists:jam_pelajaran,id_jam',
            'hari'            => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
        ]);

        // AUTOMATIC CONFLICT DETECTION
        $konflik = $this->conflictService->cekSemuaJadwalPelajaran($validated, $jadwalPelajaran->id_jadwal);

        if (!empty($konflik)) {
            return back()->withInput()->with('conflict_errors', $konflik);
        }

        $jadwalPelajaran->update($validated);

        return redirect()->route('jadwal-pelajaran.index', [
            'id_tahun_ajaran' => $validated['id_tahun_ajaran'],
            'id_kelas'        => $validated['id_kelas'],
        ])->with('success', 'Jadwal pelajaran berhasil diperbarui.');
    }

    public function destroy(JadwalPelajaran $jadwalPelajaran): RedirectResponse
    {
        $idTahunAjaran = $jadwalPelajaran->id_tahun_ajaran;
        $idKelas = $jadwalPelajaran->id_kelas;

        $jadwalPelajaran->delete();

        return redirect()->route('jadwal-pelajaran.index', [
            'id_tahun_ajaran' => $idTahunAjaran,
            'id_kelas'        => $idKelas,
        ])->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }

    public function cetak(Request $request): View
    {
        $tahunList = TahunAjaran::orderByDesc('tahun')->get();
        $tahunAktif = TahunAjaran::where('status_aktif', true)->first() ?? $tahunList->first();
        $idTahunAjaran = $request->query('id_tahun_ajaran', $tahunAktif?->id_tahun_ajaran);

        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $idKelas = $request->query('id_kelas', $kelasList->first()?->id_kelas);

        $jamList = JamPelajaran::orderBy('jam_ke')->get();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        $jadwalList = JadwalPelajaran::with(['tahunAjaran', 'kelas', 'mataPelajaran', 'guru', 'ruang', 'jamPelajaran'])
            ->where('id_tahun_ajaran', $idTahunAjaran)
            ->where('id_kelas', $idKelas)
            ->get();

        $matriksJadwal = [];
        foreach ($hariList as $h) {
            $matriksJadwal[$h] = [];
            foreach ($jamList as $j) {
                $matriksJadwal[$h][$j->id_jam] = null;
            }
        }

        foreach ($jadwalList as $jadwal) {
            if (isset($matriksJadwal[$jadwal->hari])) {
                $matriksJadwal[$jadwal->hari][$jadwal->id_jam] = $jadwal;
            }
        }

        $kelasTerpilih = $kelasList->firstWhere('id_kelas', $idKelas);
        $tahunTerpilih = $tahunList->firstWhere('id_tahun_ajaran', $idTahunAjaran);

        return view('jadwal_pelajaran.cetak', compact(
            'tahunTerpilih',
            'kelasTerpilih',
            'jamList',
            'hariList',
            'matriksJadwal'
        ));
    }
}

