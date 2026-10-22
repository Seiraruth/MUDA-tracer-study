<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JobPostingsController extends Controller
{
    /**
     * Display a listing of job postings.
     */
    public function index(Request $request)
    {
        $query = JobPosting::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('perusahaan', 'like', "%{$search}%")
                  ->orWhere('posisi', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        if ($request->has('active') && $request->input('active') !== '') {
            $query->where('is_active', $request->boolean('active'));
        }

        $jobs = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/JobPostings/Index', [
            'jobs' => $jobs,
            'filters' => [
                'search' => $request->input('search', ''),
                'active' => $request->input('active', ''),
            ],
        ]);
    }

    /**
     * Store newly created job posting.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'perusahaan' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'persyaratan' => 'required|string',
            'link_pendaftaran' => 'nullable|url|max:255',
            'kontak' => 'nullable|string|max:255',
            'deadline' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        JobPosting::create($validated);

        return back()->with('success', 'Lowongan kerja baru berhasil diposting.');
    }

    /**
     * Update job posting.
     */
    public function update(Request $request, JobPosting $jobPosting)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'perusahaan' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'persyaratan' => 'required|string',
            'link_pendaftaran' => 'nullable|url|max:255',
            'kontak' => 'nullable|string|max:255',
            'deadline' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $jobPosting->update($validated);

        return back()->with('success', 'Informasi lowongan kerja berhasil diperbarui.');
    }

    /**
     * Delete job posting.
     */
    public function destroy(JobPosting $jobPosting)
    {
        $jobPosting->delete();

        return back()->with('success', 'Lowongan kerja berhasil dihapus.');
    }
}
