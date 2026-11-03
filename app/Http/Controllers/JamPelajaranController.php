<?php

namespace App\Http\Controllers;

use App\Models\JamPelajaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JamPelajaranController extends Controller
{
    public function index(): View
    {
        $jamList = JamPelajaran::orderBy('jam_ke')->get();
        return view('jam_pelajaran.index', compact('jamList'));
    }

    public function create(): View
    {
        return view('jam_pelajaran.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'jam_ke'      => 'required|integer|min:1',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        JamPelajaran::create($validated);

        return redirect()->route('jam-pelajaran.index')->with('success', 'Master Jam Pelajaran berhasil ditambahkan.');
    }

    public function edit(JamPelajaran $jamPelajaran): View
    {
        return view('jam_pelajaran.edit', compact('jamPelajaran'));
    }

    public function update(Request $request, JamPelajaran $jamPelajaran): RedirectResponse
    {
        $validated = $request->validate([
            'jam_ke'      => 'required|integer|min:1',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        $jamPelajaran->update($validated);

        return redirect()->route('jam-pelajaran.index')->with('success', 'Master Jam Pelajaran berhasil diperbarui.');
    }

    public function destroy(JamPelajaran $jamPelajaran): RedirectResponse
    {
        if ($jamPelajaran->jadwalPelajaran()->exists()) {
            return redirect()->route('jam-pelajaran.index')->with('error', 'Jam pelajaran tidak dapat dihapus karena masih digunakan di jadwal pelajaran.');
        }

        $jamPelajaran->delete();

        return redirect()->route('jam-pelajaran.index')->with('success', 'Master Jam Pelajaran berhasil dihapus.');
    }
}

