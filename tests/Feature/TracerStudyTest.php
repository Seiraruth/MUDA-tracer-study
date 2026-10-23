<?php

namespace Tests\Feature;

use App\Models\Alumni;
use App\Models\JobPosting;
use App\Models\Mitra;
use App\Models\TracerResponse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TracerStudyTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Tracer Study Alumni SMK');
    }

    public function test_alumni_login_validation(): void
    {
        $alumni = Alumni::create([
            'nisn' => '0059998877',
            'nama' => 'Ahmad Fazi',
            'tanggal_lahir' => '2005-05-05',
            'jurusan' => 'Teknik Komputer dan Jaringan',
            'tahun_lulus' => 2023,
        ]);

        // Failed login attempt
        $failedResponse = $this->post('/alumni/login', [
            'nisn' => '0059998877',
            'tanggal_lahir' => '2000-01-01',
        ]);
        $failedResponse->assertSessionHasErrors('auth');

        // Successful login attempt
        $successResponse = $this->post('/alumni/login', [
            'nisn' => '0059998877',
            'tanggal_lahir' => '2005-05-05',
        ]);
        $successResponse->assertRedirect('/alumni/kuesioner');
        $this->assertEquals($alumni->id, session('alumni_id'));
    }

    public function test_alumni_can_submit_kuesioner(): void
    {
        $alumni = Alumni::create([
            'nisn' => '0051112233',
            'nama' => 'Dina Rosiana',
            'tanggal_lahir' => '2005-10-10',
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'tahun_lulus' => 2023,
        ]);

        $response = $this->withSession(['alumni_id' => $alumni->id])
            ->post('/alumni/kuesioner', [
                'no_hp' => '081233445566',
                'email' => 'dina@gmail.com',
                'alamat' => 'Jl. Kebon Sirih No 10',
                'status_utama' => 'Bekerja',
                'nama_perusahaan' => 'PT Software Utama',
                'jabatan' => 'Fullstack Developer',
                'kisaran_gaji' => '3.5 - 5 Juta',
                'linear_dengan_jurusan' => '1',
                'saran_sekolah' => 'Kurikulum sudah sangat bagus',
            ]);

        $response->assertRedirect('/alumni/sukses');

        $this->assertDatabaseHas('tracer_responses', [
            'alumni_id' => $alumni->id,
            'status_utama' => 'Bekerja',
            'nama_perusahaan' => 'PT Software Utama',
            'linear_dengan_jurusan' => true,
        ]);
    }

    public function test_public_statistics_can_be_filtered_by_graduation_year(): void
    {
        $alumni2023 = Alumni::create([
            'nisn' => '0010000001',
            'nama' => 'Alumni 2023',
            'tanggal_lahir' => '2005-01-01',
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'tahun_lulus' => 2023,
        ]);
        $alumni2024 = Alumni::create([
            'nisn' => '0010000002',
            'nama' => 'Alumni 2024',
            'tanggal_lahir' => '2006-01-01',
            'jurusan' => 'Teknik Komputer dan Jaringan',
            'tahun_lulus' => 2024,
        ]);

        TracerResponse::create([
            'alumni_id' => $alumni2023->id,
            'tahun_tracer' => 2026,
            'status_utama' => 'Bekerja',
            'kisaran_gaji' => '3.5 - 5 Juta',
        ]);
        TracerResponse::create([
            'alumni_id' => $alumni2024->id,
            'tahun_tracer' => 2026,
            'status_utama' => 'Kuliah',
        ]);

        $response = $this->get('/statistik?tahun_lulus=2023');

        $response->assertOk()
            ->assertSee('tahun_lulus')
            ->assertSee('Lulusan 2023')
            ->assertSee('3.5 - 5 Juta');
        $response->assertViewHas('totalAlumni', 1);
        $response->assertViewHas('totalResponses', 1);
        $response->assertViewHas('bekerja', 1);
        $response->assertViewHas('kuliah', 0);
        $response->assertViewHas('statistikRows', function ($rows) {
            return $rows->count() === 1 && (int) $rows->first()->tahun_lulus === 2023;
        });
    }

    public function test_admin_dashboard_shows_alumni_without_current_year_response(): void
    {
        $admin = User::factory()->create();
        $pending = Alumni::create([
            'nisn' => '0020000001',
            'nama' => 'Alumni Perlu Diingatkan',
            'tanggal_lahir' => '2005-02-02',
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'tahun_lulus' => 2023,
        ]);
        $responded = Alumni::create([
            'nisn' => '0020000002',
            'nama' => 'Alumni Sudah Mengisi',
            'tanggal_lahir' => '2005-03-03',
            'jurusan' => 'Teknik Komputer dan Jaringan',
            'tahun_lulus' => 2023,
        ]);
        TracerResponse::create([
            'alumni_id' => $responded->id,
            'tahun_tracer' => (int) date('Y'),
            'status_utama' => 'Kuliah',
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Admin/Dashboard')
                ->where('belumIsi.0.id', $pending->id)
                ->missing('belumIsi.1.id'));
    }

    public function test_admin_can_create_and_filter_mitra_dudi(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin)->post('/admin/mitras', [
            'nama_perusahaan' => 'PT Mitra Uji',
            'bidang_usaha' => 'Manufaktur',
            'kota' => 'Cikampek',
            'is_active' => true,
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('mitras', ['nama_perusahaan' => 'PT Mitra Uji']);
        $this->get('/admin/mitras?search=PT%20Mitra%20Uji')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Admin/Mitra/Index')
                ->where('mitras.data.0.nama_perusahaan', 'PT Mitra Uji'));
    }

    public function test_admin_can_review_alumni_suggestions(): void
    {
        $admin = User::factory()->create();
        $alumni = Alumni::create([
            'nisn' => '0030000001',
            'nama' => 'Alumni Pemberi Saran',
            'tanggal_lahir' => '2005-04-04',
            'jurusan' => 'Akuntansi dan Keuangan',
            'tahun_lulus' => 2023,
        ]);
        TracerResponse::create([
            'alumni_id' => $alumni->id,
            'tahun_tracer' => (int) date('Y'),
            'status_utama' => 'Bekerja',
            'saran_sekolah' => 'Tambahkan praktik industri.',
        ]);

        $this->actingAs($admin)->get('/admin/saran')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Admin/Saran/Index')
                ->where('sarans.data.0.alumni.nama', 'Alumni Pemberi Saran')
                ->where('sarans.data.0.saran_sekolah', 'Tambahkan praktik industri.'));
    }

    public function test_loker_page_loads(): void
    {
        JobPosting::create([
            'judul' => 'IT Support Specialist',
            'perusahaan' => 'PT Digital Solusindo',
            'posisi' => 'IT Support',
            'persyaratan' => 'Lulusan SMK TKJ',
            'is_active' => true,
        ]);

        $response = $this->get('/loker');
        $response->assertStatus(200);
        $response->assertSee('IT Support Specialist');
    }
}
