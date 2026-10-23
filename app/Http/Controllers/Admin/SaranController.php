<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TracerResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SaranController extends Controller
{
    public function index(Request $request)
    {
        $query = TracerResponse::with('alumni')
            ->whereNotNull('saran_sekolah')
            ->where('saran_sekolah', '!=', '');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('alumni', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tahun')) {
            $query->where('tahun_tracer', $request->input('tahun'));
        }

        $sarans = $query->latest()->paginate(10)->withQueryString();

        $tahunOptions = TracerResponse::distinct()
            ->whereNotNull('tahun_tracer')
            ->orderByDesc('tahun_tracer')
            ->pluck('tahun_tracer');

        return Inertia::render('Admin/Saran/Index', [
            'sarans' => $sarans,
            'filters' => [
                'search' => $request->input('search', ''),
                'tahun' => $request->input('tahun', ''),
            ],
            'tahunOptions' => $tahunOptions,
        ]);
    }
}