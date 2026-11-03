<?php

namespace App\Http\Controllers;

use App\Models\LogBentrok;
use App\Services\ConflictDetectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogBentrokController extends Controller
{
    public function __construct(
        protected ConflictDetectionService $conflictService
    ) {}

    public function index(Request $request): View
    {
        $jenisBentrok = $request->query('jenis_bentrok');
        $jenisJadwal = $request->query('jenis_jadwal');
        $tanggalMulai = $request->query('tanggal_mulai');
        $tanggalSelesai = $request->query('tanggal_selesai');

        $query = LogBentrok::query();

        if ($jenisBentrok) {
            $query->where('jenis_bentrok', $jenisBentrok);
        }

        if ($jenisJadwal) {
            $query->where('jenis_jadwal', $jenisJadwal);
        }

        if ($tanggalMulai) {
            $query->whereDate('waktu_deteksi', '>=', $tanggalMulai);
        }

        if ($tanggalSelesai) {
            $query->whereDate('waktu_deteksi', '<=', $tanggalSelesai);
        }

        $logList = $query->orderByDesc('waktu_deteksi')->paginate(15)->withQueryString();

        $jenisBentrokOptions = ['BENTROK GURU', 'BENTROK RUANG', 'BENTROK KELAS'];
        $jenisJadwalOptions = ['jadwal_pelajaran', 'jadwal_ujian', 'jadwal_pengawas', 'kegiatan_sekolah'];

        return view('log_bentrok.index', compact(
            'logList',
            'jenisBentrok',
            'jenisJadwal',
            'tanggalMulai',
            'tanggalSelesai',
            'jenisBentrokOptions',
            'jenisJadwalOptions'
        ));
    }

    public function pindai(): RedirectResponse
    {
        $hasil = $this->conflictService->pindaiSeluruhBentrok();

        if (count($hasil) > 0) {
            return redirect()->route('log-bentrok.index')->with('warning', 'Pemindaian selesai: Ditemukan ' . count($hasil) . ' bentrok jadwal dalam sistem.');
        }

        return redirect()->route('log-bentrok.index')->with('success', 'Pemindaian selesai: Tidak ditemukan bentrok pada seluruh jadwal aktif!');
    }

    public function destroy(LogBentrok $logBentrok): RedirectResponse
    {
        $logBentrok->delete();

        return redirect()->route('log-bentrok.index')->with('success', 'Entri log berhasil dihapus.');
    }

    public function clear(): RedirectResponse
    {
        LogBentrok::truncate();

        return redirect()->route('log-bentrok.index')->with('success', 'Seluruh riwayat log bentrok telah dibersihkan.');
    }

    public function cetak(Request $request): View
    {
        $jenisBentrok = $request->query('jenis_bentrok');
        $jenisJadwal = $request->query('jenis_jadwal');
        $tanggalMulai = $request->query('tanggal_mulai');
        $tanggalSelesai = $request->query('tanggal_selesai');

        $query = LogBentrok::query();

        if ($jenisBentrok) {
            $query->where('jenis_bentrok', $jenisBentrok);
        }

        if ($jenisJadwal) {
            $query->where('jenis_jadwal', $jenisJadwal);
        }

        if ($tanggalMulai) {
            $query->whereDate('waktu_deteksi', '>=', $tanggalMulai);
        }

        if ($tanggalSelesai) {
            $query->whereDate('waktu_deteksi', '<=', $tanggalSelesai);
        }

        $logList = $query->orderByDesc('waktu_deteksi')->get();

        return view('log_bentrok.cetak', compact(
            'logList',
            'jenisBentrok',
            'jenisJadwal',
            'tanggalMulai',
            'tanggalSelesai'
        ));
    }
}

