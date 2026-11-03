<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JadwalGuruController;
use App\Http\Controllers\JadwalPelajaranController;
use App\Http\Controllers\JadwalRuangController;
use App\Http\Controllers\JadwalUjianController;
use App\Http\Controllers\JamPelajaranController;
use App\Http\Controllers\KegiatanSekolahController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LogBentrokController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\PortalSiswaController;
use App\Http\Controllers\RuangController;
use App\Http\Controllers\TahunAjaranController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Portal Khusus Siswa & Orang Tua (Publik tanpa perlu edit/hapus)
Route::get('/portal-siswa', [PortalSiswaController::class, 'index'])->name('portal.siswa');
Route::get('/portal-siswa/cetak', [PortalSiswaController::class, 'cetak'])->name('portal.siswa.cetak');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Master Data
Route::resource('ruang', RuangController::class);
Route::resource('guru', GuruController::class);
Route::resource('mata-pelajaran', MataPelajaranController::class);
Route::resource('kelas', KelasController::class);
Route::resource('jam-pelajaran', JamPelajaranController::class);

Route::post('tahun-ajaran/{tahunAjaran}/set-aktif', [TahunAjaranController::class, 'setAktif'])->name('tahun-ajaran.set-aktif');
Route::resource('tahun-ajaran', TahunAjaranController::class);

// Jadwal Pelajaran (Kelas)
Route::get('jadwal-pelajaran/cetak', [JadwalPelajaranController::class, 'cetak'])->name('jadwal-pelajaran.cetak');
Route::resource('jadwal-pelajaran', JadwalPelajaranController::class);

// Jadwal Guru & Jadwal Ruang (Monitoring & Timetable)
Route::get('jadwal-guru', [JadwalGuruController::class, 'index'])->name('jadwal-guru.index');
Route::get('jadwal-guru/cetak', [JadwalGuruController::class, 'cetak'])->name('jadwal-guru.cetak');

Route::get('jadwal-ruang', [JadwalRuangController::class, 'index'])->name('jadwal-ruang.index');
Route::get('jadwal-ruang/cetak', [JadwalRuangController::class, 'cetak'])->name('jadwal-ruang.cetak');

// Jadwal Ujian & Pengawas
Route::get('jadwal-ujian/cetak', [JadwalUjianController::class, 'cetak'])->name('jadwal-ujian.cetak');
Route::resource('jadwal-ujian', JadwalUjianController::class);

// Jadwal Kegiatan Sekolah
Route::get('kegiatan-sekolah/cetak', [KegiatanSekolahController::class, 'cetak'])->name('kegiatan-sekolah.cetak');
Route::resource('kegiatan-sekolah', KegiatanSekolahController::class);

// Log & Deteksi Bentrok Otomatis
Route::get('log-bentrok', [LogBentrokController::class, 'index'])->name('log-bentrok.index');
Route::post('log-bentrok/pindai', [LogBentrokController::class, 'pindai'])->name('log-bentrok.pindai');
Route::post('log-bentrok/clear', [LogBentrokController::class, 'clear'])->name('log-bentrok.clear');
Route::get('log-bentrok/cetak', [LogBentrokController::class, 'cetak'])->name('log-bentrok.cetak');
Route::delete('log-bentrok/{logBentrok}', [LogBentrokController::class, 'destroy'])->name('log-bentrok.destroy');
