<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\KegiatanSekolah;
use App\Models\Ruang;
use App\Services\ConflictDetectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KegiatanSekolahController extends Controller
{
    public function __construct(
        protected ConflictDetectionService $conflictService
    ) {}

    public function index(Request $request): View
    {
        $search = $request->query('search');
        $idRuang = $request->query('id_ruang');

        $query = KegiatanSekolah::with(['ruang', 'penanggungJawab']);

        if ($search) {
            $query->where('nama_kegiatan', 'like', "%{$search}%");
        }

        if ($idRuang) {
            $query->where('id_ruang', $idRuang);
        }

        $kegiatanList = $query->orderBy('tanggal_mulai', 'desc')->paginate(10)->withQueryString();
        $ruangList = Ruang::orderBy('kode_ruang')->get();

        return view('kegiatan_sekolah.index', compact('kegiatanList', 'ruangList', 'search', 'idRuang'));
    }

    public function create(): View
    {
        $ruangList = Ruang::orderBy('kode_ruang')->get();
        $guruList = Guru::orderBy('nama')->get();

        return view('kegiatan_sekolah.create', compact('ruangList', 'guruList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kegiatan'       => 'required|string|max:100',
            'tanggal_mulai'       => 'required|date',
            'tanggal_selesai'     => 'required|date|after_or_equal:tanggal_mulai',
            'jam_mulai'           => 'required',
            'jam_selesai'         => 'required|after:jam_mulai',
            'id_ruang'            => 'nullable|exists:ruang,id_ruang',
            'id_penanggung_jawab' => 'required|exists:guru,id_guru',
        ]);

        // AUTOMATIC CONFLICT DETECTION (Ruang kegiatan)
        $konflik = $this->conflictService->cekSemuaKegiatanSekolah($validated);

        if (!empty($konflik)) {
            return back()->withInput()->with('conflict_errors', $konflik);
        }

        KegiatanSekolah::create($validated);

        return redirect()->route('kegiatan-sekolah.index')->with('success', 'Kegiatan sekolah berhasil ditambahkan.');
    }

    public function edit(KegiatanSekolah $kegiatanSekolah): View
    {
        $ruangList = Ruang::orderBy('kode_ruang')->get();
        $guruList = Guru::orderBy('nama')->get();

        return view('kegiatan_sekolah.edit', compact('kegiatanSekolah', 'ruangList', 'guruList'));
    }

    public function update(Request $request, KegiatanSekolah $kegiatanSekolah): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kegiatan'       => 'required|string|max:100',
            'tanggal_mulai'       => 'required|date',
            'tanggal_selesai'     => 'required|date|after_or_equal:tanggal_mulai',
            'jam_mulai'           => 'required',
            'jam_selesai'         => 'required|after:jam_mulai',
            'id_ruang'            => 'nullable|exists:ruang,id_ruang',
            'id_penanggung_jawab' => 'required|exists:guru,id_guru',
        ]);

        // AUTOMATIC CONFLICT DETECTION
        $konflik = $this->conflictService->cekSemuaKegiatanSekolah($validated, $kegiatanSekolah->id_kegiatan);

        if (!empty($konflik)) {
            return back()->withInput()->with('conflict_errors', $konflik);
        }

        $kegiatanSekolah->update($validated);

        return redirect()->route('kegiatan-sekolah.index')->with('success', 'Kegiatan sekolah berhasil diperbarui.');
    }

    public function destroy(KegiatanSekolah $kegiatanSekolah): RedirectResponse
    {
        $kegiatanSekolah->delete();

        return redirect()->route('kegiatan-sekolah.index')->with('success', 'Kegiatan sekolah berhasil dihapus.');
    }

    public function cetak(): View
    {
        $kegiatanList = KegiatanSekolah::with(['ruang', 'penanggungJawab'])
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return view('kegiatan_sekolah.cetak', compact('kegiatanList'));
    }
}

