<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\JobPosting;
use App\Models\TracerResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Render the admin dashboard with tracer study statistics.
     */
    public function index()
    {
        $totalAlumni = Alumni::count();
        $totalResponses = TracerResponse::count();

        $bekerja = TracerResponse::where('status_utama', 'Bekerja')->count();
        $kuliah = TracerResponse::where('status_utama', 'Kuliah')->count();
        $wirausaha = TracerResponse::where('status_utama', 'Wirausaha')->count();
        $mencariKerja = TracerResponse::where('status_utama', 'Mencari Kerja')->count();

        $responseRate = $totalAlumni > 0 ? round(($totalResponses / $totalAlumni) * 100, 1) : 0;
        $totalBmw = $totalResponses > 0 ? $totalResponses : 1;

        $bekerjaPct = round(($bekerja / $totalBmw) * 100, 1);
        $kuliahPct = round(($kuliah / $totalBmw) * 100, 1);
        $wirausahaPct = round(($wirausaha / $totalBmw) * 100, 1);
        $mencariKerjaPct = round(($mencariKerja / $totalBmw) * 100, 1);

        $linearCount = TracerResponse::where('status_utama', 'Bekerja')
            ->where('linear_dengan_jurusan', true)
            ->count();
        $nonLinearCount = TracerResponse::where('status_utama', 'Bekerja')
            ->where('linear_dengan_jurusan', false)
            ->count();

        // Jurusan statistics breakdown
        $jurusanList = [
            'Teknik Komputer dan Jaringan',
            'Rekayasa Perangkat Lunak',
            'Multimedia',
            'Teknik Kendaraan Ringan',
            'Akuntansi dan Keuangan',
        ];

        $byJurusan = [];
        foreach ($jurusanList as $jurusan) {
            $totalJurusan = Alumni::where('jurusan', $jurusan)->count();
            $responsesJurusan = TracerResponse::whereHas('alumni', function ($q) use ($jurusan) {
                $q->where('jurusan', $jurusan);
            })->count();

            if ($totalJurusan > 0 || $responsesJurusan > 0) {
                $byJurusan[] = [
                    'jurusan' => $jurusan,
                    'total' => $totalJurusan,
                    'responses' => $responsesJurusan,
                    'rate' => $totalJurusan > 0 ? round(($responsesJurusan / $totalJurusan) * 100) : 0,
                ];
            }
        }

        $recentResponses = TracerResponse::with('alumni')
            ->latest()
            ->take(6)
            ->get();

        $recentJobs = JobPosting::latest()
            ->take(5)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'totalAlumni' => $totalAlumni,
                'totalResponses' => $totalResponses,
                'responseRate' => $responseRate,
                'bekerja' => $bekerja,
                'bekerjaPct' => $bekerjaPct,
                'kuliah' => $kuliah,
                'kuliahPct' => $kuliahPct,
                'wirausaha' => $wirausaha,
                'wirausahaPct' => $wirausahaPct,
                'mencariKerja' => $mencariKerja,
                'mencariKerjaPct' => $mencariKerjaPct,
            ],
            'linearitas' => [
                'linearCount' => $linearCount,
                'nonLinearCount' => $nonLinearCount,
                'totalWorking' => $bekerja,
            ],
            'byJurusan' => $byJurusan,
            'recentResponses' => $recentResponses,
            'recentJobs' => $recentJobs,
        ]);
    }
}
