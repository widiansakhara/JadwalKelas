<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\JadwalPengawas;
use App\Models\JadwalUjian;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Ruang;
use App\Models\TahunAjaran;
use App\Services\ConflictDetectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalUjianController extends Controller
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
        $idKelas = $request->query('id_kelas');
        $jenisUjian = $request->query('jenis_ujian');

        $query = JadwalUjian::with(['tahunAjaran', 'kelas', 'mataPelajaran', 'ruang', 'pengawas'])
            ->where('id_tahun_ajaran', $idTahunAjaran);

        if ($idKelas) {
            $query->where('id_kelas', $idKelas);
        }

        if ($jenisUjian) {
            $query->where('jenis_ujian', $jenisUjian);
        }

        $ujianList = $query->orderBy('tanggal')->orderBy('jam_mulai')->paginate(10)->withQueryString();

        $tahunTerpilih = $tahunList->firstWhere('id_tahun_ajaran', $idTahunAjaran);

        return view('jadwal_ujian.index', compact(
            'ujianList',
            'tahunList',
            'tahunTerpilih',
            'idTahunAjaran',
            'kelasList',
            'idKelas',
            'jenisUjian'
        ));
    }

    public function create(): View
    {
        $tahunList = TahunAjaran::orderByDesc('tahun')->get();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $mapelList = MataPelajaran::orderBy('nama_mapel')->get();
        $ruangList = Ruang::orderBy('kode_ruang')->get();
        $guruList = Guru::orderBy('nama')->get();

        return view('jadwal_ujian.create', compact('tahunList', 'kelasList', 'mapelList', 'ruangList', 'guruList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id_tahun_ajaran',
            'id_kelas'        => 'required|exists:kelas,id_kelas',
            'id_mapel'        => 'required|exists:mata_pelajaran,id_mapel',
            'id_ruang'        => 'required|exists:ruang,id_ruang',
            'jenis_ujian'     => 'required|in:UTS,UAS,Ulangan Harian,Try Out',
            'tanggal'         => 'required|date',
            'jam_mulai'       => 'required',
            'jam_selesai'     => 'required|after:jam_mulai',
            'pengawas_ids'    => 'nullable|array',
            'pengawas_ids.*'  => 'exists:guru,id_guru',
        ]);

        // AUTOMATIC CONFLICT DETECTION (Ruang & Kelas)
        $konflik = $this->conflictService->cekSemuaJadwalUjian($validated);

        if (!empty($konflik)) {
            return back()->withInput()->with('conflict_errors', $konflik);
        }

        $ujian = JadwalUjian::create([
            'id_tahun_ajaran' => $validated['id_tahun_ajaran'],
            'id_kelas'        => $validated['id_kelas'],
            'id_mapel'        => $validated['id_mapel'],
            'id_ruang'        => $validated['id_ruang'],
            'jenis_ujian'     => $validated['jenis_ujian'],
            'tanggal'         => $validated['tanggal'],
            'jam_mulai'       => $validated['jam_mulai'],
            'jam_selesai'     => $validated['jam_selesai'],
        ]);

        if (!empty($validated['pengawas_ids'])) {
            foreach ($validated['pengawas_ids'] as $idGuru) {
                // Check conflict for proctor
                $bentrokPengawas = $this->conflictService->cekBentrokPengawas($ujian->id_ujian, (int) $idGuru);
                if (!$bentrokPengawas) {
                    JadwalPengawas::create([
                        'id_ujian' => $ujian->id_ujian,
                        'id_guru'  => $idGuru,
                    ]);
                }
            }
        }

        return redirect()->route('jadwal-ujian.index', [
            'id_tahun_ajaran' => $validated['id_tahun_ajaran'],
        ])->with('success', 'Jadwal ujian berhasil ditambahkan.');
    }

    public function edit(JadwalUjian $jadwalUjian): View
    {
        $jadwalUjian->load(['pengawas']);
        $tahunList = TahunAjaran::orderByDesc('tahun')->get();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $mapelList = MataPelajaran::orderBy('nama_mapel')->get();
        $ruangList = Ruang::orderBy('kode_ruang')->get();
        $guruList = Guru::orderBy('nama')->get();

        return view('jadwal_ujian.edit', compact('jadwalUjian', 'tahunList', 'kelasList', 'mapelList', 'ruangList', 'guruList'));
    }

    public function update(Request $request, JadwalUjian $jadwalUjian): RedirectResponse
    {
        $validated = $request->validate([
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id_tahun_ajaran',
            'id_kelas'        => 'required|exists:kelas,id_kelas',
            'id_mapel'        => 'required|exists:mata_pelajaran,id_mapel',
            'id_ruang'        => 'required|exists:ruang,id_ruang',
            'jenis_ujian'     => 'required|in:UTS,UAS,Ulangan Harian,Try Out',
            'tanggal'         => 'required|date',
            'jam_mulai'       => 'required',
            'jam_selesai'     => 'required|after:jam_mulai',
            'pengawas_ids'    => 'nullable|array',
            'pengawas_ids.*'  => 'exists:guru,id_guru',
        ]);

        // AUTOMATIC CONFLICT DETECTION
        $konflik = $this->conflictService->cekSemuaJadwalUjian($validated, $jadwalUjian->id_ujian);

        if (!empty($konflik)) {
            return back()->withInput()->with('conflict_errors', $konflik);
        }

        $jadwalUjian->update([
            'id_tahun_ajaran' => $validated['id_tahun_ajaran'],
            'id_kelas'        => $validated['id_kelas'],
            'id_mapel'        => $validated['id_mapel'],
            'id_ruang'        => $validated['id_ruang'],
            'jenis_ujian'     => $validated['jenis_ujian'],
            'tanggal'         => $validated['tanggal'],
            'jam_mulai'       => $validated['jam_mulai'],
            'jam_selesai'     => $validated['jam_selesai'],
        ]);

        // Update pengawas
        $jadwalUjian->pengawas()->detach();
        if (!empty($validated['pengawas_ids'])) {
            foreach ($validated['pengawas_ids'] as $idGuru) {
                $bentrokPengawas = $this->conflictService->cekBentrokPengawas($jadwalUjian->id_ujian, (int) $idGuru);
                if (!$bentrokPengawas) {
                    JadwalPengawas::create([
                        'id_ujian' => $jadwalUjian->id_ujian,
                        'id_guru'  => $idGuru,
                    ]);
                }
            }
        }

        return redirect()->route('jadwal-ujian.index', [
            'id_tahun_ajaran' => $validated['id_tahun_ajaran'],
        ])->with('success', 'Jadwal ujian berhasil diperbarui.');
    }

    public function destroy(JadwalUjian $jadwalUjian): RedirectResponse
    {
        $idTahunAjaran = $jadwalUjian->id_tahun_ajaran;
        $jadwalUjian->delete();

        return redirect()->route('jadwal-ujian.index', [
            'id_tahun_ajaran' => $idTahunAjaran,
        ])->with('success', 'Jadwal ujian berhasil dihapus.');
    }

    public function cetak(Request $request): View
    {
        $tahunList = TahunAjaran::orderByDesc('tahun')->get();
        $tahunAktif = TahunAjaran::where('status_aktif', true)->first() ?? $tahunList->first();
        $idTahunAjaran = $request->query('id_tahun_ajaran', $tahunAktif?->id_tahun_ajaran);
        $idKelas = $request->query('id_kelas');
        $jenisUjian = $request->query('jenis_ujian');

        $query = JadwalUjian::with(['tahunAjaran', 'kelas', 'mataPelajaran', 'ruang', 'pengawas'])
            ->where('id_tahun_ajaran', $idTahunAjaran);

        if ($idKelas) {
            $query->where('id_kelas', $idKelas);
        }

        if ($jenisUjian) {
            $query->where('jenis_ujian', $jenisUjian);
        }

        $ujianList = $query->orderBy('tanggal')->orderBy('jam_mulai')->get();
        $tahunTerpilih = $tahunList->firstWhere('id_tahun_ajaran', $idTahunAjaran);
        $kelasTerpilih = $idKelas ? Kelas::find($idKelas) : null;

        return view('jadwal_ujian.cetak', compact('ujianList', 'tahunTerpilih', 'kelasTerpilih', 'jenisUjian'));
    }
}

