@extends('layouts.app')

@section('title', 'Statistik Alumni - Tracer Study SMK Muhammadiyah 2 Cikampek')

@section('content')
<div class="space-y-6 md:space-y-8">
    <!-- Header -->
    <section class="bg-brand-50 border border-brand-100 rounded-2xl p-6 md:p-10 text-center">
        <span class="w-12 h-12 rounded-xl bg-white border border-brand-200 text-brand-700 flex items-center justify-center text-xl mx-auto mb-4">
            <i class="fa-solid fa-chart-column"></i>
        </span>
        <h1 class="font-display text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 mb-2">
            Statistik Alumni
        </h1>`
        <p class="text-sm md:text-base text-slate-600 max-w-2xl mx-auto leading-relaxed">
            Rekap data tracer study lulusan SMK Muhammadiyah 2 Cikampek berdasarkan indikator keberhasilan alumni
            <strong class="font-semibold text-slate-800">(Bekerja, Melanjutkan Kuliah, Wirausaha)</strong>.
        </p>
    </section>

    <!-- Statistics Cards -->
    <section aria-label="Statistik Alumni Terdata" class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8">
        <div class="flex items-center gap-3 mb-6 md:mb-7">
            <span class="w-10 h-10 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-chart-line"></i>
            </span>
            <h2 class="font-display text-lg font-bold text-slate-900">Statistik Terkini Alumni Terdata</h2>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-brand-50 border border-brand-200 rounded-xl p-5 h-full text-center">
                <span class="text-3xl font-extrabold text-brand-900 tracking-tight tabular-nums block">{{ number_format($totalAlumni) }}</span>
                <span class="text-[11px] font-semibold text-brand-800 uppercase tracking-wide block mt-2">Total Alumni</span>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl p-5 h-full text-center">
                <span class="text-3xl font-extrabold text-slate-900 tracking-tight tabular-nums block">{{ number_format($bekerja) }}</span>
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide block mt-2">Bekerja</span>
                <span class="mt-3 inline-flex items-center gap-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold px-2.5 py-0.5">{{ $bekerjaPct }}%</span>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl p-5 h-full text-center">
                <span class="text-3xl font-extrabold text-slate-900 tracking-tight tabular-nums block">{{ number_format($kuliah) }}</span>
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide block mt-2">Melanjutkan Kuliah</span>
                <span class="mt-3 inline-flex items-center gap-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold px-2.5 py-0.5">{{ $kuliahPct }}%</span>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl p-5 h-full text-center">
                <span class="text-3xl font-extrabold text-slate-900 tracking-tight tabular-nums block">{{ number_format($wirausaha) }}</span>
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide block mt-2">Wirausaha</span>
                <span class="mt-3 inline-flex items-center gap-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold px-2.5 py-0.5">{{ $wirausahaPct }}%</span>
            </div>
        </div>
    </section>

    <!-- BMW Indicator Explanation -->
    <section aria-label="Indikator BMW" class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8">
        <div class="flex items-center gap-3 mb-5">
            <span class="w-10 h-10 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-chart-pie"></i>
            </span>
            <h2 class="font-display text-lg font-bold text-slate-900">Tentang Indikator BMW</h2>
        </div>
        <p class="text-sm text-slate-600 leading-relaxed max-w-3xl">
            Data tracer study yang terkumpul digunakan untuk akreditasi sekolah &amp; evaluasi keselarasan kurikulum vokasi,
            dengan mengukur tiga indikator utama keberhasilan lulusan: <strong class="font-semibold text-slate-800">Bekerja</strong>,
            <strong class="font-semibold text-slate-800">Melanjutkan Kuliah</strong>, dan <strong class="font-semibold text-slate-800">Wirausaha</strong>.
        </p>
    </section>
</div>
@endsection
