<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KelasController extends Controller
{
    public function index(): View
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('kelas.index', compact('kelasList'));
    }

    public function create(): View
    {
        return view('kelas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:20',
            'tingkat'    => 'required|string|max:5',
            'jurusan'    => 'required|string|max:30',
        ]);

        Kelas::create($validated);

        return redirect()->route('kelas.index')->with('success', 'Data Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kela): View
    {
        $kelas = $kela;
        return view('kelas.edit', compact('kelas'));
    }

    public function update(Request $request, Kelas $kela): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:20',
            'tingkat'    => 'required|string|max:5',
            'jurusan'    => 'required|string|max:30',
        ]);

        $kela->update($validated);

        return redirect()->route('kelas.index')->with('success', 'Data Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela): RedirectResponse
    {
        if ($kela->jadwalPelajaran()->exists() || $kela->jadwalUjian()->exists()) {
            return redirect()->route('kelas.index')->with('error', 'Kelas tidak dapat dihapus karena masih memiliki jadwal pelajaran atau ujian.');
        }

        $kela->delete();

        return redirect()->route('kelas.index')->with('success', 'Data Kelas berhasil dihapus.');
    }
}

