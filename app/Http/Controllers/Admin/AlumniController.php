<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use Illuminate\Http\Request;
use Inertia\Inertia;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Reader\CSV\Reader as CSVReader;
use OpenSpout\Reader\XLSX\Reader as XLSXReader;
use OpenSpout\Writer\XLSX\Writer as XLSXWriter;

class AlumniController extends Controller
{
    /**
     * Display a listing of alumni data.
     */
    public function index(Request $request)
    {
        $query = Alumni::query()->with('responses');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jurusan')) {
            $query->where('jurusan', $request->input('jurusan'));
        }

        if ($request->filled('tahun')) {
            $query->where('tahun_lulus', $request->input('tahun'));
        }

        $alumni = $query->latest()->paginate(10)->withQueryString();

        $jurusanOptions = Alumni::distinct()->pluck('jurusan')->filter()->values();
        $tahunOptions = Alumni::distinct()->pluck('tahun_lulus')->filter()->sortDesc()->values();

        return Inertia::render('Admin/Alumni/Index', [
            'alumni' => $alumni,
            'filters' => [
                'search' => $request->input('search', ''),
                'jurusan' => $request->input('jurusan', ''),
                'tahun' => $request->input('tahun', ''),
            ],
            'jurusanOptions' => $jurusanOptions,
            'tahunOptions' => $tahunOptions,
        ]);
    }

    /**
     * Store newly created alumni record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn' => 'required|string|unique:alumni,nisn',
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jurusan' => 'required|string|max:255',
            'tahun_lulus' => 'required|integer|min:2000|max:2099',
            'no_hp' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
        ]);

        Alumni::create($validated);

        return back()->with('success', 'Data alumni berhasil ditambahkan.');
    }

    /**
     * Update existing alumni record.
     */
    public function update(Request $request, Alumni $alumni)
    {
        $validated = $request->validate([
            'nisn' => 'required|string|unique:alumni,nisn,' . $alumni->id,
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jurusan' => 'required|string|max:255',
            'tahun_lulus' => 'required|integer|min:2000|max:2099',
            'no_hp' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
        ]);

        $alumni->update($validated);

        return back()->with('success', 'Data alumni berhasil diperbarui.');
    }

    /**
     * Remove the specified alumni record.
     */
    public function destroy(Alumni $alumni)
    {
        $alumni->delete();

        return back()->with('success', 'Data alumni berhasil dihapus.');
    }

    /**
     * Export alumni data to an Excel (.xlsx) file.
     */
    public function export(Request $request)
    {
        $query = Alumni::query()->with('responses');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jurusan')) {
            $query->where('jurusan', $request->input('jurusan'));
        }

        if ($request->filled('tahun')) {
            $query->where('tahun_lulus', $request->input('tahun'));
        }

        $alumni = $query->latest()->get();

        $tempPath = tempnam(sys_get_temp_dir(), 'alumni_export_') . '.xlsx';
        $writer = new XLSXWriter();
        $writer->openToFile($tempPath);

        // Header Row
        $writer->addRow(Row::fromValues([
            'NISN',
            'Nama Lengkap',
            'Tanggal Lahir',
            'Jurusan / Program Keahlian',
            'Tahun Lulus (Angkatan)',
            'No. WhatsApp / HP',
            'Email',
            'Alamat',
            'Status Tracer Study (BMW)',
            'Detail Aktivitas Tracer',
        ]));

        foreach ($alumni as $a) {
            $tracer = $a->responses->first();
            $status = $tracer ? $tracer->status_utama : 'Belum Mengisi';
            $detail = '';
            if ($tracer) {
                if ($tracer->status_utama === 'Bekerja') {
                    $detail = "Perusahaan: {$tracer->nama_perusahaan} | Jabatan: {$tracer->jabatan} | Gaji: {$tracer->kisaran_gaji}";
                } elseif ($tracer->status_utama === 'Kuliah') {
                    $detail = "Kampus: {$tracer->nama_kampus} | Prodi: {$tracer->program_studi}";
                } elseif ($tracer->status_utama === 'Wirausaha') {
                    $detail = "Usaha: {$tracer->nama_usaha} | Bidang: {$tracer->bidang_usaha} | Omset: {$tracer->omset_bulanan}";
                } else {
                    $detail = 'Sedang mencari lowongan kerja';
                }
            }

            $writer->addRow(Row::fromValues([
                (string) $a->nisn,
                (string) $a->nama,
                $a->tanggal_lahir ? $a->tanggal_lahir->format('Y-m-d') : '',
                (string) $a->jurusan,
                (int) $a->tahun_lulus,
                (string) ($a->no_hp ?? ''),
                (string) ($a->email ?? ''),
                (string) ($a->alamat ?? ''),
                $status,
                $detail,
            ]));
        }

        $writer->close();

        return response()->download($tempPath, 'data-alumni-smk-' . date('Y-m-d') . '.xlsx')->deleteFileAfterSend(true);
    }

    /**
     * Download Excel template for importing alumni.
     */
    public function template()
    {
        $tempPath = tempnam(sys_get_temp_dir(), 'template_alumni_') . '.xlsx';
        $writer = new XLSXWriter();
        $writer->openToFile($tempPath);

        $writer->addRow(Row::fromValues([
            'NISN (Wajib)',
            'Nama Lengkap (Wajib)',
            'Tanggal Lahir YYYY-MM-DD (Wajib)',
            'Jurusan (Wajib)',
            'Tahun Lulus (Wajib)',
            'No WhatsApp / HP (Opsional)',
            'Email (Opsional)',
            'Alamat (Opsional)',
        ]));

        $writer->addRow(Row::fromValues([
            '0051234501',
            'Ahmad Kurniawan',
            '2005-06-15',
            'Teknik Komputer dan Jaringan',
            2023,
            '081234567890',
            'ahmad@gmail.com',
            'Jl. Mawar No. 12, Bandung',
        ]));

        $writer->addRow(Row::fromValues([
            '0051234502',
            'Dewi Sartika',
            '2005-08-20',
            'Rekayasa Perangkat Lunak',
            2023,
            '085678901234',
            'dewi@gmail.com',
            'Jl. Melati No. 5, Jakarta',
        ]));

        $writer->close();

        return response()->download($tempPath, 'template-import-alumni.xlsx')->deleteFileAfterSend(true);
    }

    /**
     * Import alumni records from uploaded Excel or CSV file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,txt|max:10240',
        ], [
            'file.required' => 'Silakan pilih file Excel (.xlsx atau .csv) untuk diimpor.',
            'file.mimes' => 'File harus berformat .xlsx atau .csv.',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());

        if ($ext === 'xlsx') {
            $reader = new XLSXReader();
        } else {
            $reader = new CSVReader();
        }

        try {
            $reader->open($file->getRealPath());

            $inserted = 0;
            $updated = 0;
            $isFirstRow = true;

            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    $cells = $row->toArray();

                    if ($isFirstRow) {
                        $isFirstRow = false;
                        continue;
                    }

                    $nisn = trim((string)($cells[0] ?? ''));
                    $nama = trim((string)($cells[1] ?? ''));
                    $tglLahirRaw = $cells[2] ?? '';
                    $jurusan = trim((string)($cells[3] ?? ''));
                    $tahunLulus = trim((string)($cells[4] ?? ''));
                    $noHp = trim((string)($cells[5] ?? ''));
                    $email = trim((string)($cells[6] ?? ''));
                    $alamat = trim((string)($cells[7] ?? ''));

                    if (empty($nisn) || empty($nama)) {
                        continue;
                    }

                    $parsedDate = '2005-01-01';
                    if ($tglLahirRaw instanceof \DateTimeInterface) {
                        $parsedDate = $tglLahirRaw->format('Y-m-d');
                    } elseif (!empty($tglLahirRaw)) {
                        $timestamp = strtotime((string)$tglLahirRaw);
                        if ($timestamp !== false) {
                            $parsedDate = date('Y-m-d', $timestamp);
                        }
                    }

                    $alumni = Alumni::updateOrCreate(
                        ['nisn' => $nisn],
                        [
                            'nama' => $nama,
                            'tanggal_lahir' => $parsedDate,
                            'jurusan' => $jurusan ?: 'Lainnya',
                            'tahun_lulus' => is_numeric($tahunLulus) ? (int)$tahunLulus : (int)date('Y'),
                            'no_hp' => $noHp ?: null,
                            'email' => $email ?: null,
                            'alamat' => $alamat ?: null,
                        ]
                    );

                    if ($alumni->wasRecentlyCreated) {
                        $inserted++;
                    } else {
                        $updated++;
                    }
                }
                break;
            }

            $reader->close();

            if ($inserted === 0 && $updated === 0) {
                return back()->with('error', 'Tidak ada data valid yang ditemukan pada file.');
            }

            $message = "Berhasil mengimpor {$inserted} data alumni baru";
            if ($updated > 0) {
                $message .= " dan memperbarui {$updated} data alumni yang sudah ada.";
            } else {
                $message .= ".";
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }
    }
}
