<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MitraController extends Controller
{
    public function index(Request $request)
    {
        $query = Mitra::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_perusahaan', 'like', "%{$search}%")
                  ->orWhere('bidang_usaha', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%");
            });
        }

        if ($request->has('active') && $request->input('active') !== '') {
            $query->where('is_active', $request->boolean('active'));
        }

        $mitras = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Mitra/Index', [
            'mitras' => $mitras,
            'filters' => [
                'search' => $request->input('search', ''),
                'active' => $request->input('active', ''),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'bidang_usaha' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'kontak_person' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Mitra::create($validated);

        return back()->with('success', 'Data mitra berhasil ditambahkan.');
    }

    public function update(Request $request, Mitra $mitra)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'bidang_usaha' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'kontak_person' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $mitra->update($validated);

        return back()->with('success', 'Data mitra berhasil diperbarui.');
    }

    public function destroy(Mitra $mitra)
    {
        $mitra->delete();

        return back()->with('success', 'Data mitra berhasil dihapus.');
    }
}