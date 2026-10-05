@extends('layouts.app')

@section('title', 'Tracerstudy Alumni SMK Muhammadiyah 2 Cikampek')

@section('content')
<div class="space-y-12 md:space-y-16">
    <!-- Hero -->
    <section class="relative overflow-hidden rounded-2xl border border-brand-100 bg-brand-50 px-6 py-12 md:px-10 md:py-18 text-center">
        <i aria-hidden="true" class="fa-solid fa-graduation-cap absolute right-4 top-6 text-[170px] text-brand-200/70 rotate-12 select-none pointer-events-none hidden lg:block"></i>

        <h2 class="font-display text-3xl md:text-5xl font-extrabold tracking-tight text-slate-900 mb-5 md:mb-6">
            Selamat Datang di Tracer Study Alumni SMK
        </h2>
        <p class="text-slate-600 text-base md:text-lg max-w-2xl mx-auto mb-8 md:mb-10 leading-relaxed">
            Kirimkan data diri Anda, bantu sekolah melacak indikator keberhasilan lulusan
            <strong class="font-semibold text-slate-800">(Bekerja, Melanjutkan Kuliah, Wirausaha)</strong>, serta dapatkan informasi seputar lowongan kerja alumni.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('alumni.login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-lg bg-cta text-slate-900 hover:bg-cta-600 font-bold text-base transition-all hover:-translate-y-0.5 shadow-md shadow-slate-900/15 focus-visible:ring-4 focus-visible:ring-cta-600">
                <i class="fa-solid fa-paper-plane"></i> Masuk & Isi Kuesioner Alumni
            </a>
        </div>
    </section>

    <!-- Quick Action Cards -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6" aria-label="Informasi pelataran">
        <article class="bg-white border border-slate-200 rounded-2xl p-6 flex items-start gap-4 transition-all duration-200 hover:-translate-y-1 hover:border-brand-300 hover:shadow-md">
            <span class="w-12 h-12 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-user-check"></i>
            </span>
            <div class="min-w-0">
                <h3 class="font-display text-base font-bold text-slate-900 mb-1.5">Cari & Validasi Data Alumni</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Masuk menggunakan kombinasi NISN dan Tanggal Lahir tanpa password untuk mengisi kuesioner.
                </p>
            </div>
        </article>

        <article class="bg-white border border-slate-200 rounded-2xl p-6 flex items-start gap-4 transition-all duration-200 hover:-translate-y-1 hover:border-brand-300 hover:shadow-md">
            <span class="w-12 h-12 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-newspaper"></i>
            </span>
            <div class="min-w-0">
                <h3 class="font-display text-base font-bold text-slate-900 mb-1.5">Informasi Lowongan BKK</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Lihat informasi rekrutmen kerja dan peluang karir yang disalurkan langsung oleh pihak sekolah.
                </p>
            </div>
        </article>

        <article class="bg-white border border-slate-200 rounded-2xl p-6 flex items-start gap-4 transition-all duration-200 hover:-translate-y-1 hover:border-brand-300 hover:shadow-md">
            <span class="w-12 h-12 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-chart-pie"></i>
            </span>
            <div class="min-w-0">
                <h3 class="font-display text-base font-bold text-slate-900 mb-1.5">Statistik Indikator BMW</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Data terkumpul digunakan untuk akreditasi sekolah & evaluasi keselarasan kurikulum vokasi.
                </p>
            </div>
        </article>
    </section>

    <!-- Lowongan Kerja Highlights -->
    @if($jobs->count() > 0)
    <section aria-label="Lowongan Kerja BKK" class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8">
        <div class="flex items-center justify-between gap-4 mb-6 md:mb-7">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-briefcase"></i>
                </span>
                <h3 class="font-display text-lg font-bold text-slate-900">Lowongan Kerja BKK Terbaru</h3>
            </div>
            <a href="{{ route('loker.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-brand-700 hover:text-brand-800 transition-colors">
                Lihat Semua Lowongan <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach($jobs as $job)
                <article class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col transition-all duration-200 hover:-translate-y-1 hover:border-brand-300 hover:shadow-md">
                    <div class="flex-1">
                        <div class="flex items-center justify-between gap-3 mb-3">
                            <span class="rounded-md bg-brand-50 text-brand-700 px-2.5 py-1 text-xs font-bold">{{ $job->posisi }}</span>
                        </div>
                        <h4 class="font-display text-lg font-bold text-slate-900 mb-1.5 leading-snug">{{ $job->judul }}</h4>
                        <p class="text-sm text-slate-600 flex flex-wrap items-center gap-x-1.5 mb-3">
                            <i class="fa-solid fa-building text-slate-400 text-xs" aria-hidden="true"></i> {{ $job->perusahaan }}
                            <span class="text-slate-400 select-none">•</span>
                            <i class="fa-solid fa-location-dot text-slate-400 text-xs" aria-hidden="true"></i> <span>{{ $job->lokasi ?? 'Indonesia' }}</span>
                        </p>
                        <p class="text-sm text-slate-600 line-clamp-2 leading-relaxed">
                            {{ $job->persyaratan }}
                        </p>
                    </div>
                    @if($job->deadline || $job->link_pendaftaran)
                        <div class="pt-3.5 border-t border-slate-200 flex items-center justify-between gap-3 mt-auto">
                            @if($job->deadline)
                                <span class="inline-flex items-center gap-2 text-xs text-slate-500 font-medium whitespace-nowrap">
                                    <i class="fa-regular fa-calendar text-slate-400"></i> s/d {{ $job->deadline->format('d M Y') }}
                                </span>
                            @endif
                            @if($job->link_pendaftaran)
                                <a href="{{ $job->link_pendaftaran }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-cta text-slate-900 hover:bg-cta-600 text-sm font-bold transition-colors focus-visible:ring-2 focus-visible:ring-cta-600 focus-visible:ring-offset-2">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Lamar Lowongan
                                </a>
                            @endif
                        </div>
                    @endif
                </article>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection
