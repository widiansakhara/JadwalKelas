<?php

namespace App\Services;

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\JadwalPengawas;
use App\Models\JadwalUjian;
use App\Models\JamPelajaran;
use App\Models\KegiatanSekolah;
use App\Models\Kelas;
use App\Models\LogBentrok;
use App\Models\MataPelajaran;
use App\Models\Ruang;
use Carbon\Carbon;

class ConflictDetectionService
{
    /**
     * Memeriksa seluruh potensi bentrok untuk jadwal pelajaran yang akan disimpan/diubah.
     *
     * @param array $data
     * @param int|null $ignoreId
     * @return array Array pesan kesalahan bentrok jika ada
     */
    public function cekSemuaJadwalPelajaran(array $data, ?int $ignoreId = null): array
    {
        $konflik = [];

        // 1. Cek bentrok Guru
        $bentrokGuru = $this->cekBentrokGuru(
            (int) $data['id_guru'],
            $data['hari'],
            (int) $data['id_jam'],
            (int) $data['id_tahun_ajaran'],
            $ignoreId
        );
        if ($bentrokGuru) {
            $konflik[] = $bentrokGuru;
            $this->simpanLog('BENTROK GURU', 'jadwal_pelajaran', $ignoreId, $bentrokGuru);
        }

        // 2. Cek bentrok Ruang
        $bentrokRuang = $this->cekBentrokRuang(
            (int) $data['id_ruang'],
            $data['hari'],
            (int) $data['id_jam'],
            (int) $data['id_tahun_ajaran'],
            $ignoreId
        );
        if ($bentrokRuang) {
            $konflik[] = $bentrokRuang;
            $this->simpanLog('BENTROK RUANG', 'jadwal_pelajaran', $ignoreId, $bentrokRuang);
        }

        // 3. Cek bentrok Kelas
        $bentrokKelas = $this->cekBentrokKelas(
            (int) $data['id_kelas'],
            $data['hari'],
            (int) $data['id_jam'],
            (int) $data['id_tahun_ajaran'],
            $ignoreId
        );
        if ($bentrokKelas) {
            $konflik[] = $bentrokKelas;
            $this->simpanLog('BENTROK KELAS', 'jadwal_pelajaran', $ignoreId, $bentrokKelas);
        }

        return $konflik;
    }

    /**
     * Cek bentrok Guru (Guru A tidak boleh mendapatkan dua kelas pada jam yang sama).
     */
    public function cekBentrokGuru(int $idGuru, string $hari, int $idJam, int $idTahunAjaran, ?int $ignoreId = null): ?string
    {
        $query = JadwalPelajaran::with(['kelas', 'mataPelajaran', 'jamPelajaran', 'guru'])
            ->where('id_tahun_ajaran', $idTahunAjaran)
            ->where('id_guru', $idGuru)
            ->where('hari', $hari)
            ->where('id_jam', $idJam);

        if ($ignoreId) {
            $query->where('id_jadwal', '!=', $ignoreId);
        }

        $bentrok = $query->first();

        if ($bentrok) {
            $namaGuru = $bentrok->guru?->nama ?? 'Guru terkait';
            $namaKelas = $bentrok->kelas?->nama_kelas ?? 'Kelas lain';
            $mapel = $bentrok->mataPelajaran?->nama_mapel ?? 'Mata pelajaran lain';
            $jam = $bentrok->jamPelajaran ? "Jam ke-{$bentrok->jamPelajaran->jam_ke} ({$bentrok->jamPelajaran->format_jam})" : "Jam ke-{$idJam}";

            return "Bentrok Guru: {$namaGuru} sudah terjadwal mengajar di {$namaKelas} ({$mapel}) pada hari {$hari}, {$jam}. Guru tidak boleh mengajar dua kelas pada jam yang sama.";
        }

        return null;
    }

    /**
     * Cek bentrok Ruang pada jadwal pelajaran.
     */
    public function cekBentrokRuang(int $idRuang, string $hari, int $idJam, int $idTahunAjaran, ?int $ignoreId = null): ?string
    {
        $query = JadwalPelajaran::with(['kelas', 'mataPelajaran', 'jamPelajaran', 'ruang'])
            ->where('id_tahun_ajaran', $idTahunAjaran)
            ->where('id_ruang', $idRuang)
            ->where('hari', $hari)
            ->where('id_jam', $idJam);

        if ($ignoreId) {
            $query->where('id_jadwal', '!=', $ignoreId);
        }

        $bentrok = $query->first();

        if ($bentrok) {
            $namaRuang = $bentrok->ruang?->nama_ruang ?? 'Ruang terkait';
            $namaKelas = $bentrok->kelas?->nama_kelas ?? 'Kelas lain';
            $mapel = $bentrok->mataPelajaran?->nama_mapel ?? 'Mata pelajaran lain';
            $jam = $bentrok->jamPelajaran ? "Jam ke-{$bentrok->jamPelajaran->jam_ke} ({$bentrok->jamPelajaran->format_jam})" : "Jam ke-{$idJam}";

            return "Bentrok Ruang: {$namaRuang} sudah digunakan oleh {$namaKelas} untuk pelajaran {$mapel} pada hari {$hari}, {$jam}.";
        }

        return null;
    }

    /**
     * Cek bentrok Kelas pada jadwal pelajaran.
     */
    public function cekBentrokKelas(int $idKelas, string $hari, int $idJam, int $idTahunAjaran, ?int $ignoreId = null): ?string
    {
        $query = JadwalPelajaran::with(['guru', 'mataPelajaran', 'jamPelajaran', 'kelas'])
            ->where('id_tahun_ajaran', $idTahunAjaran)
            ->where('id_kelas', $idKelas)
            ->where('hari', $hari)
            ->where('id_jam', $idJam);

        if ($ignoreId) {
            $query->where('id_jadwal', '!=', $ignoreId);
        }

        $bentrok = $query->first();

        if ($bentrok) {
            $namaKelas = $bentrok->kelas?->nama_kelas ?? 'Kelas terkait';
            $namaGuru = $bentrok->guru?->nama ?? 'Guru lain';
            $mapel = $bentrok->mataPelajaran?->nama_mapel ?? 'Mata pelajaran lain';
            $jam = $bentrok->jamPelajaran ? "Jam ke-{$bentrok->jamPelajaran->jam_ke} ({$bentrok->jamPelajaran->format_jam})" : "Jam ke-{$idJam}";

            return "Bentrok Kelas: {$namaKelas} sudah memiliki jadwal pelajaran {$mapel} bersama {$namaGuru} pada hari {$hari}, {$jam}.";
        }

        return null;
    }

    /**
     * Memeriksa bentrok jadwal ujian (Ruang dan Kelas pada tanggal dan rentang jam).
     */
    public function cekSemuaJadwalUjian(array $data, ?int $ignoreId = null): array
    {
        $konflik = [];
        $tanggal = $data['tanggal'];
        $jamMulai = strlen($data['jam_mulai']) === 5 ? $data['jam_mulai'] . ':00' : $data['jam_mulai'];
        $jamSelesai = strlen($data['jam_selesai']) === 5 ? $data['jam_selesai'] . ':00' : $data['jam_selesai'];
        $idRuang = (int) $data['id_ruang'];
        $idKelas = (int) $data['id_kelas'];

        // 1. Cek Ruang Ujian bentrok dengan Ujian lain
        $ruangUjianBentrok = JadwalUjian::with(['ruang', 'mataPelajaran', 'kelas'])
            ->whereDate('tanggal', $tanggal)
            ->where('id_ruang', $idRuang)
            ->when($ignoreId, fn($q) => $q->where('id_ujian', '!=', $ignoreId))
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->where(function ($sub) use ($jamMulai, $jamSelesai) {
                    $sub->where('jam_mulai', '<', $jamSelesai)
                        ->where('jam_selesai', '>', $jamMulai);
                });
            })
            ->first();

        if ($ruangUjianBentrok) {
            $ruangNama = $ruangUjianBentrok->ruang?->nama_ruang ?? 'Ruang terkait';
            $pesan = "Bentrok Ruang Ujian: {$ruangNama} sudah digunakan untuk {$ruangUjianBentrok->jenis_ujian} ({$ruangUjianBentrok->mataPelajaran?->nama_mapel} - {$ruangUjianBentrok->kelas?->nama_kelas}) pada tanggal {$tanggal} jam {$ruangUjianBentrok->format_jam}.";
            $konflik[] = $pesan;
            $this->simpanLog('BENTROK RUANG', 'jadwal_ujian', $ignoreId, $pesan);
        }

        // 2. Cek Ruang Ujian bentrok dengan Kegiatan Sekolah
        $ruangKegiatanBentrok = KegiatanSekolah::with('ruang')
            ->where('id_ruang', $idRuang)
            ->whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_selesai', '>=', $tanggal)
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->where('jam_mulai', '<', $jamSelesai)
                    ->where('jam_selesai', '>', $jamMulai);
            })
            ->first();

        if ($ruangKegiatanBentrok) {
            $ruangNama = $ruangKegiatanBentrok->ruang?->nama_ruang ?? 'Ruang terkait';
            $pesan = "Bentrok Ruang: {$ruangNama} terpakai untuk Kegiatan Sekolah '{$ruangKegiatanBentrok->nama_kegiatan}' pada tanggal {$tanggal} jam {$ruangKegiatanBentrok->format_jam}.";
            $konflik[] = $pesan;
            $this->simpanLog('BENTROK RUANG', 'jadwal_ujian', $ignoreId, $pesan);
        }

        // 3. Cek Kelas Ujian bentrok dengan Ujian lain di jam yang sama
        $kelasUjianBentrok = JadwalUjian::with(['kelas', 'mataPelajaran'])
            ->whereDate('tanggal', $tanggal)
            ->where('id_kelas', $idKelas)
            ->when($ignoreId, fn($q) => $q->where('id_ujian', '!=', $ignoreId))
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->where('jam_mulai', '<', $jamSelesai)
                    ->where('jam_selesai', '>', $jamMulai);
            })
            ->first();

        if ($kelasUjianBentrok) {
            $kelasNama = $kelasUjianBentrok->kelas?->nama_kelas ?? 'Kelas terkait';
            $pesan = "Bentrok Kelas Ujian: {$kelasNama} sudah memiliki jadwal ujian {$kelasUjianBentrok->mataPelajaran?->nama_mapel} pada tanggal {$tanggal} jam {$kelasUjianBentrok->format_jam}.";
            $konflik[] = $pesan;
            $this->simpanLog('BENTROK KELAS', 'jadwal_ujian', $ignoreId, $pesan);
        }

        return $konflik;
    }

    /**
     * Memeriksa bentrok pengawas ujian.
     */
    public function cekBentrokPengawas(int $idUjian, int $idGuru, ?int $ignorePengawasId = null): ?string
    {
        $ujianTerkait = JadwalUjian::find($idUjian);
        if (!$ujianTerkait) {
            return null;
        }

        $jamMulai = strlen($ujianTerkait->jam_mulai) === 5 ? $ujianTerkait->jam_mulai . ':00' : $ujianTerkait->jam_mulai;
        $jamSelesai = strlen($ujianTerkait->jam_selesai) === 5 ? $ujianTerkait->jam_selesai . ':00' : $ujianTerkait->jam_selesai;

        // Cari apakah guru ini sudah mengawas di ujian lain pada tanggal dan jam yang overlap
        $bentrok = JadwalPengawas::with(['jadwalUjian.mataPelajaran', 'jadwalUjian.kelas', 'jadwalUjian.ruang', 'guru'])
            ->where('id_guru', $idGuru)
            ->when($ignorePengawasId, fn($q) => $q->where('id', '!=', $ignorePengawasId))
            ->whereHas('jadwalUjian', function ($q) use ($ujianTerkait, $jamMulai, $jamSelesai) {
                $q->where('id_ujian', '!=', $ujianTerkait->id_ujian)
                    ->whereDate('tanggal', $ujianTerkait->tanggal)
                    ->where('jam_mulai', '<', $jamSelesai)
                    ->where('jam_selesai', '>', $jamMulai);
            })
            ->first();

        if ($bentrok) {
            $guruNama = $bentrok->guru?->nama ?? 'Guru terkait';
            $ujianBentrok = $bentrok->jadwalUjian;
            $pesan = "Bentrok Pengawas: {$guruNama} sudah bertugas mengawas ujian {$ujianBentrok?->mataPelajaran?->nama_mapel} ({$ujianBentrok?->kelas?->nama_kelas}) di ruang {$ujianBentrok?->ruang?->nama_ruang} pada tanggal {$ujianTerkait->tanggal->format('Y-m-d')} jam {$ujianBentrok?->format_jam}.";
            $this->simpanLog('BENTROK GURU', 'jadwal_pengawas', $idUjian, $pesan);
            return $pesan;
        }

        return null;
    }

    /**
     * Memeriksa bentrok untuk Kegiatan Sekolah (Ruang).
     */
    public function cekSemuaKegiatanSekolah(array $data, ?int $ignoreId = null): array
    {
        $konflik = [];
        $idRuang = !empty($data['id_ruang']) ? (int) $data['id_ruang'] : null;

        if (!$idRuang) {
            return $konflik; // Tanpa ruang spesifik, tidak ada bentrok ruang
        }

        $tglMulai = $data['tanggal_mulai'];
        $tglSelesai = $data['tanggal_selesai'];
        $jamMulai = strlen($data['jam_mulai']) === 5 ? $data['jam_mulai'] . ':00' : $data['jam_mulai'];
        $jamSelesai = strlen($data['jam_selesai']) === 5 ? $data['jam_selesai'] . ':00' : $data['jam_selesai'];

        // Cek dengan Kegiatan Sekolah lain di ruang yang sama
        $bentrokKegiatan = KegiatanSekolah::with('ruang')
            ->where('id_ruang', $idRuang)
            ->when($ignoreId, fn($q) => $q->where('id_kegiatan', '!=', $ignoreId))
            ->whereDate('tanggal_mulai', '<=', $tglSelesai)
            ->whereDate('tanggal_selesai', '>=', $tglMulai)
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->where('jam_mulai', '<', $jamSelesai)
                    ->where('jam_selesai', '>', $jamMulai);
            })
            ->first();

        if ($bentrokKegiatan) {
            $ruangNama = $bentrokKegiatan->ruang?->nama_ruang ?? 'Ruang terkait';
            $pesan = "Bentrok Ruang: {$ruangNama} sudah dipesan untuk kegiatan '{$bentrokKegiatan->nama_kegiatan}' pada rentang waktu {$bentrokKegiatan->tanggal_mulai->format('Y-m-d')} s/d {$bentrokKegiatan->tanggal_selesai->format('Y-m-d')} jam {$bentrokKegiatan->format_jam}.";
            $konflik[] = $pesan;
            $this->simpanLog('BENTROK RUANG', 'kegiatan_sekolah', $ignoreId, $pesan);
        }

        // Cek dengan Jadwal Ujian di ruang yang sama
        $bentrokUjian = JadwalUjian::with(['ruang', 'mataPelajaran'])
            ->where('id_ruang', $idRuang)
            ->whereDate('tanggal', '>=', $tglMulai)
            ->whereDate('tanggal', '<=', $tglSelesai)
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->where('jam_mulai', '<', $jamSelesai)
                    ->where('jam_selesai', '>', $jamMulai);
            })
            ->first();

        if ($bentrokUjian) {
            $ruangNama = $bentrokUjian->ruang?->nama_ruang ?? 'Ruang terkait';
            $pesan = "Bentrok Ruang: {$ruangNama} telah terjadwal untuk ujian {$bentrokUjian->mataPelajaran?->nama_mapel} pada tanggal {$bentrokUjian->tanggal->format('Y-m-d')} jam {$bentrokUjian->format_jam}.";
            $konflik[] = $pesan;
            $this->simpanLog('BENTROK RUANG', 'kegiatan_sekolah', $ignoreId, $pesan);
        }

        return $konflik;
    }

    /**
     * Memindai seluruh data database untuk menemukan bentrok yang ada.
     *
     * @return array
     */
    public function pindaiSeluruhBentrok(): array
    {
        $hasil = [];

        // 1. Pindai Jadwal Pelajaran
        $semuaJadwal = JadwalPelajaran::with(['guru', 'kelas', 'ruang', 'mataPelajaran', 'jamPelajaran'])->get();
        $checkedGuru = [];
        $checkedRuang = [];
        $checkedKelas = [];

        foreach ($semuaJadwal as $j) {
            $keyGuru = "{$j->id_tahun_ajaran}_{$j->hari}_{$j->id_jam}_{$j->id_guru}";
            if (isset($checkedGuru[$keyGuru])) {
                $prev = $checkedGuru[$keyGuru];
                $desc = "Bentrok Guru: {$j->guru?->nama} terdaftar ganda pada {$j->hari} Jam ke-{$j->jamPelajaran?->jam_ke} antara {$prev->kelas?->nama_kelas} dan {$j->kelas?->nama_kelas}.";
                $hasil[] = [
                    'jenis' => 'BENTROK GURU',
                    'tipe' => 'jadwal_pelajaran',
                    'id' => $j->id_jadwal,
                    'deskripsi' => $desc,
                ];
                $this->simpanLog('BENTROK GURU', 'jadwal_pelajaran', $j->id_jadwal, $desc);
            } else {
                $checkedGuru[$keyGuru] = $j;
            }

            $keyRuang = "{$j->id_tahun_ajaran}_{$j->hari}_{$j->id_jam}_{$j->id_ruang}";
            if (isset($checkedRuang[$keyRuang])) {
                $prev = $checkedRuang[$keyRuang];
                $desc = "Bentrok Ruang: {$j->ruang?->nama_ruang} dipakai ganda pada {$j->hari} Jam ke-{$j->jamPelajaran?->jam_ke} oleh {$prev->kelas?->nama_kelas} dan {$j->kelas?->nama_kelas}.";
                $hasil[] = [
                    'jenis' => 'BENTROK RUANG',
                    'tipe' => 'jadwal_pelajaran',
                    'id' => $j->id_jadwal,
                    'deskripsi' => $desc,
                ];
                $this->simpanLog('BENTROK RUANG', 'jadwal_pelajaran', $j->id_jadwal, $desc);
            } else {
                $checkedRuang[$keyRuang] = $j;
            }

            $keyKelas = "{$j->id_tahun_ajaran}_{$j->hari}_{$j->id_jam}_{$j->id_kelas}";
            if (isset($checkedKelas[$keyKelas])) {
                $prev = $checkedKelas[$keyKelas];
                $desc = "Bentrok Kelas: {$j->kelas?->nama_kelas} memiliki dua jadwal pada {$j->hari} Jam ke-{$j->jamPelajaran?->jam_ke}: {$prev->mataPelajaran?->nama_mapel} dan {$j->mataPelajaran?->nama_mapel}.";
                $hasil[] = [
                    'jenis' => 'BENTROK KELAS',
                    'tipe' => 'jadwal_pelajaran',
                    'id' => $j->id_jadwal,
                    'deskripsi' => $desc,
                ];
                $this->simpanLog('BENTROK KELAS', 'jadwal_pelajaran', $j->id_jadwal, $desc);
            } else {
                $checkedKelas[$keyKelas] = $j;
            }
        }

        return $hasil;
    }

    /**
     * Simpan catatan bentrok ke tabel log_bentrok.
     */
    public function simpanLog(string $jenisBentrok, string $jenisJadwal, ?int $idReferensi, string $deskripsi): LogBentrok
    {
        return LogBentrok::create([
            'jenis_bentrok' => $jenisBentrok,
            'jenis_jadwal'  => $jenisJadwal,
            'id_referensi'  => $idReferensi,
            'deskripsi'     => $deskripsi,
            'waktu_deteksi' => Carbon::now(),
        ]);
    }
}
