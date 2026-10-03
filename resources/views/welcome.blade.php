@extends('layouts.app')

@section('title', 'Selamat Datang di Tracer Study SMK')

@section('content')
<div class="space-y-8">
    <!-- Hero Banner Legacy -->
    <div class="bg-slate-700/60 border border-slate-600 rounded-lg p-8 md:p-12 text-center relative overflow-hidden">
        <h2 class="text-2xl md:text-4xl font-extrabold text-white uppercase tracking-tight mb-4 leading-tight">
            Selamat Datang di Tracer Study Alumni SMK
        </h2>
        <p class="text-slate-200 text-sm md:text-base max-w-2xl mx-auto mb-8 leading-relaxed">
            Kirimkan data diri Anda, bantu sekolah melacak indikator keberhasilan lulusan 
            <strong>(Bekerja, Melanjutkan Kuliah, Wirausaha)</strong>, serta dapatkan informasi seputar lowongan kerja alumni.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('alumni.login') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-lg bg-slate-100 text-slate-800 hover:bg-white font-bold text-sm transition-all shadow-md flex items-center justify-center gap-2">
                <i class="fa-solid fa-paper-plane"></i> Masuk & Isi Kuesioner Alumni
            </a>
            <a href="{{ route('loker.index') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-lg bg-slate-600 hover:bg-slate-500 text-white font-semibold text-sm border border-slate-500 transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-briefcase"></i> Lihat Lowongan Kerja BKK
            </a>
        </div>
    </div>

    <!-- Quick Action Cards (Legacy Slate Style) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-slate-700 border border-slate-600 rounded-lg p-6 flex items-start gap-4">
            <div class="w-12 h-12 rounded-lg bg-slate-600 text-slate-200 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-white mb-1">Cari & Validasi Data Alumni</h3>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Masuk menggunakan kombinasi NISN dan Tanggal Lahir tanpa password untuk mengisi kuesioner.
                </p>
            </div>
        </div>

        <div class="bg-slate-700 border border-slate-600 rounded-lg p-6 flex items-start gap-4">
            <div class="w-12 h-12 rounded-lg bg-slate-600 text-slate-200 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-newspaper"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-white mb-1">Informasi Lowongan BKK</h3>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Lihat informasi rekrutmen kerja dan peluang karir yang disalurkan langsung oleh pihak sekolah.
                </p>
            </div>
        </div>

        <div class="bg-slate-700 border border-slate-600 rounded-lg p-6 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-slate-600 text-slate-200 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-white mb-1">Statistik Indikator BMW</h3>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Data terkumpul digunakan untuk akreditasi sekolah & evaluasi keselarasan kurikulum vokasi.
                </p>
            </div>
        </div>
    </div>

    <!-- Real-time Statistics Cards -->
    <div class="bg-slate-700/80 border border-slate-600 rounded-lg p-6">
        <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
            <i class="fa-solid fa-chart-line text-slate-300"></i> Statistik Terkini Alumni Terdata
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-slate-800 border border-slate-600 rounded-lg p-4 text-center">
                <span class="text-xs font-semibold text-slate-400 block mb-1">Total Alumni</span>
                <span class="text-2xl font-extrabold text-white">{{ number_format($totalAlumni) }}</span>
            </div>
            <div class="bg-slate-800 border border-slate-600 rounded-lg p-4 text-center">
                <span class="text-xs font-semibold text-slate-400 block mb-1">Bekerja</span>
                <span class="text-2xl font-extrabold text-white">{{ number_format($bekerja) }} <span class="text-xs text-slate-300 font-normal">({{ $bekerjaPct }}%)</span></span>
            </div>
            <div class="bg-slate-800 border border-slate-600 rounded-lg p-4 text-center">
                <span class="text-xs font-semibold text-slate-400 block mb-1">Melanjutkan Kuliah</span>
                <span class="text-2xl font-extrabold text-white">{{ number_format($kuliah) }} <span class="text-xs text-slate-300 font-normal">({{ $kuliahPct }}%)</span></span>
            </div>
            <div class="bg-slate-800 border border-slate-600 rounded-lg p-4 text-center">
                <span class="text-xs font-semibold text-slate-400 block mb-1">Wirausaha</span>
                <span class="text-2xl font-extrabold text-white">{{ number_format($wirausaha) }} <span class="text-xs text-slate-300 font-normal">({{ $wirausahaPct }}%)</span></span>
            </div>
        </div>
    </div>

    <!-- Lowongan Kerja Highlights -->
    @if($jobs->count() > 0)
    <div class="bg-slate-700/80 border border-slate-600 rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-briefcase text-slate-300"></i> Lowongan Kerja BKK Terbaru
            </h3>
            <a href="{{ route('loker.index') }}" class="text-xs font-semibold text-slate-300 hover:text-white">
                Lihat Semua Lowongan &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($jobs as $job)
                <div class="bg-slate-800 border border-slate-600 rounded-lg p-5 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-xs text-slate-400 mb-2">
                            <span class="font-bold text-slate-200 bg-slate-700 px-2.5 py-0.5 rounded border border-slate-600">{{ $job->posisi }}</span>
                            @if($job->deadline)
                                <span>s/d {{ $job->deadline->format('d M Y') }}</span>
                            @endif
                        </div>
                        <h4 class="text-base font-bold text-white mb-1">{{ $job->judul }}</h4>
                        <p class="text-xs font-semibold text-slate-300 mb-3">{{ $job->perusahaan }} • <span class="text-slate-400 font-normal">{{ $job->lokasi ?? 'Indonesia' }}</span></p>
                        <p class="text-xs text-slate-300 line-clamp-2 leading-relaxed mb-4">
                            {{ $job->persyaratan }}
                        </p>
                    </div>
                    @if($job->link_pendaftaran)
                        <div class="pt-3 border-t border-slate-700 flex justify-end">
                            <a href="{{ $job->link_pendaftaran }}" target="_blank" class="px-3.5 py-1.5 rounded bg-slate-100 text-slate-800 hover:bg-white text-xs font-bold transition-all">
                                Lamar Lowongan
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
