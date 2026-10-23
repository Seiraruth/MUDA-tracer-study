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
        $totalAlumni = Alumni::count();
        $totalResponses = TracerResponse::count();

        $counts = TracerResponse::query()
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

        $statistikRows = TracerResponse::query()
            ->join('alumni', 'alumni.id', '=', 'tracer_responses.alumni_id')
            ->select('alumni.tahun_lulus')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN tracer_responses.status_utama = 'Bekerja' THEN 1 ELSE 0 END) as bekerja")
            ->selectRaw("SUM(CASE WHEN tracer_responses.status_utama = 'Kuliah' THEN 1 ELSE 0 END) as kuliah")
            ->selectRaw("SUM(CASE WHEN tracer_responses.status_utama = 'Wirausaha' THEN 1 ELSE 0 END) as wirausaha")
            ->selectRaw("SUM(CASE WHEN tracer_responses.status_utama = 'Mencari Kerja' THEN 1 ELSE 0 END) as mencariKerja")
            ->groupBy('alumni.tahun_lulus')
            ->orderByDesc('alumni.tahun_lulus')
            ->get();

        $gajiKisaran = TracerResponse::query()
            ->where('status_utama', 'Bekerja')
            ->whereNotNull('kisaran_gaji')
            ->select('kisaran_gaji')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('kisaran_gaji')
            ->orderByDesc('total')
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
            'statistikRows',
            'gajiKisaran'
        ));
    }
}
