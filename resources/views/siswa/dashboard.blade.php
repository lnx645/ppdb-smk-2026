@extends('layouts.app')
@section('title', 'Dashboard Siswa — PPDB SMK')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Halo, {{ auth()->user()->name }} 👋</h1>
            <p class="mt-1 text-sm text-slate-500">Ini status pendaftaran PPDB kamu.</p>
        </div>
        <span class="text-xs font-semibold text-slate-400">Akun: {{ auth()->user()->email }}</span>
    </div>

    @if(!$applicant)
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 p-8 md:p-12 text-center">
            <div class="text-5xl mb-4">📝</div>
            <h2 class="text-xl font-extrabold">Kamu belum mendaftar PPDB</h2>
            <p class="mt-2 text-sm text-slate-500 max-w-md mx-auto">Isi formulir pendaftaran sekarang. Setelah terkirim, status pendaftaran bisa kamu pantau di halaman ini.</p>
            <a href="{{ route('ppdb.form') }}" class="inline-block mt-6 px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold shadow-lg shadow-brand-600/25 transition">
                Isi Formulir PPDB →
            </a>
        </div>
    @else
        @php
            $statusColor = [
                'accepted' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                'pending'  => 'bg-amber-50 text-amber-700 border-amber-200',
                'revision' => 'bg-violet-50 text-violet-700 border-violet-200',
            ][$applicant->status] ?? 'bg-slate-50 text-slate-600 border-slate-200';
            $statusIcon = ['accepted'=>'✅','rejected'=>'❌','pending'=>'⏳','revision'=>'⚠️'][$applicant->status] ?? '📋';
        @endphp

        <!-- Status -->
        <div class="rounded-3xl border {{ $statusColor }} p-6 md:p-8 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="text-4xl">{{ $statusIcon }}</div>
                <div class="flex-1">
                    <p class="text-xs font-bold uppercase tracking-wide opacity-70">Status Pendaftaran</p>
                    <h2 class="text-2xl font-extrabold">{{ $statuses[$applicant->status] }}</h2>
                    @if($applicant->notes)
                        <p class="mt-1 text-sm opacity-80">Catatan panitia: {{ $applicant->notes }}</p>
                    @endif
                </div>
                <div class="text-sm opacity-80">
                    <p>Tahun {{ $applicant->admission_year }}</p>
                    <p>Jalur {{ $jalurs[$applicant->jalur] ?? $applicant->jalur }}</p>
                </div>
            </div>

            @if($applicant->status === 'revision')
                <div class="mt-5 pt-5 border-t border-violet-200/70 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <p class="text-sm font-semibold">Silakan perbaiki data dan upload ulang berkas yang kurang, lalu kirim kembali.</p>
                    <a href="{{ route('ppdb.edit') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold shadow-lg shadow-violet-600/25 transition whitespace-nowrap">
                        📤 Upload Ulang Berkas
                    </a>
                </div>
            @elseif($applicant->status === 'pending')
                <div class="mt-5 pt-5 border-t border-amber-200/70 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <p class="text-sm">Masih ada waktu untuk memperbarui data atau mengganti berkas sebelum diverifikasi.</p>
                    <a href="{{ route('ppdb.edit') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-white border border-amber-300 hover:bg-amber-50 text-amber-800 text-sm font-bold transition whitespace-nowrap">
                        ✏️ Perbarui Data / Berkas
                    </a>
                </div>
            @endif
        </div>

        <!-- Detail lengkap sesuai F-PD -->
        <div class="space-y-6">
            @include('partials.applicant-data')
            @include('partials.applicant-documents', ['title' => 'Berkas Pendukung'])
        </div>

        <p class="mt-4 text-xs text-slate-400 text-center">Tanggal daftar: {{ $applicant->created_at?->format('d M Y H:i') }} WIB</p>
    @endif
</div>
@endsection
