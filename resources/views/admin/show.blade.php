@extends('layouts.app')
@section('title', 'Detail Pendaftar — PPDB SMK')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <div class="mb-6">
        <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-slate-400 hover:text-brand-700">← Kembali ke dashboard</a>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <span class="w-14 h-14 rounded-2xl bg-brand-50 text-brand-700 grid place-items-center text-xl font-extrabold uppercase">{{ mb_substr($applicant->name, 0, 1) }}</span>
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight">{{ $applicant->name }}</h1>
                <p class="text-sm text-slate-500 break-words">
                    NISN {{ $applicant->nisn }}
                    @if($applicant->nik) · NIK {{ $applicant->nik }} @endif
                    · No. Pendaftaran {{ str_pad($applicant->id, 5, '0', STR_PAD_LEFT) }}
                </p>
            </div>
        </div>
        @php
            $statusColor = [
                'accepted' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                'pending'  => 'bg-amber-50 text-amber-700 border-amber-200',
                'revision' => 'bg-violet-50 text-violet-700 border-violet-200',
            ][$applicant->status] ?? 'bg-slate-50 text-slate-600 border-slate-200';
        @endphp
        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border text-sm font-bold {{ $statusColor }}">
            {{ $statuses[$applicant->status] }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Data lengkap sesuai F-PD -->
        <div class="lg:col-span-2 space-y-6">
            @include('partials.applicant-data')
            @include('partials.applicant-documents', ['title' => 'Verifikasi Berkas'])
        </div>

        <!-- Aksi -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <h3 class="font-extrabold text-sm mb-1">Hasil Verifikasi</h3>
                <p class="text-[11px] text-slate-400 mb-3">Pilih <span class="font-semibold">Perlu Perbaikan Berkas</span> untuk mengembalikan pendaftaran ke siswa.</p>
                <form action="{{ route('admin.status', $applicant) }}" method="POST" class="space-y-3">
                    @csrf @method('PATCH')
                    <select name="status" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 focus:bg-white transition">
                        @foreach($statuses as $k => $v)
                            <option value="{{ $k }}" @selected($applicant->status==$k)>{{ $v }}</option>
                        @endforeach
                    </select>
                    <textarea name="notes" rows="3" placeholder="Catatan untuk siswa, contoh: Ijazah kurang jelas / KK belum diunggah"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 focus:bg-white transition">{{ $applicant->notes }}</textarea>
                    <button type="submit" class="w-full rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold py-2.5 text-sm shadow-lg shadow-brand-600/25 transition">Simpan Status</button>
                </form>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-5 text-sm space-y-2">
                <h3 class="font-extrabold text-sm mb-1">Informasi</h3>
                <p class="flex justify-between gap-3"><span class="text-slate-400">Tanggal daftar</span><span class="font-semibold text-right">{{ $applicant->created_at?->format('d M Y H:i') }}</span></p>
                <p class="flex justify-between gap-3"><span class="text-slate-400">Terakhir update</span><span class="font-semibold text-right">{{ $applicant->updated_at?->format('d M Y H:i') }}</span></p>
                <p class="flex justify-between gap-3"><span class="text-slate-400">Akun siswa</span><span class="font-semibold text-right break-all">{{ $applicant->user->email ?? 'Tanpa akun' }}</span></p>
            </div>

            <form action="{{ route('admin.destroy', $applicant) }}" method="POST" onsubmit="return confirm('Hapus pendaftar ini? Tindakan tidak bisa dibatalkan.')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full rounded-xl border border-rose-200 text-rose-600 font-bold py-2.5 text-sm hover:bg-rose-50 transition">Hapus Pendaftar</button>
            </form>
        </div>
    </div>
</div>
@endsection
