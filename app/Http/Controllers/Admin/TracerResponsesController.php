<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TracerResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TracerResponsesController extends Controller
{
    /**
     * Display a listing of tracer study questionnaire responses.
     */
    public function index(Request $request)
    {
        $query = TracerResponse::with('alumni');

        if ($request->filled('status')) {
            $query->where('status_utama', $request->input('status'));
        }

        if ($request->filled('tahun')) {
            $query->where('tahun_tracer', $request->input('tahun'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('alumni', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('jurusan', 'like', "%{$search}%");
            })->orWhere('nama_perusahaan', 'like', "%{$search}%")
              ->orWhere('nama_kampus', 'like', "%{$search}%")
              ->orWhere('nama_usaha', 'like', "%{$search}%");
        }

        $responses = $query->latest()->paginate(10)->withQueryString();

        $tahunOptions = TracerResponse::distinct()->pluck('tahun_tracer')->filter()->sortDesc()->values();

        return Inertia::render('Admin/TracerResponses/Index', [
            'responses' => $responses,
            'filters' => [
                'status' => $request->input('status', ''),
                'tahun' => $request->input('tahun', ''),
                'search' => $request->input('search', ''),
            ],
            'tahunOptions' => $tahunOptions,
        ]);
    }

    /**
     * Delete a tracer response.
     */
    public function destroy(TracerResponse $tracerResponse)
    {
        $tracerResponse->delete();

        return back()->with('success', 'Hasil kuesioner berhasil dihapus.');
    }
}
