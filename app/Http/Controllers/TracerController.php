<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\JobPosting;
use App\Models\TracerResponse;
use Illuminate\Http\Request;

class TracerController extends Controller
{
    /**
     * Homepage with Statistics and Job Postings overview.
     */
    public function index()
    {
        $totalAlumni = Alumni::count();
        $totalResponses = TracerResponse::count();
        $bekerja = TracerResponse::where('status_utama', 'Bekerja')->count();
        $kuliah = TracerResponse::where('status_utama', 'Kuliah')->count();
        $wirausaha = TracerResponse::where('status_utama', 'Wirausaha')->count();
        $mencariKerja = TracerResponse::where('status_utama', 'Mencari Kerja')->count();

        $bekerjaPct = $totalResponses > 0 ? round(($bekerja / $totalResponses) * 100) : 0;
        $kuliahPct = $totalResponses > 0 ? round(($kuliah / $totalResponses) * 100) : 0;
        $wirausahaPct = $totalResponses > 0 ? round(($wirausaha / $totalResponses) * 100) : 0;

        $jobs = JobPosting::where('is_active', true)
            ->latest()
            ->take(4)
            ->get();

        return view('welcome', compact(
            'totalAlumni',
            'totalResponses',
            'bekerja',
            'kuliah',
            'wirausaha',
            'mencariKerja',
            'bekerjaPct',
            'kuliahPct',
            'wirausahaPct',
            'jobs'
        ));
    }

    /**
     * Show Alumni Login / Identity Check form.
     */
    public function showLogin()
    {
        if (session()->has('alumni_id')) {
            return redirect()->route('tracer.kuesioner');
        }
        return view('alumni.login');
    }

    /**
     * Authenticate Alumni via NISN + Tanggal Lahir.
     */
    public function processLogin(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string',
            'tanggal_lahir' => 'required|date',
        ], [
            'nisn.required' => 'NISN wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
        ]);

        $alumni = Alumni::where('nisn', trim($request->nisn))
            ->whereDate('tanggal_lahir', $request->tanggal_lahir)
            ->first();

        if (!$alumni) {
            return back()->withInput()->withErrors([
                'auth' => 'Data Alumni tidak ditemukan. Pastikan kombinasi NISN dan Tanggal Lahir sudah benar.',
            ]);
        }

        session(['alumni_id' => $alumni->id]);

        return redirect()->route('tracer.kuesioner')->with('success', 'Selamat datang, ' . $alumni->nama . '!');
    }

    /**
     * Show Dynamic Questionnaire Form.
     */
    public function showKuesioner()
    {
        $alumniId = session('alumni_id');
        if (!$alumniId) {
            return redirect()->route('alumni.login')->withErrors(['auth' => 'Silakan verifikasi NISN terlebih dahulu.']);
        }

        $alumni = Alumni::findOrFail($alumniId);
        $existingResponse = TracerResponse::where('alumni_id', $alumni->id)
            ->where('tahun_tracer', date('Y'))
            ->first();

        return view('alumni.kuesioner', compact('alumni', 'existingResponse'));
    }

    /**
     * Store or Update Questionnaire Response.
     */
    public function storeKuesioner(Request $request)
    {
        $alumniId = session('alumni_id');
        if (!$alumniId) {
            return redirect()->route('alumni.login');
        }

        $alumni = Alumni::findOrFail($alumniId);

        // Update contact information
        $alumni->update($request->only(['no_hp', 'email', 'alamat']));

        $validated = $request->validate([
            'status_utama' => 'required|in:Bekerja,Kuliah,Wirausaha,Mencari Kerja',
            'nama_perusahaan' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'kisaran_gaji' => 'nullable|string|max:255',
            'linear_dengan_jurusan' => 'nullable|boolean',
            'nama_kampus' => 'nullable|string|max:255',
            'program_studi' => 'nullable|string|max:255',
            'nama_usaha' => 'nullable|string|max:255',
            'bidang_usaha' => 'nullable|string|max:255',
            'omset_bulanan' => 'nullable|string|max:255',
            'saran_sekolah' => 'nullable|string',
        ]);

        TracerResponse::updateOrCreate(
            [
                'alumni_id' => $alumni->id,
                'tahun_tracer' => date('Y'),
            ],
            array_merge($validated, [
                'linear_dengan_jurusan' => $request->has('linear_dengan_jurusan') ? 1 : 0,
            ])
        );

        return redirect()->route('tracer.sukses');
    }

    /**
     * Success page after submitting questionnaire.
     */
    public function success()
    {
        $alumniId = session('alumni_id');
        if (!$alumniId) {
            return redirect()->route('alumni.login');
        }

        $alumni = Alumni::findOrFail($alumniId);
        $jobs = JobPosting::where('is_active', true)->latest()->take(3)->get();

        return view('alumni.sukses', compact('alumni', 'jobs'));
    }

    /**
     * List Job Postings (Loker BKK).
     */
    public function loker()
    {
        $jobs = JobPosting::where('is_active', true)->latest()->get();
        return view('alumni.loker', compact('jobs'));
    }

    /**
     * Logout Alumni Session.
     */
    public function logout()
    {
        session()->forget('alumni_id');
        return redirect()->route('home')->with('info', 'Sesi Anda telah berakhir.');
    }
}
