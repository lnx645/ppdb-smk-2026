@extends('layouts.app')
@section('title', 'Pendaftaran Berhasil — PPDB SMK')

@section('content')
<div class="max-w-xl mx-auto px-4 py-16 md:py-24">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 p-8 md:p-10 text-center fade-up">
        <div class="mx-auto w-20 h-20 rounded-full bg-emerald-50 grid place-items-center text-4xl">✅</div>
        <h1 class="mt-6 text-2xl md:text-3xl font-extrabold tracking-tight">Pendaftaran Berhasil!</h1>
        <p class="mt-3 text-slate-500 text-sm leading-relaxed">
            Data pendaftaran kamu sudah kami terima. Panitia PPDB akan melakukan verifikasi berkas.
            Silakan pantau pengumuman hasil seleksi dari sekolah.
        </p>

        <div class="mt-8 bg-slate-50 border border-slate-200 rounded-2xl p-5 text-left">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Langkah selanjutnya</p>
            <ol class="mt-2 space-y-1.5 text-sm text-slate-600 list-decimal list-inside">
                <li>Tunggu verifikasi panitia (1–3 hari kerja).</li>
                <li>Pantau pengumuman hasil seleksi.</li>
                <li>Jika diterima, lakukan daftar ulang di sekolah.</li>
            </ol>
        </div>

        <div class="mt-7 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('siswa.dashboard') }}" class="px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold transition">Lihat Dashboard Saya</a>
            <a href="{{ route('ppdb.index') }}" class="px-6 py-3 rounded-xl border border-slate-300 hover:bg-slate-50 font-semibold text-slate-600 transition">Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection
