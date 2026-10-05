@extends('layouts.app')

@section('title', 'Bursa Kerja Khusus (BKK) - Lowongan Kerja Alumni SMK')

@section('content')
<div class="space-y-6">
    <div class="bg-brand-50 border border-brand-100 rounded-lg p-6 text-center">
        <h1 class="text-2xl font-bold text-slate-900 mb-2">Bursa Kerja Khusus (BKK)</h1>
        <p class="text-sm text-slate-600">
            Informasi lowongan kerja & rekrutmen mitra perusahaan untuk alumni SMK.
        </p>
    </div>

    @if($jobs->isEmpty())
        <div class="bg-white border border-slate-200 rounded-lg p-8 text-center max-w-md mx-auto">
            <i class="fa-solid fa-briefcase text-3xl text-brand-500 mb-3"></i>
            <h3 class="text-base font-bold text-slate-900 mb-1">Belum Ada Lowongan Aktif</h3>
            <p class="text-sm text-slate-500">Informasi lowongan kerja akan dipublikasikan oleh tim BKK sekolah.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($jobs as $job)
                <div class="bg-white border border-slate-200 rounded-lg p-5 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-xs text-slate-500 mb-3">
                            <span class="font-bold text-brand-700 bg-brand-50 px-2.5 py-1 rounded-md border border-brand-200">{{ $job->posisi }}</span>
                            @if($job->deadline)
                                <span>s/d {{ $job->deadline->format('d M Y') }}</span>
                            @endif
                        </div>

                        <h3 class="text-lg font-bold text-slate-900 mb-1 leading-snug">{{ $job->judul }}</h3>
                        <p class="text-sm font-semibold text-slate-600 mb-3">
                            {{ $job->perusahaan }} @if($job->lokasi) • <span class="text-slate-500 font-normal">{{ $job->lokasi }}</span> @endif
                        </p>

                        <div class="p-3 rounded-md bg-slate-50 border border-slate-200 text-sm text-slate-600 space-y-1 mb-4">
                            <strong class="text-slate-800 block">Kualifikasi:</strong>
                            <p class="whitespace-pre-line leading-relaxed">{{ $job->persyaratan }}</p>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-200 space-y-2">
                        @if($job->kontak)
                            <div class="text-sm text-slate-500 flex items-center justify-between">
                                <span>Kontak:</span>
                                <span class="text-slate-800 font-semibold">{{ $job->kontak }}</span>
                            </div>
                        @endif

                        @if($job->link_pendaftaran)
                            <a href="{{ $job->link_pendaftaran }}" target="_blank"
                                class="w-full py-2.5 rounded-lg bg-cta text-slate-900 hover:bg-cta-600 font-bold text-sm shadow-sm transition-all flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Lamar Lowongan
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
