@extends('layouts.app')

@section('title', 'Kuesioner Tracer Study - ' . $alumni->nama)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Alumni Profile Legacy Slate -->
    <div class="bg-slate-700 border border-slate-600 rounded-lg p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-slate-600 border border-slate-500 flex items-center justify-center text-white font-bold text-xl">
                {{ strtoupper(substr($alumni->nama, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-xl font-bold text-white mb-0.5">{{ $alumni->nama }}</h1>
                <p class="text-xs text-slate-300">
                    NISN: {{ $alumni->nisn }} • {{ $alumni->jurusan }} (Angkatan {{ $alumni->tahun_lulus }})
                </p>
            </div>
        </div>
        <a href="{{ route('alumni.logout') }}" class="text-xs font-semibold text-slate-300 hover:text-white px-3 py-1.5 rounded bg-slate-600 border border-slate-500">
            Keluar Sesi
        </a>
    </div>

    <!-- Form Section Slate -->
    <div class="bg-slate-700 border border-slate-600 rounded-lg p-6 md:p-8 space-y-6">
        <div>
            <h2 class="text-xl font-bold text-white uppercase mb-1">Formulir Kuesioner Tracer Study {{ date('Y') }}</h2>
            <p class="text-xs text-slate-300">
                Pilih status utama Anda saat ini (BMW), lalu lengkapi informasi yang diminta di bawah ini.
            </p>
        </div>

        <form action="{{ route('tracer.kuesioner.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- STEP 1: Pembaruan Kontak -->
            <div class="bg-slate-800 border border-slate-600 rounded-lg p-5 space-y-4">
                <h3 class="text-sm font-bold text-white uppercase flex items-center gap-2 border-b border-slate-700 pb-2">
                    1. Pembaruan Kontak Alumni
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">No. WhatsApp / HP</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $alumni->no_hp) }}" required
                            placeholder="0812xxxxxxxx"
                            class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-600 text-white text-xs focus:outline-none focus:border-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $alumni->email) }}" required
                            placeholder="email@example.com"
                            class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-600 text-white text-xs focus:outline-none focus:border-slate-400">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Alamat Lengkap</label>
                    <textarea name="alamat" rows="2" 
                        class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-600 text-white text-xs focus:outline-none focus:border-slate-400">{{ old('alamat', $alumni->alamat) }}</textarea>
                </div>
            </div>

            <!-- STEP 2: Status Utama (BMW) -->
            <div class="bg-slate-800 border border-slate-600 rounded-lg p-5 space-y-4">
                <h3 class="text-sm font-bold text-white uppercase flex items-center gap-2 border-b border-slate-700 pb-2">
                    2. Status Utama Saat Ini (Indikator BMW)
                </h3>

                @php
                    $currentStatus = old('status_utama', $existingResponse->status_utama ?? 'Bekerja');
                @endphp

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <!-- Option Bekerja -->
                    <label class="status-box cursor-pointer p-4 rounded bg-slate-700 border border-slate-600 text-center transition-all">
                        <input type="radio" name="status_utama" value="Bekerja" class="sr-only" {{ $currentStatus == 'Bekerja' ? 'checked' : '' }} onchange="toggleStatusForms('Bekerja')">
                        <i class="fa-solid fa-briefcase text-lg text-slate-300 mb-2 block"></i>
                        <span class="text-xs font-bold text-white block">Bekerja</span>
                    </label>

                    <!-- Option Kuliah -->
                    <label class="status-box cursor-pointer p-4 rounded bg-slate-700 border border-slate-600 text-center transition-all">
                        <input type="radio" name="status_utama" value="Kuliah" class="sr-only" {{ $currentStatus == 'Kuliah' ? 'checked' : '' }} onchange="toggleStatusForms('Kuliah')">
                        <i class="fa-solid fa-graduation-cap text-lg text-slate-300 mb-2 block"></i>
                        <span class="text-xs font-bold text-white block">Kuliah</span>
                    </label>

                    <!-- Option Wirausaha -->
                    <label class="status-box cursor-pointer p-4 rounded bg-slate-700 border border-slate-600 text-center transition-all">
                        <input type="radio" name="status_utama" value="Wirausaha" class="sr-only" {{ $currentStatus == 'Wirausaha' ? 'checked' : '' }} onchange="toggleStatusForms('Wirausaha')">
                        <i class="fa-solid fa-store text-lg text-slate-300 mb-2 block"></i>
                        <span class="text-xs font-bold text-white block">Wirausaha</span>
                    </label>

                    <!-- Option Mencari Kerja -->
                    <label class="status-box cursor-pointer p-4 rounded bg-slate-700 border border-slate-600 text-center transition-all">
                        <input type="radio" name="status_utama" value="Mencari Kerja" class="sr-only" {{ $currentStatus == 'Mencari Kerja' ? 'checked' : '' }} onchange="toggleStatusForms('Mencari Kerja')">
                        <i class="fa-solid fa-magnifying-glass text-lg text-slate-300 mb-2 block"></i>
                        <span class="text-xs font-bold text-white block">Mencari Kerja</span>
                    </label>
                </div>
            </div>

            <!-- STEP 3: Conditional Details -->

            <!-- Conditional Bekerja -->
            <div id="form-bekerja" class="conditional-section bg-slate-800 border border-slate-600 rounded-lg p-5 space-y-4">
                <h4 class="text-xs font-bold text-slate-200 uppercase border-b border-slate-700 pb-2">Detail Pekerjaan</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Perusahaan / Instansi</label>
                        <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $existingResponse->nama_perusahaan ?? '') }}"
                            placeholder="Nama Perusahaan"
                            class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-600 text-white text-xs focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Jabatan / Posisi</label>
                        <input type="text" name="jabatan" value="{{ old('jabatan', $existingResponse->jabatan ?? '') }}"
                            placeholder="Posisi Kerja"
                            class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-600 text-white text-xs focus:outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Kisaran Gaji</label>
                        <select name="kisaran_gaji" class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-600 text-white text-xs focus:outline-none">
                            <option value="">-- Pilih Gaji --</option>
                            <option value="< 2 Juta" {{ old('kisaran_gaji', $existingResponse->kisaran_gaji ?? '') == '< 2 Juta' ? 'selected' : '' }}>< Rp 2.000.000</option>
                            <option value="2 - 3.5 Juta" {{ old('kisaran_gaji', $existingResponse->kisaran_gaji ?? '') == '2 - 3.5 Juta' ? 'selected' : '' }}>Rp 2.000.000 - Rp 3.500.000</option>
                            <option value="3.5 - 5 Juta" {{ old('kisaran_gaji', $existingResponse->kisaran_gaji ?? '') == '3.5 - 5 Juta' ? 'selected' : '' }}>Rp 3.500.000 - Rp 5.000.000</option>
                            <option value="> 5 Juta" {{ old('kisaran_gaji', $existingResponse->kisaran_gaji ?? '') == '> 5 Juta' ? 'selected' : '' }}>> Rp 5.000.000</option>
                        </select>
                    </div>
                    <div class="flex items-center pt-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="linear_dengan_jurusan" value="1" 
                                {{ old('linear_dengan_jurusan', $existingResponse->linear_dengan_jurusan ?? false) ? 'checked' : '' }}
                                class="rounded bg-slate-900 border-slate-600 text-slate-200">
                            <span class="text-xs font-semibold text-slate-200">Pekerjaan sesuai jurusan SMK?</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Conditional Kuliah -->
            <div id="form-kuliah" class="conditional-section bg-slate-800 border border-slate-600 rounded-lg p-5 space-y-4">
                <h4 class="text-xs font-bold text-slate-200 uppercase border-b border-slate-700 pb-2">Detail Perguruan Tinggi</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Kampus</label>
                        <input type="text" name="nama_kampus" value="{{ old('nama_kampus', $existingResponse->nama_kampus ?? '') }}"
                            placeholder="Nama Kampus / PT"
                            class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-600 text-white text-xs focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Program Studi / Jurusan</label>
                        <input type="text" name="program_studi" value="{{ old('program_studi', $existingResponse->program_studi ?? '') }}"
                            placeholder="Program Studi"
                            class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-600 text-white text-xs focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Conditional Wirausaha -->
            <div id="form-wirausaha" class="conditional-section bg-slate-800 border border-slate-600 rounded-lg p-5 space-y-4">
                <h4 class="text-xs font-bold text-slate-200 uppercase border-b border-slate-700 pb-2">Detail Wirausaha</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Usaha</label>
                        <input type="text" name="nama_usaha" value="{{ old('nama_usaha', $existingResponse->nama_usaha ?? '') }}"
                            placeholder="Nama Brand / Usaha"
                            class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-600 text-white text-xs focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Bidang Usaha</label>
                        <input type="text" name="bidang_usaha" value="{{ old('bidang_usaha', $existingResponse->bidang_usaha ?? '') }}"
                            placeholder="Bidang Usaha"
                            class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-600 text-white text-xs focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Omset Bulanan</label>
                        <select name="omset_bulanan" class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-600 text-white text-xs focus:outline-none">
                            <option value="">-- Pilih Omset --</option>
                            <option value="< 3 Juta" {{ old('omset_bulanan', $existingResponse->omset_bulanan ?? '') == '< 3 Juta' ? 'selected' : '' }}>< Rp 3.000.000</option>
                            <option value="3 - 10 Juta" {{ old('omset_bulanan', $existingResponse->omset_bulanan ?? '') == '3 - 10 Juta' ? 'selected' : '' }}>Rp 3.000.000 - Rp 10.000.000</option>
                            <option value="> 10 Juta" {{ old('omset_bulanan', $existingResponse->omset_bulanan ?? '') == '> 10 Juta' ? 'selected' : '' }}>> Rp 10.000.000</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Conditional Mencari Kerja -->
            <div id="form-mencari-kerja" class="conditional-section bg-slate-800 border border-slate-600 rounded-lg p-5">
                <p class="text-xs text-slate-300">
                    <i class="fa-solid fa-circle-info text-slate-400 mr-1"></i>
                    Informasi lowongan kerja dari mitra sekolah dapat diakses pada menu <a href="{{ route('loker.index') }}" target="_blank" class="text-white font-bold underline">Bursa Kerja Khusus (BKK)</a>.
                </p>
            </div>

            <!-- STEP 4: Saran -->
            <div class="bg-slate-800 border border-slate-600 rounded-lg p-5 space-y-2">
                <h3 class="text-sm font-bold text-white uppercase border-b border-slate-700 pb-2">
                    3. Saran & Masukan untuk Sekolah
                </h3>
                <textarea name="saran_sekolah" rows="3" 
                    placeholder="Saran dan masukan terkait kurikulum / fasilitas alat praktik di SMK..."
                    class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-600 text-white text-xs focus:outline-none">{{ old('saran_sekolah', $existingResponse->saran_sekolah ?? '') }}</textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                class="w-full py-3.5 rounded bg-slate-100 hover:bg-white text-slate-800 font-bold text-sm shadow flex items-center justify-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Kuesioner Tracer
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<style>
    .status-box:has(input:checked) {
        border-color: #f8fafc;
        background-color: #475569;
    }
</style>

<script>
    function toggleStatusForms(status) {
        document.querySelectorAll('.conditional-section').forEach(el => el.style.display = 'none');

        if (status === 'Bekerja') {
            document.getElementById('form-bekerja').style.display = 'block';
        } else if (status === 'Kuliah') {
            document.getElementById('form-kuliah').style.display = 'block';
        } else if (status === 'Wirausaha') {
            document.getElementById('form-wirausaha').style.display = 'block';
        } else if (status === 'Mencari Kerja') {
            document.getElementById('form-mencari-kerja').style.display = 'block';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const checkedRadio = document.querySelector('input[name="status_utama"]:checked');
        if (checkedRadio) {
            toggleStatusForms(checkedRadio.value);
        } else {
            toggleStatusForms('Bekerja');
        }
    });
</script>
@endpush
