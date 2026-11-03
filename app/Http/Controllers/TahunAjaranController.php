<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TahunAjaranController extends Controller
{
    public function index(): View
    {
        $tahunList = TahunAjaran::orderByDesc('tahun')->orderBy('semester')->get();
        return view('tahun_ajaran.index', compact('tahunList'));
    }

    public function create(): View
    {
        return view('tahun_ajaran.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tahun'        => ['required', 'regex:/^\d{4}\/\d{4}$/'],
            'semester'     => 'required|in:Ganjil,Genap',
            'status_aktif' => 'boolean',
        ]);

        $statusAktif = $request->boolean('status_aktif');

        if ($statusAktif) {
            TahunAjaran::query()->update(['status_aktif' => false]);
        }

        TahunAjaran::create([
            'tahun'        => $validated['tahun'],
            'semester'     => $validated['semester'],
            'status_aktif' => $statusAktif,
        ]);

        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun Ajaran berhasil ditambahkan.');
    }

    public function edit(TahunAjaran $tahunAjaran): View
    {
        return view('tahun_ajaran.edit', compact('tahunAjaran'));
    }

    public function update(Request $request, TahunAjaran $tahunAjaran): RedirectResponse
    {
        $validated = $request->validate([
            'tahun'        => ['required', 'regex:/^\d{4}\/\d{4}$/'],
            'semester'     => 'required|in:Ganjil,Genap',
            'status_aktif' => 'boolean',
        ]);

        $statusAktif = $request->boolean('status_aktif');

        if ($statusAktif) {
            TahunAjaran::where('id_tahun_ajaran', '!=', $tahunAjaran->id_tahun_ajaran)->update(['status_aktif' => false]);
        }

        $tahunAjaran->update([
            'tahun'        => $validated['tahun'],
            'semester'     => $validated['semester'],
            'status_aktif' => $statusAktif,
        ]);

        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun Ajaran berhasil diperbarui.');
    }

    public function setAktif(TahunAjaran $tahunAjaran): RedirectResponse
    {
        TahunAjaran::query()->update(['status_aktif' => false]);
        $tahunAjaran->update(['status_aktif' => true]);

        return redirect()->route('tahun-ajaran.index')->with('success', "Tahun ajaran {$tahunAjaran->tahun} ({$tahunAjaran->semester}) telah diaktifkan.");
    }

    public function destroy(TahunAjaran $tahunAjaran): RedirectResponse
    {
        if ($tahunAjaran->jadwalPelajaran()->exists() || $tahunAjaran->jadwalUjian()->exists()) {
            return redirect()->route('tahun-ajaran.index')->with('error', 'Tahun ajaran tidak dapat dihapus karena memiliki jadwal pelajaran atau ujian.');
        }

        $tahunAjaran->delete();

        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun Ajaran berhasil dihapus.');
    }
}

