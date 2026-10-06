@extends('layouts.app')

@section('title', 'Statistik Tracer Study - Alumni SMK Muhammadiyah 2 Cikampek')

@section('content')
@php
    // Optional data — falls back to neutral zeros / empty states until the
    // backend passes real statistics (same names as the homepage controller).
    $totalAlumni       = isset($totalAlumni)       ? $totalAlumni       : 0;
    $totalResponses    = isset($totalResponses)    ? $totalResponses    : 0;
    $bekerja           = isset($bekerja)           ? $bekerja           : 0;
    $kuliah            = isset($kuliah)            ? $kuliah            : 0;
    $wirausaha         = isset($wirausaha)         ? $wirausaha         : 0;
    $mencariKerja      = isset($mencariKerja)      ? $mencariKerja      : 0;
    $tahunLulusOptions = isset($tahunLulusOptions) ? $tahunLulusOptions : [];
    $statistikRows     = isset($statistikRows)     ? $statistikRows     : [];

    // Presentation-only percentages (values passed by the controller win).
    $bekerjaPct      = isset($bekerjaPct)      ? $bekerjaPct      : ($totalResponses < 1 ? 0 : round(($bekerja / $totalResponses) * 100));
    $kuliahPct       = isset($kuliahPct)       ? $kuliahPct       : ($totalResponses < 1 ? 0 : round(($kuliah / $totalResponses) * 100));
    $wirausahaPct    = isset($wirausahaPct)    ? $wirausahaPct    : ($totalResponses < 1 ? 0 : round(($wirausaha / $totalResponses) * 100));
    $mencariKerjaPct = isset($mencariKerjaPct) ? $mencariKerjaPct : ($totalResponses < 1 ? 0 : round(($mencariKerja / $totalResponses) * 100));

    $placedTotal = $bekerja + $kuliah + $wirausaha;
    $bekerjaShare   = $placedTotal > 0 ? round(($bekerja / $placedTotal) * 100)   : 0;
    $kuliahShare    = $placedTotal > 0 ? round(($kuliah / $placedTotal) * 100)    : 0;
    $wirausahaShare = $placedTotal > 0 ? round(($wirausaha / $placedTotal) * 100) : 0;

    $statusRows = [
        ['label' => 'Bekerja',            'count' => $bekerja,      'pct' => $bekerjaPct,      'bar' => 'bg-brand-600', 'dot' => 'bg-brand-600'],
        ['label' => 'Melanjutkan Kuliah', 'count' => $kuliah,       'pct' => $kuliahPct,       'bar' => 'bg-brand-400', 'dot' => 'bg-brand-400'],
        ['label' => 'Wirausaha',          'count' => $wirausaha,    'pct' => $wirausahaPct,    'bar' => 'bg-cta-600',   'dot' => 'bg-cta-600'],
        ['label' => 'Belum Bekerja',      'count' => $mencariKerja, 'pct' => $mencariKerjaPct, 'bar' => 'bg-slate-300',  'dot' => 'bg-slate-300'],
    ];

    $overviewCards = [
        ['label' => 'Bekerja',            'value' => $bekerja,   'pct' => $bekerjaPct,   'icon' => 'fa-briefcase',     'desc' => 'Alumni dengan status utama bekerja'],
        ['label' => 'Melanjutkan Kuliah', 'value' => $kuliah,    'pct' => $kuliahPct,    'icon' => 'fa-graduation-cap', 'desc' => 'Alumni yang melanjutkan pendidikan formal'],
        ['label' => 'Wirausaha',          'value' => $wirausaha, 'pct' => $wirausahaPct, 'icon' => 'fa-store',          'desc' => 'Alumni yang memulai usaha sendiri'],
    ];

    $careerPanels = [
        ['icon' => 'fa-briefcase',      'title' => 'Alumni Bekerja',     'count' => $bekerja,    'desc' => 'Perusahaan, jabatan, kisaran gaji, kesesuaian pekerjaan dengan jurusan.'],
        ['icon' => 'fa-graduation-cap', 'title' => 'Melanjutkan Kuliah', 'count' => $kuliah,     'desc' => 'Kampus atau institusi pendidikan lanjutan dan program studi yang tertilih.'],
        ['icon' => 'fa-store',          'title' => 'Alumni Wirausaha',   'count' => $wirausaha,  'desc' => 'Nama usaha, bidang usaha, dan omset bulanan rata-rata.'],
    ];
@endphp

<div class="space-y-12 md:space-y-16">

    <!-- ================= Header ================= -->
    <section class="relative overflow-hidden rounded-2xl border border-brand-100 bg-brand-50 px-6 py-10 md:px-10 md:py-14 text-center" aria-label="Header Statistik Tracer Study">
        <i aria-hidden="true" class="fa-solid fa-chart-column absolute right-5 top-8 text-[130px] text-brand-200/70 -rotate-12 select-none pointer-events-none hidden lg:block"></i>

        <span class="w-14 h-14 rounded-2xl bg-brand-100 border border-brand-200 text-brand-700 flex items-center justify-center text-2xl mx-auto mb-5" aria-hidden="true">
            <i class="fa-solid fa-chart-line"></i>
        </span>

        <h1 class="font-display text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900 mb-3 md:mb-4">
            Statistik Tracer Study
        </h1>
        <p class="text-slate-600 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
            Data dan informasi statistik alumni SMK Muhammadiyah 2 Cikampek
        </p>
    </section>

    <!-- ================= Filter Tahun Lulusan ================= -->
    <section class="bg-white border border-slate-200 rounded-2xl p-5 md:p-6" aria-label="Filter Tahun Lulusan">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <label for="filter-tahun-lulus" class="flex items-center gap-3 shrink-0 cursor-pointer">
                <span class="w-10 h-10 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center text-lg shrink-0" aria-hidden="true">
                    <i class="fa-solid fa-filter"></i>
                </span>
                <span class="flex flex-col leading-tight">
                    <span class="font-display text-sm font-bold text-slate-900">Tahun Lulusan</span>
                    <span class="text-xs text-slate-500">Kesebaran alumni berdasarkan tahun lulusan</span>
                </span>
            </label>

            <select id="filter-tahun-lulus" name="tahun_lulus" aria-label="Tahun Lulusan"
                class="w-full sm:w-56 rounded-lg bg-white border border-slate-300 text-slate-900 text-sm font-semibold px-4 py-2.5 focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand-200">
                <option value="">Semua Tahun Lulusan</option>
                @foreach($tahunLulusOptions as $year)
                    <option value="{{ $year }}">Lulusan {{ $year }}</option>
                @endforeach
            </select>
        </div>

        <p class="text-xs text-slate-400 mt-3.5 flex items-center gap-2">
            <i class="fa-regular fa-circle-question text-slate-400" aria-hidden="true"></i>
            Filter dapat menjadi aktif setelah data alumni terlink dengan sistem.
        </p>
    </section>

    <!-- ================= Statistik Umum (overview cards) ================= -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5" aria-label="Statistik Umum Alumni">
        <article class="bg-brand-50 border border-brand-200 rounded-2xl p-6 flex flex-col space-y-4">
            <span class="w-12 h-12 rounded-xl bg-brand text-white flex items-center justify-center text-xl shrink-0" aria-hidden="true">
                <i class="fa-solid fa-users"></i>
            </span>
            <p class="text-3xl font-extrabold text-brand-900 tracking-tight tabular-nums leading-none">{{ number_format($totalAlumni) }}</p>
            <div>
                <p class="font-display text-sm font-bold text-slate-900">Total Alumni</p>
                <p class="text-xs text-slate-500 mt-1">{{ number_format($totalResponses) }} jawaban kuesioner tersimpan</p>
            </div>
        </article>

        @foreach($overviewCards as $card)
            <article class="bg-white border border-slate-200 rounded-2xl p-6 flex flex-col space-y-4">
                <div class="flex items-center justify-between">
                    <span class="w-12 h-12 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center text-xl shrink-0" aria-hidden="true">
                        <i class="fa-solid {{ $card['icon'] }}"></i>
                    </span>
                    <span class="inline-flex items-center rounded-full bg-brand-50 text-brand-800 text-xs font-bold px-2.5 py-1 tabular-nums">{{ $card['pct'] }}%</span>
                </div>
                <p class="text-3xl font-extrabold text-slate-900 tracking-tight tabular-nums leading-none">{{ number_format($card['value']) }}</p>
                <div>
                    <p class="font-display text-sm font-bold text-slate-900">{{ $card['label'] }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ $card['desc'] }}</p>
                </div>
            </article>
        @endforeach
    </section>

    <!-- ================= Grafik & Informasi Karir ================= -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

        <!-- A. Employment status (horizontal bar chart) -->
        <section class="bg-white border border-slate-200 rounded-2xl p-6 md:p-7" aria-label="Status Pekerjaan Alumni">
            <div class="flex items-center gap-3 mb-6">
                <span class="w-10 h-10 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center text-lg shrink-0" aria-hidden="true">
                    <i class="fa-solid fa-chart-column"></i>
                </span>
                <div class="min-w-0 flex-1">
                    <h2 class="font-display text-lg font-bold text-slate-900">Status Pekerjaan Alumni</h2>
                    <p class="text-sm text-slate-500">Kesebaran jawaban tracer study berdasarkan status utama</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-slate-100 text-slate-600 text-xs font-bold px-2.5 py-1 whitespace-nowrap tabular-nums">{{ $totalResponses }} Jawaban</span>
            </div>

            @if($totalResponses > 0)
                <div class="space-y-5">
                    @foreach($statusRows as $row)
                        <div class="flex items-center gap-3">
                            <span class="w-32 sm:w-36 shrink-0 text-sm font-semibold text-slate-700">{{ $row['label'] }}</span>
                            <div class="flex-1 h-3.5 rounded-full bg-slate-200">
                                <div class="{{ $row['bar'] }} h-3.5 rounded-full" style="width: {{ max(0, min(100, $row['pct'])) }}%"></div>
                            </div>
                            <span class="w-20 text-right shrink-0 text-sm font-bold text-slate-900 tabular-nums">{{ $row['count'] }} <span class="text-slate-400 font-semibold">({{ $row['pct'] }}%)</span></span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-4 border-t border-slate-200 flex flex-wrap gap-2" aria-label="Legend status pekerjaan">
                    @foreach($statusRows as $row)
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                            <i class="w-2.5 h-2.5 rounded-full {{ $row['dot'] }}" aria-hidden="true"></i>
                            {{ $row['label'] }}
                        </span>
                    @endforeach
                </div>
            @else
                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-6 py-8 text-center">
                    <i class="fa-solid fa-chart-column text-2xl text-slate-300 mb-3" aria-hidden="true"></i>
                    <h3 class="text-base font-bold text-slate-700 mb-1">Grafik belum tersedia</h3>
                    <p class="text-sm text-slate-500 max-w-md mx-auto leading-relaxed">
                        Kesebaran status utama akan terkuplung setelah alumni mengisi kuesioner tracer study pada periode kuesioner aktif.
                    </p>
                </div>
            @endif
        </section>

        <!-- B. Career & further education info -->
        <section class="bg-white border border-slate-200 rounded-2xl p-6 md:p-7" aria-label="Informasi Karir dan Pendidikan Lanjutan">
            <div class="flex items-center gap-3 mb-6">
                <span class="w-10 h-10 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center text-lg shrink-0" aria-hidden="true">
                    <i class="fa-solid fa-chart-pie"></i>
                </span>
                <div class="min-w-0 flex-1">
                    <h2 class="font-display text-lg font-bold text-slate-900">Distribusiya Karir Alumni</h2>
                    <p class="text-sm text-slate-500">Proporsi alumni per kategori karir (Bekerja, Kuliah, Wirausaha)</p>
                </div>
            </div>

            @if($placedTotal > 0)
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="flex items-center justify-between mb-2.5 text-xs font-semibold text-slate-600">
                        <span>Distribusiya alumni per kategori</span>
                        <span class="tabular-nums">{{ number_format($placedTotal) }} alumni</span>
                    </div>
                    <div class="flex h-4 rounded-full bg-slate-200 overflow-hidden" role="img" aria-label="Distribusiya alumni per kategori karir">
                        <span class="bg-brand-600" style="width: {{ $bekerjaShare }}%" title="Bekerja {{ $bekerjaShare }}%"></span>
                        <span class="bg-cta-600" style="width: {{ $kuliahShare }}%" title="Melanjutkan Kuliah {{ $kuliahShare }}%"></span>
                        <span class="bg-brand-400" style="width: {{ $wirausahaShare }}%" title="Wirausaha {{ $wirausahaShare }}%"></span>
                    </div>
                    <div class="flex flex-wrap gap-x-4 mt-2.5 text-xs">
                        <span class="inline-flex items-center gap-1.5 text-slate-700"><i class="w-2.5 h-2.5 rounded-full bg-brand-600" aria-hidden="true"></i> Bekerja {{ $bekerjaShare }}%</span>
                        <span class="inline-flex items-center gap-1.5 text-slate-700"><i class="w-2.5 h-2.5 rounded-full bg-cta-600" aria-hidden="true"></i> Kuliah {{ $kuliahShare }}%</span>
                        <span class="inline-flex items-center gap-1.5 text-slate-700"><i class="w-2.5 h-2.5 rounded-full bg-brand-400" aria-hidden="true"></i> Wirausaha {{ $wirausahaShare }}%</span>
                    </div>
                </div>
            @else
                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-6 py-8 text-center">
                    <i class="fa-solid fa-chart-pie text-2xl text-slate-300 mb-3" aria-hidden="true"></i>
                    <h3 class="text-base font-bold text-slate-700 mb-1">Distribusiya belum tersedia</h3>
                    <p class="text-sm text-slate-500 max-w-md mx-auto leading-relaxed">
                        Proporsi alumni per kategori karir akan terkuplung ketika jawaban kuesioner tersimpan pada sistem.
                    </p>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
                @foreach($careerPanels as $panel)
                    <article class="bg-white border border-slate-200 rounded-xl p-4 flex flex-col gap-2.5">
                        <div class="flex items-center gap-2.5">
                            <span class="w-9 h-9 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center text-base shrink-0" aria-hidden="true">
                                <i class="fa-solid {{ $panel['icon'] }}"></i>
                            </span>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-sm font-bold text-slate-900 leading-tight">{{ $panel['title'] }}</h3>
                                <p class="text-[10px] text-slate-400 tabular-nums">{{ number_format($panel['count']) }} alumni</p>
                            </div>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $panel['desc'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <!-- ================= Indikator Tambahan ================= -->
    <section class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8" aria-label="Indikator Tambahan Tracer Study">
        <div class="flex items-center gap-3 mb-6">
            <span class="w-10 h-10 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center text-lg shrink-0" aria-hidden="true">
                <i class="fa-solid fa-scale-balanced"></i>
            </span>
            <div class="min-w-0 flex-1">
                <h2 class="font-display text-lg font-bold text-slate-900">Indikator Tambahan Tracer Study</h2>
                <p class="text-sm text-slate-500">Indikator keberhasilan lulusan yang terkumpul pada kuesioner</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <!-- Kesesuaian dengan jurusan -->
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                <div class="flex items-center gap-2.5 mb-3">
                    <span class="w-9 h-9 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center text-base shrink-0" aria-hidden="true">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </span>
                    <h3 class="text-sm font-bold text-slate-900">Kesesuaian Pekerjaan dengan Jurusan</h3>
                </div>
                @if(isset($linearPct) && $linearPct !== null)
                    <div class="flex items-center gap-3">
                        <div class="flex-1 h-3 rounded-full bg-slate-200">
                            <div class="bg-brand-600 h-3 rounded-full" style="width: {{ max(0, min(100, $linearPct)) }}%"></div>
                        </div>
                        <span class="text-sm font-extrabold text-brand-800 tabular-nums w-14 text-right">{{ $linearPct }}%</span>
                    </div>
                @else
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Data akan terkuplung ketika alumni bekerja mengisi indikator kesesuaian dengan jurusan pada kuesioner.
                    </p>
                @endif
            </div>

            <!-- Kisaran gaji -->
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                <div class="flex items-center gap-2.5 mb-3">
                    <span class="w-9 h-9 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center text-base shrink-0" aria-hidden="true">
                        <i class="fa-solid fa-coins"></i>
                    </span>
                    <h3 class="text-sm font-bold text-slate-900">Kisaran Gaji Alumni Bekerja</h3>
                </div>
                @if(count($gajiKisaran ?? []) > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($gajiKisaran as $band)
                            <span class="inline-flex items-center rounded-md bg-white border border-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700 tabular-nums">{{ $band['label'] }} <span class="text-slate-400 ml-1.5">{{ $band['count'] }}</span></span>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Distribusiya kisaran gaji alumni bekerja akan terkuplung ketika data kuesioner terlink dengan sistem.
                    </p>
                @endif
            </div>
        </div>
    </section>

    <!-- ================= Data Statistik Alumni (table) ================= -->
    <section class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8" aria-label="Data Statistik Alumni">
        <div class="flex items-center justify-between gap-3 flex-wrap mb-6">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center text-lg shrink-0" aria-hidden="true">
                    <i class="fa-solid fa-table"></i>
                </span>
                <div class="min-w-0 flex-1">
                    <h2 class="font-display text-lg font-bold text-slate-900">Data Statistik Alumni</h2>
                    <p class="text-sm text-slate-500">Jumlah alumni dan status tracer study per tahun lulusan</p>
                </div>
            </div>
            @if(count($statistikRows ?? []) > 0)
                <span class="inline-flex items-center rounded-full bg-brand-50 text-brand-800 text-xs font-bold px-2.5 py-1 tabular-nums">{{ number_format(count($statistikRows)) }} Tahun</span>
            @endif
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full text-sm min-w-[820px]">
                <thead>
                    <tr class="bg-brand-50">
                        <th scope="col" class="px-4 py-3 text-left font-semibold text-slate-700">No</th>
                        <th scope="col" class="px-4 py-3 text-left font-semibold text-slate-700">Tahun Lulus</th>
                        <th scope="col" class="px-4 py-3 text-right font-semibold text-slate-700">Total Alumni</th>
                        <th scope="col" class="px-4 py-3 text-right font-semibold text-slate-700">Bekerja</th>
                        <th scope="col" class="px-4 py-3 text-right font-semibold text-slate-700">Melanjutkan Kuliah</th>
                        <th scope="col" class="px-4 py-3 text-right font-semibold text-slate-700">Wirausaha</th>
                        <th scope="col" class="px-4 py-3 text-right font-semibold text-slate-700">Belum Bekerja</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @if(count($statistikRows ?? []) > 0)
                        @foreach($statistikRows as $row)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-slate-500 tabular-nums">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-900 tabular-nums whitespace-nowrap">Lulusan {{ $row['tahun'] ?? '' }}</td>
                                <td class="px-4 py-3 text-right tabular-nums font-semibold text-slate-900">{{ number_format($row['total_alumni'] ?? 0) }}</td>
                                <td class="px-4 py-3 text-right tabular-nums text-slate-600">{{ number_format($row['bekerja'] ?? 0) }}</td>
                                <td class="px-4 py-3 text-right tabular-nums text-slate-600">{{ number_format($row['kuliah'] ?? 0) }}</td>
                                <td class="px-4 py-3 text-right tabular-nums text-slate-600">{{ number_format($row['wirausaha'] ?? 0) }}</td>
                                <td class="px-4 py-3 text-right tabular-nums text-slate-600">{{ number_format($row['mencari_kerja'] ?? 0) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center">
                                <i class="fa-solid fa-table text-2xl text-slate-300 mb-3" aria-hidden="true"></i>
                                <p class="text-sm font-bold text-slate-700 mb-1">Data statistik alumni belum tersedia</p>
                                <p class="text-xs text-slate-400 max-w-md mx-auto leading-relaxed">
                                    Tabela per tahun lulusan akan terkuplung ketika data alumni dan jawaban kuesioner tracer study terlink dengan sistem.
                                </p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <p class="text-xs text-slate-400 mt-4 flex items-center gap-2">
            <i class="fa-regular fa-circle-question text-slate-400" aria-hidden="true"></i>
            Kategori "Belum Bekerja" merujuk pada status "Mencari Kerja" pada kuesioner tracer study.
        </p>
    </section>
    </div>
</div>
@endsection
