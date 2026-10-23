<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mitras', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan');
            $table->string('bidang_usaha')->nullable();
            $table->string('alamat')->nullable();
            $table->string('kota')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('kontak_person')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mitras');
    }
};