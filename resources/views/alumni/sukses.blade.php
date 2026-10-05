@extends('layouts.app')

@section('title', 'Kuesioner Berhasil Disimpan - Tracer Study SMK')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <div class="bg-white border border-slate-200 rounded-lg p-6 md:p-10 text-center space-y-6">
        <div class="w-16 h-16 rounded-full bg-brand-50 border border-brand-200 mx-auto flex items-center justify-center text-brand-600 text-2xl">
            <i class="fa-solid fa-check"></i>
        </div>

        <div>
            <h1 class="text-2xl font-bold text-slate-900 mb-2">Terima Kasih, {{ $alumni->nama }}!</h1>
            <p class="text-sm text-slate-600 max-w-md mx-auto leading-relaxed">
                Jawaban kuesioner Tracer Study SMK Anda periode tahun <strong>{{ date('Y') }}</strong> telah berhasil tersimpan di sistem.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
            <a href="{{ route('loker.index') }}" class="w-full sm:w-auto px-6 py-2.5 rounded-lg bg-cta text-slate-900 hover:bg-cta-600 font-bold text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-briefcase"></i> Lihat Lowongan Kerja BKK
            </a>
            <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 py-2.5 rounded-lg bg-white text-brand font-semibold text-sm border border-brand hover:bg-brand-50 transition-all">
                Kembali ke Beranda
            </a>
        </div>

        @if($jobs->count() > 0)
            <div class="text-left pt-6 border-t border-slate-200 space-y-3">
                <h3 class="text-sm font-bold text-slate-700 uppercase">Lowongan Kerja Terbaru BKK:</h3>
                @foreach($jobs as $job)
                    <div class="p-3.5 rounded-md bg-slate-50 border border-slate-200 flex items-center justify-between text-sm">
                        <div>
                            <h4 class="font-bold text-slate-900">{{ $job->judul }}</h4>
                            <p class="text-slate-600">{{ $job->perusahaan }} • <span class="text-slate-500">{{ $job->posisi }}</span></p>
                        </div>
                        @if($job->link_pendaftaran)
                            <a href="{{ $job->link_pendaftaran }}" target="_blank" class="px-3 py-1 rounded-md bg-cta text-slate-900 hover:bg-cta-600 font-semibold">
                                Lamar
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
