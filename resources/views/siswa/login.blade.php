@extends('layouts.app')
@section('title', 'Masuk Siswa — PPDB SMK')

@section('content')
<div class="min-h-[70vh] grid place-items-center px-4 py-12">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 p-8">
            <div class="text-center">
                <div class="mx-auto w-14 h-14 rounded-2xl hero-gradient grid place-items-center text-white text-xl shadow-lg shadow-brand-600/30">🎓</div>
                <h1 class="mt-5 text-xl font-extrabold tracking-tight">Masuk Akun Siswa</h1>
                <p class="mt-1 text-xs text-slate-500">Masuk untuk melanjutkan pendaftaran PPDB</p>
            </div>

            @if($errors->any())
                <div class="mt-5 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-xs font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Password</label>
                    <input type="password" name="password" required placeholder="••••••••"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 focus:bg-white transition">
                </div>
                <button type="submit" class="w-full rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold py-3 shadow-lg shadow-brand-600/25 transition">
                    Masuk
                </button>
            </form>

            <p class="mt-5 text-center text-xs text-slate-500">
                Belum punya akun? <a href="{{ route('register') }}" class="font-bold text-brand-700 hover:underline">Daftar akun</a>
            </p>
        </div>
        <p class="mt-4 text-center text-xs text-slate-400"><a href="{{ route('ppdb.index') }}" class="hover:text-brand-600 font-semibold">← Kembali ke beranda</a></p>
    </div>
</div>
@endsection
