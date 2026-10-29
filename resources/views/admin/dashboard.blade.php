@extends('layouts.app')
@section('title', 'Dashboard Admin — PPDB SMK')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Dashboard Admin</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola pendaftar PPDB, verifikasi berkas, dan tetapkan hasil seleksi.</p>
        </div>
        <span class="inline-flex items-center gap-2 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-full px-3.5 py-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Sesi Admin Aktif
        </span>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        @foreach([
            ['Total Pendaftar', $counts['total'], '📋', 'text-brand-700 bg-brand-50'],
            ['Menunggu', $counts['pending'], '⏳', 'text-amber-700 bg-amber-50'],
            ['Perlu Perbaikan', $counts['revision'], '⚠️', 'text-violet-700 bg-violet-50'],
            ['Diterima', $counts['accepted'], '✅', 'text-emerald-700 bg-emerald-50'],
            ['Ditolak', $counts['rejected'], '❌', 'text-rose-700 bg-rose-50'],
        ] as [$label,$value,$icon,$cls])
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xl">{{ $icon }}</span>
                    <span class="text-[11px] font-bold px-2 py-1 rounded-lg {{ $cls }}">{{ $label }}</span>
                </div>
                <div class="mt-3 text-3xl font-extrabold tracking-tight">{{ number_format($value) }}</div>
            </div>
        @endforeach
    </div>

    <!-- Filter -->
    <form method="GET" class="bg-white rounded-2xl border border-slate-200 p-4 mb-6 grid grid-cols-1 md:grid-cols-5 gap-3">
        <div class="md:col-span-2">
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="🔍 Cari nama / NISN / email"
                class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 focus:bg-white transition">
        </div>
        <select name="status" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 focus:bg-white transition">
            <option value="">Semua Status</option>
            @foreach($statuses as $k => $v)
                <option value="{{ $k }}" @selected(($filters['status'] ?? '')==$k)>{{ $v }}</option>
            @endforeach
        </select>
        <select name="jalur" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 focus:bg-white transition">
            <option value="">Semua Jalur</option>
            @foreach($jalurs as $k => $v)
                <option value="{{ $k }}" @selected(($filters['jalur'] ?? '')==$k)>{{ $v }}</option>
            @endforeach
        </select>
        <div class="flex gap-2">
            <select name="major" class="flex-1 rounded-xl border border-slate-300 bg-slate-50/50 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 focus:bg-white transition">
                <option value="">Semua Jurusan</option>
                @foreach($majors as $k => $v)
                    <option value="{{ $k }}" @selected(($filters['major'] ?? '')==$k)>{{ $k }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-xl bg-slate-900 hover:bg-slate-700 text-white text-sm font-bold px-4 transition">Filter</button>
        </div>
    </form>

    <!-- Tabel -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr class="text-[11px] uppercase tracking-wide">
                        <th class="px-5 py-3.5 text-left font-bold">Pendaftar</th>
                        <th class="px-4 py-3.5 text-left font-bold">NISN</th>
                        <th class="px-4 py-3.5 text-left font-bold">Jurusan</th>
                        <th class="px-4 py-3.5 text-left font-bold">Jalur</th>
                        <th class="px-4 py-3.5 text-left font-bold">Tahun</th>
                        <th class="px-4 py-3.5 text-left font-bold">Status</th>
                        <th class="px-5 py-3.5 text-left font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($applicants as $a)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-full bg-brand-50 text-brand-700 grid place-items-center text-xs font-extrabold uppercase">{{ mb_substr($a->name, 0, 1) }}</span>
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $a->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $a->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 font-mono text-xs text-slate-500">{{ $a->nisn }}</td>
                            <td class="px-4 py-4"><span class="text-xs font-bold bg-slate-100 text-slate-600 rounded-lg px-2.5 py-1">{{ $a->major }}</span></td>
                            <td class="px-4 py-4 text-xs text-slate-500">{{ $jalurs[$a->jalur] ?? $a->jalur }}</td>
                            <td class="px-4 py-4 text-xs text-slate-500">{{ $a->admission_year }}</td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold
                                    @if($a->status=='accepted') bg-emerald-50 text-emerald-700
                                    @elseif($a->status=='rejected') bg-rose-50 text-rose-700
                                    @elseif($a->status=='revision') bg-violet-50 text-violet-700
                                    @else bg-amber-50 text-amber-700 @endif">
                                    <span class="w-1.5 h-1.5 rounded-full
                                        @if($a->status=='accepted') bg-emerald-500
                                        @elseif($a->status=='rejected') bg-rose-500
                                        @elseif($a->status=='revision') bg-violet-500
                                        @else bg-amber-500 @endif"></span>
                                    {{ $statuses[$a->status] }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:gap-3">
                                    <a href="{{ route('admin.show', $a) }}" class="text-xs font-bold text-brand-700 hover:underline whitespace-nowrap">Detail →</a>
                                    <details class="group">
                                        <summary class="list-none cursor-pointer text-xs font-bold text-slate-500 hover:text-slate-700 whitespace-nowrap">Status ▾</summary>
                                        <div class="mt-2 space-y-1.5">
                                            <form action="{{ route('admin.status', $a) }}" method="POST" class="flex flex-wrap items-center gap-1.5">
                                                @csrf @method('PATCH')
                                                <select name="status" class="rounded-lg border border-slate-300 px-2 py-1.5 text-xs">
                                                    @foreach($statuses as $k => $v)
                                                        <option value="{{ $k }}" @selected($a->status==$k)>{{ $v }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="text" name="notes" placeholder="Catatan" class="rounded-lg border border-slate-300 px-2 py-1.5 text-xs w-24">
                                                <button type="submit" class="rounded-lg bg-brand-600 text-white text-xs font-bold px-2.5 py-1.5 hover:bg-brand-700 transition">Simpan</button>
                                            </form>
                                            <form action="{{ route('admin.destroy', $a) }}" method="POST" onsubmit="return confirm('Hapus pendaftar ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-rose-600 text-xs font-semibold hover:underline">Hapus pendaftar</button>
                                            </form>
                                        </div>
                                    </details>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="text-4xl mb-3">📭</div>
                                <p class="font-bold text-slate-700">Belum ada pendaftar</p>
                                <p class="text-xs text-slate-400 mt-1">Data pendaftar akan muncul di sini setelah ada siswa yang mendaftar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">
        {{ $applicants->withQueryString()->links() }}
    </div>
</div>
@endsection
