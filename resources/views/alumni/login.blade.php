@extends('layouts.app')

@section('title', 'Verifikasi Data Alumni - Tracer Study SMK')

@section('content')
<div class="max-w-md mx-auto py-8">
    <div class="bg-white border border-slate-200 rounded-lg p-6 md:p-8 shadow-sm">
        <div class="text-center mb-6">
            <div class="w-12 h-12 rounded-lg bg-brand-50 border border-brand-200 mx-auto flex items-center justify-center text-brand-700 text-xl mb-3">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <h2 class="text-xl font-bold text-slate-900 uppercase">Verifikasi Alumni</h2>
            <p class="text-sm text-slate-500 mt-1">
                Masukkan NISN dan Tanggal Lahir Anda sesuai data sekolah
            </p>
        </div>

        @if($errors->has('auth'))
            <div class="mb-5 p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                <strong class="font-bold block mb-0.5">Gagal Verifikasi:</strong>
                {{ $errors->first('auth') }}
            </div>
        @endif

        <form action="{{ route('alumni.login.process') }}" method="POST" class="space-y-5">
            @csrf

            <!-- NISN -->
            <div>
                <label for="nisn" class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase">
                    NISN (Nomor Induk Siswa Nasional)
                </label>
                <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}" required
                    placeholder="Masukkan 10 digit NISN"
                    class="w-full px-3.5 py-2.5 rounded-md bg-white border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand-200 font-mono">
                @error('nisn')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Lahir -->
            <div>
                <label for="tanggal_lahir" class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase">
                    Tanggal Lahir
                </label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                    class="w-full px-3.5 py-2.5 rounded-md bg-white border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand-200">
                @error('tanggal_lahir')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full py-3 rounded-lg bg-cta text-slate-900 hover:bg-cta-600 font-bold text-sm transition-all shadow-sm flex items-center justify-center gap-2">
                <i class="fa-solid fa-right-to-bracket"></i> Masuk Kuesioner Tracer
            </button>
        </form>

        <div class="mt-6 pt-4 border-t border-slate-200 text-center text-xs text-slate-500">
            Kendala login? Hubungi Pengelola BKK Sekolah
        </div>
    </div>
</div>
@endsection
