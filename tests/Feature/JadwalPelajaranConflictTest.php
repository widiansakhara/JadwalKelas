<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\GuruMapel;
use App\Models\JadwalPelajaran;
use App\Models\JadwalPengawas;
use App\Models\JadwalUjian;
use App\Models\JamPelajaran;
use App\Models\KegiatanSekolah;
use App\Models\Kelas;
use App\Models\LogBentrok;
use App\Models\MataPelajaran;
use App\Models\Ruang;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JadwalPelajaranConflictTest extends TestCase
{
    use RefreshDatabase;

    protected TahunAjaran $ta;
    protected Kelas $kelasA;
    protected Kelas $kelasB;
    protected Guru $guruA;
    protected Guru $guruB;
    protected MataPelajaran $mapel1;
    protected MataPelajaran $mapel2;
    protected Ruang $ruang1;
    protected Ruang $ruang2;
    protected JamPelajaran $jam1;
    protected JamPelajaran $jam2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ta = TahunAjaran::create([
            'tahun' => '2024/2025',
            'semester' => 'Ganjil',
            'status_aktif' => true,
        ]);

        $this->kelasA = Kelas::create(['nama_kelas' => 'X-IPA-1', 'tingkat' => 'X', 'jurusan' => 'MIPA']);
        $this->kelasB = Kelas::create(['nama_kelas' => 'X-IPA-2', 'tingkat' => 'X', 'jurusan' => 'MIPA']);

        $this->guruA = Guru::create(['nip' => '198001012005011001', 'nama' => 'Budi Santoso, M.Pd', 'email' => 'budi@sekolah.sch.id', 'no_hp' => '081234567891']);
        $this->guruB = Guru::create(['nip' => '198203152006042002', 'nama' => 'Siti Rahmawati, S.Pd', 'email' => 'siti@sekolah.sch.id', 'no_hp' => '081234567892']);

        $this->mapel1 = MataPelajaran::create(['kode_mapel' => 'MAT01', 'nama_mapel' => 'Matematika Wajib', 'jam_per_minggu' => 4]);
        $this->mapel2 = MataPelajaran::create(['kode_mapel' => 'FIS01', 'nama_mapel' => 'Fisika', 'jam_per_minggu' => 3]);

        $this->ruang1 = Ruang::create(['kode_ruang' => 'R-101', 'nama_ruang' => 'Ruang 101', 'jenis' => 'Teori', 'kapasitas' => 36]);
        $this->ruang2 = Ruang::create(['kode_ruang' => 'R-102', 'nama_ruang' => 'Ruang 102', 'jenis' => 'Teori', 'kapasitas' => 36]);

        $this->jam1 = JamPelajaran::create(['jam_ke' => 1, 'jam_mulai' => '07:00:00', 'jam_selesai' => '07:45:00']);
        $this->jam2 = JamPelajaran::create(['jam_ke' => 2, 'jam_mulai' => '07:45:00', 'jam_selesai' => '08:30:00']);
    }

    /**
     * Test Guru A tidak boleh mendapatkan dua kelas pada jam yang sama (Automatic Conflict Detection).
     */
    public function test_guru_tidak_boleh_mendapatkan_dua_kelas_pada_jam_yang_sama(): void
    {
        // 1. Jadwal awal: Guru A mengajar di Kelas A pada hari Senin Jam Ke-1 di Ruang 1
        JadwalPelajaran::create([
            'id_tahun_ajaran' => $this->ta->id_tahun_ajaran,
            'id_kelas'        => $this->kelasA->id_kelas,
            'id_mapel'        => $this->mapel1->id_mapel,
            'id_guru'         => $this->guruA->id_guru,
            'id_ruang'        => $this->ruang1->id_ruang,
            'id_jam'          => $this->jam1->id_jam,
            'hari'            => 'Senin',
        ]);

        // 2. Coba jadwalkan Guru A di Kelas B pada hari Senin Jam Ke-1 yang sama (di Ruang 2)
        $response = $this->post(route('jadwal-pelajaran.store'), [
            'id_tahun_ajaran' => $this->ta->id_tahun_ajaran,
            'id_kelas'        => $this->kelasB->id_kelas,
            'id_mapel'        => $this->mapel1->id_mapel,
            'id_guru'         => $this->guruA->id_guru, // Guru A sama!
            'id_ruang'        => $this->ruang2->id_ruang,
            'id_jam'          => $this->jam1->id_jam,   // Jam sama!
            'hari'            => 'Senin',              // Hari sama!
        ]);

        // Harus ditolak (redirect back dengan session conflict_errors)
        $response->assertSessionHas('conflict_errors');
        $this->assertDatabaseCount('jadwal_pelajaran', 1);

        // Harus dicatat otomatis di log_bentrok
        $this->assertDatabaseHas('log_bentrok', [
            'jenis_bentrok' => 'BENTROK GURU',
            'jenis_jadwal'  => 'jadwal_pelajaran',
        ]);
    }

    /**
     * Test Ruang tidak boleh dipakai oleh dua jadwal pada jam yang sama.
     */
    public function test_ruang_tidak_boleh_dipakai_dua_kelas_pada_jam_yang_sama(): void
    {
        // Jadwal awal di Ruang 1
        JadwalPelajaran::create([
            'id_tahun_ajaran' => $this->ta->id_tahun_ajaran,
            'id_kelas'        => $this->kelasA->id_kelas,
            'id_mapel'        => $this->mapel1->id_mapel,
            'id_guru'         => $this->guruA->id_guru,
            'id_ruang'        => $this->ruang1->id_ruang,
            'id_jam'          => $this->jam1->id_jam,
            'hari'            => 'Senin',
        ]);

        // Guru B mencoba menggunakan Ruang 1 untuk Kelas B di hari & jam yang sama
        $response = $this->post(route('jadwal-pelajaran.store'), [
            'id_tahun_ajaran' => $this->ta->id_tahun_ajaran,
            'id_kelas'        => $this->kelasB->id_kelas,
            'id_mapel'        => $this->mapel2->id_mapel,
            'id_guru'         => $this->guruB->id_guru,
            'id_ruang'        => $this->ruang1->id_ruang, // Ruang 1 sama!
            'id_jam'          => $this->jam1->id_jam,
            'hari'            => 'Senin',
        ]);

        $response->assertSessionHas('conflict_errors');
        $this->assertDatabaseCount('jadwal_pelajaran', 1);
        $this->assertDatabaseHas('log_bentrok', [
            'jenis_bentrok' => 'BENTROK RUANG',
        ]);
    }

    /**
     * Test Kelas tidak boleh memiliki dua jadwal pada jam yang sama.
     */
    public function test_kelas_tidak_boleh_memiliki_dua_jadwal_pada_jam_yang_sama(): void
    {
        // Jadwal awal untuk Kelas A
        JadwalPelajaran::create([
            'id_tahun_ajaran' => $this->ta->id_tahun_ajaran,
            'id_kelas'        => $this->kelasA->id_kelas,
            'id_mapel'        => $this->mapel1->id_mapel,
            'id_guru'         => $this->guruA->id_guru,
            'id_ruang'        => $this->ruang1->id_ruang,
            'id_jam'          => $this->jam1->id_jam,
            'hari'            => 'Senin',
        ]);

        // Coba input mata pelajaran lain untuk Kelas A di jam yang sama
        $response = $this->post(route('jadwal-pelajaran.store'), [
            'id_tahun_ajaran' => $this->ta->id_tahun_ajaran,
            'id_kelas'        => $this->kelasA->id_kelas, // Kelas A sama!
            'id_mapel'        => $this->mapel2->id_mapel,
            'id_guru'         => $this->guruB->id_guru,
            'id_ruang'        => $this->ruang2->id_ruang,
            'id_jam'          => $this->jam1->id_jam,
            'hari'            => 'Senin',
        ]);

        $response->assertSessionHas('conflict_errors');
        $this->assertDatabaseCount('jadwal_pelajaran', 1);
        $this->assertDatabaseHas('log_bentrok', [
            'jenis_bentrok' => 'BENTROK KELAS',
        ]);
    }

    /**
     * Test Jadwal berhasil disimpan jika tidak ada bentrok.
     */
    public function test_jadwal_berhasil_disimpan_jika_tidak_bentrok(): void
    {
        $response = $this->post(route('jadwal-pelajaran.store'), [
            'id_tahun_ajaran' => $this->ta->id_tahun_ajaran,
            'id_kelas'        => $this->kelasA->id_kelas,
            'id_mapel'        => $this->mapel1->id_mapel,
            'id_guru'         => $this->guruA->id_guru,
            'id_ruang'        => $this->ruang1->id_ruang,
            'id_jam'          => $this->jam1->id_jam,
            'hari'            => 'Senin',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('jadwal_pelajaran', 1);
    }

    /**
     * Test Ujian mendeteksi bentrok ruang pada tanggal & jam yang bertabrakan.
     */
    public function test_jadwal_ujian_mendeteksi_bentrok_ruang(): void
    {
        // Ujian 1 di Ruang 1 tanggal 2026-11-10 jam 08:00 - 10:00
        JadwalUjian::create([
            'id_tahun_ajaran' => $this->ta->id_tahun_ajaran,
            'id_kelas'        => $this->kelasA->id_kelas,
            'id_mapel'        => $this->mapel1->id_mapel,
            'id_ruang'        => $this->ruang1->id_ruang,
            'jenis_ujian'     => 'UTS',
            'tanggal'         => '2026-11-10',
            'jam_mulai'       => '08:00:00',
            'jam_selesai'     => '10:00:00',
        ]);

        // Coba jadwalkan Ujian Kelas B di Ruang 1 pada tanggal yang sama jam 09:00 - 11:00 (overlap)
        $response = $this->post(route('jadwal-ujian.store'), [
            'id_tahun_ajaran' => $this->ta->id_tahun_ajaran,
            'id_kelas'        => $this->kelasB->id_kelas,
            'id_mapel'        => $this->mapel2->id_mapel,
            'id_ruang'        => $this->ruang1->id_ruang, // Ruang sama
            'jenis_ujian'     => 'UTS',
            'tanggal'         => '2026-11-10',            // Tanggal sama
            'jam_mulai'       => '09:00',
            'jam_selesai'     => '11:00',
        ]);

        $response->assertSessionHas('conflict_errors');
        $this->assertDatabaseCount('jadwal_ujian', 1);
        $this->assertDatabaseHas('log_bentrok', [
            'jenis_bentrok' => 'BENTROK RUANG',
            'jenis_jadwal'  => 'jadwal_ujian',
        ]);
    }

    /**
     * Test Pengawas Ujian tidak boleh bentrok di dua ujian yang bersamaan.
     */
    public function test_pengawas_ujian_tidak_boleh_bentrok_jam_mengawas(): void
    {
        // Ujian 1
        $ujian1 = JadwalUjian::create([
            'id_tahun_ajaran' => $this->ta->id_tahun_ajaran,
            'id_kelas'        => $this->kelasA->id_kelas,
            'id_mapel'        => $this->mapel1->id_mapel,
            'id_ruang'        => $this->ruang1->id_ruang,
            'jenis_ujian'     => 'UTS',
            'tanggal'         => '2026-11-10',
            'jam_mulai'       => '08:00:00',
            'jam_selesai'     => '10:00:00',
        ]);
        JadwalPengawas::create([
            'id_ujian' => $ujian1->id_ujian,
            'id_guru'  => $this->guruA->id_guru,
        ]);

        // Ujian 2 di ruang lain waktu yang sama
        $ujian2 = JadwalUjian::create([
            'id_tahun_ajaran' => $this->ta->id_tahun_ajaran,
            'id_kelas'        => $this->kelasB->id_kelas,
            'id_mapel'        => $this->mapel2->id_mapel,
            'id_ruang'        => $this->ruang2->id_ruang,
            'jenis_ujian'     => 'UTS',
            'tanggal'         => '2026-11-10',
            'jam_mulai'       => '08:00:00',
            'jam_selesai'     => '10:00:00',
        ]);

        $conflictService = app(\App\Services\ConflictDetectionService::class);
        $bentrok = $conflictService->cekBentrokPengawas($ujian2->id_ujian, $this->guruA->id_guru);

        $this->assertNotNull($bentrok);
        $this->assertStringContainsString('Bentrok Pengawas', $bentrok);
    }
}

