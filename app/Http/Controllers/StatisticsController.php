<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\TracerResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $tahunLulus = $request->validate([
            'tahun_lulus' => ['nullable', 'integer', 'digits:4'],
        ])['tahun_lulus'] ?? null;

        $alumniQuery = Alumni::query();
        if ($tahunLulus !== null) {
            $alumniQuery->where('tahun_lulus', $tahunLulus);
        }

        $totalAlumni = (clone $alumniQuery)->count();
        $responseQuery = TracerResponse::query()->whereHas('alumni', function ($query) use ($tahunLulus) {
            if ($tahunLulus !== null) {
                $query->where('tahun_lulus', $tahunLulus);
            }
        });
        $totalResponses = (clone $responseQuery)->count();

        $counts = (clone $responseQuery)
            ->select('status_utama', DB::raw('COUNT(*) as aggregate'))
            ->groupBy('status_utama')
            ->pluck('aggregate', 'status_utama');

        $bekerja = (int) ($counts['Bekerja'] ?? 0);
        $kuliah = (int) ($counts['Kuliah'] ?? 0);
        $wirausaha = (int) ($counts['Wirausaha'] ?? 0);
        $mencariKerja = (int) ($counts['Mencari Kerja'] ?? 0);

        $bekerjaPct = $totalResponses > 0 ? round(($bekerja / $totalResponses) * 100) : 0;
        $kuliahPct = $totalResponses > 0 ? round(($kuliah / $totalResponses) * 100) : 0;
        $wirausahaPct = $totalResponses > 0 ? round(($wirausaha / $totalResponses) * 100) : 0;
        $mencariKerjaPct = $totalResponses > 0 ? round(($mencariKerja / $totalResponses) * 100) : 0;

        $tahunLulusOptions = Alumni::query()
            ->whereNotNull('tahun_lulus')
            ->distinct()
            ->orderByDesc('tahun_lulus')
            ->pluck('tahun_lulus');

        $statistikRows = (clone $responseQuery)
            ->join('alumni', 'alumni.id', '=', 'tracer_responses.alumni_id')
            ->select('alumni.tahun_lulus')
            ->selectRaw('COUNT(DISTINCT alumni.id) as total_alumni')
            ->selectRaw("SUM(CASE WHEN tracer_responses.status_utama = 'Bekerja' THEN 1 ELSE 0 END) as bekerja")
            ->selectRaw("SUM(CASE WHEN tracer_responses.status_utama = 'Kuliah' THEN 1 ELSE 0 END) as kuliah")
            ->selectRaw("SUM(CASE WHEN tracer_responses.status_utama = 'Wirausaha' THEN 1 ELSE 0 END) as wirausaha")
            ->selectRaw("SUM(CASE WHEN tracer_responses.status_utama = 'Mencari Kerja' THEN 1 ELSE 0 END) as mencari_kerja")
            ->groupBy('alumni.tahun_lulus')
            ->orderByDesc('alumni.tahun_lulus')
            ->get();

        $gajiKisaran = (clone $responseQuery)
            ->where('status_utama', 'Bekerja')
            ->whereNotNull('kisaran_gaji')
            ->select('kisaran_gaji as label')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('kisaran_gaji')
            ->orderByDesc('count')
            ->get();

        return view('statistik', compact(
            'totalAlumni',
            'totalResponses',
            'bekerja',
            'kuliah',
            'wirausaha',
            'mencariKerja',
            'bekerjaPct',
            'kuliahPct',
            'wirausahaPct',
            'mencariKerjaPct',
            'tahunLulusOptions',
            'tahunLulus',
            'statistikRows',
            'gajiKisaran'
        ));
    }
}
