<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tracer_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumni')->cascadeOnDelete();
            $table->integer('tahun_tracer');
            $table->enum('status_utama', ['Bekerja', 'Kuliah', 'Wirausaha', 'Mencari Kerja']);
            
            // Detail Bekerja
            $table->string('nama_perusahaan')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('kisaran_gaji')->nullable();
            $table->boolean('linear_dengan_jurusan')->default(false);
            
            // Detail Kuliah
            $table->string('nama_kampus')->nullable();
            $table->string('program_studi')->nullable();
            
            // Detail Wirausaha
            $table->string('nama_usaha')->nullable();
            $table->string('bidang_usaha')->nullable();
            $table->string('omset_bulanan')->nullable();
            
            // Saran & Masukan untuk Sekolah
            $table->text('saran_sekolah')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracer_responses');
    }
};
