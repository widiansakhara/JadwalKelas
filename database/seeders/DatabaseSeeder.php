<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\GuruMapel;
use App\Models\JadwalPelajaran;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Ruang;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with MTs Al-Ghozaly Siwatu data.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        JadwalPelajaran::truncate();
        GuruMapel::truncate();
        Guru::truncate();
        MataPelajaran::truncate();
        Kelas::truncate();
        Ruang::truncate();
        JamPelajaran::truncate();
        TahunAjaran::truncate();
        Schema::enableForeignKeyConstraints();

        // 1. Tahun Ajaran (MTs Al-Ghozaly Siwatu 2026/2027 Semester Gasal/Ganjil)
        TahunAjaran::create([
            'tahun'        => '2026/2027',
            'semester'     => 'Ganjil',
            'status_aktif' => true,
        ]);

        TahunAjaran::create([
            'tahun'        => '2026/2027',
            'semester'     => 'Genap',
            'status_aktif' => false,
        ]);

        // 2. Jam Pelajaran (Sesuai Jadwal MTs Al-Ghozaly Siwatu)
        $jamData = [
            ['jam_ke' => 1, 'jam_mulai' => '07:30:00', 'jam_selesai' => '08:10:00'],
            ['jam_ke' => 2, 'jam_mulai' => '08:10:00', 'jam_selesai' => '08:50:00'],
            ['jam_ke' => 3, 'jam_mulai' => '08:50:00', 'jam_selesai' => '09:30:00'],
            ['jam_ke' => 4, 'jam_mulai' => '09:45:00', 'jam_selesai' => '10:25:00'],
            ['jam_ke' => 5, 'jam_mulai' => '10:25:00', 'jam_selesai' => '11:05:00'],
            ['jam_ke' => 6, 'jam_mulai' => '11:05:00', 'jam_selesai' => '11:45:00'],
            ['jam_ke' => 7, 'jam_mulai' => '12:15:00', 'jam_selesai' => '12:55:00'],
            ['jam_ke' => 8, 'jam_mulai' => '12:55:00', 'jam_selesai' => '13:35:00'],
        ];
        foreach ($jamData as $jd) {
            JamPelajaran::create($jd);
        }

        // 3. Kelas (Kelas VII, VIII, IX MTs Al-Ghozaly Siwatu)
        $kelasData = [
            ['nama_kelas' => 'VII-A',  'tingkat' => 'VII',  'jurusan' => 'Reguler'],
            ['nama_kelas' => 'VII-B',  'tingkat' => 'VII',  'jurusan' => 'Reguler'],
            ['nama_kelas' => 'VII-C',  'tingkat' => 'VII',  'jurusan' => 'Reguler'],
            ['nama_kelas' => 'VIII-A', 'tingkat' => 'VIII', 'jurusan' => 'Reguler'],
            ['nama_kelas' => 'VIII-B', 'tingkat' => 'VIII', 'jurusan' => 'Reguler'],
            ['nama_kelas' => 'VIII-C', 'tingkat' => 'VIII', 'jurusan' => 'Reguler'],
            ['nama_kelas' => 'IX-A',   'tingkat' => 'IX',   'jurusan' => 'Reguler'],
            ['nama_kelas' => 'IX-B',   'tingkat' => 'IX',   'jurusan' => 'Reguler'],
            ['nama_kelas' => 'IX-C',   'tingkat' => 'IX',   'jurusan' => 'Reguler'],
            ['nama_kelas' => 'IX-D',   'tingkat' => 'IX',   'jurusan' => 'Reguler'],
        ];
        foreach ($kelasData as $kd) {
            Kelas::create($kd);
        }

        // 4. Ruang Kelas & Laboratorium
        $ruangData = [
            ['kode_ruang' => 'R-7A',    'nama_ruang' => 'Ruang Kelas VII-A',      'jenis' => 'Teori',        'kapasitas' => 32],
            ['kode_ruang' => 'R-7B',    'nama_ruang' => 'Ruang Kelas VII-B',      'jenis' => 'Teori',        'kapasitas' => 32],
            ['kode_ruang' => 'R-7C',    'nama_ruang' => 'Ruang Kelas VII-C',      'jenis' => 'Teori',        'kapasitas' => 32],
            ['kode_ruang' => 'R-8A',    'nama_ruang' => 'Ruang Kelas VIII-A',     'jenis' => 'Teori',        'kapasitas' => 32],
            ['kode_ruang' => 'R-8B',    'nama_ruang' => 'Ruang Kelas VIII-B',     'jenis' => 'Teori',        'kapasitas' => 32],
            ['kode_ruang' => 'R-8C',    'nama_ruang' => 'Ruang Kelas VIII-C',     'jenis' => 'Teori',        'kapasitas' => 32],
            ['kode_ruang' => 'R-9A',    'nama_ruang' => 'Ruang Kelas IX-A',       'jenis' => 'Teori',        'kapasitas' => 32],
            ['kode_ruang' => 'R-9B',    'nama_ruang' => 'Ruang Kelas IX-B',       'jenis' => 'Teori',        'kapasitas' => 32],
            ['kode_ruang' => 'R-9C',    'nama_ruang' => 'Ruang Kelas IX-C',       'jenis' => 'Teori',        'kapasitas' => 32],
            ['kode_ruang' => 'R-9D',    'nama_ruang' => 'Ruang Kelas IX-D',       'jenis' => 'Teori',        'kapasitas' => 32],
            ['kode_ruang' => 'LAB-KOM', 'nama_ruang' => 'Laboratorium Komputer',  'jenis' => 'Laboratorium', 'kapasitas' => 36],
            ['kode_ruang' => 'LAB-IPA', 'nama_ruang' => 'Laboratorium IPA',       'jenis' => 'Laboratorium', 'kapasitas' => 36],
        ];
        foreach ($ruangData as $rd) {
            Ruang::create($rd);
        }

        // 5. Master Mata Pelajaran (Kode 1 s/d 20 sesuai tabel jadwal)
        $mapelRaw = [
            1  => ['kode' => 'MP-01', 'nama' => "Al-Qur'an Hadist",              'jam' => 2],
            2  => ['kode' => 'MP-02', 'nama' => 'Aqidah Akhlaq',                 'jam' => 2],
            3  => ['kode' => 'MP-03', 'nama' => 'Fiqh',                          'jam' => 2],
            4  => ['kode' => 'MP-04', 'nama' => 'SKI',                           'jam' => 2],
            5  => ['kode' => 'MP-05', 'nama' => 'PKn',                           'jam' => 3],
            6  => ['kode' => 'MP-06', 'nama' => 'Bahasa Indonesia',              'jam' => 6],
            7  => ['kode' => 'MP-07', 'nama' => 'Bahasa Arab',                   'jam' => 3],
            8  => ['kode' => 'MP-08', 'nama' => 'Bahasa Inggris',                'jam' => 4],
            9  => ['kode' => 'MP-09', 'nama' => 'Matematika',                    'jam' => 5],
            10 => ['kode' => 'MP-10', 'nama' => 'IPA',                           'jam' => 5],
            11 => ['kode' => 'MP-11', 'nama' => 'IPS',                           'jam' => 4],
            12 => ['kode' => 'MP-12', 'nama' => 'Seni Budaya',                   'jam' => 2],
            13 => ['kode' => 'MP-13', 'nama' => 'Penjasorkes',                   'jam' => 2],
            14 => ['kode' => 'MP-14', 'nama' => 'Informatika (14)',              'jam' => 2],
            15 => ['kode' => 'MP-15', 'nama' => 'Ke-NU-an',                      'jam' => 1],
            16 => ['kode' => 'MP-16', 'nama' => 'Bahasa Jawa',                   'jam' => 1],
            17 => ['kode' => 'MP-17', 'nama' => 'Informatika',                   'jam' => 2],
            18 => ['kode' => 'MP-18', 'nama' => 'Kokurikuler',                   'jam' => 4],
            19 => ['kode' => 'MP-19', 'nama' => 'Takhfid',                       'jam' => 2],
            20 => ['kode' => 'MP-20', 'nama' => 'Bimbingan Konseling/Perwalian', 'jam' => 1],
        ];

        $mapels = [];
        foreach ($mapelRaw as $no => $m) {
            $mapels[$no] = MataPelajaran::create([
                'kode_mapel'     => $m['kode'],
                'nama_mapel'     => $m['nama'],
                'jam_per_minggu' => $m['jam'],
            ]);
        }

        // 6. Master Guru (Kode A s/d R) beserta Mata Pelajaran yang Diajar
        $guruRaw = [
            [
                'kode'  => 'A',
                'nip'   => '197711262007102001',
                'nama'  => '[A] Hj. Khusnul Khotimah, Alhafidzoh, M.Pd.',
                'email' => 'khusnul.khotimah@mtsalghozaly.sch.id',
                'no_hp' => '081200000001',
                'mapel' => [7], // A7: Bahasa Arab
            ],
            [
                'kode'  => 'B',
                'nip'   => 'KODE-B-002',
                'nama'  => '[B] Murod, S.Ag',
                'email' => 'murod@mtsalghozaly.sch.id',
                'no_hp' => '081200000002',
                'mapel' => [],
            ],
            [
                'kode'  => 'C',
                'nip'   => 'KODE-C-003',
                'nama'  => '[C] Titik Muzayinah, S.Ag.',
                'email' => 'titik.muzayinah@mtsalghozaly.sch.id',
                'no_hp' => '081200000003',
                'mapel' => [4], // C4: SKI
            ],
            [
                'kode'  => 'D',
                'nip'   => 'KODE-D-004',
                'nama'  => '[D] Usykur Makrufah, S.Ag',
                'email' => 'usykur.makrufah@mtsalghozaly.sch.id',
                'no_hp' => '081200000004',
                'mapel' => [10], // D10: IPA
            ],
            [
                'kode'  => 'E',
                'nip'   => 'KODE-E-005',
                'nama'  => '[E] Hj. Inganatul Azizah, S.Pd.I',
                'email' => 'inganatul.azizah@mtsalghozaly.sch.id',
                'no_hp' => '081200000005',
                'mapel' => [2, 16, 18], // E2: Aqidah Akhlaq, E16: Bahasa Jawa, E2 Ko: Kokurikuler
            ],
            [
                'kode'  => 'F',
                'nip'   => 'KODE-F-006',
                'nama'  => '[F] Zuhruful Janah, Alhafidzoh, S.Pd.I',
                'email' => 'zuhruful.janah@mtsalghozaly.sch.id',
                'no_hp' => '081200000006',
                'mapel' => [3, 7, 18], // F3: Fiqh, F7: Bahasa Arab, F3 Ko: Kokurikuler
            ],
            [
                'kode'  => 'G',
                'nip'   => 'KODE-G-007',
                'nama'  => '[G] Elfi Sukeni, S.Pd.',
                'email' => 'elfi.sukeni@mtsalghozaly.sch.id',
                'no_hp' => '081200000007',
                'mapel' => [6, 18], // G6: Bahasa Indonesia, G6 Ko: Kokurikuler
            ],
            [
                'kode'  => 'H',
                'nip'   => 'KODE-H-008',
                'nama'  => '[H] Ifa Ira Anggraeni, S.S.',
                'email' => 'ifa.anggraeni@mtsalghozaly.sch.id',
                'no_hp' => '081200000008',
                'mapel' => [11, 18], // H11: IPS, H11 Ko: Kokurikuler
            ],
            [
                'kode'  => 'I',
                'nip'   => 'KODE-I-009',
                'nama'  => '[I] Emi Rahmawati, S.Pd.',
                'email' => 'emi.rahmawati@mtsalghozaly.sch.id',
                'no_hp' => '081200000009',
                'mapel' => [9], // I 9: Matematika
            ],
            [
                'kode'  => 'J',
                'nip'   => 'KODE-J-010',
                'nama'  => '[J] Akimuddin',
                'email' => 'akimuddin@mtsalghozaly.sch.id',
                'no_hp' => '081200000010',
                'mapel' => [1, 14, 17, 18], // J 1: Al-Qur'an Hadist, J17: Informatika, J17 Ko: Kokurikuler
            ],
            [
                'kode'  => 'K',
                'nip'   => 'KODE-K-011',
                'nama'  => '[K] Restin Kusumandari, S.Pd.',
                'email' => 'restin.kusumandari@mtsalghozaly.sch.id',
                'no_hp' => '081200000011',
                'mapel' => [8, 18], // K8: Bahasa Inggris, K8 Ko: Kokurikuler
            ],
            [
                'kode'  => 'L',
                'nip'   => 'KODE-L-012',
                'nama'  => "[L] Anittabi' Millata Hanifah, S.Ag. Al Khafidzah",
                'email' => 'anittabi.hanifah@mtsalghozaly.sch.id',
                'no_hp' => '081200000012',
                'mapel' => [1, 15, 18, 19], // L1 Ko: Al-Qur'an Hadist/Kokurikuler, L15: Ke-NU-an, L19: Takhfid
            ],
            [
                'kode'  => 'M',
                'nip'   => 'KODE-M-013',
                'nama'  => '[M] Irvan Rumanto, S.Pd.I.',
                'email' => 'irvan.rumanto@mtsalghozaly.sch.id',
                'no_hp' => '081200000013',
                'mapel' => [1, 12, 18], // M1: Al-Qur'an Hadist, M12: Seni Budaya, M12 Ko: Kokurikuler
            ],
            [
                'kode'  => 'N',
                'nip'   => 'KODE-N-014',
                'nama'  => '[N] Triana Oktavia Ningtyas, Alhafidzoh, S.Pd.',
                'email' => 'triana.oktavia@mtsalghozaly.sch.id',
                'no_hp' => '081200000014',
                'mapel' => [6, 10, 18], // N6: Bahasa Indonesia, N10: IPA, N10 Ko: Kokurikuler
            ],
            [
                'kode'  => 'O',
                'nip'   => 'KODE-O-015',
                'nama'  => '[O] Najla Zaul Fadaucas, Alhafidzoh, S.Pd.',
                'email' => 'najla.fadaucas@mtsalghozaly.sch.id',
                'no_hp' => '081200000015',
                'mapel' => [9, 18, 19], // O9: Matematika, O19: Takhfid, O9 Ko: Kokurikuler
            ],
            [
                'kode'  => 'P',
                'nip'   => 'KODE-P-016',
                'nama'  => '[P] Livia Setyawati, S.H.',
                'email' => 'livia.setyawati@mtsalghozaly.sch.id',
                'no_hp' => '081200000016',
                'mapel' => [5, 18], // P5: PKn, P5 Ko: Kokurikuler
            ],
            [
                'kode'  => 'Q',
                'nip'   => 'KODE-Q-017',
                'nama'  => '[Q] Muhammad Firdaus Alfin Faza, S.Pd. Gr.',
                'email' => 'firdaus.alfin@mtsalghozaly.sch.id',
                'no_hp' => '081200000017',
                'mapel' => [13, 18], // Q13: Penjasorkes, Q13 Ko: Kokurikuler
            ],
            [
                'kode'  => 'R',
                'nip'   => 'KODE-R-018',
                'nama'  => '[R] Nuzila Nur Kholishoh, S.Pd.',
                'email' => 'nuzila.kholishoh@mtsalghozaly.sch.id',
                'no_hp' => '081200000018',
                'mapel' => [20], // R20: Bimbingan Konseling/Perwalian
            ],
        ];

        foreach ($guruRaw as $g) {
            $guru = Guru::create([
                'nip'   => $g['nip'],
                'nama'  => $g['nama'],
                'email' => $g['email'],
                'no_hp' => $g['no_hp'],
            ]);

            foreach ($g['mapel'] as $mapelNo) {
                if (isset($mapels[$mapelNo])) {
                    GuruMapel::create([
                        'id_guru'  => $guru->id_guru,
                        'id_mapel' => $mapels[$mapelNo]->id_mapel,
                    ]);
                }
            }
        }
    }
}
