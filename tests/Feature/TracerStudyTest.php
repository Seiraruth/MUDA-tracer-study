<?php

namespace Tests\Feature;

use App\Models\Alumni;
use App\Models\JobPosting;
use App\Models\TracerResponse;
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
