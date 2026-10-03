<?php

namespace Database\Seeders;

use App\Models\Alumni;
use App\Models\JobPosting;
use App\Models\TracerResponse;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin User for BKK Panel
        User::updateOrCreate(
            ['email' => 'admin@tracer.smk.sch.id'],
            [
                'name' => 'Admin BKK SMK',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Create Sample Alumni
        $alumniData = [
            [
                'nisn' => '0051234501',
                'nama' => 'Budi Santoso',
                'tanggal_lahir' => '2005-04-12',
                'jurusan' => 'Teknik Komputer dan Jaringan',
                'tahun_lulus' => 2023,
                'no_hp' => '081234567890',
                'email' => 'budi@gmail.com',
                'alamat' => 'Jl. Merdeka No. 10, Jakarta',
                'response' => [
                    'status_utama' => 'Bekerja',
                    'nama_perusahaan' => 'PT Telkom Indonesia',
                    'jabatan' => 'Network Technician',
                    'kisaran_gaji' => '3 - 5 Juta',
                    'linear_dengan_jurusan' => true,
                    'saran_sekolah' => 'Perbanyak praktik jaringan Cisco di laboratorium.',
                ]
            ],
            [
                'nisn' => '0051234502',
                'nama' => 'Siti Nurhaliza',
                'tanggal_lahir' => '2005-08-20',
                'jurusan' => 'Rekayasa Perangkat Lunak',
                'tahun_lulus' => 2023,
                'no_hp' => '081298765432',
                'email' => 'siti@gmail.com',
                'alamat' => 'Jl. Mawar No. 5, Bandung',
                'response' => [
                    'status_utama' => 'Bekerja',
                    'nama_perusahaan' => 'PT Tech Media',
                    'jabatan' => 'Junior Web Developer',
                    'kisaran_gaji' => '3 - 5 Juta',
                    'linear_dengan_jurusan' => true,
                    'saran_sekolah' => 'Tingkatkan materi Laravel dan VueJS.',
                ]
            ],
            [
                'nisn' => '0051234503',
                'nama' => 'Rian Hidayat',
                'tanggal_lahir' => '2005-11-03',
                'jurusan' => 'Rekayasa Perangkat Lunak',
                'tahun_lulus' => 2023,
                'no_hp' => '081377889900',
                'email' => 'rian@gmail.com',
                'alamat' => 'Jl. Diponegoro No. 12, Surabaya',
                'response' => [
                    'status_utama' => 'Kuliah',
                    'nama_kampus' => 'Universitas Gadjah Mada',
                    'program_studi' => 'Teknologi Informasi',
                    'saran_sekolah' => 'Pembekalan matematika teknik diperkuat.',
                ]
            ],
            [
                'nisn' => '0051234504',
                'nama' => 'Dewi Anggraini',
                'tanggal_lahir' => '2005-02-15',
                'jurusan' => 'Akuntansi dan Keuangan',
                'tahun_lulus' => 2023,
                'no_hp' => '081544332211',
                'email' => 'dewi@gmail.com',
                'alamat' => 'Jl. Sudirman No. 44, Semarang',
                'response' => [
                    'status_utama' => 'Wirausaha',
                    'nama_usaha' => 'Dewi Catering & Bakery',
                    'bidang_usaha' => 'Kuliner & Pastry',
                    'omset_bulanan' => '5 - 10 Juta',
                    'saran_sekolah' => 'Ditambahkan mata pelajaran kewirausahaan digital.',
                ]
            ],
            [
                'nisn' => '0051234505',
                'nama' => 'Agus Setiawan',
                'tanggal_lahir' => '2005-06-25',
                'jurusan' => 'Teknik Kendaraan Ringan',
                'tahun_lulus' => 2023,
                'no_hp' => '081699887766',
                'email' => 'agus@gmail.com',
                'alamat' => 'Jl. Gatot Subroto No. 3, Surakarta',
                'response' => [
                    'status_utama' => 'Bekerja',
                    'nama_perusahaan' => 'PT Astra Honda Motor',
                    'jabatan' => 'Mekanik Servis',
                    'kisaran_gaji' => '3 - 5 Juta',
                    'linear_dengan_jurusan' => true,
                    'saran_sekolah' => 'Mesin matic injeksi diperbanyak untuk bahan praktik.',
                ]
            ],
            [
                'nisn' => '0051234506',
                'nama' => 'Lestari Putri',
                'tanggal_lahir' => '2005-09-10',
                'jurusan' => 'Multimedia',
                'tahun_lulus' => 2023,
                'no_hp' => '081711223344',
                'email' => 'lestari@gmail.com',
                'alamat' => 'Jl. Pemuda No. 7, Yogyakarta',
                'response' => [
                    'status_utama' => 'Mencari Kerja',
                    'saran_sekolah' => 'Mohon infokan lowongan desain grafis dari alumni.',
                ]
            ],
        ];

        foreach ($alumniData as $data) {
            $respData = $data['response'];
            unset($data['response']);

            $alumni = Alumni::updateOrCreate(['nisn' => $data['nisn']], $data);

            TracerResponse::updateOrCreate(
                [
                    'alumni_id' => $alumni->id,
                    'tahun_tracer' => 2025,
                ],
                array_merge($respData, [
                    'tahun_tracer' => 2025,
                ])
            );
        }

        // 3. Create Sample Job Postings
        JobPosting::create([
            'judul' => 'Junior Network Engineer',
            'perusahaan' => 'PT Nusantara Global Tech',
            'posisi' => 'Network Support Specialist',
            'lokasi' => 'Jakarta Selatan',
            'persyaratan' => 'Lulusan SMK Jurusan TKJ, menguasai Mikrotik/Cisco dasar, bersedia shift.',
            'link_pendaftaran' => 'https://example.com/apply-network',
            'kontak' => 'hrd@nusantaratech.id / 081200001111',
            'deadline' => '2026-12-31',
            'is_active' => true,
        ]);

        JobPosting::create([
            'judul' => 'Junior Web Developer (PHP / Laravel)',
            'perusahaan' => 'PT Solusi Digital Kreatif',
            'posisi' => 'Backend Developer Trainee',
            'lokasi' => 'Bandung',
            'persyaratan' => 'Lulusan SMK RPL, memahami HTML/CSS/PHP/MySQL, melampirkan portofolio project.',
            'link_pendaftaran' => 'https://example.com/apply-dev',
            'kontak' => 'recruitment@solusidigital.co.id',
            'deadline' => '2026-11-30',
            'is_active' => true,
        ]);
    }
}
