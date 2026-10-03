@extends('layouts.app')

@section('title', 'Kuesioner Berhasil Disimpan - Tracer Study SMK')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <div class="bg-slate-700 border border-slate-600 rounded-lg p-6 md:p-10 text-center space-y-6">
        <div class="w-16 h-16 rounded-full bg-slate-600 border border-slate-500 mx-auto flex items-center justify-center text-white text-2xl">
            <i class="fa-solid fa-check"></i>
        </div>

        <div>
            <h1 class="text-2xl font-bold text-white uppercase mb-2">Terima Kasih, {{ $alumni->nama }}!</h1>
            <p class="text-xs text-slate-300 max-w-md mx-auto leading-relaxed">
                Jawaban kuesioner Tracer Study SMK Anda periode tahun <strong>{{ date('Y') }}</strong> telah berhasil tersimpan di sistem.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
            <a href="{{ route('loker.index') }}" class="w-full sm:w-auto px-6 py-2.5 rounded bg-slate-100 text-slate-800 font-bold text-xs hover:bg-white transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-briefcase"></i> Lihat Lowongan Kerja BKK
            </a>
            <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 py-2.5 rounded bg-slate-600 text-slate-200 font-semibold text-xs border border-slate-500 hover:bg-slate-500 transition-all">
                Kembali ke Beranda
            </a>
        </div>

        @if($jobs->count() > 0)
            <div class="text-left pt-6 border-t border-slate-600 space-y-3">
                <h3 class="text-xs font-bold text-slate-300 uppercase">Lowongan Kerja Terbaru BKK:</h3>
                @foreach($jobs as $job)
                    <div class="p-3.5 rounded bg-slate-800 border border-slate-600 flex items-center justify-between text-xs">
                        <div>
                            <h4 class="font-bold text-white">{{ $job->judul }}</h4>
                            <p class="text-slate-300">{{ $job->perusahaan }} • <span class="text-slate-400">{{ $job->posisi }}</span></p>
                        </div>
                        @if($job->link_pendaftaran)
                            <a href="{{ $job->link_pendaftaran }}" target="_blank" class="px-3 py-1 rounded bg-slate-700 hover:bg-slate-600 border border-slate-500 text-white font-semibold">
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
